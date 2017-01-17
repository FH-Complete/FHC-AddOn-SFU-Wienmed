<?php
if(php_sapi_name() != 'cli')
    die('DISABLED');
/* Copyright (C) 2014 fhcomplete.org
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
 *
 * Authors: Andreas Oesterreicher <andreas.oesterreicher@technikum-wien.at> 
 *          Nikolaus Krondraf <nikolaus.krondraf@technikum-wien.at>
 *
 */
/**
 * Script zum Anlegen der User im Samba4 und am Mailserver
 * Dieses Script muss als Root von der Commandline gestartet werden damit des Mailverzeichnis korrekt angelegt wird!
 */
require_once('../../../config/vilesci.config.inc.php');
require_once('../../../include/functions.inc.php');
require_once('../../../include/basis_db.class.php');
require_once('../../../include/phrasen.class.php');
require_once('../../ldap/vilesci/ldap.class.php');
require_once('../../../include/mail.class.php');

$p = new phrasen(DEFAULT_LANGUAGE);
ini_set('display_errors','1');
error_reporting(E_ALL);

$db = new basis_db();
$sendActivationMail = false;

//LDAP Verbindung herstellen
$ldap = new ldap();
$ldap->debug=false;
if(!$ldap->connect())
	die($ldap->errormsg);

$qry = "SELECT  
			vorname, nachname, uid, gebdatum, (SELECT matrikelnr FROM public.tbl_student WHERE student_uid=tbl_benutzer.uid) as matrikelnr, alias,
			(SELECT lektor FROM public.tbl_mitarbeiter WHERE mitarbeiter_uid=tbl_benutzer.uid) as lektor,
			(SELECT fixangestellt FROM public.tbl_mitarbeiter WHERE mitarbeiter_uid=tbl_benutzer.uid) as fixangestellt,
			(SELECT true FROM public.tbl_student WHERE student_uid=tbl_benutzer.uid) as student,
			(SELECT kontakt FROM public.tbl_kontakt WHERE kontakttyp='email' AND person_id=tbl_benutzer.person_id ORDER BY zustellung desc LIMIT 1) as email_privat, aktivierungscode,
			(SELECT bezeichnung FROM public.tbl_studiengang JOIN public.tbl_student USING(studiengang_kz) WHERE tbl_student.student_uid=tbl_benutzer.uid) as studiengang
		FROM
			public.tbl_benutzer
			JOIN public.tbl_person USING(person_id)
		WHERE
			tbl_benutzer.aktiv
		AND uid NOT IN('administrator','_DummyLektor')
        AND EXISTS (SELECT 1 FROM public.tbl_student JOIN public.tbl_prestudentstatus USING(prestudent_id) 
                WHERE tbl_student.student_uid=tbl_benutzer.uid 
                AND get_rolle_prestudent(prestudent_id,null) IN('Student','Incoming') 
                AND tbl_prestudentstatus.studiensemester_kurzbz IN 
                    (SELECT studiensemester_kurzbz FROM public.tbl_studiensemester WHERE start>now()))
		";

if($result = $db->db_query($qry))
{
	while($row = $db->db_fetch_object($result))
	{
        //Suchen ob der User bereits vorhanden ist
		if(!$dn = $ldap->GetUserDN($row->uid))
		{
			$data = array();
            
            // freie UID-Nummer für den User ermitteln
            $lastUidNumber = file_get_contents("lastUidNumber.txt");
            $uidNumber = $lastUidNumber + 1;
            while($ldap->CheckUidNumber($uidNumber) === false)
            {
                $uidNumber++;
            }
            $data['uidNumber'] = $uidNumber;
            file_put_contents("lastUidNumber.txt", $uidNumber);
            
            if($row->matrikelnr=='')
			{
				//Mitarbeiter
				$dn = "CN=$row->uid,CN=Users,DC=uni,DC=sfu,DC=ac,DC=at";
                $data['gidNumber'] = '10002';
                $data['homeDirectory']='\\\\sfusamba4dc1\\'.$row->uid;
                $data['homeDrive']='M:';
                $data['loginShell']='/bin/bash';
                $data['profilePath']='%LogonServer%\\profiles\\staff';
			}
			else
			{
				//Studierende
				$dn = "CN=$row->uid,CN=Users,DC=uni,DC=sfu,DC=ac,DC=at";
                $data['gidNumber'] = '10001';
                $data['unixHomeDirectory']='/var/spool/mail/'.$row->uid;
			}
			
			//Active Directory will das Passwort in doppelten Hochkomma und UTF16LE codiert
			$utf16_passwort = mb_convert_encoding('"'.ACCOUNT_ACTIVATION_PASSWORD.'"', "UTF-16LE", "UTF-8");

			$data['cn'] = $row->uid;
			$data['objectClass'] = array("top","person","organizationalPerson","user","posixAccount");
			$data['sn'] = $row->nachname;
			$data['givenName'] = $row->vorname;
			$data['displayName'] = $row->vorname." ".$row->nachname;
			$data['name'] = $row->vorname." ".$row->nachname;
			$data['mail'] = $row->uid.'@'.DOMAIN;
			$data["sAMAccountName"] = $row->uid;
			$data['userPrincipalName'] = $row->uid.'@'.DOMAIN;
			//$data['proxyAddresses']=array('smtp:'.$row->uid.'@'.DOMAIN, 'SMTP:'.$row->alias.'@'.DOMAIN);
			$data['msSFU30Name']=$row->uid;
            $data['msSFU30NisDomain'] = 'uni';
            $data['uid'] = $row->uid;
			
			//Passwort und UserAccountControl kann nicht beim Anlegen direkt gesetzt werden
			//Es muss nach dem Anlegen des Users gesetzt werden
			
			// UserAccountControl gibt den Status des Accounts an. Per default sind diese deaktiviert (514)
			// 512 = Normal Account
			// http://support.microsoft.com/kb/305144/en-us
			if(!$ldap->Add($dn, $data))
			{
				echo "<br>Fehler beim Anlegen von $row->uid: ".$ldap->errormsg;
				continue;
			}
			else
			{
				// Nur fuer Active Directory

				// Moegliche Fehlerquellen beim setzten des Passworts:
				// - Richtigen BIND User verwenden
				// - im AD muss das setzen des Passwortes aktiviert sein
				// - Damit das Passwort gesetzt werden darf, muss die Verbindung zum AD verschluesselt sein
				// - Passwort muss der Passwort Policy des AD entsprechen (Sonderzeichen, Gross-/Kleinschreibung etc, Mind. 6 Zeichen)
				// - Passwort muss korrekt UTF16LE kodiert sein und unter doppelten Hochkomma stehen

				$data = array();
				$data['userAccountControl']='66048'; // Normaler Account, Passwort laeuft nicht aus
				$data['unicodePwd']=$utf16_passwort;
				if(!$ldap->Modify($dn, $data))
				{
					echo "<br>Fehler beim Setzten von UserAccountControl und Passwort von $row->uid: ".$ldap->errormsg;
					continue;
				}					

                echo "<br>$row->uid erfolgreich angelegt";
                
                if($sendActivationMail)
                {
                    // Aktivierungsmail verschicken
                    $to = $row->email_privat;
                    $from = 'no-reply@'.DOMAIN;
                    $subject = 'Account Aktivierung';
                    $link = CIS_ROOT."cis/public/accountactivation.php?username=".$row->uid."&code=".$row->aktivierungscode;

                    if($row->studiengang!='')
                        $stg='Studiengang: '.$row->studiengang;
                    else
                        $stg='Lehrender / Bediensteter';

                    $text = $p->t('mail/accountaktivierung',array($row->vorname,$row->nachname,$row->uid,$row->aktivierungscode,$stg,$row->uid.'@'.DOMAIN, $link, CIS_ROOT));

                    $mail = new mail($to, $from, $subject,'Wechseln Sie in die HTML Ansicht um den Inhalt anzuzeigen');
                    $mail->setHTMLContent($text);
                    if($mail->send())
                        echo " Aktivierungsmail versandt an $to";
                    else
                        echo " Fehler beim senden des Mails an $to";
                }
								
                if($row->matrikelnr!='')
                {
                    // Verzeichnisse am Mailserver anlegen
                    exec('ssh mail.uni.sfu.ac.at /root/makemaildir.sh '.$row->uid);
                }
			}
		}
	}
}

$ldap->unbind();
?>

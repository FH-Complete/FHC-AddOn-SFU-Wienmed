<?php
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
 *			Gerald Raab <gerald.raab@technikum-wien.at>
 *
 */
/**
 * Script zum Zurücksetzen des Passwortes in Samba 4
 */
require_once('../../../config/vilesci.config.inc.php');
require_once('../../../include/functions.inc.php');
require_once('../../../include/basis_db.class.php');
require_once('../../../include/phrasen.class.php');
require_once('../../ldap/vilesci/ldap.class.php');
require_once('../../../include/mail.class.php');
require_once('../../../include/benutzerberechtigung.class.php');

$p = new phrasen(DEFAULT_LANGUAGE);
ini_set('display_errors','1');
error_reporting(E_ALL);

$user = get_uid();
$rechte = new benutzerberechtigung();
$rechte->getBerechtigungen($user);
if(!$rechte->isBerechtigt('admin'))
	die($p->t('global/FuerDieseAktionBenoetigenSieAdministrationsrechte'));

$db = new basis_db();
$sendActivationMail = false;

//LDAP Verbindung herstellen
$ldap = new ldap();
$ldap->debug=false;
if(!$ldap->connect())
	die($ldap->errormsg);

$msg_str = '';

if (isset($_POST["uid"]) && $_POST["uid"] != ""){
	$uid = $_POST["uid"];

        $sql = "select count(*) from tbl_benutzer where uid = ".$db->db_add_param($uid);
        if (!$res = $db->db_query($sql))
                die("Could not connect to DB");
        if ($db->db_fetch_row($res)[0] < 1)
                die ("no such UID");

	// check ob uservorhanden ist
	if(!$dn = $ldap->GetUserDN($uid))
	{
		die('User does not exist in AD');
	}

	//Active Directory will das Passwort in doppelten Hochkomma und UTF16LE codiert
	$utf16_passwort = mb_convert_encoding('"'.ACCOUNT_ACTIVATION_PASSWORD.'"', "UTF-16LE", "UTF-8");


	// Moegliche Fehlerquellen beim setzten des Passworts:
	// - Richtigen BIND User verwenden
	// - im AD muss das setzen des Passwortes aktiviert sein
	// - Damit das Passwort gesetzt werden darf, muss die Verbindung zum AD verschluesselt sein
	// - Passwort muss der Passwort Policy des AD entsprechen (Sonderzeichen, Gross-/Kleinschreibung etc, Mind. 6 Zeichen)
	// - Passwort muss korrekt UTF16LE kodiert sein und unter doppelten Hochkomma stehen

	$data = array();
	$data['unicodePwd']=$utf16_passwort;
	if(!$ldap->Modify($dn, $data))
	{
		die("<br>Fehler beim Setzten des Passworts von $uid: ".$ldap->errormsg);
	}
	else
		$msg_str .= "<br>$uid changed in AD";

	$akt = generateActivationKey();
	$sql = "update tbl_benutzer set aktivierungscode = '".$akt."' where uid = '".$uid."'";
	if (!$db->db_query($sql))
		die("Could not save ActivationCode in DB");
	else
		$msg_str .= '<br>Activation Code set in DB';


	if($sendActivationMail)
	{
		// TODO: mail mit aktivierungscode schicken
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
}

$ldap->unbind();
?>
<html>
<head>
</head>
<body>

<h1>Reset Password</h1>
<form name="pwreset" action='reset_password.php' method="POST">
UID: <input type="text" name="uid">
<input type="submit" name="send" value="Send">
</form>
<div style="color:green;"><?php echo $msg_str; ?></div>

</body>
</html>

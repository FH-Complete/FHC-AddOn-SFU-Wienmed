<?php
/* Copyright (C) 2006 Technikum-Wien
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
 * Authors: Christian Paminger <christian.paminger@technikum-wien.at>,
 *          Andreas Oesterreicher <andreas.oesterreicher@technikum-wien.at> and
 *          Rudolf Hangl <rudolf.hangl@technikum-wien.at> and
 *			Gerald Raab <gerald.raab@technikum-wien.at>.
 */
header("Content-type: application/xhtml+xml");
require_once('../../../config/vilesci.config.inc.php');
require_once('../../../include/functions.inc.php');
require_once('../../../include/zeugnisnote.class.php');
require_once('../../../include/datum.class.php');
require_once('../../../include/note.class.php');
require_once('../../../include/studiensemester.class.php');
require_once('../../../include/studiengang.class.php');
require_once('../../../include/mitarbeiter.class.php');
require_once('../../../include/prestudent.class.php');
require_once('../../../include/student.class.php');

$datum = new datum();
$db = new basis_db();

function draw_studienerfolg($uid, $studiensemester_kurzbz)
{
	global $xml, $note_arr, $datum, $note_wert;

	$db = new basis_db();
	$query = "SELECT 
				tbl_student.matrikelnr, tbl_student.studiengang_kz, tbl_studiengang.bezeichnung, 
				tbl_person.titelpre, tbl_person.titelpost, 
				tbl_person.vorname, tbl_person.nachname,tbl_person.gebdatum, 
				tbl_studiensemester.bezeichnung as sembezeichnung,
				tbl_studiengang.english as bezeichnung_englisch,
				tbl_studiengang.orgform_kurzbz
			FROM 
				public.tbl_person, public.tbl_student, public.tbl_studiengang, public.tbl_benutzer, 
				public.tbl_studiensemester, lehre.tbl_zeugnisnote 
			WHERE 
				tbl_student.studiengang_kz = tbl_studiengang.studiengang_kz 
				and tbl_student.student_uid = tbl_benutzer.uid 
				and tbl_benutzer.person_id = tbl_person.person_id
				and tbl_zeugnisnote.studiensemester_kurzbz=".$db->db_add_param($studiensemester_kurzbz)." 
				and tbl_student.student_uid = ".$db->db_add_param($uid); 

	if($db->db_query($query))
	{
		if(!$row = $db->db_fetch_object())
			return false;
	}
	else
		return false;

	$studiensemester = new studiensemester();
	$studiensemester_aktuell = $studiensemester->getNearest();
	$student = new student();
	$student->load($uid);
	$prestudentstatus = new prestudent();
	$prestudentstatus->getLastStatus($student->prestudent_id,'','Student');
	
	if($studiensemester_aktuell!=$prestudentstatus->studiensemester_kurzbz)
		$studiensemester_aktuell = $prestudentstatus->studiensemester_kurzbz;
	
	$studiensemester->load($studiensemester_aktuell);
	
	$semester_aktuell='';
	$qry_semester = "SELECT tbl_prestudentstatus.ausbildungssemester as semester FROM public.tbl_student, public.tbl_prestudentstatus 
					WHERE tbl_student.prestudent_id=tbl_prestudentstatus.prestudent_id 
						AND tbl_prestudentstatus.status_kurzbz in('Student','Incoming','Outgoing','Praktikant','Diplomand') 
						AND studiensemester_kurzbz=".$db->db_add_param($studiensemester_aktuell)." 
						AND tbl_student.student_uid = ".$db->db_add_param($uid);
	
	if($db->db_query($qry_semester))
		if($row_semester = $db->db_fetch_object())
			$semester_aktuell=$row_semester->semester;

	if($semester_aktuell=='')
		$studiensemester_aktuell='';
		
	$qry_semester = "SELECT tbl_prestudentstatus.ausbildungssemester as semester, tbl_prestudentstatus.orgform_kurzbz FROM public.tbl_student, public.tbl_prestudentstatus 
					WHERE tbl_student.prestudent_id=tbl_prestudentstatus.prestudent_id 
						AND tbl_prestudentstatus.status_kurzbz in('Student','Incoming','Outgoing','Praktikant','Diplomand') 
						AND studiensemester_kurzbz=".$db->db_add_param($studiensemester_kurzbz)." 
						AND tbl_student.student_uid = ".$db->db_add_param($uid);
		
	$orgform='';
	if($db->db_query($qry_semester))
	{
		if($row_semester = $db->db_fetch_object())
		{
			$row->semester=$row_semester->semester;
			$orgform = $row_semester->orgform_kurzbz;
		}
	}

	// Wenn der Studiernede keine Orgform eingetragen hat, wird die Orgform des Studiengang genommen
	if($orgform=='')
		$orgform = $row->orgform_kurzbz;
	
	$studiengang = new studiengang($row->studiengang_kz);
	$stgleiter = $studiengang->getLeitung($row->studiengang_kz);
	$stgl='';
	foreach ($stgleiter as $stgleiter_uid)
	{
		$stgl_ma = new mitarbeiter($stgleiter_uid);
		$stgl .= trim($stgl_ma->titelpre.' '.$stgl_ma->vorname.' '.$stgl_ma->nachname.' '.$stgl_ma->titelpost);
	}
	
	$stdsem = new studiensemester($studiensemester_kurzbz);
    
    switch($studiengang->typ)
    {
        case 'b':
            $studTyp = 'Bakkalaureat'; 
            break; 
        case 'm': 
            $studTyp = 'Magister'; 
            break; 
        case 'd':
            $studTyp = 'Diplom'; 
            break;
        case 'l':
            $studTyp = 'Lehrgang'; 
            break;
        case 'k':
            $studTyp = 'Kurzstudium'; 
            break;
        default: 
            $studTyp ='';
    }
	
	$xml .= "	<studienerfolg>";
	$xml .= "		<logopath>".DOC_ROOT."skin/images/</logopath>";
	$xml .= "		<studiensemester>".$row->sembezeichnung."</studiensemester>";
	$xml .= "		<studiensemester_aktuell>".$studiensemester_aktuell."</studiensemester_aktuell>";
	$xml .= "		<studiensemester_aktuell_beschreibung>".(($studiensemester->beschreibung != NULL) ? $studiensemester->beschreibung : $studiensemester_aktuell)."</studiensemester_aktuell_beschreibung>";
	$xml .=	"		<semester></semester>";
	$xml .=	"		<semester_aktuell>".$semester_aktuell.($semester_aktuell!=''?'. Semester':'')."</semester_aktuell>";
	$xml .=	"		<semester_aktuell_semester>".$semester_aktuell."</semester_aktuell_semester>";
	$xml .= "		<studiengang>".$row->bezeichnung."</studiengang>";
    $xml .= "		<studiengang_typ>".$studTyp."</studiengang_typ>";
	$xml .= "		<studiengang_englisch>".$row->bezeichnung_englisch."</studiengang_englisch>";
	$xml .= "		<studiengang_kz>".sprintf('%04s',$row->studiengang_kz)."</studiengang_kz>";
	$xml .= "		<titelpre>".$row->titelpre."</titelpre>";
	$xml .= "		<titelpost>".$row->titelpost."</titelpost>";
	$xml .= "		<vorname>".$row->vorname."</vorname>";
	$xml .= "		<nachname>".$row->nachname."</nachname>";
	$gebdatum = date('d.m.Y',strtotime($row->gebdatum));
	$xml .= "		<gebdatum>".$gebdatum."</gebdatum>";
	$xml .= "		<matrikelnr>".$row->matrikelnr."</matrikelnr>";
	$xml .= "		<studiensemester_kurzbz>".(($stdsem->beschreibung != NULL) ? $stdsem->beschreibung : $studiensemester_kurzbz)."</studiensemester_kurzbz>";
	$datum_aktuell = date('d.m.Y');
	$xml .= "		<datum>".$datum_aktuell."</datum>";
	$xml .= "		<orgform>".$orgform."</orgform>";
	$xml .= "		<studiengangsleitung>".$stgl."</studiengangsleitung>";

	if(isset($_REQUEST['typ']) && $_REQUEST['typ']=='finanzamt')
		$xml .= "		<finanzamt>(gemäß §2 Abs. 1 lit.b des Familienlastenausgleichsgesetzes 1967 zur Vorlage beim Wohnsitzfinanzamt)</finanzamt>";
	else
		$xml .= "		<finanzamt></finanzamt>";

	$obj = new zeugnisnote();

	if(!$obj->getZeugnisnoten($lehrveranstaltung_id=null, $uid, $studiensemester_kurzbz))
		die('Fehler beim Laden der Noten:'.$obj->errormsg);

	

	$gesamtstunden=0;
	$gesamtects=0;
	$notensumme=0;
	$anzahl=0;

	foreach ($obj->result as $row)
	{
		if($row->zeugnis)
		{
			if (trim($row->note)!=='')
				$note = $note_arr[$row->note];
			else
				$note = "";
			if($note!='')
			{
				$qry = "SELECT 
							wochen 
						FROM 
							public.tbl_semesterwochen
							JOIN lehre.tbl_lehrveranstaltung USING(studiengang_kz, semester)
						WHERE 
							tbl_lehrveranstaltung.lehrveranstaltung_id=".$db->db_add_param($row->lehrveranstaltung_id);

				$wochen = 15;
				if($db->db_query($qry))
					if($row_wochen = $db->db_fetch_object())
						$wochen = $row_wochen->wochen;
                    
                // Semester der LV laut Studienplan ermitteln
                $qry = "SELECT SUBSTRING(kurzbz FROM 1 FOR 2) AS studplansem FROM lehre.tbl_lehrveranstaltung WHERE lehrveranstaltung_id=".$db->db_add_param($row->lehrveranstaltung_id);
                if($db->db_query($qry))
					if($row_studplansem = $db->db_fetch_object())
						$studplansem = $row_studplansem->studplansem;

				$xml .= "			<unterrichtsfach>";
				$xml .= "				<bezeichnung><![CDATA[".$row->lehrveranstaltung_bezeichnung."]]></bezeichnung>";
				$xml .= "				<bezeichnung_englisch><![CDATA[".$row->lehrveranstaltung_bezeichnung_english."]]></bezeichnung_englisch>";
				$xml .= "				<studplansem><![CDATA[".$studplansem."]]></studplansem>";
                $xml .= "				<note>".$note."</note>";
				$xml .= "				<note_idx>".$row->note."</note_idx>";
				$sws = sprintf('%.1F',$row->semesterstunden/$wochen);
				$xml .= "				<sws>".$sws."</sws>";
				$xml .= "				<ects>".$row->ects."</ects>";
				$xml .= "				<lehrform><![CDATA[".$row->lv_lehrform_kurzbz."]]></lehrform>";
				if($row->benotungsdatum!='')
					$xml .= "				<benotungsdatum>".date('d.m.Y',$datum->mktime_fromtimestamp($row->benotungsdatum))."</benotungsdatum>";
				$xml .= "			</unterrichtsfach>";

				$gesamtstunden +=$sws;
				$gesamtects += $row->ects;
				if($note_wert[$row->note]!='')
				{
					$notensumme += $note_wert[$row->note];
					$anzahl++;
				}
			}
		}
	}


	if($anzahl!=0)
	{
		$schnitt = ($notensumme/$anzahl);
	}
	else
		$schnitt = 0;
	$xml .= "		<gesamtstunden>".sprintf('%.1F',$gesamtstunden)."</gesamtstunden>";
	$xml .= "		<gesamtects>".sprintf('%.2F',$gesamtects)."</gesamtects>";
	$xml .= "		<schnitt>".sprintf('%.2f',$schnitt)."</schnitt>";
	$xml .= "	</studienerfolg>";
}

if (isset($_REQUEST["xmlformat"]) && $_REQUEST["xmlformat"] == "xml")
{

	if(isset($_GET['uid']))
		$uid = $_GET['uid'];
	else
		$uid = null;

	$uid_arr = explode(";",$uid);

	if ($uid_arr[0] == "")
	{
		unset($uid_arr[0]);
		$uid_arr = array_values($uid_arr);
	}

	$note_arr = array();
	$note_wert = array();
	$note = new note();
	$note->getAll();
	foreach ($note->result as $n)
	{
		$note_arr[$n->note] = $n->anmerkung;
		$note_wert[$n->note] = $n->notenwert;
	}

	if(isset($_GET['ss']))
		$studiensemester_kurzbz = $_GET['ss'];
	else
		die('Studiensemester nicht uebergeben');

	//Daten holen

	$xml = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>\n";
	$xml .= "<studienerfolge>";

	if(isset($_GET['all']))
	{
		for ($i = 0; $i < sizeof($uid_arr); $i++)
		{
			//Studienbestaetigung fuer alle Semester dieses Studenten
			$qry = "SELECT * FROM public.tbl_studiensemester 
					WHERE studiensemester_kurzbz in(
						SELECT studiensemester_kurzbz 
						FROM lehre.tbl_zeugnisnote WHERE student_uid=".$db->db_add_param($uid_arr[$i]).") 
					ORDER BY start";
			if($db->db_query($qry))
				while($row = $db->db_fetch_object())
					draw_studienerfolg($uid_arr[$i], $row->studiensemester_kurzbz);
		}
	}
	else
	{
		//Studienbestaetigung fuer ein bestimmtes Semester
		for ($i = 0; $i < sizeof($uid_arr); $i++)
		{
			draw_studienerfolg($uid_arr[$i], $studiensemester_kurzbz);
		}
	}

	$xml .= "</studienerfolge>";
	echo $xml;
}
?>

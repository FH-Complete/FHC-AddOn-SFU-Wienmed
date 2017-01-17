<?php
/* Copyright (C) 2015 Technikum-Wien
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
 * Authors: Nikolaus Krondraf <nikolaus.krondraf@technikum-wien.at>
 */
require_once('../../../config/vilesci.config.inc.php');
require_once('../../../include/functions.inc.php');
require_once('../../../include/studiengang.class.php');
require_once('../../../include/student.class.php');
require_once('../../../include/benutzerberechtigung.class.php');
require_once('../../../include/zeugnisnote.class.php');
require_once('../../../include/datum.class.php');
require_once('../../../include/note.class.php');
require_once('../../../include/studiensemester.class.php');
require_once('../../../include/studienplan.class.php');
require_once('../../../include/prestudent.class.php');
require_once('../../../include/lehrveranstaltung.class.php');
require_once('../../../include/mitarbeiter.class.php');

if(!$db = new basis_db())
    die('Es konnte keine Verbindung zum Server aufgebaut werden.');

$user = get_uid();

$rechte = new benutzerberechtigung();
$rechte->getBerechtigungen($user);
if(!$rechte->isBerechtigt('student/noten', null, 'suid'))
    die('Sie haben keine Berechtigung für diese Seite');

if(!isset($_GET["uid"]))
    die('Fehler: Studenten-UID muss übergeben werden');

// Note speichern
if(isset($_POST['new'])) 
{
    if (!$rechte->isBerechtigt('student/noten', null, 'suid'))
        die('Sie haben keine Berechtigung für diese Seite');
    $datum = new datum();
    
    $zeugnisnote = new zeugnisnote();
    $zeugnisnote->new = true;
    $zeugnisnote->lehrveranstaltung_id = $_POST["lehrveranstaltung_id"];
    $zeugnisnote->student_uid = $_POST["student_uid"];
    $zeugnisnote->studiensemester_kurzbz = $_POST["studiensemester_kurzbz"];
    $zeugnisnote->note = $_POST["note"];
    $zeugnisnote->benotungsdatum = $datum->formatDatum($_POST["benotungsdatum"]);
    $zeugnisnote->insertvon = $user;
    $zeugnisnote->insertamum = date('Y-m-d H:i:s', time());
    $zeugnisnote->bemerkung = $_POST["lektor"];
    if (!$zeugnisnote->save())
        echo "<p style='color: red;'>Die Note konnte nicht gespeichert werden: " . $zeugnisnote->errormsg . "<p>";
    else
        echo "<p style='color: green;'>Note erfolgreich gespeichert</p>";
}

// Student und Prestudent laden
$student = new student($_GET["uid"]);
$prestudent = new prestudent();
$prestudent->getFirstStatus($student->prestudent_id, 'Student');
$prestudent->getLastStatus($student->prestudent_id, 'Student');

// Stammdaten der Noten und Studiensemester laden
$noten = new note();
$noten->getAll();
$studiensemester = new studiensemester;
$studiensemester->getAll("desc");
$aktStudSem = $studiensemester->getakt();

// Studienplan und LVs laden
$studienplan = new studienplan();
$studienplan->loadStudienplan($prestudent->studienplan_id);
$lehrveranstaltung = new lehrveranstaltung();
$lehrveranstaltung->loadLehrveranstaltungStudienplan($prestudent->studienplan_id);
$lehrveranstaltungen = array();
foreach($lehrveranstaltung->lehrveranstaltungen as $lv)
{
    if($lv->lehrform_kurzbz != "MOD")
        $lehrveranstaltungen[$lv->lehrveranstaltung_id] = $lv->bezeichnung . " (" . $lv->lehrform_kurzbz . ")";
}
asort($lehrveranstaltungen);

// Lektoren laden
$mitarbeiter = new mitarbeiter();
$lektoren = $mitarbeiter->getMitarbeiter(true, null, $student->studiengang_kz);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Zeugnisnote eintragen</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../../../skin/fhcomplete.css" type="text/css">
        <link rel="stylesheet" href="../../../skin/vilesci.css" type="text/css">
        <link rel="stylesheet" href="../../../skin/jquery.css" type="text/css">
        <link rel="stylesheet" href="../../../skin/tablesort.css" type="text/css">
        <link rel="stylesheet" href="../../../skin/jquery-ui-1.9.2.custom.min.css" type="text/css">
        <script type="text/javascript" src="../../../include/js/jquery1.9.min.js" ></script>
        <script type="text/javascript">
            $(document).ready(function()
			{
				$("#datepicker_datum").datepicker($.datepicker.regional['de']);
            })
        </script>
        <style>
            label 
            {
                width: 150px;
                display: inline-block;
            }
        </style>
    </head>
    <body>
        <h2>Zeugnisnote eintragen</h2>
        <form name="zeugnisnote" method="post" action="zeugnisnote.php?uid=<?php echo $student->uid ?>">
            <p>
                <label>Name:</label>
                <?php echo $student->vorname . " " . $student->nachname; ?>
                <input type="hidden" name="student_uid" value="<?php echo $student->uid ?>" id="uid"/>
            </p>
            <p>
                <label>Studienplan:</label>
                <?php echo $studienplan->bezeichnung; ?>
            </p>
            <p>
                <label>Lehrveranstaltung:</label>
                <select name="lehrveranstaltung_id">
                <?php
                  foreach($lehrveranstaltungen as $key => $value)
                  {
                      echo "<option value='" . $key . "'>" . $value . "</option>";
                  }
                ?>
                </select>
            </p>
            <p>
                <label>Studiensemester:</label>
                <select name="studiensemester_kurzbz">
                <?php foreach($studiensemester->studiensemester as $semester)
                {
                    $semester->studiensemester_kurzbz == $aktStudSem ? $selected = " selected" : $selected = "";
                    echo "<option value='" . $semester->studiensemester_kurzbz . "'" . $selected . ">" . $semester->bezeichnung . "</option>";
                }
                ?>
                </select>
            </p>
            <p>
                <label>Note:</label>
                <select name="note">
                <?php foreach($noten->result as $note)
                {
                    echo "<option value='" . $note->note . "'>" . $note->bezeichnung . "</option>";
                }
                ?>
                </select>
            </p>
            <p>
                <label>Benotungsdatum:</label>
                <input type="text" name="benotungsdatum" id="datepicker_datum">
            </p>
            <p>
                <label>Lektor:</label>
                <select name="lektor">
                <?php foreach($lektoren as $lektor)
                {
                    $lektorBez = "";
                    if($lektor->titelpre != "")
                        $lektorBez .= $lektor->titelpre . " ";
                    
                    $lektorBez .= $lektor->vorname . " " . $lektor->nachname;
                    
                    if($lektor->titelpost != "")
                        $lektorBez .= ", " . $lektor->titelpost;
                        
                    echo "<option value='" . $lektorBez . "'>" . $lektorBez . "</option>";
                }
                ?>
                </select>
            </p>
            <p>
                <input type="hidden" name="type" value="new">
                <input type="submit" name="new" value="Hinzuf&uuml;gen">
            </p>
        </form>
    </body>
</html>

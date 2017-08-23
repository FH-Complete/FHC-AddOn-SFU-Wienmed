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
 * Authors: Gerald Raab <gerald.raab@technikum-wien.at>
 *
 */
/**
 * Script zum befüllen von Spezialgruppen
 */
require_once('../../../config/vilesci.config.inc.php');
require_once('../../../include/functions.inc.php');
require_once('../../../include/basis_db.class.php');
require_once('../../../include/phrasen.class.php');
require_once('../../../include/gruppe.class.php');
require_once('../../../include/benutzerberechtigung.class.php');
require_once('../../../include/benutzergruppe.class.php');

$p = new phrasen(DEFAULT_LANGUAGE);
ini_set('display_errors','0');
//error_reporting(E_ALL);

$user = get_uid();
$rechte = new benutzerberechtigung();
$rechte->getBerechtigungen($user);
if(!$rechte->isBerechtigt('admin'))
	die($p->t('global/FuerDieseAktionBenoetigenSieAdministrationsrechte'));

$grp_str = '';
$gruppenaufteilung = '';
$gruppenaufteilung_err = '';

$semester = NULL;
if (isset($_REQUEST["sem"]) && $_REQUEST["sem"] > 0)
	$semester = $_REQUEST["sem"];
// TODO: Studiengang und Studiensemester auswählbar machen
$studiengang_kz = 1;
$studiensemester_kurzbz = 'WS2017';

$groups = new gruppe();
$groups->getgruppe($studiengang_kz = $studiengang_kz, $semester = $semester, NULL, NULL, NULL, NULL, $order = 'gruppe_kurzbz');
foreach ($groups->result as $res)
{
	$grp_str .= '<input type="checkbox" name="grp[]" value="'.$res->gruppe_kurzbz.'"> '.$res->gruppe_kurzbz.'<br>';
}

$sem_dd_str = '<option value="-1">Alle anzeigen</option>';
for ($i=1; $i<=6; $i++)
{
	if (isset($_REQUEST["sem"]) && $_REQUEST["sem"] == $i)
		$sel = ' selected';
	else {
		$sel = '';
	}
	$sem_dd_str .= '<option value="'.$i.'"'.$sel.'>'.$i.'</option>';
}

$grp_arr = array();
if (isset($_REQUEST["send"]))
{
	foreach ($_REQUEST["grp"] as $gruppe)
	{
		array_push($grp_arr, $gruppe);
	}
}


$studlist = NULL;
if (isset($_REQUEST["stud"]) && $_REQUEST["stud"] != '')
{
	$studis = trim($_REQUEST["stud"]);
	$studlist = explode(PHP_EOL, $_REQUEST['stud']);
	$stud_arr = array();
	foreach ($studlist as $uid)
	{

		if ($uid)
			array_push($stud_arr, trim($uid));

	}
}

if ($studlist && count($grp_arr) > 0)
{
	$bengrp = new benutzergruppe();
	shuffle($stud_arr);
	$studcounter = 0;
	$grpcounter = 0;
	$stud_gesamt = count($stud_arr);
	$grp_gesamt = count($grp_arr);
	$stud_pro_grp = intval($stud_gesamt/$grp_gesamt);
	$extras = $stud_gesamt - $stud_pro_grp * $grp_gesamt;
	if ($extras > 0)
		$ext = 1;
	else {
		$ext = 0;
	}
	$gruppenaufteilung = '';
	for ($gruppen = 0; $gruppen < $extras; $gruppen++)
	{
		$gruppenaufteilung .= $grp_arr[$gruppen].': ';
		for ($s = 0; $s < $stud_pro_grp+$ext; $s++)
		{


			$bengrp->new = true;
			$bengrp->uid = $stud_arr[$s+$studcounter];
			$bengrp->gruppe_kurzbz = $grp_arr[$gruppen];
			$bengrp->updateamum = NULL;
			$bengrp->updatevon = NULL;
			$bengrp->insertvon = $user;
			$bengrp->insertamum = NULL;
			$bengrp->studiensemester_kurzbz = $studiensemester_kurzbz;
			if ($bengrp->save())
				$gruppenaufteilung .= $stud_arr[$s+$studcounter].',';
			else {
				$gruppenaufteilung_err .= $stud_arr[$s+$studcounter].',';
			}


		}
		$gruppenaufteilung .= '<br>';
		$studcounter+=$stud_pro_grp+$ext;
	}

	for ($gruppen = 0; $gruppen < $grp_gesamt-$extras; $gruppen++)
	{
		$gruppenaufteilung .= $grp_arr[$gruppen+$extras].': ';
		for ($s = 0; $s < $stud_pro_grp; $s++)
		{

			$bengrp->new = true;
			$bengrp->uid = $stud_arr[$s+$studcounter];
			$bengrp->gruppe_kurzbz = $grp_arr[$gruppen+$extras];
			$bengrp->updateamum = NULL;
			$bengrp->updatevon = NULL;
			$bengrp->insertvon = $user;
			$bengrp->insertamum = NULL;
			$bengrp->studiensemester_kurzbz = $studiensemester_kurzbz;
			if ($bengrp->save())
				$gruppenaufteilung .= $stud_arr[$s+$studcounter].',';
			else {
				$gruppenaufteilung_err .= $stud_arr[$s+$studcounter].',';
			}
		}
		$gruppenaufteilung .= '<br>';
		$studcounter+=$stud_pro_grp;
	}

	$msg_str = count($studlist).' / '.count($grp_arr).' - '.$stud_pro_grp;

}
if ($gruppenaufteilung_err != '')
	$gruppenaufteilung_err = 'Fehler bei folgenden UIDs: '.$gruppenaufteilung_err;
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>

<h1>Gruppen bef&uuml;llen</h1>
<form name="populategroups" action='populate_groups.php' method="POST">
Semester
<select name="sem" onchange="document.populategroups.submit();">
	<?php echo $sem_dd_str; ?>
</select>
<br><br>

<table>
	<tr>
		<th>Matrikelnummer*</th>
		<th>=></th>
		<th>Gruppen**</th>
	</tr>
	<tr>
		<td valign="top">
			<textarea name="stud" cols="20" rows="42"></textarea>
		</td>
		<td></td>
		<td valign="top">
			<?php echo $grp_str; ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<input type="submit" name="send" value="Send">
		</td>
	</tr>
</table>
</form>
<div style="color:green;"><?php echo $gruppenaufteilung; ?></div>
<div style="color:red;"><?php echo $gruppenaufteilung_err; ?></div>

* eine Matrikelnummer pro Zeile<br>
** die Matrikelnummern werden gleichmäßig und zufällig auf die gewählten Gruppen verteilt
</body>
</html>

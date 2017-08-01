<?php
/* Copyright (C) 2017 fhcomplete.org
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
 */
/**
 * Exportiert die Spezialgruppen fuer Mailverteiler
 * Format:
 * BIF1SPZ1: uid1, uid2, uid3
 *
 * Wenn ein Fehler auftritt wird ein Exit-Code 1 zurueckgegeben
 */
require_once(dirname(__FILE__).'/../../../../config/vilesci.config.inc.php');
require_once(dirname(__FILE__).'/../../../../include/functions.inc.php');
require_once(dirname(__FILE__).'/../../../../include/globals.inc.php');
require_once(dirname(__FILE__).'/../../../../include/studiensemester.class.php');
require_once(dirname(__FILE__).'/../../../../include/studiengang.class.php');
require_once(dirname(__FILE__).'/../../../../include/benutzerberechtigung.class.php');

$db = new basis_db();

// Wenn das Script nicht ueber Commandline gestartet wird, muss eine
// Authentifizierung stattfinden
if(php_sapi_name() != 'cli')
{
	$uid = get_uid();
	$rechte = new benutzerberechtigung();
	$rechte->getBerechtigungen($uid);
	if(!$rechte->isBerechtigt('admin'))
	{
		echo 'Sie haben keine Berechtigung fuer diese Seite';
		exit(1);
	}
}

$ss = new studiensemester();
$ss_nearest = $ss->getaktorNearest();
$ss_nearest_to_akt = $ss->getNearestFrom($ss_nearest);

if ($ss_nearest == '' || $ss_nearest_to_akt == '')
{
	echo 'Failed to get Semester';
	exit(1);
}


$sql_query = "SELECT
					distinct gruppe_kurzbz, studiengang_kz, tbl_benutzer.uid
				FROM
					public.tbl_gruppe
					JOIN public.tbl_studiengang USING(studiengang_kz)
					JOIN public.tbl_benutzergruppe USING(gruppe_kurzbz)
					JOIN public.tbl_benutzer ON(tbl_benutzergruppe.uid = tbl_benutzer.uid)
				WHERE
					tbl_studiengang.aktiv
					AND tbl_gruppe.mailgrp = true
					AND tbl_benutzergruppe.uid NOT LIKE '\\\\_%'
					AND
						(
							(
								tbl_studiengang.studiengang_kz=10005 AND
								(studiensemester_kurzbz IS NULL
								OR
								studiensemester_kurzbz in(".$db->db_add_param($ss_nearest).",".$db->db_add_param($ss_nearest_to_akt).")
								)
							)
							OR
							(
								tbl_studiengang.studiengang_kz!=10005 AND
								(studiensemester_kurzbz IS NULL
								OR
								studiensemester_kurzbz =".$db->db_add_param($ss_nearest)."
								)
							)
						)
					AND tbl_benutzer.aktiv
				ORDER BY gruppe_kurzbz";

	if ($result_mg = $db->db_query($sql_query))
	{
		while ($row_mg = $db->db_fetch_object($result_mg))
		{
			$gruppen[$row_mg->gruppe_kurzbz][]=$row_mg->uid;
		}

		foreach($gruppen as $gruppe=>$uids)
		{
			if(count($uids)>0)
				echo $gruppe.': '.implode(',',$uids)."\n";
		}
	}
	else
	{
		echo 'Query failed'.$qry;
		exit(1);
	}

?>

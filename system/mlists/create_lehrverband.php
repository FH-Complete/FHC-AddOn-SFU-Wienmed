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
 * Exportiert die Lehrverbandsgruppen fuer Mailverteiler
 * Format:
 * gruppe: uid1, uid2, uid3
 *
 * Wenn ein Fehler auftritt wird ein Exit-Code 1 zurueckgegeben
 */
require_once(dirname(__FILE__).'/../../../../config/vilesci.config.inc.php');
require_once(dirname(__FILE__).'/../../../../include/functions.inc.php');
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

$stg = new studiengang();
if($stg->getAll())
{
	foreach($stg->result as $row_stg)
	{
		$stg_kz = $row_stg->studiengang_kz;
		$stg_kzbz = $row_stg->kuerzel;
		$gruppen = array();

		$sql_query = "
			SELECT
				DISTINCT semester, verband, gruppe, student_uid
			FROM
				public.tbl_student
				JOIN public.tbl_benutzer ON(tbl_student.student_uid = tbl_benutzer.uid)
			WHERE
				studiengang_kz=".$db->db_add_param($stg_kz)."
				AND student_uid NOT LIKE '\\\\_%'
				AND semester<10
				AND semester>0
				AND tbl_benutzer.aktiv
			ORDER BY semester";

		if($result = $db->db_query($sql_query))
		{
			while($row = $db->db_fetch_object($result))
			{
				$gruppen[$stg_kzbz.$row->semester][] = $row->student_uid;
				if(trim($row->verband)!='')
				{
					$gruppen[$stg_kzbz.$row->semester.$row->verband][] = $row->student_uid;
					if(trim($row->gruppe)!='')
						$gruppen[$stg_kzbz.$row->semester.$row->verband.$row->gruppe][] = $row->student_uid;
				}
			}
		}
		else
		{
			exit(1);
		}

		foreach($gruppen as $name=>$teilnehmer)
		{
			if(count($teilnehmer)>0)
				echo $name.': '.implode(',',$teilnehmer)."\n";
		}
	}
}
else
{
	echo $stg->errormsg;
	exit(1);
}

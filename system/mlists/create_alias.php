<?php
/* Copyright (C) 2017 fhcomplete.org
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
 * Exportiert die Mail Aliase
 * Format: alias: uid
 *
 * Wenn ein Fehler auftritt wird ein Exit-Code 1 zurueckgegeben
 */
require_once(dirname(__FILE__).'/../../../../config/vilesci.config.inc.php');
require_once(dirname(__FILE__).'/../../../../include/functions.inc.php');
require_once(dirname(__FILE__).'/../../../../include/globals.inc.php');
require_once(dirname(__FILE__).'/../../../../include/studiensemester.class.php');
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

//Zusammenbauen der Studiengaenge die keine Alias Adressen bekommen

$noalias_kz = $db->db_implode4SQL($noalias);

$qry = "SELECT
			vorname, nachname, uid, alias
		FROM
			public.tbl_person
			JOIN public.tbl_benutzer USING(person_id)
			LEFT JOIN public.tbl_student ON(uid=student_uid)
		WHERE
			alias is not null";

if($noalias_kz!='')
	$qry.=" AND (studiengang_kz NOT IN($noalias_kz) OR studiengang_kz is null)";

$qry.="	AND (tbl_benutzer.aktiv OR
				(tbl_benutzer.aktiv=false
				AND updateaktivam >= now()-(SELECT CASE public.get_rolle_prestudent (prestudent_id,null)
											WHEN 'Abbrecher' THEN '".DEL_ABBRECHER_WEEKS." weeks'::interval
											WHEN 'Absolvent' THEN '".DEL_STUDENT_WEEKS." weeks'::interval
											ELSE '".DEL_MITARBEITER_WEEKS." weeks'::interval
											END
											)
				)
			)
ORDER BY nachname, vorname";

if($result = $db->db_query($qry))
{
	while($row = $db->db_fetch_object($result))
		echo $row->alias.": ".$row->uid."\n";
}
else
{
	exit(1);
}
?>

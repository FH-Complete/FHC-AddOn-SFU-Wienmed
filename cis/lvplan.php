<?php
/* Copyright (C) 2008 Technikum-Wien
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
 * Authors: Gerald Raab 	<gerald.raab@technikum-wien.at >
 */

require_once('../../../config/cis.config.inc.php');
require_once('../../../config/global.config.inc.php');
require_once('../../../include/basis_db.class.php');
require_once('../../../include/functions.inc.php');
require_once('../../../include/phrasen.class.php');
require_once('../../../include/studiensemester.class.php');
require_once('../../../include/benutzer.class.php');
require_once('../../../include/benutzerberechtigung.class.php');

$sprache = getSprache();
$p=new phrasen($sprache);

$uid=get_uid();
$berechtigung=new benutzerberechtigung();
$berechtigung->getBerechtigungen($uid);


if (!$db = new basis_db())
	die($p->t('global/fehlerBeimOeffnenDerDatenbankverbindung'));

if (!$uid=get_uid())
	die('Sie sind nicht angemeldet. Es wurde keine Benutzer UID gefunden ! <a href="javascript:history.back()">Zur&uuml;ck</a>');

if($berechtigung->isBerechtigt('admin') && $_GET['uid'])
        $uid = $_GET["uid"];

$sql_query="SELECT titelpre, titelpost, uid, nachname, vorname FROM campus.vw_benutzer WHERE uid LIKE '$uid'";
	//echo $sql_query;
$result=$db->db_query($sql_query);

if($db->db_num_rows($result)==0)
{
	//GastAccount
	$titelpre='';
	$titelpost='';
	$uid='';
	$nachname='';
	$vornamen='';
	//echo "User not found!";
}
else
{
	$titelpre=$db->db_result($result,0,'"titelpre"');
	$titelpost=$db->db_result($result,0,'"titelpost"');
	$uid=$db->db_result($result,0,'"uid"');
	$nachname=$db->db_result($result,0,'"nachname"');
	$vornamen=$db->db_result($result,0,'"vorname"');
}



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Lehrveranstaltungsplan</title>
<link href="../../../skin/style.css.php" rel="stylesheet" type="text/css">
<link href="../../../skin/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css">
<script src="../../../include/js/jquery1.9.min.js" type="text/javascript"></script>

</head>

<body id="inhalt">
<h1><?php echo $p->t("lvplan/lehrveranstaltungsplan");?></h1>
<table class="cmstable" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td class="cmscontent" valign="top">
<?php
        echo'
        <h2>'.$p->t('lvplan/persoenlichenAbonnieren').'</h2>
        <div>
        <a class="Item" href="../../../cms/content.php?content_id='.$p->t('dms_link/lvplanSyncFAQ').'" target="_blank">'.$p->t('lvplan/anleitungLVPlanSync').'</a>
        <br><br>';

        echo '<table>';
        $caldavurl = APP_ROOT.'webdav/lvplan.php/calendars/'.$uid.'/LVPlan-'.$uid;
        echo '<tr><td colspan="2"><b>Android</b><br>Installieren Sie eine CalDav-Sync-App (z.B. CalDAV Sync Free Beta) aus dem Play-Store und fügen Sie einen Account mit folgender URL hinzu:</td></tr>';
        echo '<tr><td valign="top">'.$p->t('lvplan/caldavURL').':</td><td>'.$caldavurl.'<br><br></td></tr>';
        echo '<tr><td colspan="2"><b>IPhone</b><br>fügen Sie unter Einstellungen einen Kalender-Account vom Typ CalDav mit folgender URL hinzu:</td></tr>';
        echo '<tr><td valign="top">'.$p->t('lvplan/caldavURLMac').':</td><td>'.APP_ROOT.'webdav/lvplan.php/principals/'.$uid.'<br><br></td></tr>';
        echo '<tr><td colspan= "2"><b>Andere Kalenderapplikationen (Outlook, Google ...)</b><br>verbinden Sie folgende URL als ICAL/Internetkalender:</td></tr>';
        echo '<tr><td><a class="Item" href="'.APP_ROOT.'webdav/google.php?cal='.encryptData($uid,LVPLAN_CYPHER_KEY).'&'.microtime(true).'">'.$p->t('lvplan/googleURL').'</a>:</td><td>'.APP_ROOT.'webdav/google.php?cal='.encryptData($uid,LVPLAN_CYPHER_KEY).'&'.microtime(true).'</td></tr>';
        echo '</table>';
        echo '  </div>';
?>

</td>
</tr>
</tbody>
</table>
</body>
</html>

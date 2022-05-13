<?php
/*
Plugin Name: EZ SQL Reports Shortcode Widget and DB Backup
Plugin URI: http://wordpress.ieonly.com/category/my-plugins/sql-reports/
Author: Eli Scheetz
Author URI: https://supersecurehosting.com/
Description: Create and save SQL queries, run them from the Reports tab in your Admin, place them on the Dashboard for certain User Roles, or place them on Pages and Posts using the shortcode. And keep your database safe with scheduled backups.
Version: 5.21.35
*/
foreach (array("plugins_url", "get_option", "add_filter", "add_action", "add_shortcode", "register_activation_hook") as $func)
	if (!function_exists("$func"))
		die('You are not allowed to call this page directly.<p>You could try starting <a href="/">here</a>.');
/*            ___
 *           /  /\     ELISQLREPORTS Main Plugin File
 *          /  /:/     @package ELISQLREPORTS
 *         /__/::\
 Copyright \__\/\:\__  © 2011-2021 Eli Scheetz (email: support@supersecurehosting.com)
 *            \  \:\/\
 *             \__\::/ This program is free software; you can redistribute it
 *     ___     /__/:/ and/or modify it under the terms of the GNU General Public
 *    /__/\   _\__\/ License as published by the Free Software Foundation;
 *    \  \:\ /  /\  either version 2 of the License, or (at your option) any
 *  ___\  \:\  /:/ later version.
 * /  /\\  \:\/:/
  /  /:/ \  \::/ This program is distributed in the hope that it will be useful,
 /  /:/_  \__\/ but WITHOUT ANY WARRANTY; without even the implied warranty
/__/:/ /\__    of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
\  \:\/:/ /\  See the GNU General Public License for more details.
 \  \::/ /:/
  \  \:\/:/ You should have received a copy of the GNU General Public License
 * \  \::/ with this program; if not, write to the Free Software Foundation,
 *  \__\/ Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA        */

$GLOBALS["ELISQLREPORTS"] = array("query_times" => array(), "reports_keys" => array(), "backup_file" => false, "Report_SQL" => "",
	"images_path" => plugins_url("/images/", __FILE__), 
	"create-report-url" => admin_url("admin.php?page=ELISQLREPORTS-create-report"),
	"reports_array" => get_option("ELISQLREPORTS_reports_array", array()),
	"settings_array" => get_option("ELISQLREPORTS_settings_array", array()));
if (!(isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["default_styles"])))
	$GLOBALS["ELISQLREPORTS"]["settings_array"]["default_styles"] = "overflow: auto;";
foreach (array_keys($GLOBALS["ELISQLREPORTS"]["reports_array"]) as $GLOBALS["ELISQLREPORTS"]["reports_key"])
	$GLOBALS["ELISQLREPORTS"]["reports_keys"][sanitize_title($GLOBALS["ELISQLREPORTS"]["reports_key"])] = $GLOBALS["ELISQLREPORTS"]["reports_key"];
$GLOBALS["ELISQLREPORTS"]["boxes"] = array("Saved Reports"=>"",
"Plugin Links"=>'<a target="_blank" href="https://www.paypal.com/donate?hosted_button_id=ZN3QCSQ74R5J6"><img id="pp_button" src="'.$GLOBALS["ELISQLREPORTS"]["images_path"].'btn_donateCC_WIDE.gif" border="0" alt="Make a Donation with PayPal"></a>
<ul class="sidebar-links">
	<li><a target="_blank" href="https://wordpress.org/support/plugin/elisqlreports/reviews/">Plugin Reviews on wordpress.org</a></li>
	<li><a target="_blank" href="https://wordpress.org/plugins/elisqlreports/#faq">Plugin FAQs on wordpress.org</a></li>
	<li><a target="_blank" href="https://wordpress.org/support/plugin/elisqlreports/">Forum Posts on wordpress.org</a></li>
	<li><a target="_blank" href="http://wordpress.ieonly.com/category/my-plugins/sql-reports/">Plugin Posts on Eli\'s Blog</a></li>
</ul>',
"Edit Report"=>'<div id="SQLFormDel" style="width: 256px;"><input type="submit" style="float: right; background-color: #F00;" value="DELETE REPORT" onclick="if (confirm(\'Are you sure you want to DELETE This Report?\')) { document.SQLForm.action=\'admin.php?page=ELISQLREPORTS-create-report\'; document.SQLForm.rSQL.value=\'DELETE_REPORT\'; document.SQLForm.rName.value=oldName; }"><input style="float: left;" type="button" value="Edit SQL" onclick="showhide(\'SQLFormEdit\', true); this.style.display=\'none\'; document.SQLForm.rSQL.focus();"><br style="clear: both;" /></div><div id="SQLFormSaveTo"></div>',
"Save Settings"=>'<input type="submit" value="Save Settings" class="button-primary" style="float: right;"><br style="clear: right;" />');

function ELISQLREPORTS_install() {
	global $wp_version;
	if (version_compare($wp_version, "2.6", "<"))
		die(sprintf(__("Upgrade to %s now!",'elisqlreports'), "2.6"));
}
register_activation_hook(__FILE__, "ELISQLREPORTS_install");

function ELISQLREPORTS_display_header($pTitle, $optional_box = array()) {
	echo '<script type="text/javascript">
function showhide(id) {
	divx = document.getElementById(id);
	if (divx) {
		if (divx.style.display == "none" || arguments[1]) {
			divx.style.display = "block";
			divx.parentNode.className = (divx.parentNode.className+"close").replace(/close/gi,"");
			return true;
		} else {
			divx.style.display = "none";
			return false;
		}
	}
}
</script>
<h1 id="top_title">'.$pTitle.'</h1>
<div id="admin-page-container">
<form method="POST" name="SQLForm" id="SQLForm" action="'.str_replace('&amp;','&', htmlspecialchars( $_SERVER['REQUEST_URI'] , ENT_QUOTES ) ).'">
	<div id="ELISQLREPORTS-right-sidebar" style="width: 300px;" class="metabox-holder">';
	if (is_array($optional_box)) {
		$js = '
<script type="text/javascript">
function stuffbox_showhide(id) {
	divx = document.getElementById(id);
	if (divx) {
		if (divx.style.display == "none" || arguments[1]) {';
		$else = '
			if (divx = document.getElementById("ELISQLREPORTS-right-sidebar"))
				divx.style.width = "30px";
			if (divx = document.getElementById("ELISQLREPORTS-main-section"))
				divx.style.marginRight = "40px";';
		foreach ($optional_box as $bTitle) {
			$md5 = md5($bTitle);
			echo '
	<div id="box_'.$md5.'" class="stuffbox"><h3 title="Click to toggle" onclick="stuffbox_showhide(\'inside_'.$md5.'\');" style="cursor: pointer;" class="hndle"><span id="title_'.$md5.'">'.$bTitle.'</span></h3>
		<div id="inside_'.$md5.'" style="padding: 10px;" class="inside">
'.$GLOBALS["ELISQLREPORTS"]["boxes"][$bTitle].'
		</div>
	</div>';
			$js .= "\nif (divx = document.getElementById('inside_$md5'))\n\tdivx.style.display = 'block';\nif (divx = document.getElementById('title_$md5'))\n\tdivx.innerHTML = '".preg_replace("/\\\\/", "\\\\\\\\", preg_replace("/'/", "'+\"'\"+'", preg_replace('/\\+n/', "", $bTitle)))."';";
			$else .= "\nif (divx = document.getElementById('inside_$md5'))\n\tdivx.style.display = 'none';\nif (divx = document.getElementById('title_$md5'))\n\tdivx.innerHTML = '".substr($bTitle, 0, 1)."';";
		}
		echo $js.'
			if (divx = document.getElementById("ELISQLREPORTS-right-sidebar"))
				divx.style.width = "300px";
			if (divx = document.getElementById("ELISQLREPORTS-main-section"))
				divx.style.marginRight = "310px";
			return true;
		} else {'.$else.'
			return false;
		}
	}
}
function getWindowWidth(min) {
	if (typeof window.innerWidth != "undefined" && window.innerWidth > min)
		min = window.innerWidth;
	else if (typeof document.documentElement != "undefined" && typeof document.documentElement.clientWidth != "undefined" && document.documentElement.clientWidth > min)
		min = document.documentElement.clientWidth;
	else if (typeof document.getElementsByTagName("body")[0].clientWidth != "undefined" && document.getElementsByTagName("body")[0].clientWidth > min)
		min = document.getElementsByTagName("body")[0].clientWidth;
	return min;
}
if (getWindowWidth(780) == 780) 
	setTimeout("stuffbox_showhide(\'inside_'.$md5.'\')", 200);
</script>';
	} else
		echo $optional_box;
	echo '
	</div>
	<div id="ELISQLREPORTS-main-section" style="margin-right: 310px;">
		<div class="metabox-holder" style="width: 100%;" id="ELISQLREPORTS-metabox-container">';
}

function ELISQLREPORTS_set_backupdir() {
	$err403 = "<?php // Silence is golden.";
	if (!(isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]) && strlen($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]) && is_dir($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]))) {
		$upload = wp_upload_dir();
		$GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"] = trailingslashit($upload["basedir"]).'SQL_Backups';
		if (!is_dir($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]) && !@mkdir($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"] = $upload["basedir"];
		if (!is_file(trailingslashit($upload["basedir"]).'index.php'))
			@file_put_contents(trailingslashit($upload["basedir"]).'index.php', $err403);
	}
	if (!is_file(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]).'.htaccess'))
		@file_put_contents(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]).'.htaccess', "Options -Indexes");
	if (!is_file(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]).'index.php'))
		@file_put_contents(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]).'index.php', $err403);
}

function ELISQLREPORTS_make_Backup($date_format, $backup_type = "manual", $db_name = DB_NAME, $db_host = DB_HOST, $db_user = DB_USER, $db_password = DB_PASSWORD) {
	global $wpdb, $wp_version;
	ELISQLREPORTS_set_backupdir();
	$db_date = date($date_format);
	$db_port = '';
	if (strpos($db_host, ':')) {
		list($db_host, $db_port) = explode(':', $db_host, 2);
		if (is_numeric($db_port))
			$db_port = ' --port='.escapeshellarg($db_port);
		else
			$db_port = ' --socket='.escapeshellarg($db_port);
	}
	$db_port .= ' ';
	$subject = "$backup_type.$db_name.$db_host.sql";
	$filename = "z.$db_date.$subject";
	$backup_file = trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]).$filename;
	$content = '';
	$return = '<div class="error">';
	$uid = md5(time());
	$message = "\r\n--$uid\r\nContent-type: text/html; charset=\"iso-8859-1\"\r\nContent-Transfer-Encoding: 7bit\r\n\r\n";
	if (isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_method"]) && $GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_method"] == 1) {
		$mysqlbasedir = $wpdb->get_row("SHOW VARIABLES LIKE 'basedir'");
		if(substr(PHP_OS,0,3) == 'WIN')
			$backup_command = '"'.(isset($mysqlbasedir->Value)?trailingslashit(str_replace('\\', '/', $mysqlbasedir->Value)).'bin/':'').'mysqldump.exe"';
		else
			$backup_command = (isset($mysqlbasedir->Value)&&is_file(trailingslashit($mysqlbasedir->Value).'bin/mysqldump')?trailingslashit($mysqlbasedir->Value).'bin/':'').'mysqldump';		
		$backup_command .= ' --user='.escapeshellarg($db_user).' --password='.escapeshellarg($db_password).' --add-drop-table --skip-lock-tables --host='.escapeshellarg($db_host).$db_port.escapeshellarg($db_name);
		if (isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["compress_backup"]) && $GLOBALS["ELISQLREPORTS"]["settings_array"]["compress_backup"]) {
			$backup_command .= ' | gzip > ';
			$backup_file .= '.gz';
		} else
			$backup_command .= ' -r ';
		passthru($backup_command.escapeshellarg($backup_file), $errors);
		$return .= "Command Line Backup of $subject returned $errors error".($errors!=1?'s':'');
	} elseif ($GLOBALS["ELISQLREPORTS"]["backup_file"] = fopen($backup_file, 'w')) {
		if ($GLOBALS["ELISQLREPORTS"]["backup_connection"] = @mysqli_connect($db_host, $db_user, $db_password, $db_name)) {
			$server = strtolower(isset($_SERVER["HTTP_HOST"])?$_SERVER["HTTP_HOST"]:(isset($_SERVER["SERVER_NAME"])?$_SERVER["SERVER_NAME"]:$_SERVER["SERVER_ADDR"]));
			$ip = explode("$server", get_option("siteurl")."$server");
			if (!(count($ip) == 3 && strlen(trim($ip[1], " \t\r\n/")) > 0))
				$ip[1] = $_SERVER["SERVER_ADDR"];
			fwrite($GLOBALS["ELISQLREPORTS"]["backup_file"], '-- EZ SQL Backup for '.$server.' ('.trim($ip[1]).')
--
-- Host: '.$db_host.'    Database: '.$db_name.'
-- ------------------------------------------------------
-- WordPress version '.$wp_version.'  '.$db_date.'

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE=\'+00:00\' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE=\'NO_AUTO_VALUE_ON_ZERO\' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;');
			$sql = "show full tables where Table_Type = 'BASE TABLE'";
			$result = mysqli_query($GLOBALS["ELISQLREPORTS"]["backup_connection"], $sql);
			$errors = "";
			if (mysqli_errno($GLOBALS["ELISQLREPORTS"]["backup_connection"]))
				$errors .= "/* SQL ERROR: ".mysqli_error($GLOBALS["ELISQLREPORTS"]["backup_connection"])." */\n\n/*$sql*/\n\n";
			else {
				while ($row = mysqli_fetch_row($result)) {
					$errors .= ELISQLREPORTS_get_structure($row[0]);
					if (!is_numeric($rows = ELISQLREPORTS_get_data($row[0])))
						$errors .= $rows;
				}
				mysqli_free_result($result);
				$sql = "show full tables where Table_Type = 'VIEW'";
				if ($result = mysqli_query($GLOBALS["ELISQLREPORTS"]["backup_connection"], $sql)) {
					while ($row = mysqli_fetch_row($result))
						$errors .= ELISQLREPORTS_get_structure($row[0], "View");
					mysqli_free_result($result);
				}
			}
			fclose($GLOBALS["ELISQLREPORTS"]["backup_file"]);
			$return .= "Backup: $subject Saved";
			$message .= "A database backup was saved on <a href='".trailingslashit(get_option("siteurl"))."wp-admin/admin.php?page=ELISQLREPORTS-settings'>".(get_option("blogname"))."</a>.\r\n<p><pre>$errors</pre><p>";
			if (isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["compress_backup"]) && $GLOBALS["ELISQLREPORTS"]["settings_array"]["compress_backup"]) {
				$zip = new ZipArchive();
				if ($zip->open($backup_file.'.zip', ZIPARCHIVE::CREATE) === true) {
					$zip->addFile($backup_file, $filename);
					$zip->close();
				}
				if (is_file($backup_file) && is_file($backup_file).'.zip') {
					if (@unlink($backup_file))
						$backup_file .= '.zip';
				} else
					$return .= " but not Zipped";
			}
		} else
			$return .= 'Database Connection ERROR: '.mysqli_connect_error();
	} else
		$return .= "Failed to save backup!";
	if (isset($GLOBALS["ELISQLREPORTS"]["settings_array"][$backup_type."_backup"]) && $GLOBALS["ELISQLREPORTS"]["settings_array"][$backup_type."_backup"] > 0) {
		$sql_files = array();
		if ($handle = opendir($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"])) {
			while (false !== ($entry = readdir($handle)))
				if (is_file(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]).$entry))
					if (strpos($entry, $subject))
						$sql_files[] = "$entry";
			closedir($handle);
			rsort($sql_files);
		}
		$del=0;
		while (count($sql_files)>$GLOBALS["ELISQLREPORTS"]["settings_array"][$backup_type."_backup"])
			if (@unlink(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]).array_pop($sql_files)))
				$del++;
		$message .= "\r\nNumber of archives:<li>Deleted: $del</li><li>Kept: ".count($sql_files)."</li><p>";
	}
	if (strlen($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_email"])) {
		$headers = 'From: '.get_option("admin_email")."\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: multipart/mixed; boundary=\"$uid\"\r\n";
		$upload = wp_upload_dir();
		if (file_exists($backup_file)) {
			$file_size = filesize($backup_file);
			$handle = fopen($backup_file, "rb");
			$content .= "The backup has been attached to this email for your convenience.\r\n\r\n--$uid\r\nContent-Type: application/octet-stream; name=\"".basename($backup_file)."\"\r\nContent-Transfer-Encoding: base64\r\nContent-Disposition: attachment; filename=\"".basename($backup_file)."\"\r\n\r\n".chunk_split(base64_encode(fread($handle, $file_size)), 70, "\r\n");
			fclose($handle);
		}
		if (mail($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_email"], preg_replace('/^<[^>]+?>/', "", $return), $message.$content."\r\n\r\n--$uid--", $headers))
			$return .= " Email Sent!";
		else
			mail($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_email"], preg_replace('/^<[^>]+?>/', "", $return), $message.strlen($content)." bytes is too large to attach but you can download it <a href='".admin_url("admin.php?page=ELISQLREPORTS-settings&Download_SQL_Backup=".basename($backup_file))."'>here</a>.\r\n\r\n--$uid--", $headers);
	}
	return $return.'</div>';
}
function ELISQLREPORTS_get_structure($table, $type='Table') {
	fwrite($GLOBALS["ELISQLREPORTS"]["backup_file"], "\n\n--\n-- Table structure for ".strtolower($type)." `$table`\n--\n\n");
	$sql = "SHOW CREATE $type `$table`; ";
	if ($result = mysqli_query($GLOBALS["ELISQLREPORTS"]["backup_connection"], $sql)) {
		if ($row = mysqli_fetch_assoc($result))
			fwrite($GLOBALS["ELISQLREPORTS"]["backup_file"], "DROP ".strtoupper($type)." IF EXISTS `$table`;\n/*!40101 SET @saved_cs_client     = @@character_set_client */;\n/*!40101 SET character_set_client = utf8 */;\n".preg_replace('/CREATE .+? VIEW/', 'CREATE VIEW', $row["Create $type"]).";\n/*!40101 SET character_set_client = @saved_cs_client */;");
		mysqli_free_result($result);
	} else
		return "/* requires the SHOW VIEW privilege and the SELECT privilege */\n\n";
	return '';
}
function ELISQLREPORTS_get_data($table) {
	$sql = "SELECT * FROM `$table`;";
	if ($result = mysqli_query($GLOBALS["ELISQLREPORTS"]["backup_connection"], $sql)) {
		$num_rows = mysqli_num_rows($result);
		$num_fields = mysqli_num_fields($result);
		$return = 0;
		if ($num_rows > 0) {
			fwrite($GLOBALS["ELISQLREPORTS"]["backup_file"], "\n\n--\n-- Dumping data for table `$table`\n--\n\nLOCK TABLES `$table` WRITE;\n/*!40000 ALTER TABLE `$table` DISABLE KEYS */;\n");
			$field_type = array();
			$field_list = " (";
			for ($i = 0; $meta = mysqli_fetch_field($result); $i++) {
				array_push($field_type, $meta->type);
				$field_list .= ($i?', ':'')."`$meta->name`";
			}
			$field_list .= ")";
			$field_list = ""; // field_list is not required for insert
			$maxInsertSize = 100000;
			$statementSql = "";
			for ($index = 0; $row = mysqli_fetch_row($result); $index++) {
				$return++;
				if (strlen($statementSql) > $maxInsertSize) {
					fwrite($GLOBALS["ELISQLREPORTS"]["backup_file"], $statementSql.";\n");
					$statementSql = "";
				}
				if (strlen($statementSql) == 0)
					$statementSql = "INSERT INTO `$table`$field_list VALUES ";
				else
					$statementSql .= ",";
				$statementSql .= "(";
				for ($i = 0; $i < $num_fields; $i++) {
					if (is_null($row[$i]))
						$statementSql .= "null";
					else {
						if ($field_type[$i] == 'int')
							$statementSql .= $row[$i];
						else
							$statementSql .= "'" . mysqli_real_escape_string($GLOBALS["ELISQLREPORTS"]["backup_connection"], $row[$i]) . "'";
					}
					if ($i < $num_fields - 1)
						$statementSql .= ",";
				}
				$statementSql .= ")";
			}
			if ($statementSql)
				fwrite($GLOBALS["ELISQLREPORTS"]["backup_file"], $statementSql.";\n/*!40000 ALTER TABLE `$table` ENABLE KEYS */;\nUNLOCK TABLES;");
		}
		mysqli_free_result($result);
	} else
		$return = "SELECT ERROR for `$table`: ".mysqli_error($GLOBALS["ELISQLREPORTS"]["backup_connection"])."\n";
	return $return;
}

function ELISQLREPORTS_view_report($Report_Name = '', $MySQL = '') {
	global $current_user;
	if ($Report_Name == '')
		$Report_Name = 'Unsaved Report';
	elseif ($MySQL == '') {
		if (isset($GLOBALS["ELISQLREPORTS"]["reports_array"][$Report_Name]))
			$MySQL = ($GLOBALS["ELISQLREPORTS"]["reports_array"][$Report_Name]);
		elseif (isset($GLOBALS["ELISQLREPORTS"]["reports_array"][$GLOBALS["ELISQLREPORTS"]["reports_keys"][$Report_Name]])) {
			$Report_Name = $GLOBALS["ELISQLREPORTS"]["reports_keys"][$Report_Name];
			$MySQL = ($GLOBALS["ELISQLREPORTS"]["reports_array"][$Report_Name]);
		} else
			$MySQL = $GLOBALS["ELISQLREPORTS"]["Report_SQL"];
	}
	$report = '<div id="'.sanitize_title($Report_Name).'" class="ELISQLREPORTS-Report-DIV" style="'.$GLOBALS["ELISQLREPORTS"]["settings_array"]["default_styles"].'"><h2 class="ELISQLREPORTS-Report-Name">'.$Report_Name.'</h2>';
	if (is_admin()) {
		if (isset($_GET["SQL_ORDER_BY"]) && is_array($_GET["SQL_ORDER_BY"])) {
			foreach ($_GET["SQL_ORDER_BY"] as $_GET_SQL_ORDER_BY) {
				if (strlen(trim(str_replace("`", '', $_GET_SQL_ORDER_BY)))>0) {
					$_GET_SQL_ORDER_BY = trim(str_replace("`", '', $_GET_SQL_ORDER_BY));
					if ($pos = strripos($MySQL, " ORDER BY "))
						$MySQL = substr($MySQL, 0, $pos + 10)."`".($_GET_SQL_ORDER_BY)."`, ".substr($MySQL, $pos + 10);
					elseif ($pos = strripos($MySQL, " LIMIT "))
						$MySQL = substr($MySQL, 0, $pos)." ORDER BY `".($_GET_SQL_ORDER_BY)."`".substr($MySQL, $pos);
					else
						$MySQL .= " ORDER BY `".($_GET_SQL_ORDER_BY)."`";
				}
			}
		}
	}
	$SQLkey = ELISQLREPORTS_query($MySQL);
	if ($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["rows"] && $GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["result"] && is_array($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["result"]) && count($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["result"]) == $GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["rows"]) {
		$report .= '<table border=1 cellspacing=0 cellpadding=4 class="ELISQLREPORTS-table"><thead><tr class="ELISQLREPORTS-Header-Row">';
		foreach (array_keys($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["result"][0]) as $field) {
			if ($Report_Name == 'Unsaved Report')
				$report .= '<th><b><a href="javascript: document.SQLForm.submit();" onclick="document.SQLForm.action+=\'&SQL_ORDER_BY[]='.$field.'\'">'.$field.'</a></b></th>';
			else
				$report .= '<th><b>'.$field.'</b></th>';
		}
		$OddEven=array('Even','Odd');
		$report .= '</tr></thead><tbody>';
		for ($row=0; $row<count($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["result"]); $row++) {
			$report .= '<tr class="ELISQLREPORTS-Row-'.$row.' ELISQLREPORTS-'.($OddEven[$row%2]).'-Row">';
			foreach ($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["result"][$row] as $value)
				$report .= '<td>'.($value).'</td>';//is_array(maybe_unserialize($value))?print_r(maybe_unserialize($value),1):
			$report .= '</tr>';
		}
		$report .= '</tbody></table>';
	} elseif ($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["errors"])
		foreach ($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["errors"] as $error)
			$report .= '<div class="error"><ul><li>Error: '.(is_admin()?$error:'Query failed!').'</li></ul></div>';
	elseif ($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["rows"])
		$report .= '<div class="updated"><ul><li>Query affected '.$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["rows"].' rows!</li></ul></div>'.print_r(array("<pre>",$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["result"],"</pre>"), 1);
	else
		$report .= '<li>No Results!</li>';
	return do_shortcode($report.'</div>');
}
function ELISQLREPORTS_eval($SQL) {
	global $current_user, $wpdb;
	if (@preg_match_all('/<\?php[\s]*(.+?)[\s]*\?>/i', $SQL, $found)) {
		if (isset($found[1]) && is_array($found[1]) && count($found[1])) {
			foreach ($found[1] as $php_code)
				eval("\$found[2][] = $php_code;");
			$SQL = $wpdb->prepare(preg_replace('/<\?php[\s]*(.+?)[\s]*\?>/i', '%s', str_replace('%', '%%', $SQL)), $found[2]);
		}
	}
	return $SQL;
}
function ELISQLREPORTS_query($SQL) {
	global $wpdb;
	$SQLkey = md5($SQL);
	if (!isset($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey])) {
		$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey] = array("time" => microtime(true), "sql" => $SQL, "result" => false, "rows" => 0, "errors" => array());
		foreach (preg_split('/[\s]*[;]+[\r\n]+[;\s]*/', trim($SQL).";\n") as $SQ) {
			if (strlen($SQ)) {
				$SQ = ELISQLREPORTS_eval($SQ);
				if (strtoupper(substr($SQ, 0, 7)) == "SELECT " || strtoupper(substr($SQ, 0, 5)) == "SHOW ") {
					$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["result"] = $wpdb->get_results($SQ, ARRAY_A);
					$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["rows"] = $wpdb->num_rows;
					if ($wpdb->last_error)
						$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["errors"][] = $wpdb->last_error;
				} elseif ($SQ) {
					$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["rows"] = $wpdb->query($SQ);
					if (strtoupper(substr($SQ, 0, 7)) == "INSERT ")
						$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["result"] = $wpdb->insert_id;
					if (strtoupper(substr($SQ, 0, 7)) == "UPDATE ")
						$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["result"] = 0;
					if ($wpdb->last_error)
						$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["errors"][] = $wpdb->last_error;
				}
			}
		}
		$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["time"] = microtime(true) - $GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["time"];
	}
	return $SQLkey;
}
function ELISQLREPORTS_dashboard_report_roles($Report_Name) {
	global $wp_roles;
	if (!isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["dashboard_reports"][$Report_Name]))
		$report_roles = array();
	elseif (is_array($GLOBALS["ELISQLREPORTS"]["settings_array"]["dashboard_reports"][$Report_Name]))
		$report_roles = $GLOBALS["ELISQLREPORTS"]["settings_array"]["dashboard_reports"][$Report_Name];
	else
		if ($GLOBALS["ELISQLREPORTS"]["settings_array"]["dashboard_reports"][$Report_Name] == 1)
			$report_roles = array_keys($wp_roles->roles);
		else
			$report_roles = array($GLOBALS["ELISQLREPORTS"]["settings_array"]["dashboard_reports"][$Report_Name]);
	return $report_roles;
}
function ELISQLREPORTS_report_form($Report_Name = '', $Report_SQL = '') {
	global $wp_roles;
	$mt = microtime(true);
	if (isset($_GET["dbug"])) {
		echo 'Query started: '.$Report_SQL.' at '.$mt.' seconds.<br />';
		ob_flush();
	}
	if (strlen(trim($GLOBALS["ELISQLREPORTS"]["Report_SQL"]))>0)
		$Report_SQL = trim($GLOBALS["ELISQLREPORTS"]["Report_SQL"]);
	$SQLkey = ELISQLREPORTS_query($Report_SQL);
	$optional_box = '<div id="SQLFormSaveFrom"><div style="float: left; width: 256px;">';
	if (isset($wp_roles->roles) && is_array($wp_roles->roles) && strlen($Report_Name)) {
		$selectedRoles = ELISQLREPORTS_dashboard_report_roles($Report_Name);
		$optional_box .= 'Display report on dashboard for:<br /><select name="ELISQLREPORTS_dashboard_reports[]" onchange="setButtonValue(\'Save Changes\');" multiple size="'.count($wp_roles->roles).'">';
		foreach ($wp_roles->roles as $roleKey => $role)
			$optional_box .= '<option value="'.$roleKey.'"'.(in_array($roleKey, $selectedRoles)?' selected':'').'>'.$role["name"]."</option>\n";
		$optional_box .= "</select>\n";
	}
	$optional_box .= '<input style="float: right;" id="gobutton" type="submit" class="button-primary" value="'.(strlen($Report_Name)?'Save Report" /><br style="clear: right;" />Shortcode:<br />[SQLREPORT name="'.sanitize_title($Report_Name).'"]<br />':'Test SQL" /><br style="clear: right;" />').'<br /></div><div style="float: left; width: 256px;">Report Name:<br /><input style="width: 100%;" type="text" id="reportName" name="rName" value="'.htmlspecialchars($Report_Name).'" onchange="setButtonValue(\'Save Report\');" onkeyup="setButtonValue(\'Save Report\');" /><br /></div><br style="clear: left;" /></div>';
	echo '<div id="SQLFormEdit">Type or Paste your SQL into this box and give your report a name<br />
	<textarea width="100%" style="width: 100%;" rows="10" name="rSQL" class="shadowed-box" onchange="setButtonValue(\'Update Report\');" onkeyup="setButtonValue(\'Update Report\');">'.htmlspecialchars($Report_SQL).'</textarea><br />'.$optional_box.'<br /></div></form>
<script type="text/javascript">
function moveForm() {
	rN = document.getElementById("SQLFormSaveTo");
	if (rN && document.getElementById("SQLFormSaveFrom").innerHTML) {
		rN.innerHTML = document.getElementById("SQLFormSaveFrom").innerHTML;
		document.getElementById("SQLFormSaveFrom").innerHTML = "";
	}
}
'.(strlen($Report_Name)?"showhide('SQLFormEdit');":"showhide('SQLFormDel');").'
'.($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["errors"]?"showhide('SQLFormEdit', true);\n":"").'moveForm();
var oldName="'.str_replace("\"", "\\\"", str_replace('\\', '\\\\', $Report_Name)).'";
function setButtonValue(newval) {
	rN = document.getElementById(\'reportName\').value;
	if (oldName.length > 0) {
		if (rN.length > 0 && rN != oldName)
			newval = newval + " As";
	} else {
		if (rN.length > 0)
			newval = "Save Report";
		else
			newval = "Test SQL";
	}
	document.getElementById(\'gobutton\').value = newval;
}
</script>
'.ELISQLREPORTS_CSV_script();
	if (isset($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["result"]) && $GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["result"] === 0 && isset($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["rows"]) && $GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["rows"])
		$Report_SQL = preg_replace('/^UPDATE (.+?) SET (.+?) WHERE /i', 'SELECT * FROM \\1 WHERE ', $Report_SQL);
	elseif (isset($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["time"]) && isset($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["rows"]) && $GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["rows"])
		echo 'Query returned '.$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["rows"].' rows in '.substr($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["time"], 0, 6).' seconds.<br /><br />If you want to include the "Export to CSV" button on your page or post then use the additional shortcode [SQLEXPORTCSV]';
	ob_flush();
	return $Report_SQL;
}
function ELISQLREPORTS_default_report($Report_Name = '') {
	if (current_user_can('activate_plugins')) {
		$GLOBALS["ELISQLREPORTS"]["reports_array"] = get_option('ELISQLREPORTS_reports_array', array());
		if (isset($GLOBALS["ELISQLREPORTS"]["reports_array"]) && is_array($GLOBALS["ELISQLREPORTS"]["reports_array"]) && isset($GLOBALS["ELISQLREPORTS"]["reports_array"][$Report_Name])) {
			ELISQLREPORTS_display_header($Report_Name, array("Edit Report", "Saved Reports"));
			/*if (!(strlen($Report_Name) > 0 && isset($GLOBALS["ELISQLREPORTS"]["reports_array"][$Report_Name]))) {
				$Report_Names = array_keys($GLOBALS["ELISQLREPORTS"]["reports_array"]);
				$Report_Name = $Report_Names[count($Report_Names)-1];
			}*/
			$MySQL = ($GLOBALS["ELISQLREPORTS"]["reports_array"][$Report_Name]);
			$MySQL = ELISQLREPORTS_report_form($Report_Name, $MySQL);
			echo ELISQLREPORTS_view_report($Report_Name, $MySQL);
		} else
			ELISQLREPORTS_create_report();
	} else
		echo ELISQLREPORTS_view_report($Report_Name);
	echo '<br style="clear: both;">';
	if (isset($_GET["debug"]) && is_admin())
		print_r(array("<pre>", $GLOBALS["ELISQLREPORTS"]["query_times"], "</pre>"));
	echo '</div></div></div>';
}
function ELISQLREPORTS_create_report() {
	global $wpdb;
	ELISQLREPORTS_display_header('Create SQL Report', array("Edit Report", "Plugin Links", "Saved Reports"));
	$GLOBALS["ELISQLREPORTS"]["reports_array"] = get_option('ELISQLREPORTS_reports_array', array());
	if (strlen(trim($GLOBALS["ELISQLREPORTS"]["Report_SQL"]))==0) {
		$GLOBALS["ELISQLREPORTS"]["Report_SQL"] = "SELECT CONCAT('<a href=\"javascript:void(0);\" onclick=\"document.SQLForm.rSQL.value=\\'SHOW FIELDS FROM `',TABLE_NAME,'`\\';\">',TABLE_NAME,'</a>') AS `SCHEMA`, CONCAT('<a href=\"javascript:void(0);\" onclick=\"document.SQLForm.rSQL.value=\\'SELECT * FROM `',TABLE_NAME,'`\\';\">',TABLE_ROWS,'</a>') AS `ROWS`, CONCAT(ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024, 1), 'K') AS `SIZE` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."'";
		$Report_Name = '<font color="red">Table List</font>';
	} elseif (!isset($Report_Name))
		$Report_Name = "";
	$end = ELISQLREPORTS_view_report($Report_Name, $GLOBALS["ELISQLREPORTS"]["Report_SQL"]);
	$Report_Name = '';
	ELISQLREPORTS_report_form($Report_Name, $GLOBALS["ELISQLREPORTS"]["Report_SQL"]);
	echo $end.'</div></div></div>';
}
function ELISQLREPORTS_settings() {
	global $wpdb;
	ELISQLREPORTS_display_header('SQL Reports - Plugin Settings', array("Save Settings", "Plugin Links", "Saved Reports"));
	echo '<div class="postbox">
	<h3 class="hndle"><span>SQL Report Options</span></h3>
	<div class="inside" style="margin: 10px;"><div style="float: left; margin: 5px;">Default Styles for Report DIV:<br /><textarea name="ELISQLREPORTS_default_styles"cols=30 rows=2>'.$GLOBALS["ELISQLREPORTS"]["settings_array"]["default_styles"].'</textarea></div><div style="float: left; margin: 5px;">Sort <b>Saved Reports</b> by:<br />';
	foreach (array("Date Created", "Alphabetical") as $mg => $menu_sort)
		echo '<div style="padding: 4px 24px;" id="menu_sort_div_'.$mg.'"><input type="radio" name="ELISQLREPORTS_menu_sort" value="'.$mg.'"'.($GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_sort"]==$mg||$mg==0?' checked':'').' />'.$menu_sort.'</div>';
	echo '</div><div style="float: left; margin: 5px;">Display <b>Saved Reports</b> on the Admin Menu:<br />';
	foreach (array("No", "Yes") as $mg => $menu_display)
		echo '<div style="padding: 4px 24px;" id="menu_display_div_'.$mg.'"><input type="radio" name="ELISQLREPORTS_menu_display" value="'.$mg.'"'.($GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_display"]==$mg||$mg==0?' checked':'').' />'.$menu_display.'</div>';
	echo '</div><br style="clear: left;"></div></div>
	<div id="backuprestore" class="postbox"><h3 class="hndle"><span>Database Backup Option</span></h3><div class="inside" style="margin: 10px;"><form method=post><table width="100%" border=0><tr><td width="1%" valign="top">Backup&nbsp;Method:</td><td width="99%">';
	foreach (array("Auto-detect", "Command Line (mysqldump)", "PHP (mysqli_query)") as $mg => $backup_method)
		echo '<div style="float: left; padding: 0 24px 8px 0;"><input type="radio" name="ELISQLREPORTS_backup_method" value="'.$mg.'"'.($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_method"]==$mg||$mg==0?' checked':'').' />'.$backup_method.'</div>';
	echo '<div style="float: left; padding: 0 24px 8px 0;"><input type="checkbox" name="ELISQLREPORTS_compress_backup" value="1"'.(isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["compress_backup"]) && $GLOBALS["ELISQLREPORTS"]["settings_array"]["compress_backup"]?' checked':'').' />Compress Backup Files</div></td></tr><tr><td width="1%">Save&nbsp;all&nbsp;backups&nbsp;to:</td><td width="99%"><input style="width: 100%" name="ELISQLREPORTS_backup_dir" value="'.$GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"].'"></td></tr><tr><td width="1%">Email&nbsp;all&nbsp;backups&nbsp;to:</td><td width="99%"><input style="width: 100%" name="ELISQLREPORTS_backup_email" value="'.$GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_email"].'"></td></tr></table><br />Automatically make and keep <input size=1 name="ELISQLREPORTS_hourly_backup" value="'.$GLOBALS["ELISQLREPORTS"]["settings_array"]["hourly_backup"].'"> Hourly and <input size=1 name="ELISQLREPORTS_daily_backup" value="'.$GLOBALS["ELISQLREPORTS"]["settings_array"]["daily_backup"].'"> Daily backups.<br />';
	if ($next = wp_next_scheduled('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly')))
		echo "<li>next hourly backup: ".date("Y-m-d H:i:s", $next)." (About ".ceil(($next-time())/60)." minute".(ceil(($next-time())/60)==1?'':'s')." from now)</li>";
//	else echo md5(serialize($args)).'='.serialize($args);
	if ($next = wp_next_scheduled('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily')))
		echo "<li>next daily backup: ".date("Y-m-d H:i:s", $next)." (Less than ".ceil(($next-time())/60/60)." hour".(ceil(($next-time())/60/60)==1?'':'s')." from now)</li>";
	echo '</form></div></div>
	<div id="backuprestore" class="postbox"><h3 class="hndle"><span>Database Maintenance</span></h3>
		<div class="inside" style="margin: 10px;">
			<form method=post>';
	ELISQLREPORTS_set_backupdir();
	$opts = array("Y-m-d-H-i-s" => "Make A New Backup", "DELETE Post Revisions" => array("DELETE FROM wp_posts WHERE `wp_posts`.`post_type` = 'revision'", "DELETE FROM wp_postmeta WHERE `wp_postmeta`.`post_id` NOT IN (SELECT `wp_posts`.`ID` FROM `wp_posts`)", "OPTIMIZE TABLE wp_posts, wp_postmeta"), "DELETE Spam Comments" => array("DELETE FROM wp_comments WHERE `wp_comments`.`comment_approved` = 'spam'", "DELETE FROM wp_commentmeta WHERE `wp_commentmeta`.`comment_id` NOT IN (SELECT `wp_comments`.`comment_ID` FROM `wp_comments`)", "OPTIMIZE TABLE wp_comments, wp_commentmeta"));
	$repair_tables = $wpdb->get_col("show full tables where Table_Type = 'BASE TABLE'");
	if (is_array($repair_tables) && count($repair_tables))
		$opts["REPAIR All Tables"] = array('REPAIR TABLE `'.implode('`, `', $repair_tables).'`');
	$backupDB = get_option("ELISQLREPORTS_BACKUP_DB", base64_encode("nothing"));
	$js = "Restore to the following Database:<br />";
	$local = true;
	if (!is_array($backupDB))
		$backupDB = maybe_unserialize(base64_decode($backupDB));
	if (!is_array($backupDB))
		$backupDB = array("DB_NAME" => DB_NAME, "DB_HOST" => DB_HOST, "DB_USER" => DB_USER, "DB_PASSWORD" => DB_PASSWORD);
	foreach ($backupDB as $db_key => $db_value) {
		$js .= $db_key.':<input name="'.$db_key;
		if (isset($_POST[$db_key])) {
			$backupDB[$db_key] = $_POST[$db_key];
			$js .= '" readonly="true';
		}
		$js .= '" value="'.$backupDB[$db_key].'"><br />';
		if (constant($db_key) != $backupDB[$db_key])
			$local = false;
	}
	update_option("ELISQLREPORTS_BACKUP_DB", base64_encode(maybe_serialize($backupDB)));
	$js .= 'Warning: This '.($local?'is':'is NOT').' your currently active WordPress database conection info for this site.<br /><select name="db_date">';
	if (isset($_POST["db_date"]) && strlen($_POST["db_date"])) {
		if (isset($opts[$_POST["db_date"]]) && is_array($opts[$_POST["db_date"]])) {
			foreach ($opts[$_POST["db_date"]] as $MySQLexec) {
				$SQLkey = ELISQLREPORTS_query($MySQLexec);
				if (isset($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["errors"]) && $GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["errors"])
					echo "<li>".$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["errors"]."</li>";
				else {
					if (preg_match('/ FROM /', $MySQLexec))
						echo preg_replace('/^(.+?) FROM (.+?) .*/', '<li>\\1 '.$GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["rows"].' Records from \\2 Succeeded!</li>', $MySQLexec);
					else
						echo "<li>$MySQLexec Succeeded!</li>";
				}
			}
		} elseif (is_file(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]).basename($_POST["db_date"]))) {
			//Restore Backup to the DB with the posted credentials
			if (isset($_POST["DB_NAME"]) && strlen(trim($_POST["DB_NAME"])) && isset($_POST["db_nonce"]) && wp_verify_nonce($_POST["db_nonce"], $_POST["db_date"])) {
				if (substr($_POST["db_date"], -8) == '.sql.zip')
					$GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_method"] = 0;
				echo ELISQLREPORTS_make_backup("Y-m-d-H-i-s", "pre-restore", $_POST["DB_NAME"], $_POST["DB_HOST"], $_POST["DB_USER"], $_POST["DB_PASSWORD"]);
				$mysqlbasedir = $wpdb->get_row("SHOW VARIABLES LIKE 'basedir'");
				if(substr(PHP_OS,0,3) == "WIN")
					$backup_command = '"'.(isset($mysqlbasedir->Value)?trailingslashit(str_replace('\\', '/', $mysqlbasedir->Value)).'bin/':'').'mysql.exe"';
				else
					$backup_command = (isset($mysqlbasedir->Value)&&is_file(trailingslashit($mysqlbasedir->Value).'bin/mysql')?trailingslashit($mysqlbasedir->Value).'bin/':'').'mysql';
				if (strpos($_POST["DB_HOST"], ':')) {
					list($db_host, $db_port) = explode(':', $_POST["DB_HOST"], 2);
					if (is_numeric($db_port))
						$db_port = ' --port='.escapeshellarg($db_port);
					else
						$db_port = ' --socket='.escapeshellarg($db_port);
				} else {
					$db_host = $_POST["DB_HOST"];
					$db_port = '" ';
				}
				$backup_command .= ' --user='.escapeshellarg($_POST['DB_USER']).' --password='.escapeshellarg($_POST['DB_PASSWORD']).' --host='.escapeshellarg($db_host).$db_port.escapeshellarg(trim($_POST["DB_NAME"]));
				if (substr($_POST["db_date"], -7) == '.sql.gz') {
					passthru('gunzip -c '.escapeshellarg(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]['backup_dir']).basename($_POST['db_date'])).' | '.$backup_command, $errors);
					echo "<li>Restore process executed Gzip extraction with $errors error".($errors==1?'':'s').'!</li><br>';
				} elseif (substr($_POST['db_date'], -8) == '.sql.zip') {
					$zip = new ZipArchive;
					if ($zip->open(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]['backup_dir']).basename($_POST['db_date'])) === TRUE) {
						$zip->extractTo(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]['backup_dir']));
						$zip->close();
					}
					if (is_file($file_sql = trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]['backup_dir']).substr(basename($_POST['db_date']), 0, -4))) {
						passthru($backup_command.' -e '.escapeshellarg("source $file_sql"), $errors);
						if ($errors) {
							if ($full_sql = file_get_contents($file_sql)) {
								$queries = 0;
								$errors = array();
								$startpos = 0;
								while ($endpos = strpos($full_sql, ";\n", $startpos)) {
									if ($sql = trim(@preg_replace("|/\*.+\*/[;\t ]*|", "", substr($full_sql, $startpos, $endpos - $startpos)).' ')) {
										if (mysqli_query($GLOBALS["ELISQLREPORTS"]["backup_connection"], $sql))
											$queries++;
										else
											$errors[] = "<li>".mysqli_error($GLOBALS["ELISQLREPORTS"]["backup_connection"])."</li>";
									}
									$startpos = $endpos + 2;
								}
								echo "<li>Restore Process executed $queries queries with ".count($errors).' error'.(count($errors)==1?'':'s').'!</li><br>'.implode("\n", $errors);
							} else
								echo "Error Reading File: $file_sql";
						} else
							echo "<li>Restore process executed Zip extraction with $errors error".($errors==1?'':'s').'!</li><br>';
					} else
						echo '<li>ERROR: Failed to extract Zip Archive!</li><br>';
				} elseif (substr($_POST['db_date'], -4) == '.sql' && is_file($file_sql = trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]['backup_dir']).basename($_POST['db_date']))) {
					passthru($backup_command.' -e '.escapeshellarg("source $file_sql"), $errors);
					echo "<li>Restore process executed MySQL with $errors error".($errors==1?'':'s').'!</li><br>';
				}
			} else {
				die($js.'<option value="'.$_POST['db_date'].'">RESTORE '.$_POST['db_date'].'</option></select><br /><input name="db_nonce" type="checkbox" value="'.wp_create_nonce($_POST['db_date']).'"> Yes, I understand that I will be completely erasing this database with my backup file.<br /><input type="submit" value="Restore Backup to Database Now!"></div></form></div></div></body></html>');
			}
		} else
			echo ELISQLREPORTS_make_Backup($_POST['db_date']);
	} elseif (isset($_GET['delete']) && is_file($file_sql = trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]['backup_dir']).basename($_GET['delete'])))
		@unlink($file_sql);
	echo '<div id="makebackup">
			<select name="db_date" id="db_date" onchange="if (this.value == \'RESTORE\') make_restore();">';
	foreach ($opts as $opt => $arr)
		echo '<option value="'.$opt.'">'.(is_array($arr)?$opt:$arr).'</option>';
	$sql_files = array();
	if ($handle = opendir($GLOBALS["ELISQLREPORTS"]["settings_array"]['backup_dir'])) {
		while (false !== ($entry = readdir($handle)))
			if (is_file(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]['backup_dir']).$entry) && strpos($entry, ".sql"))
				$sql_files[$entry] = filesize(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]['backup_dir']).$entry);
		closedir($handle);
		krsort($sql_files);
		if (count($sql_files)) {
			$files = "\n<b>Current Backups:</b>";
			$upload = wp_upload_dir();
			foreach ($sql_files as $entry => $size)
				$files .= "\n<li>($size) ".htmlentities($entry).' <a target="_blank" href="'.str_replace('&Download_SQL_Backup=', '&lastDownload_SQL_Backup=', $_SERVER['REQUEST_URI']).'&Download_SQL_Backup='.urlencode($entry).'">[Download]</a> | <a href="'.str_replace('&delete=', '&lastdelete=', $_SERVER['REQUEST_URI']).'&delete='.urlencode($entry).'">[DELETE]</a></li>';
			echo '<option value="RESTORE">RESTORE A Backup</option>';
		} else
			$files = "\n<b>No backups have yet been made</b>";
	} else
		$files = "\n<b>Could not read files in ".$GLOBALS["ELISQLREPORTS"]["settings_array"]['backup_dir']."</b>";
	foreach ($sql_files as $entry => $size)
		$js .= "<option value=\"$entry\">RESTORE $entry ($size)</option>";
	$js .= '</select><br /><input type="submit" value="Restore Selected Backup to Database">';
	echo "</select><input type=submit value=Run /></div><script type='text/javascript'>function make_restore() {document.getElementById('makebackup').innerHTML='$js';}</script><br />$files\n</form></div></div></div></div></div>";
}
add_action('ELISQLREPORTS_daily_backup', 'ELISQLREPORTS_make_Backup', 10, 2);
add_action('ELISQLREPORTS_hourly_backup', 'ELISQLREPORTS_make_Backup', 10, 2);

function ELISQLREPORTS_deactivation() {
	while (wp_next_scheduled('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily')))
		wp_clear_scheduled_hook('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily'));
	while (wp_next_scheduled('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly')))
		wp_clear_scheduled_hook('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly'));
}
register_deactivation_hook(__FILE__, 'ELISQLREPORTS_deactivation');

function ELISQLREPORTS_activation() {
	$GLOBALS["ELISQLREPORTS"]["settings_array"] = get_option('ELISQLREPORTS_settings_array', array());
	if (isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["daily_backup"]) && $GLOBALS["ELISQLREPORTS"]["settings_array"]["daily_backup"] && !wp_next_scheduled('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily')))
		wp_schedule_event(time(), 'daily', 'ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily'));
	if (isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["hourly_backup"]) && $GLOBALS["ELISQLREPORTS"]["settings_array"]["hourly_backup"] && !wp_next_scheduled('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly')))
		wp_schedule_event(time(), 'hourly', 'ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly'));
}
register_activation_hook(__FILE__, 'ELISQLREPORTS_activation');

function ELISQLREPORTS_menu() {
	global $wp_version;
	wp_enqueue_style('ELISQLREPORTS_admin', plugins_url('admin.css', __FILE__));
	ELISQLREPORTS_set_backupdir();
	if (current_user_can("activate_plugins")) {
		if (isset($_GET["Download_SQL_Backup"]) && strpos($_GET["Download_SQL_Backup"], ".sql") && is_file(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]).basename($_GET["Download_SQL_Backup"])) && ($fp = fopen(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]).basename($_GET["Download_SQL_Backup"]), 'rb'))) {
			header("Content-Type: application/octet-stream;");
			header('Content-Disposition: attachment; filename="'.basename($_GET["Download_SQL_Backup"]).'"');
			header("Content-Length: ".filesize(trailingslashit($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]).basename($_GET["Download_SQL_Backup"])));
			fpassthru($fp);
			exit;
		}
		$img_path = basename(__FILE__);
		$Full_plugin_logo_URL = get_option("siteurl");
		if (!(isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_sort"]) && is_numeric($GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_sort"])))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_sort"] = 0;
		if (!(isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_display"]) && is_numeric($GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_display"])))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_display"] = 1;
		if (!(isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["hourly_backup"]) && is_numeric($GLOBALS["ELISQLREPORTS"]["settings_array"]["hourly_backup"])))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["hourly_backup"] = 0;
		if (!(isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["daily_backup"]) && is_numeric($GLOBALS["ELISQLREPORTS"]["settings_array"]["daily_backup"])))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["daily_backup"] = 0;
		if (!(isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_email"]) && strlen(trim($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_email"]))))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_email"] = '';
		if (!(isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_method"]) && is_numeric($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_method"])))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_method"] = 0;
		if (!(isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["compress_backup"]) && is_numeric($GLOBALS["ELISQLREPORTS"]["settings_array"]["compress_backup"])))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["compress_backup"] = 0;
		if (isset($_POST["rName"]))
			if (isset($_POST["ELISQLREPORTS_dashboard_reports"]) && is_array($_POST["ELISQLREPORTS_dashboard_reports"]))
				$GLOBALS["ELISQLREPORTS"]["settings_array"]["dashboard_reports"][$_POST["rName"]] = $_POST["ELISQLREPORTS_dashboard_reports"];
			else
				unset($GLOBALS["ELISQLREPORTS"]["settings_array"]["dashboard_reports"][$_POST["rName"]]);
		if (isset($_POST["ELISQLREPORTS_backup_method"]) && is_numeric($_POST["ELISQLREPORTS_backup_method"])) {
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_method"] = intval($_POST["ELISQLREPORTS_backup_method"]);
			if (isset($_POST["ELISQLREPORTS_compress_backup"]))
				$GLOBALS["ELISQLREPORTS"]["settings_array"]["compress_backup"] = 1;
			else
				$GLOBALS["ELISQLREPORTS"]["settings_array"]["compress_backup"] = 0;
		}
		if (isset($_POST["ELISQLREPORTS_backup_email"]) && (trim($_POST["ELISQLREPORTS_backup_email"]) != $GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_email"]))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_email"] = trim($_POST["ELISQLREPORTS_backup_email"]);
		if (isset($_POST["ELISQLREPORTS_backup_dir"]) && strlen(trim($_POST["ELISQLREPORTS_backup_dir"])) && is_dir($_POST["ELISQLREPORTS_backup_dir"]) && (!isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]) || $_POST["ELISQLREPORTS_backup_dir"] != $GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"]))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["backup_dir"] = $_POST["ELISQLREPORTS_backup_dir"];
		if (isset($_POST["ELISQLREPORTS_daily_backup"]) && is_numeric($_POST["ELISQLREPORTS_daily_backup"]) && ($_POST["ELISQLREPORTS_daily_backup"] != $GLOBALS["ELISQLREPORTS"]["settings_array"]["daily_backup"])) {
			if ($GLOBALS["ELISQLREPORTS"]["settings_array"]["daily_backup"] = intval($_POST["ELISQLREPORTS_daily_backup"])) {
				if (!wp_next_scheduled('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily')))
					wp_schedule_event(time(), 'daily', 'ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily'));
			} elseif (wp_next_scheduled('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily')))
				wp_clear_scheduled_hook('ELISQLREPORTS_daily_backup', array("Y-m-d-H-i-s", 'daily'));
		}
		if (isset($_POST["ELISQLREPORTS_hourly_backup"]) && is_numeric($_POST["ELISQLREPORTS_hourly_backup"]) && ($_POST["ELISQLREPORTS_hourly_backup"] != $GLOBALS["ELISQLREPORTS"]["settings_array"]["hourly_backup"])) {
			if ($GLOBALS["ELISQLREPORTS"]["settings_array"]["hourly_backup"] = intval($_POST["ELISQLREPORTS_hourly_backup"])) {
				if (!wp_next_scheduled('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly')))
					wp_schedule_event(time(), 'hourly', 'ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly'));
			} elseif (wp_next_scheduled('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly')))
				wp_clear_scheduled_hook('ELISQLREPORTS_hourly_backup', array("Y-m-d-H-i-s", 'hourly'));
		}
		if (isset($_POST["ELISQLREPORTS_menu_sort"]) && is_numeric($_POST["ELISQLREPORTS_menu_sort"]))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_sort"] = intval($_POST["ELISQLREPORTS_menu_sort"]);
		if (isset($_POST["ELISQLREPORTS_menu_display"]) && is_numeric($_POST["ELISQLREPORTS_menu_display"]))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_display"] = intval($_POST["ELISQLREPORTS_menu_display"]);
		if (isset($_POST["ELISQLREPORTS_default_styles"]))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["default_styles"] = trim($_POST["ELISQLREPORTS_default_styles"]);
		$Full_plugin_logo_URL = $GLOBALS["ELISQLREPORTS"]["images_path"]."ELISQLREPORTS-16x16.gif";
		update_option("ELISQLREPORTS_settings_array", $GLOBALS["ELISQLREPORTS"]["settings_array"]);
		if (isset($_POST["rName"]))
			$Report_Name = stripslashes($_POST["rName"]);
		else
			$Report_Name = "";
		if (isset($_POST["rSQL"]) && strlen($_POST["rSQL"]) > 0) {
			if ($_POST["rSQL"] == "DELETE_REPORT" && strlen($Report_Name) && isset($GLOBALS["ELISQLREPORTS"]["reports_array"][$Report_Name])) {
				$GLOBALS["ELISQLREPORTS"]["Report_SQL"] = $GLOBALS["ELISQLREPORTS"]["reports_array"][$Report_Name];
				unset($GLOBALS["ELISQLREPORTS"]["reports_array"][$Report_Name]);
				unset($_POST["rName"]);// I should get rid of this and use other conditions elsewhere
				update_option("ELISQLREPORTS_reports_array", $GLOBALS["ELISQLREPORTS"]["reports_array"]);
			} else {
				$GLOBALS["ELISQLREPORTS"]["Report_SQL"] = stripslashes($_POST["rSQL"]);
				$SQLkey = ELISQLREPORTS_query($GLOBALS["ELISQLREPORTS"]["Report_SQL"]);
				if ((!(isset($GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["errors"]) && $GLOBALS["ELISQLREPORTS"]["query_times"][$SQLkey]["errors"])) && strlen($Report_Name) > 0) {
					$GLOBALS["ELISQLREPORTS"]["reports_array"][$Report_Name] = $GLOBALS["ELISQLREPORTS"]["Report_SQL"];
					update_option("ELISQLREPORTS_reports_array", $GLOBALS["ELISQLREPORTS"]["reports_array"]);
				}
			}
		}
		$base_page = "ELISQLREPORTS-settings";
		add_menu_page(__("SQL Reports Plugin Settings"), __("SQL Reports"), "activate_plugins", $base_page, "ELISQLREPORTS_settings", $Full_plugin_logo_URL);
		add_submenu_page($base_page, __("SQL Reports Plugin Settings"), '<div class="dashicons dashicons-admin-generic"></div> '.__("Plugin Settings"), "activate_plugins", $base_page, "ELISQLREPORTS_settings");
		$GLOBALS["ELISQLREPORTS"]["boxes"]["Saved Reports"] = '<ul style="list-style: none;">';
		if (isset($GLOBALS["ELISQLREPORTS"]["reports_array"]) && is_array($GLOBALS["ELISQLREPORTS"]["reports_array"])) {
			$Report_Number = 0;
			if ($GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_sort"])
				ksort($GLOBALS["ELISQLREPORTS"]["reports_array"]);
			foreach ($GLOBALS["ELISQLREPORTS"]["reports_array"] as $Rname => $Rquery) {
				$Report_Number++;
				$Rslug = 'ELISQLREPORTS-'.sanitize_title($Rname.'-'.$Report_Number);
				if ((!isset($_GET["page"]) || $_GET["page"] != $Rslug) && $Rname == $Report_Name) {
					header("Location: admin.php?page=$Rslug");
					die("Report Renamed - Redirecting to the new page...");
				}
				$Rfunc = str_replace('-', '_', $Rslug);
				if ($GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_display"] || ($_GET["page"] == $Rslug))
					add_submenu_page($base_page, $Rname, '<div class="dashicons dashicons-admin-page"></div> '.$Rname, "activate_plugins", $Rslug, $Rfunc);
				$GLOBALS["ELISQLREPORTS"]["boxes"]["Saved Reports"] .= "<li class='dashReport'><a href=\"?page=$Rslug\">$Rname</a>\n";
			}
		}
		$GLOBALS["ELISQLREPORTS"]["boxes"]["Saved Reports"] .= '</ul>';
		add_submenu_page($base_page, __("Create SQL Report"), '<div class="dashicons dashicons-welcome-add-page"></div> Create Report', "activate_plugins", "ELISQLREPORTS-create-report", "ELISQLREPORTS_create_report");
	}
}
add_action("admin_menu", "ELISQLREPORTS_menu");

function ELISQLREPORTS_enqueue_scripts() {
    wp_enqueue_style('dashicons');
}
add_action('admin_enqueue_scripts', 'ELISQLREPORTS_enqueue_scripts');

function ELISQLREPORTS_dashboard_setup() {
	global $current_user;
	$current_user = wp_get_current_user();
	if (isset($GLOBALS["ELISQLREPORTS"]["reports_array"]) && isset($current_user->roles[0]) && is_array($GLOBALS["ELISQLREPORTS"]["reports_array"])) {
		$Report_Number = 0;
		if ($GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_sort"])
			ksort($GLOBALS["ELISQLREPORTS"]["reports_array"]);
		foreach ($GLOBALS["ELISQLREPORTS"]["reports_array"] as $Rname => $Rquery) {
			$Report_Number++;
			$Rslug = sanitize_title($Rname);
			if (in_array($current_user->roles[0], ELISQLREPORTS_dashboard_report_roles($Rname)))
				wp_add_dashboard_widget('ELISQLREPORTS-'.$Rslug, $Rname, 'ELISQLREPORTS_'.str_replace('-', '_', $Rslug).'_'.$Report_Number.'_view');
		}
	}
}
add_action("wp_dashboard_setup", "ELISQLREPORTS_dashboard_setup"); 

function ELISQLREPORTS_sanitize_array($list) {
	return $list;
}

class ELISQLREPORTS_Widget_Class extends WP_Widget {
	function __construct() {
		parent::__construct('ELISQLREPORTS-Widget', __('EZ SQL Report'), array('classname' => 'ELISQLREPORTS_Widget_Class', 'description' => __('Display one of your saved Reports in the widget area.')));
	}
	function widget($args, $instance) {
		extract($args);
		if (isset($instance['title']) && strlen($instance['title']) && isset($GLOBALS["ELISQLREPORTS"]["reports_keys"][$instance['title']])) {
			if (!(is_array($cache = get_option("ELISQLREPORTS_cache_".md5($instance['title']), array())) && count($cache) == 2 && substr($cache[0]."0123456789", 0, 10) == date("Y-m-d")))
				update_option("ELISQLREPORTS_cache_".md5($instance['title']), $cache = array(date("Y-m-d H:i:s"), ELISQLREPORTS_view_report($instance['title'])));
			echo $before_widget.$before_title.$GLOBALS["ELISQLREPORTS"]["reports_keys"][$instance['title']].$after_title."\n<style>#".$instance['title']." h2.ELISQLREPORTS-Report-Name {display: none;}</style><!-- $cache[0] -->\n".($cache[1]).$after_widget;
		}
	}
	function flush_widget_cache() {
		wp_cache_delete('ELISQLREPORTS_Widget_Class', 'widget');
	}
	function update($new, $old) {
		$instance = $old;
		$instance['title'] = strip_tags($new['title']);
		return $instance;
	}
	function form($instance) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		echo '<p><label for="'.$this->get_field_id('title').'">'.__('Report to Distplay').':</label><br />
		<select name="'.$this->get_field_name('title').'" id="'.$this->get_field_id('title').'"><option value="">Select a Report</option>';
		foreach ($GLOBALS["ELISQLREPORTS"]["reports_array"] AS $Rname => $Rquery)
			echo '<option value="'.sanitize_title($Rname).'"'.(sanitize_title($Rname)==$title?" selected":"").'>'.$Rname.'</option>';
		echo '</select></p>';
	}
}
add_action("widgets_init", function() {register_widget("ELISQLREPORTS_Widget_Class");});

function ELISQLREPORTS_init() {
	if (isset($GLOBALS["ELISQLREPORTS"]["reports_array"]) && is_array($GLOBALS["ELISQLREPORTS"]["reports_array"])) {
		$Report_Number = 0;
		if (isset($GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_sort"]) && $GLOBALS["ELISQLREPORTS"]["settings_array"]["menu_sort"])
			ksort($GLOBALS["ELISQLREPORTS"]["reports_array"]);
		foreach ($GLOBALS["ELISQLREPORTS"]["reports_array"] AS $Rname => $Rquery) {
			$Report_Number++;
			$Rslug = 'ELISQLREPORTS-'.sanitize_title($Rname.'-'.$Report_Number);
			$Rfunc = 'function '.str_replace('-', '_', $Rslug);
			$Rfunc_create = '_report("'.str_replace('"', '\\"', $Rname).'"); }';
			eval($Rfunc.'() { ELISQLREPORTS_default'.$Rfunc_create);
			eval($Rfunc.'_view() { echo ELISQLREPORTS_view'.$Rfunc_create);
		}
	}
}
add_action("init", "ELISQLREPORTS_init");

function ELISQLREPORTS_link($lText, $lAddress, $lDashicon = "", $lTag = "", $lAnchor = "") {
	if (substr($lAddress, 0, 1) == "&")
		$lAddress = $GLOBALS["ELISQLREPORTS"]["create-report-url"].$lAddress;
	elseif (substr($lAddress, 0, 1) != "#")
		$lAnchor = ' target="_blank"';
	return ($lTag?"<$lTag>":"").($lAddress?'<a'.$lAnchor.' href="'.$lAddress.'">':"").($lDashicon?'<span class="dashicons dashicons-'.$lDashicon.'"></span>':"").$lText.($lAddress?'</a>':"").($lTag?"</$lTag>":"");
}

function ELISQLREPORTS_set_plugin_action_links($links_array, $plugin_file) {
	if (strlen($plugin_file) > 10 && $plugin_file == substr(__file__, (-1 * strlen($plugin_file))))
		$links_array = array_merge(array(ELISQLREPORTS_link("Create SQL Report", "&", "")), $links_array);
	return $links_array;
}
add_filter("plugin_action_links", "ELISQLREPORTS_set_plugin_action_links", 1, 2);

function ELISQLREPORTS_set_plugin_row_meta($links_array, $plugin_file) {
	if (strlen($plugin_file) > 10 && $plugin_file == substr(__file__, (-1 * strlen($plugin_file))))
		$links_array = array_merge($links_array, array(ELISQLREPORTS_link("Donate", "https://www.paypal.com/donate?hosted_button_id=ZN3QCSQ74R5J6", "heart")));
	return $links_array;
}
add_filter("plugin_row_meta", "ELISQLREPORTS_set_plugin_row_meta", 1, 2);

function ELISQLREPORTS_shortcode($attr) {
	$report = '';
	if (isset($attr['name']) && strlen(trim($attr['name']))) {
		if (isset($attr['style']) && strlen(trim($attr['style'])))
			$GLOBALS["ELISQLREPORTS"]["settings_array"]["default_styles"] = $attr['style'];
		$report = '<div id="'.sanitize_title($attr['name']).'-wrapper"><div id="'.sanitize_title($attr['name']).'-parent">'.ELISQLREPORTS_view_report($attr['name']).'<br style="clear: both;"></div></div>';
	}
	return $report;
}
add_shortcode("SQLREPORT", "ELISQLREPORTS_shortcode");

function ELISQLREPORTS_CSV_script() {
return '<script type="text/javascript">
jQuery(document).ready(function( $ ){ 
  jQuery.getScript("https://cdn.jsdelivr.net/npm/table2csv@1.1.4/src/table2csv.min.js", function () { 
    if ($(".ELISQLREPORTS-table").length) { 
      $("table.ELISQLREPORTS-table").each(function () { 
        var $table = $(this); 
        var $title = $(this).siblings(".ELISQLREPORTS-Report-Name")[0].textContent; 
        var $button = $("<button type=\'button\'>"); 
        $button.text("Export to CSV"); 
        $button.insertBefore($table); 
        $button.click(function () { 
          $table.table2csv("download", { "filename": $title + ".csv" }); 
        });
      });
    }
  });
});
</script>
';
}
add_shortcode("SQLEXPORTCSV", "ELISQLREPORTS_CSV_script");

function ELISQLREPORTS_get_var($attr, $MySQL = "") {
	global $wpdb;
	if (!is_array($attr)) {
		if (strlen($attr) > 0 && strlen($MySQL) == 0)
			$MySQL = $attr;
		$attr = array("column_offset"=>0, "row_offset"=>0);
	} elseif (isset($attr["query"]))
		$MySQL = $attr["query"];
	if (!(isset($attr["column_offset"]) && is_numeric($attr["column_offset"])))
		$attr["column_offset"] = 0;
	if (!(isset($attr["row_offset"]) && is_numeric($attr["row_offset"])))
		$attr["row_offset"] = 0;
	if (isset($GLOBALS["ELISQLREPORTS"]["reports_array"][$MySQL]))
		$var = $wpdb->get_var(ELISQLREPORTS_eval($GLOBALS["ELISQLREPORTS"]["reports_array"][$MySQL]), $attr["column_offset"], $attr["row_offset"]);
	elseif (isset($GLOBALS["ELISQLREPORTS"]["reports_keys"][$MySQL]) && isset($GLOBALS["ELISQLREPORTS"]["reports_array"][$GLOBALS["ELISQLREPORTS"]["reports_keys"][$MySQL]]))
		$var = $wpdb->get_var(ELISQLREPORTS_eval($GLOBALS["ELISQLREPORTS"]["reports_array"][$GLOBALS["ELISQLREPORTS"]["reports_keys"][$MySQL]]), $attr["column_offset"], $attr["row_offset"]);
	else {
		if ($MySQL = array_search($MySQL, $GLOBALS["ELISQLREPORTS"]["reports_array"]))
			$var = $wpdb->get_var(ELISQLREPORTS_eval($GLOBALS["ELISQLREPORTS"]["reports_array"][$MySQL]), $attr["column_offset"], $attr["row_offset"]);
		else
			$var = "This SQL Query has not been allowed by an Administrator.";
	}
	if (isset($_GET["get_var"]) && ($_GET["get_var"] == "debug") && !$var && $wpdb->last_error)
		return $wpdb->last_error;
	else
		return $var;
}
add_shortcode("sqlgetvar", "ELISQLREPORTS_get_var");

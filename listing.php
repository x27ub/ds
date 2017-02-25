<?php
session_start();
$SID = session_id();
if(empty($SID)) session_start() or die('Could not start session');
$_SESSION['sid'] = $SID;

require_once (dirname(__FILE__).'/class_htmlAssemble.php');
require_once (dirname(__FILE__).'/class_createForms.php');
require_once (dirname(__FILE__).'/class_dbOperations.php');
require_once (dirname(__FILE__).'/functions.php');

//$suffix="";
//$tableName = "tx_deStipendium";

$doc = new htmlAssemble();
$doc->htmlhead();

$tbl = isset($_GET['tbl']) ? $_GET['tbl'] : NULL;
$script = isset($_GET['script']) ? $_GET['script'] : NULL;
$query_no = isset($_GET['q']) ? $_GET['q'] : NULL;
$limit = isset($_GET['limit']) ? $_GET['limit'] : NULL;
$sorta = isset($_GET['sorta']) ? $_GET['sorta'] : "ASC";
$sortb = isset($_GET['sortb']) ? $_GET['sortb'] : NULL;
$sortc = isset($_GET['sortc']) ? $_GET['sortc'] : NULL;
$sense = isset($_GET['sense']) ? $_GET['sense'] : NULL;
$sorting = $sb = $sc = $fields = $where = "";
$header = "";

if ($sorta) {
	$sorting .= " `".$sorta."` $sense, ";
} else $sorting = "`uid` ASC ";
if ($sortb) {
	$sb = " `".$sortb."` $sense, ";
	$sorting .= $sb;
	$sb = substr($sb, 0, -2);
	$sb = " ,".$sb;
} else {
	$sb = "";
	$sorting .= "";
}
if ($sortc) {
	$sc = " `".$sortc."` $sense, ";
	$sorting .= $sc;
	$sc = substr($sc, 0, -2);
	$sc = " ,".$sc;
} else {
	$sc = $sb = "";
	$sorting .= "";
}
//echo "<p>sb = $sb, sc = $sc</p>";
$sorting = substr($sorting, 0, -2);
//echo "<p>q = $query_no, limit = $limit, sense = $sense, sorting = $sorting</p>";

date_default_timezone_set('CET');
echo "<p>".date("Y/m/d H:i:s")."</p>";
$form = "%Y/%m/%d %H:%M:%s";
$d = strftime($form, 0);
echo "<p>$d</p>";

if ($query_no OR $query_no == 0) {
			switch ($query_no) {
				case 0:
					$fields = " `f_foerderer`,`f_r_form`,`f_plz_ort`,`f_name`,`f_funktion`,`f_tel`,`f_email`,`f_zweckbindung`,`f_stg`,`f_land`,`f_jahr`,`f_hs`,`f_id` ";
					//$sorting = "";
					$where = " `hidden` = 0 AND `deleted` = 0 ";
					$header = "<h1>F&ouml;rderer Liste</h1>";
					break;
				case 1:
					$fields = " `mat_bew_nr`,`name`, `vorname`, `note_rangliste`, `gruppe` ";
					$sorting = "`note_rangliste` $sense " . $sb . $sc;
					$where = "`status` = 1"; // need to be =1
					$header = "<h1>List 1: mat_bew_nr, Name, Vorname, note_rangliste, Gruppe where Status = 1 and no sorting</h1>";
					break;
				case 2:
					$fields = " `mat_bew_nr`,`name`, `vorname`, `stg_kuerzel`, `note_rangliste`, `migration`, `moegl_bew_zeitraum`, `gruppe` ";
					$sorting = "`stg_kuerzel` $sense, `note_rangliste` $sense "  . $sb . $sc;
					$where = "`status` = 1"; // need to be =1					
					$header = "<h1>List 2: mat_bew_nr, Name, Vorname, stg_kuerzel, note_rangliste, migration, moegl_bew_zeitraum, Gruppe where Status = 1 with sorting stg_kuerzel, note_rangliste</h1>";
					break;
				case 3:
					$fields = " `mat_bew_nr`,`name`, `vorname`, `stg_kuerzel`, `note_rangliste`, `migration`, `moegl_bew_zeitraum` ";
					$sorting = "`note_rangliste` $sense "  . $sb . $sc;
					$where = "`status` = 1 AND (stip_dauer < moegl_bew_zeitraum)";// change <= to <
					$header = "<h1>List 3: mat_bew_nr, Name, Vorname, stg_kuerzel, note_rangliste, migration, moegl_bew_zeitraum where Status = 1 and stip_dauer < moegl_bew_zeitraum and with sorting note_rangliste</h1>";
					break;
				case 4:
					$fields = " `gruppe`, `mat_bew_nr`, `name`, `vorname`, `stg_kuerzel`, `note_rangliste`, `moegl_bew_zeitraum`, `stip_dauer`, `bew_ende`, `bew_beginn`, `zweck_stip`";
					$where = " `bew` = 'ja'";
					$header = "<h1>Gruppe, mat_bew_nr, Name, Vorname, stg_kuerzel, note_rangliste, moegl_bew_zeitraum, stip_dauer, bew_ende, bew_beginn, zweck_stip WHERE bew=ja</h1>";
					break;
				case 5:
					$header = "<h1>List 4: Free List</h1>";
					break;
			} 
} else $query_no = "";

$dbOp = new dbOperations($dbConfig,$fieldset_baseQuery,$path,$tbl,$suffix,$salt="I|1Tofu+-Wev>w.W=S\$Y+5$44HC;[<");
$data_array = $dbOp->getDataFromDB($script,$limit,$sorting,$fields,$where);
$table = $data_array['table'];
$csv = $data_array['html'];

print $header;
print $csv;
print $table;

$doc->htmlfoot();

function get_realIp(){
  if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }elseif(isset($_SERVER['HTTP_X_REAL_IP'])){
        $_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_REAL_IP'];
    }
return $_SERVER['REMOTE_ADDR'];
}
$realIP = get_realIp();
$ip    = $_SERVER['REMOTE_ADDR'];
$agent = $_SERVER['HTTP_USER_AGENT'];
$prot  = $_SERVER['SERVER_PROTOCOL'];
$req   = $_SERVER['REQUEST_METHOD'];
$gate  = $_SERVER['GATEWAY_INTERFACE'];
$host  = $_SERVER['REMOTE_PORT'];
$sname = $_SERVER['SERVER_NAME'];
$ref 	= $_SERVER['HTTP_HOST'];
$requri = $_SERVER['REQUEST_URI'];
$fileName = "log.txt";
$fileHandle = fopen($fileName, 'a') or die("can't open file");
$dte = date("D, d M Y g:i:s a O");
$string = "$realIP <=| ($ip) -$requri- -$sname -$host -$ref -$req -$gate -$prot -$dte [$agent] \n";
fwrite($fileHandle,$string);
fclose($fileHandle);
?>

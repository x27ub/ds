<?php
if($_SERVER["HTTPS"] != "on") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
require_once (dirname(__FILE__).'/class_htmlAssemble.php');
require_once (dirname(__FILE__).'/class_createForms.php');
require_once (dirname(__FILE__).'/class_dbOperations.php');
require_once (dirname(__FILE__).'/functions.php');

session_start();
$SID = session_id();
if(empty($SID)) session_start() or die('Could not start session');
$_SESSION['sid'] = $SID;

$suffix="";
$tableName = "tx_deStipendium";
$script = "single.php";

// the links to the sponor's form
$links_spons = <<< EOLS
<div class="links">
<h1 class="links">Zur F&ouml;rderer Bearbeitermaske</h1>
<p class="links"><a class="links" href="list_sponsors.php">Listenansicht</a><a class="links" href="sponsors.php">Einzelansicht</a></p>
</div>
<hr>\n
EOLS;

// options for sorting 
$optsa = "<option value=\"\">choose column</option>\n";
$optselected = "<option value=\"uid\" selected=\"selected\">uid</option>\n";
$optnosel ="<option value=\"uid\">uid</option>\n";
$optsb = "<option value=\"crdate\">Created</option>\n";
$optsb .= "<option value=\"tstamp\">Last changed</option>\n";
$optsb .= "<option value=\"name\">Name</option>\n";
$optsb .= "<option value=\"vorname\">Vorname</option>\n";
$optsb .= "<option value=\"mat_bew_nr\">Matr. No</option>\n";
$optsb .= "<option value=\"geschl\">Geschlecht</option>\n";
$optsb .= "<option value=\"staat\">Staat</option>\n";
$optsb .= "<option value=\"status\">Status</option>\n";
$optsb .= "<option value=\"gruppe\">Gruppe</option>\n";
$optsb .= "<option value=\"ausgangsnote\">Ausgangsnote A</option>\n";
$optsb .= "<option value=\"ausgangsnote_b\">Ausgangsnote B</option>\n";
$optsb .= "<option value=\"ausgangsnote_c\">Ausgangsnote C</option>\n";
$optsb .= "<option value=\"ausgangsnote_d\">Ausgangsnote D</option>\n";
$optsb .= "<option value=\"stg_kuerzel\">Stg K&uuml;rzel</option>\n";
$optsb .= "<option value=\"stip_dauer\">Stipendiendauer</option>\n";

$options1 = $optsa . $optselected .$optsb;
$options2 = $optsa . $optnosel .$optsb;

$doc = new htmlAssemble();
$doc->htmlhead();
print $links_spons;
/*
date_default_timezone_set('CET');
echo "<p>".date("Y/m/d H:i:s")."</p>";
$form = "%Y/%m/%d %H:%M:%s";
$d = strftime($form, 0);
echo "<p>$d</p>";
*/
// the starting form
$selectionForm = <<< EOF
<h1>Bearbeitungsformular Bewerber</h1>
<div>
<form name="selection" action="listing.php" method="get">
<div>
<input type="hidden" name="tbl" value="$tableName">
<input type="hidden" name="script" value="$script">
<label for="q" class="sel_w1">Which list do you want?</label><select name="q" class="sel_w1">
<option value="1" selected>List_1</option>
<option value="2">List_2</option>
<option value="3">List_3</option>
<option value="4">List_4</option>
<option value="5">List_5</option>
</select></div>
<div><label for="limit" class="in_w1">Put the no of results.</label><input type="number" name="limit" min="1" max="5000" step="1" class="in_w1"></div>		
<h2>Sorting by</h2>
<div><label for="sorta" class="sel_w1">column A =</label><select name="sorta" class="sel_w1">
$options1</select></div>
<div><label for="sorta" class="sel_w1">column B =</label><select name="sortb" class="sel_w1">
$options2</select></div>
<div><label for="sorta" class="sel_w1">column C =</label><select name="sortc" class="sel_w1">
$options2</select></div>
<div><label for="sense" class="sel_w1">Which way?</label><select name="sense" class="sel_w1">
<option value="ASC">Ascending</option>
<option value="DESC">Descending</option>
</select></div>
<div><input type="submit" name="submit" value="Submit" class="button" id="query"></div>
</form>
</div>
EOF;

$dbOp = new dbOperations($dbConfig,$fieldset_baseQuery,$path,$tableName,$suffix,$salt="I|1Tofu+-Wev>w.W=S\$Y+5$44HC;[<");
//$dbOp->durchschn_grp1();
//$dbOp->ausgangsn_grp1(); // only can work if $dbOp->durchschn_grp1(); has been executed
//$dbOp->ausgangsn_grp2();
//$dbOp->ects_grp2();
//$dbOp->ausgangsn_grp3();
//$dbOp->ects_grp4();
//$dbOp->note_rangliste();
//$dbOp->note_rangliste("b");
//$dbOp->note_rangliste("c");
//$dbOp->note_rangliste("d");
$data_array = $dbOp->getDataFromDB($script,$limit=FALSE, $sort=FALSE, $fields=FALSE,$where=" `hidden`= 0 AND `edited`= 0 ");
$table = $data_array['table'];
$csv = $data_array['html'];

print $csv;
print $selectionForm;
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

<?php
session_start() or die('Could not start session');
$SID = session_id();
$_SESSION['sid'] = $SID;

require_once (dirname(__FILE__).'/class_htmlAssemble.php');
require_once (dirname(__FILE__).'/class_createForms.php');
require_once (dirname(__FILE__).'/class_dbOperations.php');
require_once (dirname(__FILE__).'/functions.php');

$suffix="";
$tableName = "tx_deStipendium";
$icon = "*";  // default value is star, defined here because of $icon in array. Try to change $icon to 1 or yes

$doc = new htmlAssemble($doctype = FALSE, $htmltype="", $title="Applicant Administration");
$doc->htmlhead();
global $base;

$csv = ((IsSet($csv)) ? $csv : "" );
$info = ((IsSet($info)) ? $info : $info['html'] = "");
$content = <<< EOC
<div class="main">\n<h1>Administration Form Applicants</h1>
<p><a href="$base/">Back</a> to list view.</p>
</div>\n
EOC;

date_default_timezone_set('CET');
/*
echo "<p>".date("Y/m/d H:i:s")."</p>";
$form = "%Y/%m/%d %H:%M:%s";
$d = strftime($form, 0);
echo "<p>$d</p>";
*/
//echo "<pre>";
//print_r($fieldset0);
//echo "</pre>";

$selfbase = $_SERVER['PHP_SELF'];
$getBasis = new dbOperations($dbConfig,$fieldset0,$path,$tableName,$suffix,$salt="I|1Tofu+-Wev>w.W=S\$Y+5$44HC;[<");
$getBasis->createTable();
$max_array = $getBasis->getValuesFromDB();
$a_vals = $getBasis->checkField('MAX(`uid`)');
$maxuid = $a_vals['MAX(`uid`)'];
$max = $max_array['max'];
$diff = $maxuid - $max + 1;

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

if ($maxuid > 0) {
	$return = fillForm(1,$fieldset0,$tableName);
	for ($i=0; $i<count($return['uploads']); $i++) {
		$varname = "upfield";//$return['uploads'][$i];
		$varname = $varname.$i;
		$$varname = $return['uploads'][$i];
	}
} else $upfield0 = "datei_url"; // if table is empty fillForm() returns nothing: need a fix

//  table empty
if ($max_array['max'] <= 0) { // first time here OR button 'new' pressed
	if (!$_POST OR $_POST['submit'] === 'new') {		
		$dbOp = new dbOperations($dbConfig,$fieldset0,$path,$tableName,$suffix,$salt="I|1Tofu+-Wev>w.W=S\$Y+5$44HC;[<");	
		$getrand = $dbOp->genrand();
		$chksid = $getrand;
		$maxuid = 0;
		$ai = $dbOp->get_autoindex();
		$m = $ai; //$maxuid+1;
		$self = $selfbase."?id=".$m;		
		$color = "red";
		$form = new createForms($self,$fieldset0,$icon);
		$theform = $form->toString();
		$between = "<p id=\"newentry\">You can make a new entry only!</p>";
		$string = buildHeading($theform, $color, $between);
		print $string;
	} elseif ($_POST['submit'] === 'Submit') {		
		$dbOp = new dbOperations($dbConfig,$fieldset0,$path,$tableName,$suffix,$salt="I|1Tofu+-Wev>w.W=S\$Y+5$44HC;[<");
		$newuid = $dbOp->checkField('MAX(`uid`)');
		$maxuid = $newuid['MAX(`uid`)'];
		if (!$maxuid) {
			$maxuid = $max_array['max'];
			$m = $maxuid+1;
			$return = fillForm($m,$fieldset0,$tableName);
			$filledfields = $return['fields'];
		} else {
			$maxuid = 0;
			$m = $maxuid+1;
			$filledfields = $fieldset0;
		}
		$self = $selfbase."?id=".$m;
		$entry = $dbOp->write2Table();
		print $entry['msg'];
		$data_array = $dbOp->getDataFromDB();
		$a_result = $dbOp->getIndividualVal($m, $upfield0.", gruppe");
		$color = "yellow";
		if ($a_result) {
			//$file_url = $a_result[$upfield1];
			$anhang_url = $a_result[$upfield0];
			$link1 = "<div class=\"files\">".generateLink($anhang_url);
			$link2 = "</div>\n";//generateLink($file_url)."</div>";
		} else {
			$link1 = "<div class=\"files\">".generateLink();
			$link2 = generateLink()."</div>\n";
		}
		$csv = $data_array['htmlslim'];
		$form = new createForms($self,$filledfields,$icon);  // the starting form, when db entries exist
		$info = $form->displayInfo($a_result);
		$between = $link1;
		$between .= $link2;
		$theform = $form->toString('Save');
		$string = buildHeading($theform, $color, $between);
		print $string;
	}
} elseif ($max_array['max'] > 0) { // table has entries
	if (!$_POST) { // first time here
		$dbOp = new dbOperations($dbConfig,$fieldset0,$path,$tableName,$suffix,$salt="I|1Tofu+-Wev>w.W=S\$Y+5$44HC;[<");
		$newuid = $dbOp->checkField('MAX(`uid`)');
		$maxuid = $newuid['MAX(`uid`)'];	
		if ($_GET) {
			$n = $_GET['id'];
			$n = abs($n);
			if ($n == 0) $m = 1;
			elseif ($n <= $maxuid) $m = $n;
			else $m = $maxuid;
			$self = $selfbase."?id=".$m;
		}
		else {
			$m = $maxuid;
			$self = $selfbase."?id=".$m;
		}		
		$currid = $dbOp->checkField('uid',$m);
		$saved = $dbOp->checkField('saved',$m,'uid');
		if (!$currid['bool']) {			
			$filledfields = $fieldset0;
			$color = "red";
		} else {
			$return = fillForm($m,$fieldset0,$tableName);
			$filledfields = $return['fields'];
			if ($saved['saved'] == 0) $color = "yellow";
			elseif ($saved['saved'] > 0) $color = "green";
			else $color = "yellow";	
		}
		$data_array = $dbOp->getDataFromDB();
		$a_result = $dbOp->getIndividualVal($m, $upfield0.", gruppe");
		if ($a_result) {
			//$file_url = $a_result[$upfield1];
			$anhang_url = $a_result[$upfield0];
			$link1 = "<div class=\"files\">".generateLink($anhang_url);
			$link2 = "</div>\n";//generateLink($file_url)."</div>";
			$button = "Save";
		} else {
			$link1 = "<div class=\"files\">There is no entry for uid <b>$m</b>!";
			$link2 = "</div>\n";
			$button = "no button";
		}
		$csv = $data_array['htmlslim'];		
		$form = new createForms($self,$filledfields,$icon);  // the starting form, when db entries exist
		$info = $form->displayInfo($a_result);
		$between = $link1;
		$between .= $link2;
		$theform = $form->toString($button);
		$string = buildHeading($theform, $color, $between);
		print $string;
	} elseif ($_POST['submit'] === 'new') { // 'new' pressed
		$dbOp = new dbOperations($dbConfig,$fieldset0,$path,$tableName,$suffix,$salt="I|1Tofu+-Wev>w.W=S\$Y+5$44HC;[<");
		$getrand = $dbOp->genrand();
		$chksid = $getrand;
		$newuid = $dbOp->checkField('MAX(`uid`)');
		$maxuid = $newuid['MAX(`uid`)'];
		if (!$maxuid) $maxuid = $max_array['max'];
		$color = "red";
		$ai = $dbOp->get_autoindex();
		$m = $ai; //$maxuid+1;
		$self = $selfbase."?id=".$m;
		$form = new createForms($self,$fieldset0,$icon);
		$theform = $form->toString();
		$between = "<p id=\"newentry\">You are about to make a new entry!</p>";
		$string = buildHeading($theform, $color, $between);
		print $string;
	} elseif ($_POST['submit'] === 'Submit') { // 'Submit' pressed
		$m = $maxuid;
		$self = $selfbase."?id=".$m;
		$dbOp = new dbOperations($dbConfig,$fieldset0,$path,$tableName,$suffix,$salt="I|1Tofu+-Wev>w.W=S\$Y+5$44HC;[<");		
		$newsid = $dbOp->genrand($m);
		$newuid = $dbOp->checkField('MAX(`uid`)');
		$entry = $dbOp->write2Table();
		print $entry['msg'];
		if ($entry['entry'] == 1) {			
			$ai = $dbOp->get_autoindex();
			$m = $ai-1; // $maxuid+1;
			$self = $selfbase."?id=".$m;
		} else {
			$m = $maxuid;
			$self = $selfbase."?id=".$m;
		}
		$return = fillForm($m,$fieldset0,$tableName);
		$filledfields = $return['fields'];
		$data_array = $dbOp->getDataFromDB();
		$a_result = $dbOp->getIndividualVal($m, $upfield0.", gruppe");
		$color = "yellow";
		if ($a_result) {
			//$file_url = $a_result[$upfield1];
			$anhang_url = $a_result[$upfield0];
			$link1 = "<div class=\"files\">".generateLink($anhang_url);
			$link2 = "</div>\n";//generateLink($file_url)."</div>";
		} else {
			$link1 = "<div class=\"files\">".generateLink();
			$link2 = generateLink()."</div>";
		}
		$csv = $data_array['htmlslim'];
		$form = new createForms($self,$filledfields,$icon);  // the starting form, when db entries exist
		$info = $form->displayInfo($a_result);
		$between = $link1;
		$between .= $link2;
		$theform = $form->toString('Save');
		$string = buildHeading($theform, $color, $between);
		print $string;
	} elseif ($_POST['submit'] === 'Save') { // 'Save' pressed
		if ($_GET) {
			$n = $_GET['id'];
			$n = abs($n);
			if ($n == 0) $m = 1;
			elseif ($n <= $maxuid) $m = $n;
			else $m = $maxuid;
			//$m = $n;
			$self = $selfbase."?id=".$m;
		}
		else {
			$m = $maxuid+1;
			$self = $selfbase."?id=".$m;
		}
		$dbOp = new dbOperations($dbConfig,$fieldset0,$path,$tableName,$suffix,$salt="I|1Tofu+-Wev>w.W=S\$Y+5$44HC;[<");
		$getrand = $dbOp->genrand();
		$chksid = $getrand;
		$dbOp->updateTable($m);

		$data_array = $dbOp->getDataFromDB();
		//$a_result = $dbOp->getIndividualVal($m, $upfield0);
		$a_result = $dbOp->getIndividualVal($m, $upfield0.", gruppe");
		$group = $a_result['gruppe'];
		$dbOp->bonus_gesamt($m);
		$dbOp->bonus_verfahren($m);
		if ($group) {
			switch ($group) {
				case 1:
					$dbOp->durchschn_grp1($m);
					$dbOp->ausgangsn_grp1($m);
					$dbOp->note_rangliste("",$m);
					break;
				case 2:
					$dbOp->ausgangsn_grp2($m);
					$dbOp->ects_grp2($m);
					$dbOp->note_rangliste("b",$m);
					break;
				case 3:
					$dbOp->ausgangsn_grp3($m);
					$dbOp->note_rangliste("c",$m);
					break;
				case 4:
					$dbOp->ausgangsn_grp4($m);
					$dbOp->ects_grp4($m);
					$dbOp->note_rangliste("d",$m);
					break;
			}
		} else {
			print "<p>ERROR: the variable group is empty, hence no grades have been worked out.</p>";
		}

		$return = fillForm($m,$fieldset0,$tableName);
		$filledfields = $return['fields'];
		$color = "green";
		//print_r($a_result);
		if ($a_result) {
			//$file_url = $a_result[$upfield1];
			$anhang_url = $a_result[$upfield0];
			$link1 = "<div class=\"files\">".generateLink($anhang_url);
			$link2 = "</div>\n";//generateLink($file_url)."</div>";
		} else {
			$link1 = "<div class=\"files\">".generateLink();
			$link2 = generateLink()."</div>\n";
		}
		$csv = $data_array['htmlslim'];
		$form = new createForms($self,$filledfields,$icon);  // the starting form, when db entries exist
		$info = $form->displayInfo($a_result);
		$between = $link1;
		$between .= $link2;
		$theform = $form->toString('Save');
		$string = buildHeading($theform, $color, $between);
		print $string;
	} elseif ($_POST['submit'] === 'next') { // 'next' pressed
	$dbOp = new dbOperations($dbConfig,$fieldset0,$path,$tableName,$suffix,$salt="I|1Tofu+-Wev>w.W=S\$Y+5$44HC;[<");
		$newuid = $dbOp->checkField('MAX(`uid`)');
		$maxuid = $newuid['MAX(`uid`)'];
		if ($_GET) {
			$n = $_GET['id'];
			$n = abs($n);
			if ($maxuid == 1) $n = 1;
			else $n++;
			if ($n == 0) $m = 1;
			elseif ($n <= $maxuid) $m = $n;
			else $m = $n % $maxuid;//$m = $maxuid;
			$self = $selfbase."?id=".$m;
		}
		else {
			$m = $maxuid;
			$self = $selfbase."?id=".$m;
		}
		$currid = $dbOp->checkField('uid',$m);
		$saved = $dbOp->checkField('saved',$m,'uid');
		if (!$currid['bool']) {
			$filledfields = $fieldset0;
			$color = "red";
		} else {
			$return = fillForm($m,$fieldset0,$tableName);
			$filledfields = $return['fields'];
			if ($saved['saved'] == 0) $color = "yellow";
			elseif ($saved['saved'] > 0) $color = "green";
			else $color = "yellow";
		}	
		$data_array = $dbOp->getDataFromDB();
		$a_result = $dbOp->getIndividualVal($m, $upfield0.", gruppe");
		if ($a_result) {
			//$file_url = $a_result[$upfield1];
			$anhang_url = $a_result[$upfield0];
			$link1 = "<div class=\"files\">".generateLink($anhang_url);
			$link2 = "</div>";//generateLink($file_url)."</div>";
			$button = "Save";
		} else {
			$link1 = "<div class=\"files\">There is no entry for uid <b>$m</b>!";
			$link2 = "</div>\n";
			$button = "no button";
		}
		$csv = $data_array['htmlslim'];
		$form = new createForms($self,$filledfields,$icon);  // the starting form, when db entries exist
		$info = $form->displayInfo($a_result);
		$between = $link1;
		$between .= $link2;
		$theform = $form->toString($button);
		$string = buildHeading($theform, $color, $between);
		print $string;
	} elseif ($_POST['submit'] === 'previous') { // 'previous' pressed
		$dbOp = new dbOperations($dbConfig,$fieldset0,$path,$tableName,$suffix,$salt="I|1Tofu+-Wev>w.W=S\$Y+5$44HC;[<");
		$newuid = $dbOp->checkField('MAX(`uid`)');
		$maxuid = $newuid['MAX(`uid`)'];		
		if ($_GET) {
			$n = $_GET['id'];
			$n--;
			//$n = abs($n);			
 			if ($n < 0) $m = $maxuid + $n;
 			elseif ($n == 0) $m = $maxuid;
 			elseif ($n >= $maxuid) $m = $maxuid;
 			else $m = $n % $maxuid;
			$self = $selfbase."?id=".$m;
		}
		else {
			$m = $maxuid;
			$self = $selfbase."?id=".$m;
		}
		$currid = $dbOp->checkField('uid',$m);
		$saved = $dbOp->checkField('saved',$m,'uid');
		if (!$currid['bool']) {
			$filledfields = $fieldset0;
			$color = "red";
		} else {
			$return = fillForm($m,$fieldset0,$tableName);
			$filledfields = $return['fields'];
			if ($saved['saved'] == 0) $color = "yellow";
			elseif ($saved['saved'] > 0) $color = "green";
			else $color = "yellow";	
		}
		$data_array = $dbOp->getDataFromDB();
		$a_result = $dbOp->getIndividualVal($m, $upfield0.", gruppe");
		if ($a_result) {
			//$file_url = $a_result[$upfield1];
			$anhang_url = $a_result[$upfield0];
			$link1 = "<div class=\"files\">".generateLink($anhang_url);
			$link2 = "</div>\n";//generateLink($file_url)."</div>";
			$button = "Save";
		} else {
			$link1 = "<div class=\"files\">There is no entry for uid <b>$m</b>!";
			$link2 = "</div>\n";
			$button = "no button";
		}
		$csv = $data_array['htmlslim'];
		$form = new createForms($self,$filledfields,$icon);  // the starting form, when db entries exist
		$info = $form->displayInfo($a_result);
		$between = $link1;
		$between .= $link2;
		$theform = $form->toString($button);
		$string = buildHeading($theform, $color, $between);
		print $string;
	}
		
} else {
	echo "<p>max_array['max'] is not defined</p>";
}

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

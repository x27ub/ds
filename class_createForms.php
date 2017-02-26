<?php

class createForms {
	var $actionTarget; // path to receiving page
	private $inputForms; // array of htmlFormInput
	var $temp; // temporary var for displaying contents of array, delete after dev.
	var $hiddenVariables; // assoc name/value
	var $reqicon = ""; // required icon
	var $defreq = "*"; // default required icon
	var $reqtag = "abbr";
	var $reqopen;
	var $reqclose;
	var $btn_class; // class for submit or reset buttons
	var $sub_id; // id for submit  button
	var $res_id; // id for reset button
	
	
	function __construct($action_target,$input_forms,$reqicon,$class="button",$id="subform",$id_res="resform") {
		$this->actionTarget = $action_target;
		$this->inputForms = $input_forms;
		$this->btn_class = $class;
		$this->sub_id = $id;
		$this->res_id = $id_res;
		//$this->hiddenVariables = array('fid'=>'fid'.$fid); still needs implementation
		$this->reqicon = $reqicon;
		//$this->defreq = "*";
		$this->temp = $input_forms; // temporary for displaying contents of array
		$this->reqopen = "<$this->reqtag class=\"reqstar\" title=\"required field\">";
		$this->reqclose = "</$this->reqtag>";
	}

	// helper functions
	function array_items($array,$key) {
		if (array_key_exists($key, $array)) return ($array[$key]);
		else return FALSE;
	}

	function array_key_check($array,$key) {
		if (array_key_exists($key, $array)) return TRUE;
		else return FALSE;
	}

	function truncateReturn($aString,$integer) {
		$length = strlen($aString);
		$aString = substr($aString, 0, $length-$integer);
		return $aString;
	}
	
	function putSelected($value) {
		$code ="=>";
		$pos = strpos($code, $value);
		if ($pos) return FALSE; // if $pos is false
		else {
			$split = eplode("\=>",$value);
			$return = arrray();
			$return[0] = $split[0];
			$return[1] = $split[1];
			return $return;
		}
	}

	function toString($submit=FALSE,$reset=FALSE) {
		//$chksid = isset($_POST['checksid']) ? $_POST['checksid'] : -1;
		global $chksid;	
		//generate the form
		$return_string = "";
		$return_string .= "<div id=\"myFormCont\">\n";
		$return_string .= "<form enctype=\"multipart/form-data\" action=\"$this->actionTarget\" method=\"post\" name=\"myform\">\n";
		$return_string .= "<div><input type=\"hidden\" name=\"checksid\" value=\"$chksid\" size=\"100\"></div>\n";
		$return_string .= $this->makeButton('new');
		$return_string .= $this->makeButton('previous');
		$return_string .= $this->makeButton('next');
		// generate the individual fieldsets with their resp. form fields
		$return_string .= $this->makeFieldset();
		$return_string .= "<br>\n";
		// default submit is 'submit'
		if ($submit) $thelabel = $submit;		
		else $thelabel = "submit";
		$return_string .= $this->makeButton($thelabel);
		if ($reset) $thelabel = $reset;
		else $thelabel = "reset";
		$return_string .= $this->makeButton($thelabel);
		$return_string .= "\n</form>\n</div>\n";
		return ($return_string);
	}

	function makeFieldset() {
		$return_string = "";
		// walk thru the given array: key level 0
		$form_array = $this->inputForms;
		while ($formArray = each($form_array)) {
			$fieldset = $formArray['key'];
			if (is_string($fieldset)) { // fieldset is set, thus generate it
				$return_string .= "\n<fieldset><legend>".$fieldset."</legend>\n";
				$return_string .= $this->makeFormFields($form_array,$fieldset);
				$return_string .= "</fieldset>\n";
			} elseif (is_int($fieldset)) { // no fieldset
				$return_string .= "<div class=\"form-container-$fieldset\">";
				$return_string .= $this->makeFormFields($form_array,$fieldset);
				$return_string .= "</div>";
			} else {
				$return_string .= "<div class=\"form-container-$fieldset\">";
				$return_string .= "ERROR: fieldsets must be integer or string only.";
				$return_string .= "</div>";
			}
		}
		return ($return_string);
	}

	function makeFormFields($vararray,$varkey) {
		$return_string = "";
		$class = "";
		// walk thru the given array: key level 1
		while ($formConf = each($vararray[$varkey])) {
			$label = $formConf['key'];
			while ($type = each($vararray[$varkey][$label])) {
				$typeArray = $type['key']; // $typeArray = input, textarea (key level 2)
				if ($this->array_key_check($type['value'], 'type')) $shape = $type['value']['type'];
				//else $shape = "";
				//endow lable with 'class' or 'label_class' if defined
				if ($this->array_key_check($type['value'], 'class')) { 
					$classv = $type['value']['class'];
					$class = "class=\"$classv\"";
				}
				if ($this->array_key_check($type['value'], 'label_class')) {
					$lclass = $type['value']['label_class'];
					if ($lclass === NULL) $class = "";
					elseif (is_string($lclass)) $class = "class=\"$lclass\"";
					else $class = "";				
				}
				//echo "<p>lclass = $lclass</p>"; echo "<p>class = $class</p>";
				// generate from individual form fields according to type and shape
				if ($typeArray === 'input' && ($shape === 'text' OR  $shape === 'email' OR  $shape === 'url' OR  $shape === 'tel' OR  $shape === 'search' OR  $shape === 'date' OR  $shape === 'time' OR  $shape === 'datetime' OR  $shape === 'datetime-local' OR  $shape === 'month' OR  $shape === 'week' OR $shape === 'number' OR $shape === 'range')) {
					$result = $this->makeInputField($vararray, $varkey, $label, $typeArray);
					$return_string .= "<div><label $class for=\"{$label}\">".$label.": ".$result[1]." </label>";
					$return_string .= "<".$typeArray." ";
					$return_string .= $result[0];
				}  elseif($typeArray === 'input' AND $shape === 'datalist') {
					$result = $this->makeDataList($vararray, $varkey, $label, $typeArray);
					$return_string .= "<div><label $class for=\"{$label}\">".$label.": ".$result[1]." </label>\n";
					$return_string .= "<".$typeArray." "; // "<".$typeArray." type=\"text\" ";
					$return_string .= $result[0];
				} elseif($typeArray === 'input' AND $shape === 'file') {
					$result = $this->makeUpFile($vararray, $varkey, $label, $typeArray);
					$return_string .= "<div><label $class for=\"{$label}\">".$label.": ".$result[1]." </label>\n";
					$return_string .= "<".$typeArray." ";
					$return_string .= $result[0];					
				} elseif($typeArray === 'input' && $shape === 'password') {
					$result = $this->makePassword($vararray, $varkey, $label, $typeArray);
					$return_string .= "<div><label $class for=\"{$label}\">".$label.": ".$result[1]." </label>";
					$return_string .= "<".$typeArray." ";
					$return_string .= $result[0];
				} elseif($typeArray === 'input' && $shape === 'submit') {
					$return_string .= "<div><label $class for=\"{$label}\">".$label.": </label>";
					$return_string .= "<".$typeArray." ";
					$return_string .= $this->makeNextButton($vararray, $varkey, $label, $typeArray);
				} elseif($typeArray === 'input' && $shape === 'radio') {
					$result = $this->makeRadio($typeArray, $vararray, $varkey, $label, $typeArray);
					$return_string .= "<div><label $class for=\"{$label}\">".$label.": ".$result[1]." </label>";
					$return_string .= $result[0];
				} elseif($typeArray === 'input' && $shape === 'checkbox') {
					$result = $this->makeCheckbox($typeArray, $vararray, $varkey, $label, $typeArray);
					$return_string .= "<div><label $class for=\"{$label}\">".$label.": ".$result[1]." </label>";
					$return_string .= $result[0];
				} elseif($typeArray === 'select') {
					$result = $this->makeSelect($typeArray, $vararray, $varkey, $label, $typeArray);
					$return_string .= "<div><label $class for=\"{$label}\">".$label.": ".$result[1]." </label>\n"; // " </label><br>\n"; ?
					$return_string .= $result[0];
				} elseif($typeArray === 'textarea') {
					$result = $this->makeTextarea($vararray, $varkey, $label, $typeArray);
					$return_string .= "<div><label $class for=\"{$label}\">".$label.": ".$result[1]." </label>";
					$return_string .= "<".$typeArray." ";
					$return_string .= $result[0];
				} elseif ($typeArray === 'input' && $shape === 'hidden') {
					$return_string .= "<div><".$typeArray." ";
					$return_string .= $this->makeHiddenField($vararray, $varkey, $label, $typeArray);
					// these functions need still to be created 
				} 
//				elseif ($typeArray === 'input' && ($shape === 'number' OR $shape === 'range')) {
//					$result = $this->makeInputField($vararray, $varkey, $label, $typeArray);
//					$return_string .= "<div><label for=\"{$label}\">".$label.": ".$result[1]." </label>";
//					$return_string .= "<".$typeArray." ";
//					$return_string .= $result[0];
//				} 
				elseif ($typeArray === 'input' && $shape === 'color') {
					$result = $this->makeInputField($vararray, $varkey, $label, $typeArray);
					$return_string .= "<div><label $class for=\"{$label}\">".$label.": ".$result[1]." </label>";
					$return_string .= "<".$typeArray." ";
					$return_string .= $result[0];
				} elseif ($typeArray === 'input' && $shape === 'pattern') {
					$result = $this->makeInputPattern($vararray, $varkey, $label, $typeArray);
					$return_string .= "<div><label $class for=\"{$label}\">".$label.": ".$result[1]." </label>";
					$return_string .= "<".$typeArray." type=\"text\" ";
					$return_string .= $result[0];
				} else echo "ERROR: shape =<pre>". print_r($shape)."</pre>  not defined properly.";
			} 
			$return_string .= "</div>\n";
			//$return_string .= "<pre>".$shape."</pre>";
		}
		return $return_string;
	}

	function makeInputField($thearray,$thekey,$thelabel,$thetype) {
		$return_string = "";
		$required = "";
			while ($config = each($thearray[$thekey][$thelabel][$thetype])) {
			$name = $config['key'];
			$value = $config['value'];
			if ($name === '@#db') {
				$return_string .= '';
			} elseif ($name === 'label_class') {
				$return_string .= "";
			} elseif ($name === 'required') {
				$return_string .= '';
				$value = (!empty($value) ?  $this->reqicon : $this->defreq);
				//$required = "<abbr class=\"reqstar\" title=\"required field\">".$value."</abbr>";
				$required = $this->reqopen.$value.$this->reqclose;
			} else {
				$return_string .= $name."=\"".$value."\" ";
			}
		}
		$return_string = $this->truncateReturn($return_string,1);
		$return_string .= ">";
		$result = array();
		$result[0] = $return_string;
		$result[1] = $required;
		return $result;
	}
	
	function makeInputPattern($thearray,$thekey,$thelabel,$thetype) {
		$return_string = "";
		$required = "";
			while ($config = each($thearray[$thekey][$thelabel][$thetype])) {
			$name = $config['key'];
			$value = $config['value'];
			if ($name === '@#db') {
				$return_string .= '';
			} elseif ($name === 'label_class') {
				$return_string .= '';
			} elseif ($name === 'required') {
				$return_string .= '';
				$value = (!empty($value) ?  $this->reqicon : $this->defreq);
				//$required = "<abbr class=\"reqstar\" title=\"required field\">".$value."</abbr>";
				$required = $this->reqopen.$value.$this->reqclose;
			} elseif ($value === 'pattern') {
				$return_string .= '';
			} else {
				$return_string .= $name."=\"".$value."\" ";
			}
		}
		$return_string = $this->truncateReturn($return_string,1);
		$return_string .= ">";
		$result = array();
		$result[0] = $return_string;
		$result[1] = $required;
		return $result;
	}
	
	function makeDataList($thearray,$thekey,$thelabel,$thetype) {
		$return_string = "";
		$required = "";
		$datalist = "";
		while ($config = each($thearray[$thekey][$thelabel][$thetype])) {
			$name = $config['key'];
			$value = $config['value'];
			if ($name === '@#db') {
				$return_string .= '';
			} elseif ($name === 'label_class') {
				$return_string .= "";
			} elseif ($name === 'required') {
				$return_string .= '';
				$value = (!empty($value) ?  $this->reqicon : $this->defreq);
				//$required = "<abbr class=\"reqstar\" title=\"required field\">".$value."</abbr>";
				$required = $this->reqopen.$value.$this->reqclose;
			} elseif ($name === 'type') {
				$return_string .= "";
			} elseif ($name === 'match') {
					$return_string .= "list=\"".$value."\" ";
					$datalist .= "\n<datalist id=\"".$value."\">\n";
			} elseif ($name === 'optionslist') {
				while ($options =  each($thearray[$thekey][$thelabel][$thetype]['optionslist'])) { //each($thearray[$thekey][$thelabel][$thetype] $typeArray,
					$itemkey = $options['key'];
					$item = $options['value'];
					$datalist .= "<option value=\"".$item."\"></option>\n";
					//$datalist .= "<option value=\"".$item."\">$item</option>\n";
				}
				$datalist .= "</datalist>\n";
			} else {
				$return_string .= $name."=\"".$value."\" ";
			}
		}
		$return_string = $this->truncateReturn($return_string,1);
		$return_string .= ">";
		$return_string .= $datalist;
		$result = array();
		$result[0] = $return_string;
		$result[1] = $required;
		return $result;
	}
	
	function makeUpFile($thearray,$thekey,$thelabel,$thetype) {
		$varSuffix = "";
		$id_progress = "";
		$return_string = "";
		$required = "";
		$file_prop = array();
			while ($config = each($thearray[$thekey][$thelabel][$thetype])) {
			$name = $config['key'];
			$value = $config['value'];
			if ($name === '@#db') {
				$return_string .= '';
			} elseif ($name === 'label_class') {
				$return_string .= "";
			} elseif ($name === 'required') {
				$return_string .= '';
				$value = (!empty($value) ?  $this->reqicon : $this->defreq);
				//$required = "<abbr class=\"reqstar\" title=\"required field\">".$value."</abbr>";
				$required = $this->reqopen.$value.$this->reqclose;
			} elseif ($name === 'accept') {
				if (is_array($value)) {
					$i = -1;
					$arraylength = count($config['value']);
					$acceptattr = $name;
					$return_string .= $name."=\"";
					while ($mime = each($value)) {
						$i++;
						$mimekey = $mime['key'];
						$mimeval = $mime['value'];
						if ($i < $arraylength-1) {
							$return_string .= $mimeval.", ";
						} else {
							$return_string .= $mimeval."\" ";
						}
					}
				} else {
					$return_string .= $name."=\"".$value."\" ";
				}
			} elseif ($name === 'maxfilesize') {
				$return_string .= '';
				$file_prop['maxfilesize'] = $value;
			} elseif ($name === 'allowed_filetypes') {
				$return_string .= '';
				$file_prop['allowed_filetypes'] = $value;
			} elseif ($name === 'allowed_extensions') {
				$return_string .= '';
				$file_prop['allowed_extensions'] = $value;
			} elseif ($name === 'name') {
				$return_string .= $name."=\"".$value."\" ";
				$varSuffix = "_".$value;
				$id_progress = "progress_key".$varSuffix;
				$file_prop[$name] = $value;
			} else {
				$return_string .= $name."=\"".$value."\" ";
			}
		}
		$return_string = $this->truncateReturn($return_string,1);
		$return_string .= ">";
		$return_string .= "\n<input type=\"hidden\" name=\"APC_UPLOAD_PROGRESS\" id=\"$id_progress\" value=\"$varSuffix\" />";
		$result = array();
		$result[0] = $return_string;
		$result[1] = $required;
		$result[2] = $file_prop;
		return $result;
	}

	function makePassword($thearray,$thekey,$thelabel,$thetype) {
		$return_string = "";
		$required = "";
		while ($config = each($thearray[$thekey][$thelabel][$thetype])) {
			$name = $config['key'];
			$value = $config['value'];
			// avoid html output for db params
			if ($name === '@#db') {
				$return_string .= '';
			} elseif ($name === 'label_class') {
				$return_string .= "";
			} elseif ($name === 'required') {
				$return_string .= '';
				$value = (!empty($value) ?  $this->reqicon : $this->defreq);			
				//$required = "<abbr class=\"reqstar\" title=\"required field\">".$value."</abbr>";
				$required = $this->reqopen.$value.$this->reqclose;
			} else {
				$return_string .= $name."=\"".$value."\" ";
			}
		}
		$return_string = $this->truncateReturn($return_string,1);
		$return_string .= ">";
		$result = array();
		$result[0] = $return_string;
		$result[1] = $required;
		return $result;
	}

	function makeNextButton($thearray,$thekey,$thelabel,$thetype) {
		$return_string = "";
		while ($config = each($thearray[$thekey][$thelabel][$thetype])) {
			$name = $config['key'];
			$value = $config['value'];
			$return_string .= $name."=\"".$value."\" ";
		}
		$return_string = $this->truncateReturn($return_string,1);
		$return_string .= ">";
		return $return_string;
	}

	function makeTextarea($thearray,$thekey,$thelabel,$thetype) {
		$return_string = "";
		$required = "";
		$fill = '';
		while ($config = each($thearray[$thekey][$thelabel][$thetype])) {
			$name = $config['key'];
			$value = $config['value'];
			// avoid html output for db params
			if ($name === '@#db') {
				$return_string .= "";
			} elseif ($name === 'default') {
				$fill = $value;
			} elseif ($name === 'label_class') {
				$return_string .= "";
			} elseif ($name === 'required') {
				$return_string .= '';
				$value = (!empty($value) ?  $this->reqicon : $this->defreq);
				//$required = "<abbr class=\"reqstar\" title=\"required field\">".$value."</abbr>";
				$required = $this->reqopen.$value.$this->reqclose;
			} else {
				$return_string .= $name."=\"".$value."\" ";
			}
		}
		$return_string = $this->truncateReturn($return_string,1);
		$return_string .= ">".$fill."</textarea>";
		$result = array();
		$result[0] = $return_string;
		$result[1] = $required;
		return $result;
	}

	function makeRadio($theTypeArray,$thearray,$thekey,$thelabel,$thetype) {
		$return_string = "";
		$required = "";
		$config_string = "";
		$value_length = count($thearray[$thekey][$thelabel][$thetype]['value']);
		$config_length = count($thearray[$thekey][$thelabel][$thetype]);
		$radio_string = '';
		$value_string = '';
		$j = 0;
		$i = 0;
		$return_string .= "\n<ul style=\"list-style:none;\">\n"; // label and first radio button not on same line

		while ($value_array = each($thearray[$thekey][$thelabel][$thetype]['value'])) {
			$j++;
			$keyval = $value_array['key'];
			$value = $value_array['value'];
			$return_string .= "<li><".$theTypeArray." ";
			// find out if radio button ought to be checked
			if(strstr($value, "=")) {
				$pos = strpos($value, "=");
				$trimvalue = substr($value, 0, $pos);
				$checked = "checked=\"checked\"";
				$value_string = "value=\"".$keyval."\" ".$checked." ";
				$label = $trimvalue;
				$i++; // count the no of checked radios
			} else {
				$value_string = "value=\"".$keyval."\" ";
				$label = $value;
			}
			// write the radio buttons prams
			while ($config = each($thearray[$thekey][$thelabel][$thetype])) {								
				$name = $config['key'];
				$value = $config['value'];
				$items = each($config);
				if ($config['key'] === 'value') {
					$config_string .= '';
				} elseif ($name === 'label_class') {
					$return_string .= "";
				} elseif ($config['key'] === '@#db') {
					$config_string .= '';
				} elseif ($name === 'required') {
					$return_string .= '';
					$value = (!empty($value) ?  $this->reqicon : $this->defreq);
					//$required = "<abbr class=\"reqstar\" title=\"required field\">".$value."</abbr>";
					$required = $this->reqopen.$value.$this->reqclose;
				} else {
					$value = $config['value'];
					$config_string .= $name."=\"".$value."\" ";
				}
			}
			$return_string .= $config_string.$value_string;
			$return_string = $this->truncateReturn($return_string,1);
			if ($j < $value_length) {
				$return_string .= ">$label</li>\n"; // makes radio buttons vertical
			} else $return_string .= ">$label</li>\n</ul>"; // no <br> tag after last radio button
		}
		if ($i>1) { // no of checked radios must be 0 or 1
			$return_string .= "<p class=\"phperror\">ERROR: $i radio buttons marked as checked</p>";
		}
		$result = array();
		$result[0] = $return_string;
		$result[1] = $required;
		return $result;
	}

	function makeCheckbox($theTypeArray,$thearray,$thekey,$thelabel,$thetype) {
		$return_string = "";
		$required = "";
		$config_string = "";
		$value_length = count($thearray[$thekey][$thelabel][$thetype]['value']);
		$config_length = count($thearray[$thekey][$thelabel][$thetype]);
		$radio_string = '';
		$value_string = '';
		$j = 0;
		$i = 0;
		$return_string .= "\n<ul style=\"list-style:none;\">\n"; // label and first check box not on same line

		while ($value_array = each($thearray[$thekey][$thelabel][$thetype]['value'])) {
			$j++;
			$keyval = $value_array['key'];
			$value = $value_array['value'];
			$return_string .= "<li><".$theTypeArray." ";
			// find out if the box ought to be checked
			if(strstr($value, "=")) {
				$pos = strpos($value, "=");
				$trimvalue = substr($value, 0, $pos);
				$checked = "checked=\"checked\"";
				$value_string = "value=\"".$keyval."\" ".$checked." ";
				$label = $trimvalue;
				$i++; // no of checked checkboxes
			} else {
				$value_string = "value=\"".$keyval."\" ";
				$label = $value;
			}
			// write <input type="checkbox" ... > and other params
			while ($config = each($thearray[$thekey][$thelabel][$thetype])) {				
				$name = $config['key'];
				$value = $config['value'];
				if ($name === 'value') {
					$config_string .= '';
				} elseif ($name === 'label_class') {
					$return_string .= "";
				} elseif ($name === '@#db') {
					$config_string .= '';
				} elseif ($name === 'name') {
					$config_string .= $name."=\"".$value."[]\" "; // name=somename[] needs to be an array
				} elseif ($name === 'required') {
					$return_string .= '';
					$value = (!empty($value) ?  $this->reqicon : $this->defreq);
					//$required = "<abbr class=\"reqstar\" title=\"required field\">".$value."</abbr>";
					$required = $this->reqopen.$value.$this->reqclose;
				} else $config_string .= $name."=\"".$value."\" ";
			}
			$return_string .= $config_string.$value_string;
			$return_string = $this->truncateReturn($return_string,1);
			if ($j < $value_length) {
				$return_string .= ">$label</li>\n"; // makes check boxes vertical
			} else $return_string .= ">$label</li>\n</ul>"; // no <br> tag after last radio button
		}
		$result = array();
		$result[0] = $return_string;
		$result[1] = $required;
		return $result;
	}

	function makeSelect($theTypeArray,$thearray,$thekey,$thelabel,$thetype) {
		$return_string = "";
		$required = "";
		// find out if selection list is with 'optgroup' or 'options'
		if ($this->array_key_check($thearray[$thekey][$thelabel][$thetype],'optgroup')) {
			if ($this->array_key_check($thearray[$thekey][$thelabel][$thetype],'multiple')){
				$return_string .= "<br>\n"; // label and selection field not on same line
			}
			// 1 produce <select name= ... >
			$return_string .= "<".$theTypeArray." ";
			while ($config = each($thearray[$thekey][$thelabel][$thetype])) {
				$name = $config['key'];
				$value = $config['value'];
				$check = $this->putSelected($value);
				if(!$check){
					$value = $config['value'];
				} else {
					$value = $check[0];
					$value .= " selected=\"" . $check[1] . "\" ";
				}
				// avoid html output for db params
				if ($name === '@#db') {
					$return_string .= '';
				} elseif ($name === 'label_class') {
					$return_string .= "";
				} elseif ($name === 'required') {
					$return_string .= '';
					$value = (!empty($value) ?  $this->reqicon : $this->defreq);
					//$required = "<abbr class=\"reqstar\" title=\"required field\">".$value."</abbr>";
					$required = $this->reqopen.$value.$this->reqclose;
				} else {
					// want no 'options= ...' and [] if name
					if ($name === 'name') {
						$return_string .= $name."=\"".$value."[]\" "; // name=somename[] needs to be an array
					} elseif ($name === 'optgroup') {
						$return_string .= "";
					} else $return_string .= $name."=\"".$value."\" ";
				}
			}
			$return_string = $this->truncateReturn($return_string,1);
			$return_string .= ">\n";
			// 2 produce <optgroup ... >
			while ($optgroup = each($thearray[$thekey][$thelabel][$thetype]['optgroup'])) {
				$optlabel = $optgroup['key'];
				$return_string .= "<optgroup label=\"$optlabel\">\n";
				// produce <option ...> ... </options>
				while ($items = each($thearray[$thekey][$thelabel][$thetype]['optgroup'][$optlabel])) {
					//$value = $config['value'];
					$itemkey = $items['key'];
					$item = $items['value'];
					if(strstr($item, "=")) {
						$pos = strpos($item, "=");
						$trimvalue = substr($item, 0, $pos);
						$selected = "selected=\"selected\"";
						$value_string = "value=\"".$itemkey."\" ".$selected." ";
						$label = $trimvalue; // $item without the '=selected' part
						$return_string .= "<option";
						// put a value=".." into the option's tag
						$return_string .= " ".$value_string; //" value=\"".$item."\"";
						$return_string = $this->truncateReturn($return_string,1);
						$return_string .= ">$label</option>\n";
					} else {
						$value_string = "value=\"".$item."\" ";
						$return_string .= "<option";
						// put a value=".." into the option's tag or comment out
						$return_string .= " value=\"".$itemkey."\"";
						$return_string .= ">$item</option>\n";
					}
				}
				$return_string .= "</optgroup>\n";
			}
			$return_string .= "</select>\n";
		} elseif ($this->array_key_check($thearray[$thekey][$thelabel][$thetype],'options')) {
			if ($this->array_key_check($thearray[$thekey][$thelabel][$thetype],'multiple')){
				$return_string .= "<br>\n"; // label and selection field not on same line
			}
			// 1 produce <select name= ... >			
			$return_string .= "<".$theTypeArray." ";
			while ($config = each($thearray[$thekey][$thelabel][$thetype])) {
				$name = $config['key'];
				$value = $config['value'];
				// avoid html output for db params
				if ($name === '@#db') {
					$return_string .= '';
				} elseif ($name === 'required') {
					$return_string .= '';
					$value = (!empty($value) ?  $this->reqicon : $this->defreq);
					//$required = "<abbr class=\"reqstar\" title=\"required field\">".$value."</abbr>";
					$required = $this->reqopen.$value.$this->reqclose;
				} else {
					// want no 'options= ...' and [] if name
					if ($name === 'name') {
						$return_string .= $name."=\"".$value."[]\" "; // name="somename[]" needs to be an array
					} elseif ($name === 'options') {
						$return_string .= "";
					} else $return_string .= $name."=\"".$value."\" ";
				}
			}
			$return_string = $this->truncateReturn($return_string,1);
			$return_string .= " >\n";			
			while ($options = each($thearray[$thekey][$thelabel][$thetype]['options'])) {
				$value = $config['value'];
				$itemkey = $options['key'];
				$item = $options['value'];
				// 2 produce <option ...> ... </options>
				if(strstr($item, "=")) {
					$pos = strpos($item, "=");
					$trimvalue = substr($item, 0, $pos);
					$selected = "selected=\"selected\"";
					$value_string = "value=\"".$itemkey."\" ".$selected." ";
					$label = $trimvalue; // $item without the '=selected' part
					$return_string .= "<option";
					// put a value=".." into the option's tag
					$return_string .= " ".$value_string; //" value=\"".$item."\"";
					$return_string = $this->truncateReturn($return_string,1);
					$return_string .= ">$label</option>\n";
				} else {
					$value_string = "value=\"".$value."\" ";
					$return_string .= "<option";
					// put a value=".." into the option's tag or comment out
					$return_string .= " value=\"".$itemkey."\"";
					$return_string .= ">$item</option>\n";
				}
			}
			$return_string .= "</select>\n";
		} else {
			$return_string .= "<".$theTypeArray."><option>Error!</option></select>";
			$return_string .= "<p class=\"phperror\">ERROR: configuration array must indicate";
			$return_string .= " exactly  one of the two values: 'optgroup' or 'options'. Not 'option' !</p>";
		}
		$result = array();
		$result[0] = $return_string;
		$result[1] = $required;
		return $result;
	}

	function makeHiddenField($thearray,$thekey,$thelabel,$thetype) {
		$return_string = "";
		while ($config = each($thearray[$thekey][$thelabel][$thetype])) {
			$name = $config['key'];
			$value = $config['value'];
			if ($name === '@#db') {
				$return_string .= '';
			} elseif ($name === 'label_class') {
				$return_string .= "";
			} else { //? elseif ($name !== '@#db') {
				$return_string .= $name."=\"".$value."\" ";
			}
			//$return_string .= $name."=\"".$value."\" ";
		}
		$return_string = $this->truncateReturn($return_string,1);
		$return_string .= ">";
		return $return_string;
	}

	function makeButton($buttonType) {
		$class = $this->btn_class;
		$id = $this->sub_id;
		$id_res = $this->res_id;
		if ($buttonType === "submit") {			
			$return_string = "<input type=\"submit\" name=\"submit\" value=\"Submit\" class=\"$class\" id=\"$id\">";
		} elseif ($buttonType === 'reset') {
			$return_string = "<input type=\"reset\" name=\"reset\" value=\"Reset Form\" class=\"$class\" id=\"$id_res\">";
		} elseif ($buttonType === "no button") {
			$return_string = "";
		} else {
		 	$return_string = "<input type=\"submit\" name=\"submit\" value=\"$buttonType\" class=\"$class\" id=\"$buttonType\">";
		}
		return($return_string);
	}
	
	function getIndividualVal($uid, $fields=FALSE, $table=FALSE) {
		$return = array();
		$stringOFields = "";
		if (!$table) $table = $this->tableName . $this->tableSuffix;
		if (!$fields) $stringOFields = "";
		else {
			$pieces = explode(", ", $fields);
			$no = count($pieces);
			for ($i=0;$i<$no;$i++) {
				$trimmed = trim($pieces[$i]); // just in case: no spaces around 'field names'
				$stringOFields .= "`$trimmed`, ";
				$$trimmed = $trimmed;
				//echo $trimmed;
			}
			//echo " $no --> ".$stringOFields."<br>";
		}
		$query = "SELECT `uid`, `crdate`, `tstamp`, `hidden`, `deleted`, ";
		$query .= $stringOFields;
		$query .= " `pid`, `fid` FROM `".$table."` WHERE `uid`= ".$uid." AND `deleted` = 0 ORDER BY `uid` ASC";
		//echo "<br>".$query."</br>";
		$sql_connect = @new mysqli($this->host, $this->user, $this->pass, $this->dbname);
		$sql_connect->set_charset($this->charset);
		if (mysqli_connect_error())
		die("Cannot connect to database! ");
		else {
			if($result = $sql_connect->query($query)) {
				$rows = $result->num_rows;
				$cols = $result->field_count;
				//$result = $sql_connect->query($query);
				$row = $result->fetch_assoc();
				if ($fields AND $rows > 0) {
					$return['uid'] = $row['uid'];
					$return['crdate'] = $row['crdate'];
					$return['tstamp'] = $row['tstamp'];
					$return['hidden'] = $row['hidden'];
					$return['deleted'] = $row['deleted'];
					$pieces = explode(", ", $fields);
					$no = count($pieces);
					for ($i=0;$i<$no;$i++) {
						$trimmed = trim($pieces[$i]);
						$$trimmed = $row[$trimmed];
						//echo "<br>".$trimmed." = ".$$trimmed."<br>";
						$return[$trimmed] = $$trimmed;
					}
					$return['pid'] = $row['pid'];
					$return['fid'] = $row['fid'];
					echo "<pre>";
					print_r($return);
					//print_r($row);
					echo "</pre>";												
				} else {}
			}
		}
		return $return;
	}
	
	function displayInfo($a_data) {
		$display = "";
		$return = array();
		$format = '%d-%m-%Y at %k:%M:%S hrs';
		//strftime($format,$maxpoint);
		if ($a_data) {
			$uid = $a_data['uid'];
			$crdate = $a_data['crdate'];
			$crdate = strftime($format,$crdate);
			$tstamp = $a_data['tstamp'];
			$tstamp = strftime($format,$tstamp);
			$deleted = $a_data['deleted'];
			$hidden = $a_data['hidden'];
			$edited = $a_data['edited'];
			if (array_key_exists('gruppe', $a_data)) {
				if ($a_data['gruppe'] == 0) $group = "not yet selected";
				else $group = $a_data['gruppe'];			
			}
			else {
				$group = "not available";
			}
			$display = "<div class=\"metainfo\" id=\"metai\">\n<p>";
			if ($deleted == 1) {
				$display .= "Record <b class=\"errormsg\"> 'DELETED'</b></p>\n<p>";
			}
			else {
				if ($hidden == 1) {
					$display .= "Record <b class=\"errormsg\"> 'HIDDEN'</b></p>\n<p>";
				}
				if ($edited == 1) {
					$display .= "Record fully<b class=\"errormsg\"> 'EDITED'</b></p>\n<p>";
				}
			}
			
			$display .= "Group: <b>".$group."</b>";
			$display .= "</p>\n<p>";
			$display .= "This is uid: <b>".$uid."</b>";
			$display .= "</p>\n<p>";
			$display .= "Created on: <b>".$crdate."</b>";
			$display .= "</p>\n<p>";
			$display .= "Last  changed: <b>".$tstamp."</b>";
			$display .= "</div>\n";
			
			$return['hidden'] = $hidden;
			$return['deleted'] = $deleted;
			$return['html'] = $display;
		} else {
			$return['deleted'] = NULL;
			$return['html'] = "<p>Record <b class=\"errormsg\">'DELETED'</b> from table.</p>";
		}
		return $return;
	}
}

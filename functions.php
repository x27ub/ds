<?php
require_once (dirname(__FILE__).'/variables.php');


$now = time();
$deltaT = (18*365*24*3600); // 18a
$maxpoint = $now - $deltaT;
$minpoint = ($now-(80*365*24*3600));
$cusformat = '%d-%m-%Y';
$format = '%Y-%m-%d';
$fmaxpoint = strftime($format,$maxpoint);
$fminpoint = strftime($format,$minpoint);
//echo $fminpoint."<br>".$fmaxpoint;

function mk_date_range($lower,$upper,$start) {
	$tp = $start;
	$format = '%d-%m-%Y';
	$format = '%Y-%m-%d';
	//global $format;
	$range = array();
	$maxpoint = $tp - $upper;
	$minpoint = ($tp- $lower);
	$fmaxpoint = strftime($format,$maxpoint);
	$fminpoint = strftime($format,$minpoint);
	$range['max'] = $fmaxpoint;
	$range['min'] = $fminpoint;
	return $range;
}

function mk_year_array($range,$negrg=FALSE) {
	$now = time();
	$yearonly = '%Y';
	$currYear = strftime($yearonly,$now);
	$a_selectYs = array(0=>'bitte ausw&auml;hlen');
	$i = 0;
	if (!$negrg) $negrange = (-1 * $range);
	else $negrange = (-1 * $negrg);
	for ($i=0; $i<$range; $i++) {	
		$currYear = $currYear + $negrange + $i;
		$a_selectYs[$currYear] = $currYear;
		$currYear = strftime($yearonly,$now);		
	}
	for ($i=0; $i<$range; $i++) {		
		$currYear = $currYear + $i;
		$a_selectYs[$currYear] = $currYear;
		$currYear = strftime($yearonly,$now);		
	}
	return $a_selectYs;
}

//$list_upload = array(0=>1);

function fillForm($t,$array_fields,$table,$applicantsTable,$sponsorsTable,$path="",$suffix="",$salt="I|1Tofu+-Wev>w.W=S\$Y+5$44HC;[<") {
//echo "<pre>array_fields = ";
//print_r($array_fields);
//echo "</pre>";
	//global $dbConfig, $fieldset0, $fieldset_v, $fieldset_spons, $path, $tableName, $suffix, $icon, $ext, $accept, $allowfiles, $maxfilesize, $fmaxpoint, $fminpoint, $country;
	global $dbConfig, $icon, $ext, $accept, $allowfiles, $maxfilesize, $fmaxpoint, $fminpoint, $country;
	global $a_selectYs, $fmaxpoint, $fminpoint, $d16, $d40, $d1, $dx, $list_upload, $format, $cusformat;
	$mat_bew_nr=$name=$vorname=$geb=$str_nr=$str_zusatz=$plz_ort=$email=$tel=$status=$stipendium=$stipendium_name=$stipendium_hoehe=$hzb=$deutsch=$mathe=$fremdsprache=$durschn_kern=$ausgangsnote=$ects_b=$soll_etcs_b=$ects_abw_b=$ects_abw_bemer_b=$studleistungen_b=$ausgangsnote_b=$note_bachelor_c=$ausgangsnote_c=$ects_d=$soll_ects_d=$ects_abw_d=$ects_abw_bemer_d=$studleistungen_d=$note_bachelor_d=$ausgangsnote_d=$sem=$hs=$bonus_gesamt=$bonus_verfahren=$note_rangliste=$bew_beginn=$bew_ende=$stip_dauer=$s_id_nr=$s_staat=$s_hs_sem=$s_fach_sem=$s_abschl=$s_foerd_monate=$f_foerder=$f_str_nr=$f_plz_ort=$f_name=$f_funktion=$f_tel=$f_email=$f_summe=$f_foerd_beginn=$f_foerd_ende=$f_id=$a_f_r_form_k=$f_freie_m=$f_gebundene_m=$durchschn_kern=$soll_ects_b=$verl_dauer_grund=$durchschn_kern=$us="";	
	$s_jahr = $f_jahr = $a_selectYs;
	$staat = array('AFG'=>'Afghanistan','ALA'=>'&Aring;land','ALB'=>'Albania','DZA'=>'Algeria','ASM'=>'American Samoa','AND'=>'Andorra','AGO'=>'Angola','AIA'=>'Anguilla','ATA'=>'Antarctica','ATG'=>'Antigua and Barbuda','ARG'=>'Argentina','ARM'=>'Armenia','ABW'=>'Aruba','AUS'=>'Australia','AUT'=>'Austria','AZE'=>'Azerbaijan','BHR'=>'Bahrain','BGD'=>'Bangladesh','BRB'=>'Barbados','BLR'=>'Belarus','BEL'=>'Belgium','BLZ'=>'Belize','BEN'=>'Benin','BMU'=>'Bermuda','BTN'=>'Bhutan','BOL'=>'Bolivia','BES'=>'Bonaire, Sint Eustatius and Saba','BIH'=>'Bosnia and Herzegovina','BWA'=>'Botswana','BVT'=>'Bouvet Island','BRA'=>'Brazil','IOT'=>'British Indian Ocean Territory','VGB'=>'British Virgin Islands','BRN'=>'Brunei','BGR'=>'Bulgaria','BFA'=>'Burkina Faso','BDI'=>'Burundi','KHM'=>'Cambodia','CMR'=>'Cameroon','CAN'=>'Canada','CPV'=>'Cape Verde','CYM'=>'Cayman Islands','CAF'=>'Central African Republic','TCD'=>'Chad','CHL'=>'Chile','CHN'=>'China','CXR'=>'Christmas Island','CCK'=>'Cocos Keeling) Islands','COL'=>'Colombia','COM'=>'Comoros','COD'=>'Congo','COG'=>'Congo-Brazzaville','COK'=>'Cook Islands','CRI'=>'Costa Rica','CIV'=>'CÃ´te d?~@~YIvoire','HRV'=>'Croatia','CUB'=>'Cuba','CCUW'=>'CuraÃ§ao','CYP'=>'Cyprus','CZE'=>'Czech Republic','DNK'=>'Denmark','DJI'=>'Djibouti','DMA'=>'Dominica','DOM'=>'Dominican Republic','ECU'=>'Ecuador','EGY'=>'Egypt','SLV'=>'El Salvador','GNQ'=>'Equatorial Guinea','ERI'=>'Eritrea','EST'=>'Estonia','ETH'=>'Ethiopia','FLK'=>'Falkland Islands','FRO'=>'Faroes','FJI'=>'Fiji','FIN'=>'Finland','FRA'=>'France','GUF'=>'French Guiana','PYF'=>'French Polynesia','ATF'=>'French Southern Territories','GAB'=>'Gabon','GMB'=>'Gambia','GEO'=>'Georgia','DEU'=>'Germany','GHA'=>'Ghana','GIB'=>'Gibraltar','GRC'=>'Greece','GRL'=>'Greenland','GRD'=>'Grenada','GLP'=>'Guadeloupe','GUM'=>'Guam','GTM'=>'Guatemala','GGY'=>'Guernsey','GIN'=>'Guinea','GNB'=>'Guinea-Bissau','GUY'=>'Guyana','HTI'=>'Haiti','HMD'=>'Heard Island and McDonald Islands','HND'=>'Honduras','HKG'=>'Hong Kong SAR of China','HUN'=>'Hungary','ISL'=>'Iceland','IND'=>'India',
'IDN'=>'Indonesia','IRN'=>'Iran','IRQ'=>'Iraq','IRL'=>'Ireland','IMN'=>'Isle of Man','ISR'=>'Israel','ITA'=>'Italy','JAM'=>'Jamaica','JPN'=>'Japan','JEY'=>'Jersey','JOR'=>'Jordan','KAZ'=>'Kazakhstan','KEN'=>'Kenya','KIR'=>'Kiribati','KWT'=>'Kuwait','KGZ'=>'Kyrgyzstan','LAO'=>'Laos','LVA'=>'Latvia','LBN'=>'Lebanon','LSO'=>'Lesotho','LBR'=>'Liberia','LBY'=>'Libya','LIE'=>'Liechtenstein','LTU'=>'Lithuania','LUX'=>'Luxembourg','MAC'=>'Macao SAR of China','MKD'=>'Macedonia','MDG'=>'Madagascar','MWI'=>'Malawi','MYS'=>'Malaysia','MDV'=>'Maldives','MLI'=>'Mali','MLT'=>'Malta','MHL'=>'Marshall Islands','MTQ'=>'Martinique','MRT'=>'Mauritania','MUS'=>'Mauritius','MYT'=>'Mayotte','MEX'=>'Mexico','FSM'=>'Micronesia','MDA'=>'Moldova','MCO'=>'Monaco','MNG'=>'Mongolia','MNE'=>'Montenegro','MSR'=>'Montserrat','MAR'=>'Morocco','MOZ'=>'Mozambique','MMR'=>'Myanmar','NAM'=>'Namibia','NRU'=>'Nauru','NPL'=>'Nepal','NLD'=>'Netherlands','ANT'=>'Netherlands Antilles','NCL'=>'New Caledonia','NZL'=>'New Zealand','NIC'=>'Nicaragua','NER'=>'Niger','NGA'=>'Nigeria','NIU'=>'Niue','NFK'=>'Norfolk Island','PRK'=>'North Korea','MNP'=>'Northern Marianas','NOR'=>'Norway','OMN'=>'Oman','PAK'=>'Pakistan','PLW'=>'Palau','PSE'=>'Palestine','PAN'=>'Panama','PNG'=>'Papua New Guinea','PRY'=>'Paraguay','PER'=>'Peru','PHL'=>'Philippines','PCN'=>'Pitcairn Islands','POL'=>'Poland','PRT'=>'Portugal','PRI'=>'Puerto Rico','QAT'=>'Qatar','REU'=>'Reunion','ROU'=>'Romania','RUS'=>'Russia','RWA'=>'Rwanda','BLM'=>'Saint BarthÃ©lemy','SHN'=>'Saint Helena, Ascension and Tristan da Cunha','KNA'=>'Saint Kittss and Nevis','LCA'=>'Saint Lucia','MAF'=>'Saint Martin','SPM'=>'Saint Pierre and Miquelon','VCT'=>'Saint Vincent and the Grenadines','WSM'=>'Samoa','SMR'=>'San Marino','STP'=>'SÃ£o TomÃ© e PrÃ­ncipe','SAU'=>'Saudi Arabia','SEN'=>'Senegal','SRB'=>'Serbia','CSG'=>'Serbia and Montenegro','SYC'=>'Seychelles','SLE'=>'Sierra Leone','SGP'=>'Singapore','SXM'=>'Sint Maarten','SVK'=>'Slovakia','SVN'=>'Slovenia','SLB'=>'Solomon Islands','SOM'=>'Somalia','ZAF'=>'South Africa','SGS'=>'South Georgia and the South Sandwich Islands','KOR'=>'South Korea','SSD'=>'South Sudan','ESP'=>'Spain','LKA'=>'Sri Lanka','SDN'=>'Sudan','SUR'=>'Suriname','SJM'=>'Svalbard','SWZ'=>'Swaziland','SWE'=>'Sweden','CHE'=>'Switzerland','SYR'=>'Syria','TWN'=>'Taiwan','TJK'=>'Tajikistan','TZA'=>'Tanzania','THA'=>'Thailand','BHS'=>'The Bahamas','TLS'=>'Timor-Leste','TGO'=>'Togo','TKL'=>'Tokelau','TON'=>'Tonga','TTO'=>'Trinidad and Tobago','TUN'=>'Tunisia','TUR'=>'Turkey','TKM'=>'Turkmenistan','TCA'=>'Turks and Caicos Islands','TUV'=>'Tuvalu','UGA'=>'Uganda','UKR'=>'Ukraine','ARE'=>'United Arab Emirates','GBR'=>'United Kingdom','USA'=>'United States','UMI'=>'United States Minor Outlying Islands','URY'=>'Uruguay','VIR'=>'US Virgin Islands','UZB'=>'Uzbekistan','VUT'=>'Vanuatu','VAT'=>'Vatican City','VEN'=>'Venezuela','VNM'=>'Vietnam','WLF'=>'Wallis and Futuna','ESH'=>'Western Sahara','YEM'=>'Yemen','ZMB'=>'Zambia','ZWE'=>'Zimbabwe');
	$r_eingang_bew=$mitarbeit_betrieb=$pflege_anghoeriger=$fam_hintergrund=$migration=$bafoeg_bezug=$bew=$zweck_stip=$f_zweckbindung=$f_veroeff=$f_weitergabe=$f_traeger_hs=$ueberschr=$ausschluss=$verl_dauer=$preis=$beruf_ausbildung=$praktika=$ges_pol_eng=$hs_eng=$kind=$krankheit = array('0'=>'bitte ausw&auml;hlen', 'ja'=>'ja','nein'=>'nein');
	$b_krankheit=$b_mitarbeit_betrieb=$b_pflege_anghoeriger=$b_fam_hintergrund=$bonus_preis=$bonus_beruf_ausbildung=$bonus_praktika=$bonus_ges_pol_eng=$bonus_hs_eng=$b_kind=$b_krankheit = array('0.00'=>'0','0.20'=>'0.2','0.40'=>'0.4','0.60'=>'0.6');
	$anr = array('x'=>'bitte ausw&auml;hlen','Frau'=>'Frau','Herr'=>'Herr');
	$geschl = array('x'=>'x','m'=>'m&auml;nnlich','f'=>'weiblich');
	$s_geschl = $f_geschl = array('0'=>'0','1'=>'1','2'=>'2');
	$stg = array ('keinen'=>'bitte ausw&auml;hlen', 'Accounting, Auditing und Taxation'=>'Accounting, Auditing und Taxation', 'Agrarwirtschaft'=>'Agrarwirtschaft', 'Automobilwirtschaft'=>'Automobilwirtschaft', 'Automotive Management'=>'Automotive Management', 'Betriebswirtschaft'=>'Betriebswirtschaft', 'Energie- und Ressourcenmanagement'=>'Energie- und Ressourcenmanagement', 'Gesundheits- und Tourismusmanagement'=>'Gesundheits- und Tourismusmanagement', 'Immobilienmanagement'=>'Immobilienmanagement', 'Immobilienwirtschaft'=>'Immobilienwirtschaft', 'International Finance'=>'International Finance', 'International Master of Landscape Architecture'=>'International Master of Landscape Architecture', 'Internationales Finanzmanagement'=>'Internationales Finanzmanagement', 'International Management'=>'International Management', 'Kunsttherapie (B.A.)'=>'Kunsttherapie (B.A.)', 'Kunsttherapie (M.A.)'=>'Kunsttherapie (M.A.)', 'Landschaftsarchitektur'=>'Landschaftsarchitektur', 'Landschaftsplanung und Naturschutz'=>'Landschaftsplanung und Naturschutz', 'Nachhaltiges Produktmanagement'=>'Nachhaltiges Produktmanagement', 'Pferdewirtschaft'=>'Pferdewirtschaft', 'Prozessmanagement'=>'Prozessmanagement', 'Stadtplanung'=>'Stadtplanung', 'Theatertherapie (B.A.)'=>'Theatertherapie (B.A.)', 'Umweltschutz'=>'Umweltschutz', 'Unternehmensfuehrung'=>'Unternehmensfuehrung', 'Unternehmensrestrukturierung und Insolvenzmanagement'=>'Unternehmensrestrukturierung und Insolvenzmanagement', 'Volkswirtschaftslehre'=>'Volkswirtschaftslehre', 'Wirtschaftsrecht/Business Law'=>'Wirtschaftsrecht/Business Law','Nachhaltige Stadt- und Regionalentwicklung'=>'Nachhaltige Stadt- und Regionalentwicklung', 'Controlling'=>'Controlling');
	$stg_kuerzel = array('0'=>'bitte ausw&auml;hlen','AAT'=>'AAT','BW'=>'BW','IFB'=>'IFB','IFM'=>'IFM','AW'=>'AW','IM'=>'IM','PW'=>'PW','PZM'=>'PZM','VWL'=>'VWL','LA'=>'LA','LPN'=>'LPN','SP'=>'SP','UW'=>'UW','IMLA'=>'IMLA','ERM'=>'ERM','GTM'=>'GTM','IMMOB'=>'IMMOB','IMMOM'=>'IMMOM','URI'=>'URI','UF'=>'UF','WR'=>'WR','AUW'=>'AUW','AUM'=>'AUM','NPM'=>'NPM','NRM'=>'NRM','NSR'=>'NSR','CON'=>'CON');
	$stg_abschl = array('keinen'=>'bitte ausw&auml;hlen', 'B.Sc.'=>'B.Sc.', 'B.A.'=>'B.A.', 'M.Sc'=>'M.Sc', 'M.A.'=>'M.A.', 'MBA'=>'MBA', 'B.Eng.'=>'B.Eng.', 'M.Eng.'=>'M.Eng.', 'LL.B.'=>'LL.B.', 'LL.M'=>'LL.M');
	$fakultaet = array('0'=>'bitte ausw&auml;hlen','FWR'=>'FWR','FAVM'=>'FAVM','FLUS'=>'FLUS','FBF'=>'FBF');
	$rsz = array('0'=>'bitte ausw&auml;hlen','3'=>'3','4'=>'4','6'=>'6','7'=>'7','8'=>'8');
	$status = array(1=>'Bewerbung akzeptiert',0=>'Bewerbung abgelehnt',2=>'Ausschluss Quotenregelung');
	$a_stip_dauer = array('1 Semester'=>'1 Semester','2 Semester'=>'2 Semester');
	$moegl_bew_zeitraum = array(0=>'keiner', 1=>'1 Semester',2=>'2 Semester');
	$f_foerderer = array();
	$f_r_form = array('0'=>'bitte ausw&auml;hlen', '03'=>'Privatperson und Einzelunternehmen','06'=>'Personengesellschaft','09'=>'Kapitalgesellschaft','12'=>'Sonstige juristische Person des privaten Rechts','15'=>'Juristische Person des &ouml;ffentlichen Rechts');
	$f_r_form_k =  array('0'=>'00','03'=>'03','06'=>'06','09'=>'09', '12'=>'12','15'=>'15');
	$f_stg = array('keinen'=>'bitte ausw&auml;hlen','Accounting, Auditing und Taxation'=>'Accounting, Auditing und Taxation','Agrarwirtschaft'=>'Agrarwirtschaft','Automobilwirtschaft'=>'Automobilwirtschaft','Automotive Management'=>'Automotive Management','Betriebswirtschaft'=>'Betriebswirtschaft','Energie- und Ressourcenmanagement'=>'Energie- und Ressourcenmanagement','Gesundheits- und Tourismusmanagement'=>'Gesundheits- und Tourismusmanagement','Immobilienmanagement'=>'Immobilienmanagement','Immobilienwirtschaft'=>'Immobilienwirtschaft','International Finance'=>'International Finance','International Master of Landscape Architecture'=>'International Master of Landscape Architecture','Internationales Finanzmanagement'=>'Internationales Finanzmanagement','International Management'=>'International Management','Kunsttherapie (B.A.)'=>'Kunsttherapie (B.A.)','Kunsttherapie (M.A.)'=>'Kunsttherapie (M.A.)','Landschaftsarchitektur'=>'Landschaftsarchitektur','Landschaftsplanung & Naturschutz'=>'Landschaftsplanung & Naturschutz','Nachhaltiges Produktmanagement'=>'Nachhaltiges Produktmanagement','Pferdewirtschaft'=>'Pferdewirtschaft','Prozessmanagement'=>'Prozessmanagement','Stadtplanung'=>'Stadtplanung','Theatertherapie (B.A.)' => 'Theatertherapie (B.A.)','Umweltschutz'=>'Umweltschutz','Unternehmensf&uuml;hrung'=>'Unternehmensf&uuml;hrung','Unternehmensrestrukturierung und Insolvenzmanagement'=>'Unternehmensrestrukturierung und Insolvenzmanagement','Volkswirtschaftslehre'=>'Volkswirtschaftslehre','Wirtschaftsrecht/Business Law'=>'Wirtschaftsrecht/Business Law');
	$s_fach =  array('0'=>'bitte ausw&auml;hlen','003'=>'003', '013'=>'013', '021'=>'021', '134'=>'134', '175'=>'175', '182'=>'182', '458'=>'458', '042'=>'042');
	$gruppe =  array('0'=>'bitte ausw&auml;hlen','1'=>'1','2'=>'2','3'=>'3','4'=>'4');
	$ws_bafoeg = array(0=>'Wenn ja, welches Semester',1=>'im Wintersemster');
	$ss_bafoeg = array(0=>'nein',1=>'ja');
	$s_land = array(0 =>'bitte ausw&auml;hlen','8'=>'8');
	$s_hs_art = array(0 =>'bitte ausw&auml;hlen','6'=>'6');
	$s_hs = array('0'=>'bitte ausw&auml;hlen','6791'=>'6791', '6792'=>'6792');
	$s_traeger_hs = array(0 =>'bitte ausw&auml;hlen',2=>'2');	
	$s_abschl = array('0'=>'bitte ausw&auml;hlen','184'=>'184','284'=>'284','390'=>'390');
	$s_bafoeg = array('0'=>'bitte ausw&auml;hlen','1'=>'1','2'=>'2');
	$foed_id = getSponsors($sponsorsTable);
	$f_land =array('8'=>'8');
	$f_hs_art = array('6'=>'6');
	$f_hs=array('0'=>'bitte ausw&auml;hlen','7691'=>'7691','7692'=>'7692');
	$f_traeger_hs = array(0=>'bitte ausw&auml;hlen',2=>'2');	
	$dneg = (float)(-0.6);

	$getValues = new dbOperations($dbConfig,$array_fields,$path,$table,$suffix,$salt);
	$val = $getValues->getValuesFromDB();
	$variables = $val['values'];
	$uid = $variables[$t]['uid'];
	$list_select = $list_date = $list_upload = array();
	date_default_timezone_set('CET');
	if ($val['max'] !== 0 AND $uid >= 0) {
	// produce an array with names of date fields and one with names of select fields
		while ($level0 = each($array_fields)) {
			$key0 = $level0['key'];
				while ($level1 = each($array_fields[$key0])) {
					$key1 = $level1['key'];
					$flag_l2 = 0;
						while ($level2 = each($array_fields[$key0][$key1])) {
							$key2 = $level2['key']; // type of field: input, select, textarea
								if ($key2 === 'select') {
									$flag_l2 = 1;									
								}
							$flag_l3 = $flag_up = 0; // serves at level 3
								while ($level3 = each($array_fields[$key0][$key1][$key2])) {
									$key3 = $level3['key'];  // type, name, class, options, @#db, (value) ...
									$value3 = $level3['value']; // text, number, date ...

									if ($value3 === 'date') {
										$flag_l3 = 1;										
									}
									if ($flag_l3 == 1 AND $key3 == 'name') {
										$list_date[$value3] = $value3;
									}
									if ($flag_l2 == 1 AND $key3 == 'name') {
										$list_select[$value3] = $value3;
									}
									if ($value3 === 'file') {
										$flag_up = 1;
									}
									if ($flag_up == 1 AND $key3 == 'name') {
										$list_upload[] = $value3 ;
									}
								}							 														
						}
				}
		}
//echo "<pre>";
//print_r($variables[$t]);
//echo "</pre>";
		foreach ($variables[$t] as $key => $value) {
			if (array_key_exists($key, $list_date)) {
				$value = strftime($cusformat, $value);
				$$key = $value;	
				//echo "<br>$$key => value = ".$value ."<br>";			
			} elseif (array_key_exists($key, $list_select)) {  //echo "<p>$list_select[$key]</p>";
			// this can work only if the variable name of 'options' arrays coincides with the name: 'name' -> $name
				$varname = $$key;
//echo "<br>value = ".$value ."<br>";
				// in case the field is empty or NULL
				if ($value === NULL OR !isset($value) OR !$value OR empty($value)) $value = 0;
				if (array_key_exists($value, $varname)) {
						$tmp = $varname[$value] . "=selected";
						$varname[$value] = $tmp;
						//echo "<p>exists: $varname[$value] ==> $key ==> $value</p>";					
				} else {
					//echo "<p>$varname[$value] ==> $key ==> $value</p>";
					$varname[$value] = "no array, key doesn't exist";
				}
				$$key = $varname;							
			} else {
				$$key = $value;					
				//echo "<p>key = $key ==> value = $value</p>";
			}
		}
//echo "<hr>";
			
// filled variables
$fieldset_v  = array('Record Info' => array('hidden' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'hidden',
														'min'=> 0,
														'max'=> 1,
														'step'=> 1,
														'value'=> $hidden,
														'class'=>'in_w1',
														'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0',								
												)
											),
											'deleted' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'deleted',
														'readonly'=>'readonly',
														'min'=> 0,
														'max'=> 1,
														'step'=> 1,
														'value'=> $deleted,
														'class'=>'in_w1',
														'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0',								
												)
											),
											'edited' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'edited',
														'min'=> 0,
														'max'=> 1,
														'step'=> 1,
														'value'=> $edited,
														'class'=>'in_w1',
														'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0',								
												)
											),
										),
'Pers&ouml;nliche Angaben' => array(
		'Matrikel/Bewerbernummer' => 
				array(
						'input' => 
						array(	'type' => 'text',
								'name' => 'mat_bew_nr',
								'value'=> $mat_bew_nr,
								'class'=>'in_w1',
								'@#db' => 'VARCHAR( 120 ) NOT NULL',
						),
				),
		'Anrede' =>
				array(	'select' =>
						array(	'name'=>'anr',	
								'options'=> $anr, //$a_title,
								'class'=>'sel_w1',							
								'@#db' => 'VARCHAR( 10 ) NOT NULL',
					
						),
				),
		'Vorname' =>
				array(	'input' =>
						array(	'type'=>'text',
								'name'=>'vorname',
								'value'=> $vorname,
								'class'=>'in_w1',
								'@#db' => 'VARCHAR( 80 ) NOT NULL',
						),
				),
        'Name' =>
				array('input' =>
						array(	'type'=>'text',
								'name'=>'name',
								'value'=> $name,
								'class'=>'in_w1',
								'@#db' => 'VARCHAR( 80 ) NOT NULL',
						),
				),
        'Geburtsdatum' =>
				array('input' =>
						array(  'type'=>'date',
								'name'=>'geb',
								'value'=> $geb,
								'min'=> $d16,
								'max' => $d40,
								'class'=>'in_w1',
								'@#db' => 'INT( 11 ) NOT NULL',
						),
				),
        'Geschlecht' =>
				array('select' =>
						array(	'name'=>'geschl',
								'options'=> $geschl, //$a_sex,
								'class'=>'sel_w1',
								'@#db' => 'VARCHAR( 10 ) NOT NULL',																								
						),
				),
        'Staatsangeh&ouml;rigkeit' =>
				array('select' =>
						array(	'name'=>'staat',
								'options'=> $staat, //$var_f_land,
								'class'=>'sel_w1',							
								'@#db' => 'VARCHAR( 4 ) NOT NULL',
						),
				),			
		),
// 2nd fieldset
	'Kontaktdaten' => 
		array( 'Stra&szlig;e, Nummer' => 
				array('input' =>
						array( 	'type'=>'text',
								'name'=>'str_nr',
								'value'=> $str_nr,
								'class'=>'in_w1',
								'@#db' => 'VARCHAR( 100 ) NOT NULL',
						),
				),
		'ggf. Zimmer, Etage' =>  array(
     									'input' => 
											array(  'type' => 'text',
          											'name' => 'str_zusatz',
          											'value'=> $str_zusatz,
													'class'=>'in_w1',
          											'@#db' => 'VARCHAR( 40 ) NOT NULL',
     		 								),     
    							),	
    	'PLZ Ort' =>  array(
     					'input' => 
    							array( 'type' => 'text',
          								'name' => 'plz_ort',
          								'value'=> $plz_ort,
    									'class'=>'in_w1',
          								'@#db' => 'VARCHAR( 80 ) NOT NULL',
      							)
   					 ),
		'E-Mail' =>
					array('input' =>
									array(	'type' => 'text',
											'name'=>'email',
											'value' => $email,
											'class'=>'in_w1',
											'@#db' => 'VARCHAR( 80 ) NOT NULL',
						),
				),
		'Telephon' =>
					array('input' =>
									array(	'type' => 'text',
											'name'=>'tel',
											'value' => $tel,
											'class'=>'in_w1',
											'@#db' => 'VARCHAR( 40 ) NOT NULL',
						),
				),
    ),
// 3rd fieldset
		'Angaben zu weiteren F&ouml;rderungen / Ausschlussgr&uuml;nde' => 
    			array(   		    			
    			'rechtzeitiger Eingang' =>
						array('select' =>
							array(	'name'=>'r_eingang_bew',
									'options'=> $r_eingang_bew, //$a_yesno,
									'class'=>'sel_w1',
									'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
							)
						),
						'Bezug weiterer F&ouml;rderungen' =>
						array('input' =>
							array(	'type'=>'text',								
									'name'=>'stipendium',
									'value'=> $stipendium,
									'class'=>'in_w1',
									'@#db' => 'VARCHAR( 120 ) NOT NULL',								
							)
						),
						'Falls ja, Name' =>
							array('input' =>
								array(	'type'=>'text',
										'name'=>'stipendium_name',								
										'value' => $stipendium_name,
										'class'=>'in_w1',
										'@#db' => 'VARCHAR( 120 ) NOT NULL',
								)
						),
						'und H&ouml;he/Monat in [&euro;]' =>
							array('input' =>
								array(	'type'=>'text',
										'name'=>'stipendium_hoehe',
										'value'=> $stipendium_hoehe,
										'class'=>'in_w1',
										'@#db' => 'VARCHAR( 120 ) NOT NULL',											
								)
						),
						'&Uuml;berschreitung der F&ouml;rderungsh&ouml;chstdauer (keine Verl&auml;ngerung)' =>
						array('select' =>
							array(	'name'=>'ueberschr',
									'options'=> $ueberschr, //$a_yesno,
									'class'=>'sel_w1',
									'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
							)
						),
						'Sonstiger Grund Ausschluss aus Verfahren' =>
						array('select' =>
							array(	'name'=>'ausschluss',
									'options'=> $ausschluss, //$a_yesno,
									'class'=>'sel_w1',
									'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
							)
						),
						'Status' =>
						array('select' =>
									array(	'name'=>'status',
											'options' => $status, //$a_status,											
											'class'=>'in_w1 status',
											'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0',
									),
						),
				),
// 4th fieldset a
	'Notendurchschnitt: Studienanf&auml;nger Bachelor' => array(
															'Durchschnittsnote Hochschulzugangsberechtigung' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'hzb',
																			'value'=> $hzb,
																			'min'=> 0,
																			'max'=> 15,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),
															'Durchschnittsnote Deutsch' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'deutsch',
																			'value'=> $deutsch,
																			'min'=> 0,
																			'max'=> 15,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),
															'Durchschnittsnote Mathe' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'mathe',
																			'value'=> $mathe,
																			'min'=> 0,
																			'max'=> 15,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),
															'Durchschnittsnote beste Fremdsprache' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'fremdsprache',
																			'value'=> $fremdsprache,
																			'min'=> 0,
																			'max'=> 15,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),
															'Durchschnittsnote Kernkompetenzf&auml;cher' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'durchschn_kern',
																			'value'=> $durchschn_kern,
																			'min'=> 0,
																			'max'=> 15,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),
															'Ausgangsnote Studienanf&auml;nger Bachelorstudiengang' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ausgangsnote',
																			'value'=> $ausgangsnote,
																			'min'=> 0,
																			'max'=> 15,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),
														),
// 4th fieldset b
	'Notendurchschnitt: Bachelor ab 2.Semester ' => array(
															'ECTS' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ects_b',
																			'value'=> $ects_b,
																			'min'=> 0,
																			'max'=> 280,
																			'step'=> 1,
																			'class'=>'in_w1',
																			'@#db' => 'INT( 3 ) NOT NULL',
																	),
																),
															'Soll-ECTS' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'soll_ects_b',
																			'value'=> $soll_ects_b,
																			'min'=> 0,
																			'max'=> 280,
																			'step'=> 1,
																			'class'=>'in_w1',
																			'@#db' => 'INT( 3 ) NOT NULL',
																	),
																),
															'ECTS SOLL-IST Abweichung' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ects_abw_b',
																			'value'=> $ects_abw_b,
																			'min'=> -280,
																			'max'=> 280,
																			'step'=> 1,
																			'class'=>'in_w1',
																			'@#db' => 'INT( 3 ) NOT NULL',
																	),
																),
															'Bemerkung ECTS-Abweichung' =>
																array(	'input' =>
																	array(	'type'=>'text',
																			'name'=>'ects_abw_bemer_b',
																			'value'=> $ects_abw_bemer_b,
																			'class'=>'in_w1',																		
																			'@#db' => 'TEXT  NOT NULL',
																	),
																),
															'Durchschnitt Studienleistungen ' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'studleistungen_b',
																			'value'=> $studleistungen_b,
																			'min'=> 0,
																			'max'=> 15,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),															
															'Ausgangsnote Bachelorstudierende ab dem 2.Semester' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ausgangsnote_b',
																			'value'=> $ausgangsnote_b,
																			'min'=> 0,
																			'max'=> 15,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),			
							),
// 4th fieldset c
	'Notendurchschnitt: Studienanf&auml;nger Masterstudiengang' => array(
															'Abschlussnote des vorausgegangenen Studiums' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'note_bachelor_c',
																			'value'=> $note_bachelor_c,
																			'min'=> 0,
																			'max'=> 15,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),
															'Ausgangsnote Studienanf&auml;nger Masterstudiengang' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ausgangsnote_c',
																			'value'=> $ausgangsnote_c,
																			'min'=> 0,
																			'max'=> 15,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),
							),
// 4th fieldset d
	'Notendurchschnitt: Masterstudiengang ab 2.Semester ' => array(
															'ECTS' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ects_d',
																			'value'=> $ects_d,
																			'min'=> 0,
																			'max'=> 280,
																			'step'=> 1,
																			'class'=>'in_w1',
																			'@#db' => 'INT( 3 ) NOT NULL',
																	),
																),
															'Soll-ECTS' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'soll_ects_d',
																			'value'=> $soll_ects_d,
																			'min'=> 0,
																			'max'=> 280,
																			'step'=> 1,
																			'class'=>'in_w1',
																			'@#db' => 'INT( 3 ) NOT NULL',
																	),
																),
															'ECTS SOLL-IST Abweichung' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ects_abw_d',
																			'value'=> $ects_abw_d,
																			'min'=> -280,
																			'max'=> 280,
																			'step'=> 1,
																			'class'=>'in_w1',
																			'@#db' => 'INT( 3 ) NOT NULL',
																	),
																),
															'Bemerkung ECTS-Abweichung' =>
																array(	'input' =>
																	array(	'type'=>'text',
																			'name'=>'ects_abw_bemer_d',
																			'value'=> $ects_abw_bemer_d,
																			'class'=>'in_w1',																			
																			'@#db' => 'TEXT NOT NULL',
																	),
																),
															'Durchschnitt Studienleistungen ' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'studleistungen_d',
																			'value'=> $studleistungen_d,
																			'min'=> 0,
																			'max'=> 15,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),
															'Abschlussnote des vorausgegangenen Studiums' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'note_bachelor_d',
																			'value'=> $note_bachelor_d,
																			'min'=> 0,
																			'max'=> 15,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),
															'Ausgangsnote Masterstudierende ab dem 2.Semester' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ausgangsnote_d',
																			'value'=> $ausgangsnote_d,
																			'min'=> 0,
																			'max'=> 15,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),
							),																					
// 5th fieldset			
	'Angaben zum Studium' => array( 'Studiengang' =>  
									array(	'select' =>
										array(	'name'=>'stg',
												'options'=> $stg,
												'class'=>'sel_w1',
												'@#db' => 'VARCHAR( 60 ) NOT NULL',								
										),
									),									
									'Externes Studiengangk&uuml;rzel' =>  
									array(	'select' =>
										array(	'name'=>'stg_kuerzel',
												'options'=> $stg_kuerzel,
												'class'=>'sel_w1',
												'@#db' => 'VARCHAR( 60 ) NOT NULL DEFAULT 0',								
										),
									),
									'Angestrebter Abschluss' =>  
									array(	'select' =>
										array(	'name'=>'stg_abschl',
												'options'=> $stg_abschl,
												'class'=>'sel_w1',
												'@#db' => 'VARCHAR( 60 ) NOT NULL',								
										),
									),
									'Fakult&auml;t' =>  
									array(	'select' =>
										array(	'name'=>'fakultaet',
												'options'=> $fakultaet,
												'class'=>'sel_w1',
												'@#db' => 'VARCHAR( 60 ) NOT NULL',								
										),
									),
									'Semester zu Beginn des Vergabezeitraumes' =>
									array('input' =>
										array(	'type'=>'number',								
												'name'=>'sem',
												'value'=> $sem,
												'min'=> 0,
												'max'=> 20,
												'step'=> 1,
												'class'=>'in_w1',
												'@#db' => 'TINYINT( 2 ) NOT NULL',								
										)
									),
									'Hochschulsemester' =>
									array('input' =>
										array('type'=>'number',
											      'name'=>'hs',
											      'value'=>$hs,
											      'min'=> 0,
											      'max'=> 30,
											      'step'=> 1,
											      'class'=>'in_w1',
											      '@#db' => 'TINYINT( 2 ) NULL'
											      )
									),
									'Regelstudienzeit' =>  
									array(	'select' =>
										array(	'name'=>'rsz',
												'options'=> $rsz,
												'class'=>'sel_w1',
												'@#db' => 'TINYINT( 2 ) NOT NULL',								
										),
									),
									'Verl&auml;ngerung der F&ouml;rderungsh&ouml;chstdauer aus schwerwiegenden Gr&uuml;nden?' =>  
									array(	'select' =>
										array(	'name'=>'verl_dauer',
												'options'=> $verl_dauer, //$a_yesno,
												'class'=>'sel_w1',
												'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
										),
									),
									'Grund' =>
									array('textarea' =>
										array(	'name'=>'verl_dauer_grund',
												'default'=>$verl_dauer_grund,
												'@#db' => 'TEXT',
												'wrap'=>'soft',
												'cols' => '50',
												'rows' => '6',
												'id' => 'verl_dauer_grund',
												'class'=>'in_w1',								
										)
									),										
							),
// 6th fieldset 
	'Praktika, Berufserfahung, Engagement und Preise' => array(
															'Preise' =>  
																array(	'select' =>
																	array(	'name'=>'preis',
																			'options'=> $preis, //$a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																	),
																),
															'Bonus Preise' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_preis',
																			'options'=> $bonus_preis, //$a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
															'Berufst&auml;tigkeit/Berufsausbildung' =>  
																array(	'select' =>
																	array(	'name'=>'beruf_ausbildung',
																			'options'=> $beruf_ausbildung, //$a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																	),
																),
															'Bonus Berufst&auml;tigkeit/Berufsausbildung' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_beruf_ausbildung',
																			'options'=> $bonus_beruf_ausbildung, //$a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
															'Praktika' =>  
																array(	'select' =>
																	array(	'name'=>'praktika',
																			'options'=> $praktika, //$a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																	),
																),
															'Bonus Praktika' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_praktika',
																			'options'=> $bonus_praktika, //$a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
															'Gesellschaftliches/politisches Engagement' =>  
																array(	'select' =>
																	array(	'name'=>'ges_pol_eng',
																			'options'=> $ges_pol_eng, //$a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																	),
																),
															'Bonus Gesellschaftliches/politisches Engagement' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_ges_pol_eng',
																			'options'=> $bonus_ges_pol_eng, //$a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
															'Hochschulengagement' =>  
																array(	'select' =>
																	array(	'name'=>'hs_eng',
																			'options'=> $hs_eng, //$a_yesno,
																			'class'=>'sel_w1  m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																	),
																),													
															'Bonus Hochschulengagement' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_hs_eng',
																			'options'=> $bonus_hs_eng, //$a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
														),
// 7th fieldset
	'Besondere pers&ouml;nliche oder famili&auml;re Umst&auml;nde' => array('Betreuung eigener Kinder' =>  
																		array(	'select' =>
																				array(	'name'=>'kind',
																						'options'=> $kind, //$a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																				),
																			),
																		'Bonus Betreuung eigener Kinder' =>  
																			array(	'select' =>
																				array(	'name'=>'b_kind',
																						'options'=> $b_kind, //$a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Krankheiten/Behinderungen' =>  
																			array(	'select' =>
																				array(	'name'=>'krankheit',
																						'options'=> $krankheit, //$a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																				),
																			),
																		'Bonus Krankheiten/Behinderungen' =>  
																			array(	'select' =>
																				array(	'name'=>'b_krankheit',
																						'options'=> $b_krankheit, //$a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Mitarbeit im famili&auml;ren Betrieb' =>  
																			array(	'select' =>
																				array(	'name'=>'mitarbeit_betrieb',
																						'options'=> $mitarbeit_betrieb, //$a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																				),
																			),
																		'Bonus Mitarbeit im famili&auml;ren Betrieb' =>  
																			array(	'select' =>
																				array(	'name'=>'b_mitarbeit_betrieb',
																						'options'=> $b_mitarbeit_betrieb, //$a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Betreuung pflegebed&uuml;rftiger naher Angeh&ouml;riger' =>  
																			array(	'select' =>
																				array(	'name'=>'pflege_anghoeriger',
																						'options'=> $pflege_anghoeriger, //$a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																				),
																			),
																		'Bonus Betreuung pflegebed&uuml;rftiger naher Angeh&ouml;riger' =>  
																			array(	'select' =>
																				array(	'name'=>'b_pflege_anghoeriger',
																						'options'=> $b_pflege_anghoeriger, //$a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Famili&auml;rer Hintergrund' =>  
																			array(	'select' =>
																				array(	'name'=>'fam_hintergrund',
																						'options'=> $fam_hintergrund, //$a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																				),
																			),					
																		'Bonus famili&auml;rer Hintergrund' =>  
																			array(	'select' =>
																				array(	'name'=>'b_fam_hintergrund',
																						'options'=> $b_fam_hintergrund, //$a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Migrationshintergrund' =>  
																			array(	'select' =>
																				array(	'name'=>'migration',
																						'options'=> $migration, //$a_yesno,
																						'class'=>'sel_w1',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																				),
																			),
							),
// 8th fieldset
	'Angaben zu den BAf&ouml;G Leistungen' => array(
													'Bezug von BAf&ouml;G' =>  
															array(	'select' =>
																array(	'name'=>'bafoeg_bezug',
																		'options'=> $bafoeg_bezug, //$a_yesno,
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																),
															),
													'Ich beziehe im F&ouml;rderzeitraum Baf&ouml;G' =>
															array('select' =>
																array(	'name'=>'ws_bafoeg',
																		'options' => $ws_bafoeg, //array(0=>'Wenn ja, welches Semester',1=>'im Wintersemster'),																	
																		'class'=>'sel_w1',
																		'@#db' => 'TINYINT( 1 ) NOT NULL'							
																)
															),
													'und auch im Sommersemester' =>
															array('select' =>
																array(	'name'=>'ss_bafoeg',
																		'options' => $ss_bafoeg, //array(0=>'nein',1=>'ja'),																		
																		'class'=>'sel_w1',
																		'@#db' => 'TINYINT( 1 ) NOT NULL'							
																)
															),
													),													
// 9th fieldset
	'Entscheidung &uuml;ber Stipendium' => array( 'Gruppe' =>
															array('select' =>
																array(	'name'=>'gruppe',
																		'options' => $gruppe,										
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL'							
																)
															),
													'Bonus gesamt' =>
													array('input' =>
														array(	'type'=>'number',								
																'name'=>'bonus_gesamt',
																'value'=> $bonus_gesamt,
																'min'=> 0,
																'max'=> 6,
																'step'=> 0.1,
																'class'=>'in_w1',
																'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
														),
													),
													'Bonus Verfahren' =>
													array('input' =>
														array(	'type'=>'number',								
																'name'=>'bonus_verfahren',
																'value'=> $bonus_verfahren,
																'min'=> 0,
																'max'=> 0.8,
																'step'=> 0.1,
																'class'=>'in_w1',
																'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
														),
													),
													'Note Rangliste' =>
													array('input' =>
														array(	'type'=>'number',								
																'name'=>'note_rangliste',
																'value'=> $note_rangliste,
																'min'=> $dneg,
																'max'=> 15,
																'step'=> 0.01,
																'class'=>'in_w1',
																'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
														),
													),
													'm&ouml;glicher Bewilligungszeitraum' =>  
															array(	'select' =>
																array(	'name'=>'moegl_bew_zeitraum',
																		'class'=>'sel_w1',
																		'options'=> $moegl_bew_zeitraum, 
																		'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0',								
																),
															),
													'Bewilligung?' =>  
															array(	'select' =>
																array(	'name'=>'bew',
																		'options'=> $bew, //$a_yesno,
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																),
															),
													'Beginn der Bewilligung' =>
													array('input' =>
														array(	'type'=>'date',								
																'name'=>'bew_beginn',
																'value'=> $bew_beginn,
																'min' => $dx,
																'max' => $d1,
																'class'=>'in_w1',
																'@#db' => 'INT(11) NOT NULL',								
														),
													),
												   'Ende der Bewilligung' =>
													array('input' =>
														array(	'type'=>'date',								
																'name'=>'bew_ende',
																'value'=> $bew_ende,
																'min' => $dx,
																'max' => $d1,
																'class'=>'in_w1',
																'@#db' => 'INT(11) NOT NULL',								
														)
													),
													'Stipendiendauer' =>
													array('input' =>
														array(	'type'=>'datalist',
																'match'	=> 'stip_dauer',
																'optionslist' => $a_stip_dauer,
																'value'=> $stip_dauer,						
																'name'=>'stip_dauer',
																'class'=>'in_w1',
																'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0'							
														),
													),
													'zweckgebundens Stipendium?' =>  
															array(	'select' =>
																array(	'name'=>'zweck_stip',
																		'options'=> $zweck_stip, //$a_yesno,
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																),
															),
													'Beurlaubung?' =>
													array('textarea' =>
														array(	'name'=>'us',
																'default'=>$us,
																'@#db' => 'TEXT',
																'wrap'=>'soft',
																'cols' => '50',
																'rows' => '6',
																'id' => 'us',
																'class'=>'in_w1',								
														)
													),
											),
// 10th fieldset
	'StaLA (Stipendiat)' => array(		'Berichtsland' =>
										array(	'select' =>
											array(	'name'=>'s_land',
													'options'=> $s_land, //array('8'=>'8'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Berichtsjahr' =>
										array(	'select' =>
											array(	'name'=>'s_jahr',
													'options'=> $s_jahr, //$a_selectYs,
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Hochschulart' =>
										array(	'select' =>
											array(	'name'=>'s_hs_art',
													'options'=> $s_hs_art, //array('6'=>'6'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Hochschule' =>
										array(	'select' =>
											array(	'name'=>'s_hs',
													'options'=> $s_hs, // array('0'=>'bitte ausw&auml;hlen','6791'=>'6791', '6792'=>'6792'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Tr&auml;gerschaft der Hochschule' =>
										array(	'select' =>
											array(	'name'=>'s_traeger_hs',
													'options'=> $s_traeger_hs, //array('2'=>'2'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'ID-Nummer' =>
											array('input' =>
												array(	'type'=>'text',								
														'name'=>'s_id_nr',
														'value'=> $s_id_nr,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 12 ) NOT NULL',								
												)
											),
										'Geschlecht' =>
											array('select' =>
												array(	'name'=>'s_geschl',
														'options'=> $s_geschl, //$a_sex2,
														'class'=>'sel_w1',
														'@#db' => 'VARCHAR( 2 ) NOT NULL',																								
												),
											),
       									 'Staatsangeh&ouml;rigkeit' =>
											array('input' =>
												array(	'type'=>'text',
														'name'=>'s_staat',
														'value'=> $s_staat,
														'class'=>'in_w1',						
														'@#db' => 'VARCHAR( 2 ) NOT NULL',
												),
											),
										'Anzahl HS-Semester an deutschen Hochschulen (im Berichtsjahr)' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'s_hs_sem',
														'value'=> $s_hs_sem,
														'min'=> 0,
														'max'=> 20,
														'step'=> 1,
														'class'=>'in_w1',
														'@#db' => 'TINYINT( 2 ) NOT NULL',								
												)
											),	
										'Anzahl Fachsemester an deutschen Hochschulen (im Berichtsjahr)' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'s_fach_sem',
														'value'=> $s_fach_sem,
														'min'=> 0,
														'max'=> 20,
														'step'=> 1,
														'class'=>'in_w1',
														'@#db' => 'TINYINT( 4 ) NOT NULL',								
												)
											),
										'Angestrebte Abschlusspr&uuml;fung' =>
											array(	'select' =>
												array(	'name'=>'s_abschl',
														'options'=> $s_abschl,														
														'class'=>'sel_w1',
														'@#db' => 'VARCHAR( 4 ) NOT NULL',								
												)
											),
										'Studienfach' =>
											array('select' =>
												array(	'name'=>'s_fach',
														'options'=> $s_fach,
														'class'=>'sel_w1',
														'@#db' => 'VARCHAR( 4 ) NOT NULL',								
												)
											),
										'Zahl der F&ouml;rdermonate im Berichtsjahr' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'s_foerd_monate',
														'value'=> $s_foerd_monate,
														'min'=> 0,
														'max'=> 48,
														'step'=> 1,
														'class'=>'in_w1',
														'@#db' => 'TINYINT( 2 ) NOT NULL',								
												)
											),
										'Baf&ouml;G Bezug?' =>  
															array(	'select' =>
																array(	'name'=>'s_bafoeg',
																		'options'=>$s_bafoeg, // array('0'=>'bitte ausw&auml;hlen','1'=>'1','2'=>'2'),
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																),
															),																	
							),
// 11th fieldset
	'Daten F&ouml;rderer' => array(	'F&ouml;rderer Id' =>
															array('select' =>
																array(	'options' => $foed_id,						
																		'name'=>'foed_id',
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 20 ) NOT NULL DEFAULT 0'							
																)
															),
							),
// 12th fieldset						
			'Bemerkungen / Dateien' => array( 'Bemerkungen' =>
											array('textarea' =>
												array(	'name'=>'comments',
														'default'=>$comments,
														'@#db' => 'TEXT',
														'wrap'=>'soft',
														'cols' => '50',
														'rows' => '6',
														'id' => 'commentfield',
														//'virtual' => '',
														//'physical' => '',
														//'required' => '1'
														'class'=>'in_w1',
												),
											),
										'Datei hochladen' =>
											array('input' =>
												array(	'type'=>'file',
														'name'=>'datei_url',
														'accept'=>$accept,
														'maxfilesize'=> $maxfilesize,
														'allowed_filetypes'=> $allowfiles,
														'allowed_extensions'=> $ext,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 255 ) NOT NULL',								
												),
											),
									'saved' =>
											array('input' =>
												array(	'type'=>'hidden',								
														'name'=>'saved',
														'value'=>0,													
														'@#db' => 'INT( 11 ) NOT NULL DEFAULT 0',							
												)
											),
										
									),
); // end array filled

$fieldset_spons_v = array('Record Info' => array('hidden' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'hidden',
														'min'=> 0,
														'max'=> 1,
														'step'=> 1,
														'value'=> $hidden,
														'class'=>'in_w1',
														'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0',								
												)
											),
											'deleted' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'deleted',
														'readonly'=>'readonly',
														'min'=> 0,
														'max'=> 1,
														'step'=> 1,
														'value'=> $deleted,
														'class'=>'in_w1',
														'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0',								
												)
											),
											'edited' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'edited',
														'min'=> 0,
														'max'=> 1,
														'step'=> 1,
														'value'=> $edited,
														'class'=>'in_w1',
														'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0',								
												)
											),
										),
// 1-11th fieldset 
	'F&ouml;rderer: Stammdaten' => array('F&ouml;rderer/Firma ' =>  
															array(	'input' =>
																array(	'type'=>'text',
																		'name'=>'f_foerderer',
																		'value'=> $f_foerderer,
																		'class'=>'in_w1',																		
																		'@#db' => 'VARCHAR( 255 ) NOT NULL',								
																),
															),
											'Rechtsform ' =>
															array('select' =>
																array(	'name'=>'f_r_form',
																		'options' => $f_r_form,																		
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 20 )  NOT NULL'							
																)
															),
							),
// 2-11th fieldset
	'F&ouml;rderer: Kontaktdaten' => array( 'Str./Nr.' =>
											array('input' =>
												array(	'type'=>'text',								
														'name'=>'f_str_nr',
														'value'=> $f_str_nr,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 160 ) NOT NULL',								
												)
											),
											'PLZ, Ort.' =>
											array('input' =>
												array(	'type'=>'text',								
														'name'=>'f_plz_ort',
														'value'=> $f_plz_ort,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 160 ) NOT NULL',								
												)
											),
											'Geschlecht' =>  
												array(	'select' =>
													array(	'name'=>'f_geschl',
															'options'=> $f_geschl, //$f_sex,
															'class'=>'sel_w1',
															'@#db' => 'VARCHAR( 4 ) NOT NULL',								
													),
												),
											'Name, Vorname (Ansprechpartner)' =>
											array('input' =>
												array(	'type'=>'text',								
														'name'=>'f_name',
														'value'=> $f_name,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 255 ) NOT NULL',								
												)
											),
											'Funktion (Ansprechpartner)' =>
											array('input' =>
												array(	'type'=>'text',								
														'name'=>'f_funktion',
														'value'=> $f_funktion,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 255 ) NOT NULL',								
												)
											),
											'Telefon (Ansprechpartner)' =>
											array('input' =>
												array(	'type'=>'tel',								
														'name'=>'f_tel',
														'value'=> $f_tel,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 80 ) NOT NULL',								
												)
											),
											'E-Mail (Ansprechpartner)' =>
											array('input' =>
												array(	'type'=>'email',								
														'name'=>'f_email',
														'value'=> $f_email,
														'class'=>'in_w1',
														'id'=>'email',
														'@#db' => 'VARCHAR( 120 ) NOT NULL',								
												)
											),
							),
// 3-11th fieldset
	'F&ouml;rderer: Angaben zur F&ouml;rderung' => array(
									'Zweckbindung' =>  
												array(	'select' =>
													array(	'name'=>'f_zweckbindung',
															'options'=> $f_zweckbindung, //$a_yesno,
															'class'=>'sel_w1',
															'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
													),
												),
									'Studiengang' =>  
												array(	'select' =>
													array(	'name'=>'f_stg',
															'options'=> $f_stg,
															'class'=>'sel_w1',
															'@#db' => 'VARCHAR( 120 ) NOT NULL',								
													),
												),
									'Summe Mittel' =>
											array('input' =>
												array(	'type'=>'text',								
														'name'=>'f_summe',
														'value'=> $f_summe,
														'class'=>'in_w1',
														'@#db' => 'DECIMAL( 6,2 ) NOT NULL DEFAULT 0',								
												)
											),
									'F&ouml;rderung beginnt am' =>
											array('input' =>
												array(	'type'=>'date',								
														'name'=>'f_foerd_beginn',
														'value'=> $f_foerd_beginn,
														'min' => $dx,
														'max' => $d1,
														'class'=>'in_w1',
														'@#db' => 'INT( 11) NOT NULL',								
												)
											),
									'F&ouml;rderung endet am' =>
											array('input' =>
												array(	'type'=>'date',								
														'name'=>'f_foerd_ende',
														'value'=> $f_foerd_ende,
														'min' => $dx,
														'max' => $d1,
														'class'=>'in_w1',
														'@#db' => 'INT( 11 ) NOT NULL',								
												)
											),
							),
// 4-11th fieldset
	'F&ouml;rderer: Weitere Angaben' => array('Zustimmung Ver&ouml;ffentlichung?' =>  
															array(	'select' =>
																array(	'name'=>'f_veroeff',
																		'options'=> $f_veroeff, //$a_yesno,
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																),
															),
													'Zustimmung Weitergabe Kontaktdaten an Stipendiat?' =>
															array('select' =>
																array(	'name'=>'f_weitergabe',
																		'options' => $f_weitergabe, //$a_yesno,																			
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',							
																)
															),
										),																					
// 12th fieldset
	'StaLA (F&ouml;rderer)' => array( 'Berichtsland' =>
										array(	'select' =>
											array(	'name'=>'f_land',
													'options'=> $f_land, //array('8'=>'8'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Berichtsjahr' =>
										array(	'select' =>
											array(	'name'=>'f_jahr',
													'options'=> $f_jahr, //$a_selectYs,
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Hochschulart' =>
										array(	'select' =>
											array(	'name'=>'f_hs_art',
													'options'=> $f_hs_art, //array('6'=>'6'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Hochschule' =>
										array(	'select' =>
											array(	'name'=>'f_hs',
													'options'=> $f_hs, //array('0'=>'bitte ausw&auml;hlen','7691'=>'7691','7692'=>'7692'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
											)
										),
										'Tr&auml;gerschaft der Hochschule' =>
										array(	'select' =>
											array(	'name'=>'f_traeger_hs',
													'options'=> $f_traeger_hs, //array('2'=>'2'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'ID-Nummer' =>
											array('input' =>
												array(	'type'=>'text',								
														'name'=>'f_id',
														'value'=> $f_id,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 12 ) NOT NULL',								
												)
											),
										'Rechtsform des Mittelgebers (K&uuml;rzel)' =>
										array(	'select' =>
											array(	'name'=>'f_r_form_k',
													'options'=> $f_r_form_k,
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 60 ) NOT NULL',								
											)
										),
										'Gesamtsumme weitergegebene freie Mittel im Berichtsjahr' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'f_freie_m',
														'value'=> $f_freie_m,
														'min'=> 0,
														'max'=> 1000000,
														//'step'=> 0.01,
														'class'=>'in_w1',
														'@#db' => 'DECIMAL( 8,2 ) NOT NULL DEFAULT 0',								
												)
											),
										'Gesamtsumme weitergegebene zweckgebundene Mittel im Berichtsjahr' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'f_gebundene_m',
														'value'=> $f_gebundene_m,
														'min'=> 0,
														'max'=> 1000000,
														//'step'=> 0.01,
														'class'=>'in_w1',
														'@#db' => 'DECIMAL( 8,2 ) NOT NULL DEFAULT 0',								
												)
											),										
						),
			'Bemerkungen / Dateien' => array( 'Bemerkungen' =>
											array('textarea' =>
												array(	'name'=>'comments',
														'default'=>$comments,
														'@#db' => 'TEXT',
														'wrap'=>'soft',
														'cols' => '50',
														'rows' => '6',
														'id' => 'commentfield',
														//'virtual' => '',
														//'physical' => '',
														//'required' => '1'
														'class'=>'in_w1',
												),
											),
										'Datei hochladen' =>
											array('input' =>
												array(	'type'=>'file',
														'name'=>'datei_url',
														'accept'=>$accept,
														'maxfilesize'=> $maxfilesize,
														'allowed_filetypes'=> $allowfiles,
														'allowed_extensions'=> $ext,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 255 ) NOT NULL',								
												),
											),
									'saved' =>
											array('input' =>
												array(	'type'=>'hidden',								
														'name'=>'saved',
														'value'=>0,													
														'@#db' => 'INT( 11 ) NOT NULL DEFAULT 0',							
												)
											),
										
									),
	); // end filled array

$return = array();
if ($table===$applicantsTable) $return['fields'] = $fieldset_v;
elseif ($table===$sponsorsTable) $return['fields'] = $fieldset_spons_v;
else $return['fields'] = array(0=>"The variable \$table is not set.");
//$return['fields'] = $fieldset_v;
$return['uploads'] = $list_upload;
$return['uid'] = $uid;
$return['max'] = $val['max'];
	} else {
		$return = array();
		$return['fields'] = $array_fields;
		$return['uid'] = -1;
	}
//echo "return[fields] = <pre>";
//print_r($return['fields']);
//echo "</pre>";
	return $return;
}

function generateLink($url=FALSE) {
	global $path, $relpath;
	if (!$url OR (strpos($url,"no file ") == 1) OR $url === 'upload error' OR $url === 'file too big' OR $url === 'no filename' OR $url === 'no file ukn') {
		// strpos($url,"no file ") returns 0
		$link = "<p>No file was uploded.</p>";		
	} elseif ($url === "false mime type") {
		$link = "<p>The false mime type was uploded.</p>";
	}
	else {
		//$link = "<p>The file <b><a href=\"".$path.$url."\" >".$url."</a></b> was uploaded.</p>";
		$link = "<p>The file <b><a target=\"_blank\" href=\"".$relpath.$path.$url."\" >".$url."</a></b> was uploaded.</p>";
	}
	return $link;
}

function getSponsors($sponsorsTable,$a_var=FALSE) {
	global $dbConfig, $fieldset_spons, $path, $salt, $suffix;
	$table = $sponsorsTable;
	$a_sponsors = array(0 => "Choose Sponsor");

	if(!$a_var) $a_var = new dbOperations($dbConfig,$fieldset_spons,$path,$table,$suffix,$salt);
	$val = $a_var->getValuesFromDB("f_foerderer",$table);
	$res = $val['values'];
	if ($res == -2) $a_sponsors = array(0 => '2nd query failed!');
	elseif ($res == -1) $a_sponsors = array(0 => "Table for sponosrs $table doesn't exist!");
	elseif ($res == 0) $a_sponsors = array(0 =>'Tabelle leer! F&ouml;rderer anlegen.');
	elseif ($res) {
		$j = 0;
		foreach ($res as $value_array) {
		$i = 0;
		$a=$b = "";
			foreach ($value_array as $key => $value) {
				if ($i%2==0) {
					$$key = $value;
					$a=$value;
					//print "<br>Key: " . "$$key.$i  = " . $value . "<br>";				
				} else {
					$$key = $value;
					$b=$value;
					//print "<br>Value: " . "$$key.$i = ". $value . "<br>";
				}
				$a_sponsors[$a]=$b;
				//$a_sponsors[$key]=$value;
				$i++;
			}		
//	echo "<br>a_spons $j<br><pre>";
//	print_r($a_sponsors);
//	echo "<pre>";
				$j++;
			}
//	echo "<br>res<br><pre>";
//	print_r($res);
//	echo "<pre>";
	} else $a_sponsors = array(0 => '$res doesn\'t exist');
	return $a_sponsors;
}


function buildHeading($theform, $color, $between=FALSE) {
	global $content, $csv, $info;
	if (!$between) $between = "";
	if (!$info) {
		$info['html'] = "";
	}
	else {
 		if ($info['deleted'] === NULL) $color = "red";
 		elseif ($info['deleted'] == 1) $color = "red";
	}
	$string = $content;	
	$string .= "<div class=\"addInfo\">\n";
	$string .= $info['html'];
	$string .= $between;
	$string .= $csv."\n";
	$string .= "<p id=\"indicator\" style=\"background:$color;\"></p></div>\n";
	$string .= $theform;
	return  $string;
}

?>

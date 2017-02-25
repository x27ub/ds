<?php
//phpinfo();
session_start();
$SID = session_id();
if(empty($SID)) session_start() or die('Could not start session');
$_SESSION['sid'] = $SID;

require_once (dirname(__FILE__).'/class_htmlAssemble.php');
require_once (dirname(__FILE__).'/class_createForms.php');
require_once (dirname(__FILE__).'/class_dbOperations.php');
//require_once (dirname(__FILE__).'/class_createConfigArray.php');

$suffix="_01";
$tableName = "tx_applicants";
$fid = 1;

$doc = new htmlAssemble();
$doc->htmlhead();

//db params
$dbConfig = array();
$dbConfig['dbhost'] =  '127.0.0.1';
$dbConfig['dbuser'] =  'db315406_4';
$dbConfig['dbpass'] =  'pass';
$dbConfig['dbname'] =  'db315406_4';


// variables needed, config array and action
$_POST['submit'] = (isset($_POST['submit']) ? $_POST['submit'] : NULL);

$icon = "*";  // default value is star, defined here because of $icon in array. Try to change $icon to 1 or yes
$self = $_SERVER['PHP_SELF'];

function mk_date_range($lower,$upper,$start) {
	$tp = $start;
	$format = '%Y-%m-%d';
	$range = array();
	$maxpoint = $tp - $upper;
	$minpoint = ($tp- $lower);
	$fmaxpoint = strftime($format,$maxpoint);
	$fminpoint = strftime($format,$minpoint);
	$range['max'] = $fmaxpoint;
	$range['min'] = $fminpoint;
	return $range;
}

function mk_year_array($range) {
	$now = time();
	$yearonly = '%Y';
	$currYear = strftime($yearonly,$now);
	$a_selectYs = array();
	//$a_selectYs['0'] = "bitte ausw&auml;hlen";
	$a_selectYs[$currYear] = $currYear;
	for ($i=0; $i<$range; $i++) {		
		$currYear = $currYear + 1;
		$a_selectYs[$currYear] = $currYear;		
	}
	//echo "<pre>". print_r($a_selectYs)."</pre>";
	return $a_selectYs;
}

//birth date range
$now = time();
$delta16 = (16*365*24*3600); // 16a
$delta40 = (40*365*24*3600); // 40a
$v = mk_date_range($delta16, $delta40,$now);
$d16 = $v['max'];
$d40 = $v['min'];
// end date range
$delta1 = -(1*365*24*3600); // 1a
$timepoint = time() + (10*24*3600);

$yearonly = '%Y';
$thisyear = strftime($yearonly,$now);
$thedate = strtotime("1 September ".$thisyear);
$v2 = mk_date_range($lower=0, $delta1, $thedate);
$d1 = $v2['max'];
$dx = $v2['min'];
$a_selectYs = mk_year_array(0);

echo $d40."<br>".$d16."<br>";
echo $d1."<br>".$dx."<br>";


$allowfiles1 = array('/image/','/audio/','/video/');
$allowfiles2 = array('/image/','/audio/');
$allowfiles3 = array('/audio/','/video/');
$ext1 = array('.jpeg','.jpg','.gif','.bmp','.png','.mov','.mp3','.mp4','.avi','.mpg','.mpeg','.flv','.ukn');
$ext2 = array('.jpeg','.jpg','.gif','.bmp','.png','.ukn');
$ext3 = array('.mov','.mp3','.mp4','.avi','.mpg','.mpeg','.flv','.ukn');

$countryCodes = array('AFG'=>'Afghanistan','ALA'=>'&Aring;land','ALB'=>'Albania','DZA'=>'Algeria','ASM'=>'American Samoa','AND'=>'Andorra','AGO'=>'Angola','AIA'=>'Anguilla','ATA'=>'Antarctica','ATG'=>'Antigua and Barbuda','ARG'=>'Argentina','ARM'=>'Armenia','ABW'=>'Aruba','AUS'=>'Australia','AUT'=>'Austria','AZE'=>'Azerbaijan','BHR'=>'Bahrain','BGD'=>'Bangladesh','BRB'=>'Barbados','BLR'=>'Belarus','BEL'=>'Belgium','BLZ'=>'Belize','BEN'=>'Benin','BMU'=>'Bermuda','BTN'=>'Bhutan','BOL'=>'Bolivia','BES'=>'Bonaire, Sint Eustatius and Saba','BIH'=>'Bosnia and Herzegovina','BWA'=>'Botswana','BVT'=>'Bouvet Island','BRA'=>'Brazil','IOT'=>'British Indian Ocean Territory','VGB'=>'British Virgin Islands','BRN'=>'Brunei','BGR'=>'Bulgaria','BFA'=>'Burkina Faso','BDI'=>'Burundi','KHM'=>'Cambodia','CMR'=>'Cameroon','CAN'=>'Canada','CPV'=>'Cape Verde','CYM'=>'Cayman Islands','CAF'=>'Central African Republic','TCD'=>'Chad','CHL'=>'Chile','CHN'=>'China','CXR'=>'Christmas Island','CCK'=>'Cocos Keeling) Islands','COL'=>'Colombia','COM'=>'Comoros','COD'=>'Congo','COG'=>'Congo-Brazzaville','COK'=>'Cook Islands','CRI'=>'Costa Rica','CIV'=>'Côte d’Ivoire','HRV'=>'Croatia','CUB'=>'Cuba','CUW'=>'Curaçao','CYP'=>'Cyprus','CZE'=>'Czech Republic','DNK'=>'Denmark','DJI'=>'Djibouti','DMA'=>'Dominica','DOM'=>'Dominican Republic','ECU'=>'Ecuador','EGY'=>'Egypt','SLV'=>'El Salvador','GNQ'=>'Equatorial Guinea','ERI'=>'Eritrea','EST'=>'Estonia','ETH'=>'Ethiopia','FLK'=>'Falkland Islands','FRO'=>'Faroes','FJI'=>'Fiji','FIN'=>'Finland','FRA'=>'France','GUF'=>'French Guiana','PYF'=>'French Polynesia','ATF'=>'French Southern Territories','GAB'=>'Gabon','GMB'=>'Gambia','GEO'=>'Georgia','DEU'=>'Germany=checked','GHA'=>'Ghana','GIB'=>'Gibraltar','GRC'=>'Greece','GRL'=>'Greenland','GRD'=>'Grenada','GLP'=>'Guadeloupe','GUM'=>'Guam','GTM'=>'Guatemala','GGY'=>'Guernsey','GIN'=>'Guinea','GNB'=>'Guinea-Bissau','GUY'=>'Guyana','HTI'=>'Haiti','HMD'=>'Heard Island and McDonald Islands','HND'=>'Honduras','HKG'=>'Hong Kong SAR of China','HUN'=>'Hungary','ISL'=>'Iceland','IND'=>'India','IDN'=>'Indonesia','IRN'=>'Iran','IRQ'=>'Iraq','IRL'=>'Ireland','IMN'=>'Isle of Man','ISR'=>'Israel','ITA'=>'Italy','JAM'=>'Jamaica','JPN'=>'Japan','JEY'=>'Jersey','JOR'=>'Jordan','KAZ'=>'Kazakhstan','KEN'=>'Kenya','KIR'=>'Kiribati','KWT'=>'Kuwait','KGZ'=>'Kyrgyzstan','LAO'=>'Laos','LVA'=>'Latvia','LBN'=>'Lebanon','LSO'=>'Lesotho','LBR'=>'Liberia','LBY'=>'Libya','LIE'=>'Liechtenstein','LTU'=>'Lithuania','LUX'=>'Luxembourg','MAC'=>'Macao SAR of China','MKD'=>'Macedonia','MDG'=>'Madagascar','MWI'=>'Malawi','MYS'=>'Malaysia','MDV'=>'Maldives','MLI'=>'Mali','MLT'=>'Malta','MHL'=>'Marshall Islands','MTQ'=>'Martinique','MRT'=>'Mauritania','MUS'=>'Mauritius','MYT'=>'Mayotte','MEX'=>'Mexico','FSM'=>'Micronesia','MDA'=>'Moldova','MCO'=>'Monaco','MNG'=>'Mongolia','MNE'=>'Montenegro','MSR'=>'Montserrat','MAR'=>'Morocco','MOZ'=>'Mozambique','MMR'=>'Myanmar','NAM'=>'Namibia','NRU'=>'Nauru','NPL'=>'Nepal','NLD'=>'Netherlands','ANT'=>'Netherlands Antilles','NCL'=>'New Caledonia','NZL'=>'New Zealand','NIC'=>'Nicaragua','NER'=>'Niger','NGA'=>'Nigeria','NIU'=>'Niue','NFK'=>'Norfolk Island','PRK'=>'North Korea','MNP'=>'Northern Marianas','NOR'=>'Norway','OMN'=>'Oman','PAK'=>'Pakistan','PLW'=>'Palau','PSE'=>'Palestine','PAN'=>'Panama','PNG'=>'Papua New Guinea','PRY'=>'Paraguay','PER'=>'Peru','PHL'=>'Philippines','PCN'=>'Pitcairn Islands','POL'=>'Poland','PRT'=>'Portugal','PRI'=>'Puerto Rico','QAT'=>'Qatar','REU'=>'Reunion','ROU'=>'Romania','RUS'=>'Russia','RWA'=>'Rwanda','BLM'=>'Saint Barthélemy','SHN'=>'Saint Helena, Ascension and Tristan da Cunha','KNA'=>'Saint Kitts and Nevis','LCA'=>'Saint Lucia','MAF'=>'Saint Martin','SPM'=>'Saint Pierre and Miquelon','VCT'=>'Saint Vincent and the Grenadines','WSM'=>'Samoa','SMR'=>'San Marino','STP'=>'São Tomé e Príncipe','SAU'=>'Saudi Arabia','SEN'=>'Senegal','SRB'=>'Serbia','CSG'=>'Serbia and Montenegro','SYC'=>'Seychelles','SLE'=>'Sierra Leone','SGP'=>'Singapore','SXM'=>'Sint Maarten','SVK'=>'Slovakia','SVN'=>'Slovenia','SLB'=>'Solomon Islands','SOM'=>'Somalia','ZAF'=>'South Africa','SGS'=>'South Georgia and the South Sandwich Islands','KOR'=>'South Korea','SSD'=>'South Sudan','ESP'=>'Spain','LKA'=>'Sri Lanka','SDN'=>'Sudan','SUR'=>'Suriname','SJM'=>'Svalbard','SWZ'=>'Swaziland','SWE'=>'Sweden','CHE'=>'Switzerland','SYR'=>'Syria','TWN'=>'Taiwan','TJK'=>'Tajikistan','TZA'=>'Tanzania','THA'=>'Thailand','BHS'=>'The Bahamas','TLS'=>'Timor-Leste','TGO'=>'Togo','TKL'=>'Tokelau','TON'=>'Tonga','TTO'=>'Trinidad and Tobago','TUN'=>'Tunisia','TUR'=>'Turkey','TKM'=>'Turkmenistan','TCA'=>'Turks and Caicos Islands','TUV'=>'Tuvalu','UGA'=>'Uganda','UKR'=>'Ukraine','ARE'=>'United Arab Emirates','GBR'=>'United Kingdom','USA'=>'United States','UMI'=>'United States Minor Outlying Islands','URY'=>'Uruguay','VIR'=>'US Virgin Islands','UZB'=>'Uzbekistan','VUT'=>'Vanuatu','VAT'=>'Vatican City','VEN'=>'Venezuela','VNM'=>'Vietnam','WLF'=>'Wallis and Futuna','ESH'=>'Western Sahara','YEM'=>'Yemen','ZMB'=>'Zambia','ZWE'=>'Zimbabwe');

//$mat_bew_nr = "ad37645xh88765"; $name = "Lastname"; $vorname = "Firstname"; $geb = "07.07.1990";
//$str_nr = "this 67"; $str_zusatz = "4/11 additional"; $plz_ort = "72622 Nurtingen"; $e_mail = "user@domain.tld"; $tel = "07022201453";
//$stipendium = "stipend orgnization"; $stipendium_name = "stipend name"; $stipendium_hoehe = "350 &euro;";
$status =  array(1=>'Bewerbung akzeptiert',0=>'Bewerbung abgelehnt',2=>'Ausschluss Quotenregelung');
$a_yesno = array('0'=>'bitte ausw&auml;hlen', 'j'=>'ja','n'=>'nein');
$a_sex = array('x'=>'x','m'=>'m&auml;nnlich','f'=>'weiblich');
$a_sex2 = array('0'=>'0','1'=>'1','2'=>'2');
$a_zerotwo = array('0'=>'0','0.2'=>'0.2','0.4'=>'0.4','0.6'=>'0.6');
$a_title = array('0'=>'bitte ausw&auml;hlen','Fr.'=>'Frau','Hr.'=>'Herr');
$a_stg = array('no subject chosen'=>'bitte ausw&auml;hlen','Accounting, Auditing und Taxation'=>'Accounting, Auditing und Taxation','Agrarwirtschaft'=>'Agrarwirtschaft','Automobilwirtschaft'=>'Automobilwirtschaft','Automotive Management'=>'Automotive Management','Betriebswirtschaft'=>'Betriebswirtschaft','Energie- und Ressourcenmanagement'=>'Energie- und Ressourcenmanagement','Gesundheits- und Tourismusmanagement'=>'Gesundheits- und Tourismusmanagement','Immobilienmanagement'=>'Immobilienmanagement','Immobilienwirtschaft'=>'Immobilienwirtschaft','International Finance'=>'International Finance','International Master of Landscape Architecture'=>'International Master of Landscape Architecture','Internationales Finanzmanagement'=>'Internationales Finanzmanagement','International Management'=>'International Management','Landschaftsarchitektur'=>'Landschaftsarchitektur','Landschaftsplanung & Naturschutz'=>'Landschaftsplanung & Naturschutz','Nachhaltiges Produktmanagement'=>'Nachhaltiges Produktmanagement','Pferdewirtschaft'=>'Pferdewirtschaft','Prozessmanagement'=>'Prozessmanagement','Stadtplanung'=>'Stadtplanung','Umweltschutz'=>'Umweltschutz','Unternehmensf&uuml;hrung'=>'Unternehmensf&uuml;hrung','Unternehmensrestrukturierung und Insolvenzmanagement'=>'Unternehmensrestrukturierung und Insolvenzmanagement','Volkswirtschaftslehre'=>'Volkswirtschaftslehre','Wirtschaftsrecht/Business Law'=>'Wirtschaftsrecht/Business Law');
$a_stg_kuerzel = array(''=>'bitte ausw&auml;hlen','AAT'=>'AAT','BW'=>'BW','IFB'=>'IFB','IFM'=>'IFM','AW'=>'AW','IM'=>'IM','PW'=>'PW','PZM'=>'PZM','VWL'=>'VWL','LA'=>'LA','LPN'=>'LPN','SP'=>'SP','UW'=>'UW','IMLA'=>'IMLA','ERM'=>'ERM','GTM'=>'GTM','IMMOB'=>'IMMOB','IMMOM'=>'IMMOM','URI'=>'URI','UF'=>'UF','WR'=>'WR','AUW'=>'AUW','AUM'=>'AUM','NPM'=>'NPM');
$a_stg_abschl = array('0'=>'bitte ausw&auml;hlen','BA'=>'Bachelor','MA'=>'Master');
$a_fakultaet = array('0'=>'bitte ausw&auml;hlen','fwr'=>'FWR','favm'=>'FAVM','flus'=>'FLUS','fbf'=>'FBF');
$a_rsz = array('0'=>'bitte ausw&auml;hlen','3'=>'3','4'=>'4','6'=>'6','7'=>'7');
$a_stip_dauer = array('1 Semester'=>'1 Semester','2 Semester'=>'2 Semester');
$a_f_r_form = array('00'=>'bitte ausw&auml;hlen', 'Privatperson und Einzelunternehmen'=>'Privatperson und Einzelunternehmen','Personengesellschaft'=>'Personengesellschaft','Kapitalgesellschaft'=>'Kapitalgesellschaft','Sonstige juristische Person des privaten Rechts'=>'Sonstige juristische Person des privaten Rechts','Juristische Person des &ouml;ffentlichen Rechts'=>'Juristische Person des &ouml;ffentlichen Rechts');
$a_f_r_form_k = array('00'=>'00','03'=>'03','06'=>'06','09'=>'09', '12'=>'12','15'=>'15');
$a_s_fach = array('0'=>'bitte ausw&auml;hlen','003'=>'003', '013'=>'013', '021'=>'021', '134'=>'134', '175'=>'175', '182'=>'182', '458'=>'458', '042'=>'042');
$gruppe = array('0'=>'bitte ausw&auml;hlen','1'=>'1','2'=>'2','3'=>'3','4'=>'4');
$moegl_bew_zeitraum = array(0=>'keiner', 1=>'1 Semester',2=>'2 Semester');

$accept = array('text/html','image/jpeg','audio/mpeg', 'video/quicktime');
$accept1 = array('image/jpeg','text/html');
$accept2 = "text/html";

$fieldsets = array('Record Info' => array('hidden' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'hidden',
														//'readonly'=>'readonly',
														'min'=> 0,
														'max'=> 1,
														'step'=> 1,
														//'value'=> 0,
														'class'=>'in_w1',
														'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0',								
												)
											),
											'deleted' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'deleted',
														'min'=> 0,
														'max'=> 1,
														'step'=> 1,
														//'value'=> 0,
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
								//'value'=> $mat_bew_nr,
								'class'=>'in_w1',
								'@#db' => 'VARCHAR( 120 ) NOT NULL',
						),
				),
		'Anrede' =>
				array(	'select' =>
						array(	'name'=>'anr',	
								'options'=> $a_title,
								'class'=>'sel_w1',							
								'@#db' => 'VARCHAR( 10 ) NOT NULL',
					
						),
				),
		'Vorname' =>
				array(	'input' =>
						array(	'type'=>'text',
								'name'=>'name',
								//'value'=> $name,
								'class'=>'in_w1',
								'@#db' => 'VARCHAR( 80 ) NOT NULL',
						),
				),
        'Name' =>
				array('input' =>
						array(	'type'=>'text',
								'name'=>'vorname',
								//'value'=> $vorname,
								'class'=>'in_w1',
								'@#db' => 'VARCHAR( 80 ) NOT NULL',
						),
				),
        'Geburtsdatum' =>
				array('input' =>
						array(  'type'=>'date',
								'name'=>'geb',
								//'value'=> $geb,
								'min'=> $d16,
								'max' => $d40,
								'class'=>'in_w1',
								'@#db' => 'INT( 11 ) NOT NULL',
						),
				),
        'Geschlecht' =>
				array('select' =>
						array(	'name'=>'geschl',
								'options'=> $a_sex,
								'class'=>'sel_w1',
								'@#db' => 'VARCHAR( 10 ) NOT NULL',																								
						),
				),
        'Staatsangeh&ouml;rigkeit' =>
				array('select' =>
						array(	'name'=>'staat',
								'options'=> $countryCodes,
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
								//'value'=> $str_nr,
								'class'=>'in_w1',
								'@#db' => 'VARCHAR( 100 ) NOT NULL',
						),
				),
		'ggf. Zimmer, Etage' =>  array(
     									'input' => 
											array(  'type' => 'text',
          											'name' => 'str_zusatz',
          											//'value'=> $str_zusatz,
													'class'=>'in_w1',
          											'@#db' => 'VARCHAR( 40 ) NOT NULL',
     		 								),     
    							),	
    	'PLZ Ort' =>  array(
     					'input' => 
    							array( 'type' => 'text',
          								'name' => 'plz_ort',
          								//'value'=> $plz_ort,
    									'class'=>'in_w1',
          								'@#db' => 'VARCHAR( 80 ) NOT NULL',
      							)
   					 ),
		'E-Mail' =>
					array('input' =>
									array(	'type' => 'text',
											'name'=>'email',
											//'value' => $email,
											'class'=>'in_w1',
											'@#db' => 'VARCHAR( 80 ) NOT NULL',
						),
				),
		'Telephon' =>
					array('input' =>
									array(	'type' => 'text',
											'name'=>'tel',
											//'value' => $tel,
											'class'=>'in_w1',
											'@#db' => 'VARCHAR( 40 ) NOT NULL',
						),
				),
    ),
// 3rd fieldset
		'Angaben zu weiteren F&ouml;rderungen / Ausschlussgr&uuml;nde' => 
    			array( 'Status' =>
					array('select' =>
									array(	'name'=>'status',
											'options' => $status,											
											//'value' => $status,
											'class'=>'in_w1 status',
											'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0',
						),
				),
   		    			
    			'rechtzeitiger Eingang' =>
						array('select' =>
							array(	'name'=>'r_eingang_bew',
									'options'=> $a_yesno,
									'class'=>'sel_w1',
									'@#db' => 'VARCHAR( 4 ) NOT NULL',								
							)
						),
						'Bezug weiterer F&ouml;rderungen' =>
						array('input' =>
							array(	'type'=>'text',								
									'name'=>'stipendium',
									//'value'=> $stipendium,
									'class'=>'in_w1',
									'@#db' => 'VARCHAR( 120 ) NOT NULL',								
							)
						),
						'Falls ja, Name' =>
							array('input' =>
								array(	'type'=>'text',
										'name'=>'stipendium_name',								
										//'value' => $stipendium_name,
										'class'=>'in_w1',
										'@#db' => 'VARCHAR( 120 ) NOT NULL',
								)
						),
						'und H&ouml;he/Monat in [&euro;]' =>
							array('input' =>
								array(	'type'=>'text',
										'name'=>'stipendium_hoehe',
										//'value'=> $stipendium_hoehe,
										'class'=>'in_w1',
										'@#db' => 'VARCHAR( 120 ) NOT NULL',											
								)
						),
						'&Uuml;berschreitung der F&ouml;rderungsh&ouml;chstdauer (keine Verl&auml;ngerung)' =>
						array('select' =>
							array(	'name'=>'ueberschr',
									'options'=> $a_yesno,
									'class'=>'sel_w1',
									'@#db' => 'VARCHAR( 4 ) NOT NULL',								
							)
						),
						'Sonstiger Grund Ausschluss aus Verfahren' =>
						array('select' =>
							array(	'name'=>'ausschluss',
									'options'=> $a_yesno,
									'class'=>'sel_w1',
									'@#db' => 'VARCHAR( 4 ) NOT NULL',								
							)
						),
				),
// 4th fieldset a
	'Notendurchschnitt: Studienanf&auml;nger Bachelor' => array(
															'Durchschnittsnote Hochschulzugangsberechtigung' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'hzb',
																			//'value'=> $hzb,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL',
																	),
																),
															'Durchschnittsnote Deutsch' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'deutsch',
																			//'value'=> $deutsch,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL',
																	),
																),
															'Durchschnittsnote Mathe' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'mathe',
																			//'value'=> $mathe,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL',
																	),
																),
															'Durchschnittsnote beste Fremdsprache' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'fremdsprache',
																			//'value'=> $fremdsprache,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL',
																	),
																),
															'Durchschnittsnote Kernkompetenzf&auml;cher' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'durchschn_kern',
																			//'value'=> $durschn_kern,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL',
																	),
																),
															'Ausgangsnote Studienanf&auml;nger Bachelorstudiengang' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ausgangsnote',
																			//'value'=> $ausgangsnote,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL',
																	),
																),
														),
// 4th fieldset b
	'Notendurchschnitt: Bachelor ab 2.Semester ' => array(
															'ECTS' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ects_b',
																			//'value'=> $ects_b,
																			'min'=> 0,
																			'max'=> 180,
																			'step'=> 1,
																			'class'=>'in_w1',
																			'@#db' => 'INT( 3 ) NOT NULL',
																	),
																),
															'Soll-ECTS' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'soll_ects_b',
																			//'value'=> $soll_etcs_b,
																			'min'=> 0,
																			'max'=> 180,
																			'step'=> 1,
																			'class'=>'in_w1',
																			'@#db' => 'INT( 3 ) NOT NULL',
																	),
																),
															'ECTS SOLL-IST Abweichung' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ects_abw_b',
																			//'value'=> $ects_abw_b,
																			'min'=> 0,
																			'max'=> 180,
																			'step'=> 1,
																			'class'=>'in_w1',
																			'@#db' => 'INT( 3 ) NOT NULL',
																	),
																),
															'Bemerkung ECTS-Abweichung' =>
																array(	'input' =>
																	array(	'type'=>'text',
																			'name'=>'ects_abw_bemer_b',
																			//'value'=> $ects_abw_bemer_b,
																			'class'=>'in_w1',																		
																			'@#db' => 'TEXT  NOT NULL',
																	),
																),
															'Durchschnitt Studienleistungen ' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'studleistungen_b',
																			//'value'=> $studleistungen_b,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL',
																	),
																),															
															'Ausgangsnote Bachelorstudierende ab dem 2.Semester' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ausgangsnote_b',
																			//'value'=> $ausgansnote_b,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL',
																	),
																),			
							),
// 4th fieldset c
	'Notendurchschnitt: Studienanf&auml;nger Masterstudiengang' => array(
															'Abschlussnote des vorausgegangenen Studiums' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'note_bachelor_c',
																			//'value'=> $note_bachelor_c,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL',
																	),
																),
															'Ausgangsnote Studienanf&auml;nger Masterstudiengang' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ausgangsnote_c',
																			//'value'=> $ausgangsnote_c,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL',
																	),
																),
							),
// 4th fieldset d
	'Notendurchschnitt: Masterstudiengang ab 2.Semester ' => array(
															'ECTS' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ects_d',
																			//'value'=> $ects_d,
																			'min'=> 0,
																			'max'=> 180,
																			'step'=> 1,
																			'class'=>'in_w1',
																			'@#db' => 'INT( 3 ) NOT NULL',
																	),
																),
															'Soll-ECTS' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'soll_ects_d',
																			//'value'=> $soll_ects_d,
																			'min'=> 0,
																			'max'=> 180,
																			'step'=> 1,
																			'class'=>'in_w1',
																			'@#db' => 'INT( 3 ) NOT NULL',
																	),
																),
															'ECTS SOLL-IST Abweichung' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ects_abw_d',
																			//'value'=> $ects_abw_d,
																			'min'=> 0,
																			'max'=> 180,
																			'step'=> 1,
																			'class'=>'in_w1',
																			'@#db' => 'INT( 3 ) NOT NULL',
																	),
																),
															'Bemerkung ECTS-Abweichung' =>
																array(	'input' =>
																	array(	'type'=>'text',
																			'name'=>'ects_abw_bemer_d',
																			//'value'=> $ects_abw_bemer_d,
																			'class'=>'in_w1',																			
																			'@#db' => 'TEXT NOT NULL',
																	),
																),
															'Durchschnitt Studienleistungen ' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'studleistungen_d',
																			//'value'=> $studleistungen_d,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL',
																	),
																),
															'Abschlussnote des vorausgegangenen Studiums' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'note_bachelor_d',
																			//'value'=> $note_bachelor_d,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL',
																	),
																),
															'Ausgangsnote Masterstudierende ab dem 2.Semester' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'ausgangsnote_d',
																			//'value'=> $ausgansnote_d,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL',
																	),
																),
							),																					
// 5th fieldset			
	'Angaben zum Studium' => array( 'Studiengang' =>  
									array(	'select' =>
										array(	'name'=>'stg',
												'options'=> $a_stg,
												'class'=>'sel_w1',
												'@#db' => 'VARCHAR( 60 ) NOT NULL',								
										),
									),									
									'Externes Studiengangk&uuml;rzel' =>  
									array(	'select' =>
										array(	'name'=>'stg_kuerzel',
												'options'=> $a_stg_kuerzel,
												'class'=>'sel_w1',
												'@#db' => 'VARCHAR( 60 ) NOT NULL',								
										),
									),
									'Angestrebter Abschluss' =>  
									array(	'select' =>
										array(	'name'=>'stg_abschl',
												'options'=> $a_stg_abschl,
												'class'=>'sel_w1',
												'@#db' => 'VARCHAR( 60 ) NOT NULL',								
										),
									),
									'Fakult&auml;t' =>  
									array(	'select' =>
										array(	'name'=>'fakultaet',
												'options'=> $a_fakultaet,
												'class'=>'sel_w1',
												'@#db' => 'VARCHAR( 60 ) NOT NULL',								
										),
									),
									'Fachsemester' =>
									array('input' =>
										array(	'type'=>'number',								
												'name'=>'sem',
												//'value'=> $sem,
												'min'=> 0,
												'max'=> 20,
												'step'=> 1,
												'class'=>'in_w1',
												'@#db' => 'TINYINT( 2 ) NOT NULL',								
										)
									),
									'Hochschulsemester' =>
									array('input' =>
										array(	'type'=>'number',								
												'name'=>'hs',
												//'value'=> $hs,
												'min'=> 0,
												'max'=> 20,
												'step'=> 1,
												'class'=>'in_w1',
												'@#db' => 'TINYINT( 2 ) NOT NULL',								
										)
									),
									'Regelstudienzeit' =>  
									array(	'select' =>
										array(	'name'=>'rsz',
												'options'=> $a_rsz,
												'class'=>'sel_w1',
												'@#db' => 'TINYINT( 2 ) NOT NULL',								
										),
									),
									'Verl&auml;ngerung der F&ouml;rderungsh&ouml;chstdauer aus schwerwiegenden Gr&uuml;nden?' =>  
									array(	'select' =>
										array(	'name'=>'verl_dauer',
												'options'=> $a_yesno,
												'class'=>'sel_w1',
												'@#db' => 'VARCHAR( 2 ) NOT NULL',								
										),
									),
									'Grund' =>
									array('textarea' =>
										array(	'name'=>'verl_dauer_grund',
												'default'=>'',//$verl_dauer_grund,
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
																			'options'=> $a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																	),
																),
															'Bonus Preise' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_preis',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL',								
																	),
																),
															'Berufst&auml;tigkeit/Berufsausbildung' =>  
																array(	'select' =>
																	array(	'name'=>'beruf_ausbildung',
																			'options'=> $a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																	),
																),
															'Bonus Berufst&auml;tigkeit/Berufsausbildung' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_beruf_ausbildung',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL',								
																	),
																),
															'Praktika' =>  
																array(	'select' =>
																	array(	'name'=>'praktika',
																			'options'=> $a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																	),
																),
															'Bonus Praktika' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_praktika',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL',								
																	),
																),
															'Gesellschaftliches/politisches Engagement' =>  
																array(	'select' =>
																	array(	'name'=>'ges_pol_eng',
																			'options'=> $a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																	),
																),
															'Bonus Gesellschaftliches/politisches Engagement' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_ges_pol_eng',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL',								
																	),
																),
															'Hochschulengagement' =>  
																array(	'select' =>
																	array(	'name'=>'hs_eng',
																			'options'=> $a_yesno,
																			'class'=>'sel_w1  m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																	),
																),													
															'Bonus Hochschulengagement' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_hs_eng',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL',								
																	),
																),
														),
// 7th fieldset
	'Besondere pers&ouml;nliche oder famili&auml;re Umst&auml;nde' => array('Betreuung eigener Kinder' =>  
																		array(	'select' =>
																				array(	'name'=>'kind',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																				),
																			),
																		'Bonus Betreuung eigener Kinder' =>  
																			array(	'select' =>
																				array(	'name'=>'b_kind',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL',								
																				),
																			),
																		'Krankheiten/Behinderungen' =>  
																			array(	'select' =>
																				array(	'name'=>'krankheit',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																				),
																			),
																		'Bonus Krankheiten/Behinderungen' =>  
																			array(	'select' =>
																				array(	'name'=>'b_krankheit',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL',								
																				),
																			),
																		'Mitarbeit im famili&auml;ren Betrieb' =>  
																			array(	'select' =>
																				array(	'name'=>'mitarbeit_betrieb',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																				),
																			),
																		'Bonus Mitarbeit im famili&auml;ren Betrieb' =>  
																			array(	'select' =>
																				array(	'name'=>'b_mitarbeit_betrieb',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL',								
																				),
																			),
																		'Betreuung pflegebed&uuml;rftiger naher Angeh&ouml;riger' =>  
																			array(	'select' =>
																				array(	'name'=>'pflege_anghoeriger',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																				),
																			),
																		'Bonus Betreuung pflegebed&uuml;rftiger naher Angeh&ouml;riger' =>  
																			array(	'select' =>
																				array(	'name'=>'b_pflege_anghoeriger',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL',								
																				),
																			),
																		'Famili&auml;rer Hintergrund' =>  
																			array(	'select' =>
																				array(	'name'=>'fam_hintergrund',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																				),
																			),					
																		'Bonus famili&auml;rer Hintergrund' =>  
																			array(	'select' =>
																				array(	'name'=>'b_fam_hintergrund',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL',								
																				),
																			),
																		'Migrationshintergrund' =>  
																			array(	'select' =>
																				array(	'name'=>'migration',
																						'options'=> $a_yesno,
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
																		'options'=> $a_yesno,
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																),
															),
													'Ich beziehe im F&ouml;rderzeitraum Baf&ouml;G' =>
															array('select' =>
																array(	'options' => array(0=>'Wenn ja, welches Semester',1=>'im Wintersemster'),						
																		'name'=>'ws_bafoeg',
																		'class'=>'sel_w1',
																		'@#db' => 'TINYINT( 1 ) NOT NULL'							
																)
															),
													'und auch im Sommersemester' =>
															array('select' =>
																array(	'options' => array(0=>'nein',1=>'ja'),						
																		'name'=>'ss_bafoeg',
																		'class'=>'sel_w1',
																		'@#db' => 'TINYINT( 1 ) NOT NULL'							
																)
															),
													),													
// 9th fieldset
	'Entscheidung &uuml;ber Stipendium' => array( 'Gruppe' =>
															array('select' =>
																array(	'options' => $gruppe,						
																		'name'=>'gruppe',
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL'							
																)
															),
													'Bonus gesamt' =>
													array('input' =>
														array(	'type'=>'number',								
																'name'=>'bonus_gesamt',
																//'value'=> $bonus_gesamt,
																'min'=> 0,
																'max'=> 2,
																'step'=> 0.1,
																'class'=>'in_w1',
																'@#db' => 'DECIMAL(3,2) NOT NULL',								
														),
													),
													'Bonus Verfahren' =>
													array('input' =>
														array(	'type'=>'number',								
																'name'=>'bonus_verfahren',
																//'value'=> $bonus_verfahren,
																'min'=> 0,
																'max'=> 0.8,
																'step'=> 0.1,
																'class'=>'in_w1',
																'@#db' => 'DECIMAL(3,2) NOT NULL',								
														),
													),
													'Note Rangliste' =>
													array('input' =>
														array(	'type'=>'number',								
																'name'=>'note_rangliste',
																//'value'=> $note_rangliste,
																'min'=> 0,
																'max'=> 6,
																'step'=> 0.01,
																'class'=>'in_w1',
																'@#db' => 'DECIMAL(3,2) NOT NULL',								
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
																		'options'=> $a_yesno,
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																),
															),
													'Beginn der Bewilligung' =>
													array('input' =>
														array(	'type'=>'date',								
																'name'=>'bew_beginn',
																//'value'=> $bew_beginn,
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
																//'value'=> $bew_ende,
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
																//'value'=> $stip_dauer,						
																'name'=>'stip_dauer',
																'class'=>'in_w1',
																'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0'							
														),
													),
													'zweckgebundens Stipendium?' =>  
															array(	'select' =>
																array(	'name'=>'zweck_stip',
																		'options'=> $a_yesno,
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																),
															),
													'Beurlaubung?' =>
													array('textarea' =>
														array(	'name'=>'us',
																'default'=>'',// $us,
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
													'options'=> array('8'=>'8'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Berichtsjahr' =>
										array(	'select' =>
											array(	'name'=>'s_jahr',
													'options'=> $a_selectYs,
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Hochschulart' =>
										array(	'select' =>
											array(	'name'=>'s_hs_art',
													'options'=> array('6'=>'6'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Hochschule' =>
										array(	'select' =>
											array(	'name'=>'s_hs',
													'options'=> array('0'=>'bitte ausw&auml;hlen','6791'=>'6791', '6792'=>'6792'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Tr&auml;gerschaft der Hochschule' =>
										array(	'select' =>
											array(	'name'=>'s_traeger_hs',
													'options'=> array('2'=>'2'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'ID-Nummer' =>
											array('input' =>
												array(	'type'=>'text',								
														'name'=>'s_id_nr',
														//'value'=> $s_id_nr,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 12 )  NOT NULL',								
												)
											),
										'Geschlecht' =>
											array('select' =>
												array(	'name'=>'s_geschl',
														'options'=> $a_sex2,
														'class'=>'sel_w1',
														'@#db' => 'VARCHAR( 2 ) NOT NULL',																								
												),
											),
       									 'Staatsangeh&ouml;rigkeit' =>
											array('input' =>
												array(	'type'=>'text',
														'name'=>'s_staat',
														//'value'=> $s_staat,
														'class'=>'in_w1',						
														'@#db' => 'VARCHAR( 2 ) NOT NULL',
												),
											),
										'Anzahl HS-Semester an deutschen Hochschulen (im Berichtsjahr)' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'s_hs_sem',
														//'value'=> $s_hs_sem,
														'min'=> 0,
														'max'=> 20,
														'step'=> 1,
														'class'=>'in_w1',
														'@#db' => 'TINYINT( 2 )  NOT NULL',								
												)
											),	
										'Anzahl Fachsemester an deutschen Hochschulen (im Berichtsjahr)' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'s_fach_sem',
														//'value'=> $s_fach_sem,
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
														//'value'=> $s_abschl,
														'options'=> array('0'=>'bitte ausw&auml;hlen','184'=>'184','284'=>'284','390'=>'390'),
														'class'=>'sel_w1',
														'@#db' => 'VARCHAR( 4 ) NOT NULL',								
												)
											),
										'Studienfach' =>
											array('select' =>
												array(	'name'=>'s_fach',
														'options'=> $a_s_fach,
														'class'=>'sel_w1',
														'@#db' => 'VARCHAR( 4 ) NOT NULL',								
												)
											),
										'Zahl der F&ouml;rdermonate im Berichtsjahr' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'s_foerd_monate',
														//'value'=> $s_foerd_monate,
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
																		'options'=> array('0'=>'bitte ausw&auml;hlen','1'=>'1','2'=>'2'),
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																),
															),																	
							),
// 1-11th fieldset 
	'F&ouml;rderer: Stammdaten' => array('F&ouml;rderer/Firma ' =>  
															array(	'input' =>
																array(	'type' => 'text',
																		'name'=>'f_foerderer',
																		//'value'=> $f_foerder,
																		'class'=>'in_w1',																		
																		'@#db' => 'VARCHAR( 255 ) NOT NULL',								
																),
															),
											'Rechtsform ' =>
															array('select' =>
																array(	'options' => $a_f_r_form,						
																		'name'=>'f_r_form',
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
														//'value'=> $f_str_nr,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 160 ) NOT NULL',								
												)
											),
											'PLZ, Ort.' =>
											array('input' =>
												array(	'type'=>'text',								
														'name'=>'f_plz_ort',
														//'value'=> $f_plz_ort,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 160 ) NOT NULL',								
												)
											),
											'Geschlecht' =>  
												array(	'select' =>
													array(	'name'=>'f_geschl',
															'options'=> $a_sex,
															'class'=>'sel_w1',
															'@#db' => 'VARCHAR( 4 ) NOT NULL',								
													),
												),
											'Name, Vorname (Ansprechpartner)' =>
											array('input' =>
												array(	'type'=>'text',								
														'name'=>'f_name',
														//'value'=> $f_name,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 255 ) NOT NULL',								
												)
											),
											'Funktion (Ansprechpartner)' =>
											array('input' =>
												array(	'type'=>'text',								
														'name'=>'f_funktion',
														//'value'=> $f_funktion,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 255 ) NOT NULL',								
												)
											),
											'Telefon (Ansprechpartner)' =>
											array('input' =>
												array(	'type'=>'tel',								
														'name'=>'f_tel',
														//'value'=> $f_tel,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 80 ) NOT NULL',								
												)
											),
											'E-Mail (Ansprechpartner)' =>
											array('input' =>
												array(	'type'=>'email',								
														'name'=>'f_e-mail',
														//'value'=> $f_e-mail,
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
															'options'=> $a_yesno,
															'class'=>'sel_w1',
															'@#db' => 'VARCHAR( 4 ) NOT NULL',								
													),
												),
									'Studiengang' =>  
												array(	'select' =>
													array(	'name'=>'f_stg',
															'options'=> $a_stg,
															'class'=>'sel_w1',
															'@#db' => 'VARCHAR( 120 ) NOT NULL',								
													),
												),
									'Summe Mittel' =>
											array('input' =>
												array(	'type'=>'text',								
														'name'=>'f_summe',
														//'value'=> $f_summe,
														'class'=>'in_w1',
														'@#db' => 'DECIMAL( 6,2 ) NOT NULL',								
												)
											),
									'F&ouml;rderung beginnt am' =>
											array('input' =>
												array(	'type'=>'date',								
														'name'=>'f_foerd_beginn',
														//'value'=> $f_foerd_beginn,
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
														//'value'=> $f_foerd_ende,
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
																		'options'=> $a_yesno,
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL',								
																),
															),
													'Zustimmung Weitergabe Kontaktdaten an Stipendiat?' =>
															array('select' =>
																array(	'options' => $a_yesno,						
																		'name'=>'f_weitergabe',
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL'							
																)
															),
										),																					
// 12th fieldset
	'StaLA (F&ouml;rderer)' => array( 'Berichtsland' =>
										array(	'select' =>
											array(	'name'=>'f_land',
													'options'=> array('8'=>'8'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Berichtsjahr' =>
										array(	'select' =>
											array(	'name'=>'f_jahr',
													'options'=> $a_selectYs,
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Hochschulart' =>
										array(	'select' =>
											array(	'name'=>'f_hs_art',
													'options'=> array('6'=>'6'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Hochschule' =>
										array(	'select' =>
											array(	'name'=>'f_hs',
													'options'=> array('0'=>'bitte ausw&auml;hlen','7691'=>'7691','7692'=>'7692'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Tr&auml;gerschaft der Hochschule' =>
										array(	'select' =>
											array(	'name'=>'f_traeger_hs',
													'options'=> array('2'=>'2'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'ID-Nummer' =>
											array('input' =>
												array(	'type'=>'text',								
														'name'=>'f_id',
														//'value'=> $f_id,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 12 ) NOT NULL',								
												)
											),
										'Rechtsform des Mittelgebers (K&uuml;rzel)' =>
										array(	'select' =>
											array(	'name'=>'f_r_form_k',
													'options'=> $a_f_r_form_k,
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 60 ) NOT NULL',								
											)
										),
										'Gesamtsumme weitergegebene freie Mittel im Berichtsjahr' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'f_freie_m',
														//'value'=> $f_freie_m,
														'min'=> 0,
														'max'=> 1000000,
														'step'=> 0.01,
														'class'=>'in_w1',
														'@#db' => 'DECIMAL( 8,2 ) NOT NULL',								
												)
											),
										'Gesamtsumme weitergegebene zweckgebundene Mittel im Berichtsjahr' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'f_gebundene_m',
														//'value'=> $f_gebundene_m,
														'min'=> 0,
														'max'=> 1000000,
														'step'=> 0.01,
														'class'=>'in_w1',
														'@#db' => 'DECIMAL( 8,2 ) NOT NULL',								
												)
											),										
						),
// 13th fieldset						
			'Bemerkungen / Dateien' => array( 'Bemerkungen' =>
											array('textarea' =>
												array(	'name'=>'comments',
														'default'=>'',// $comments,
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
														'accept'=>$accept1,
														'maxfilesize'=> $maxfilesize,
														'allowed_filetypes'=> $allowfiles1,
														'allowed_extensions'=> $ext1,
														'class'=>'in_w1',
														'@#db' => 'VARCHAR( 255 ) NOT NULL',								
												),
											),
									'saved' =>
											array('input' =>
												array(	'type'=>'hidden',								
														'name'=>'saved',
														//'value'=>0,													
														'@#db' => 'INT( 11 ) NOT NULL DEFAULT 0',							
												)
											),
										
									),
); // end array 3

echo "<pre>";
//print_r($fieldsets);
//print_r($dbConfig);
//echo "FILES =";
//print_r($_FILES);
echo "</pre>";
//$confArray = new createConfigArray($dbConfig,$tableName,$suffix);
//if($confArray->checkFormId()) $fid = $confArray->checkFormId();
//else $fid = 1; 

$path = "http://mydomain.com/uploads/";
$dbOp = new dbOperations($dbConfig,$fieldsets,$path,$tableName,$suffix,$salt="I|1Tofu+-Wev>w.W=S\$Y+5$44HC;[<",$fid);
$form = new createForms($self,$fieldsets,$icon);


if (!$_POST['submit']) {
	$p = "<pre>"; $sp = "</pre>";
	$content = <<< EOC
<div class="main"><h1>Administration Form</h1>
<p>need buttons here: 'back' and 'next'</p>\n
EOC;
	//echo "no submit";
	print $content . $form->toString() . "</div>";
	
} elseif ($_POST['submit'] == 'Submit' AND TRUE) {
	$dbOp->createTable();
	$dbOp->write2Table();
} else {};

$doc->htmlfoot();
?>

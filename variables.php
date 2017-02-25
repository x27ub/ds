<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('Europe/Berlin');
date_default_timezone_set('Etc/GMT+1');

require_once (dirname(__FILE__).'/functions.php');

$path = "../uploads/";
$base = "/ds";

//db params
$dbConfig = array();
$dbConfig['dbhost'] =  '127.0.0.1';
$dbConfig['dbuser'] =  'db315406_4';
$dbConfig['dbpass'] =  'pass';
$dbConfig['dbname'] =  'db315406_4';


//birth date range
$now = time();
$delta16 = (16*365*24*3600); // 16a
$delta40 = (60*365*24*3600); // 60a
$v = mk_date_range($delta16,$delta40,$now);
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
$a_selectYs = mk_year_array(7,3);

$now = time();
$deltaT = (18*365*24*3600); // 18a
$maxpoint = $now - $deltaT;
$minpoint = ($now-(80*365*24*3600));
$format = '%Y-%m-%d';
$fmaxpoint = strftime($format,$maxpoint);
$fminpoint = strftime($format,$minpoint);

$country = array('AFG'=>'Afghanistan','ALA'=>'&Aring;land','ALB'=>'Albania','DZA'=>'Algeria','ASM'=>'American Samoa','AND'=>'Andorra','AGO'=>'Angola','AIA'=>'Anguilla','ATA'=>'Antarctica','ATG'=>'Antigua and Barbuda','ARG'=>'Argentina','ARM'=>'Armenia','ABW'=>'Aruba','AUS'=>'Australia','AUT'=>'Austria','AZE'=>'Azerbaijan','BHR'=>'Bahrain','BGD'=>'Bangladesh','BRB'=>'Barbados','BLR'=>'Belarus','BEL'=>'Belgium','BLZ'=>'Belize','BEN'=>'Benin','BMU'=>'Bermuda','BTN'=>'Bhutan','BOL'=>'Bolivia','BES'=>'Bonaire, Sint Eustatius and Saba','BIH'=>'Bosnia and Herzegovina','BWA'=>'Botswana','BVT'=>'Bouvet Island','BRA'=>'Brazil','IOT'=>'British Indian Ocean Territory','VGB'=>'British Virgin Islands','BRN'=>'Brunei','BGR'=>'Bulgaria','BFA'=>'Burkina Faso','BDI'=>'Burundi','KHM'=>'Cambodia','CMR'=>'Cameroon','CAN'=>'Canada','CPV'=>'Cape Verde','CYM'=>'Cayman Islands','CAF'=>'Central African Republic','TCD'=>'Chad','CHL'=>'Chile','CHN'=>'China','CXR'=>'Christmas Island','CCK'=>'Cocos Keeling) Islands','COL'=>'Colombia','COM'=>'Comoros','COD'=>'Congo','COG'=>'Congo-Brazzaville','COK'=>'Cook Islands','CRI'=>'Costa Rica','CIV'=>'Côte d’Ivoire','HRV'=>'Croatia','CUB'=>'Cuba','CUW'=>'Curaçao','CYP'=>'Cyprus','CZE'=>'Czech Republic','DNK'=>'Denmark','DJI'=>'Djibouti','DMA'=>'Dominica','DOM'=>'Dominican Republic','ECU'=>'Ecuador','EGY'=>'Egypt','SLV'=>'El Salvador','GNQ'=>'Equatorial Guinea','ERI'=>'Eritrea','EST'=>'Estonia','ETH'=>'Ethiopia','FLK'=>'Falkland Islands','FRO'=>'Faroes','FJI'=>'Fiji','FIN'=>'Finland','FRA'=>'France','GUF'=>'French Guiana','PYF'=>'French Polynesia','ATF'=>'French Southern Territories','GAB'=>'Gabon','GMB'=>'Gambia','GEO'=>'Georgia','DEU'=>'Germany','GHA'=>'Ghana','GIB'=>'Gibraltar','GRC'=>'Greece','GRL'=>'Greenland','GRD'=>'Grenada','GLP'=>'Guadeloupe','GUM'=>'Guam','GTM'=>'Guatemala','GGY'=>'Guernsey','GIN'=>'Guinea','GNB'=>'Guinea-Bissau','GUY'=>'Guyana','HTI'=>'Haiti','HMD'=>'Heard Island and McDonald Islands','HND'=>'Honduras','HKG'=>'Hong Kong SAR of China','HUN'=>'Hungary','ISL'=>'Iceland','IND'=>'India',
'IDN'=>'Indonesia','IRN'=>'Iran','IRQ'=>'Iraq','IRL'=>'Ireland','IMN'=>'Isle of Man','ISR'=>'Israel','ITA'=>'Italy','JAM'=>'Jamaica','JPN'=>'Japan','JEY'=>'Jersey','JOR'=>'Jordan','KAZ'=>'Kazakhstan','KEN'=>'Kenya','KIR'=>'Kiribati','KWT'=>'Kuwait','KGZ'=>'Kyrgyzstan','LAO'=>'Laos','LVA'=>'Latvia','LBN'=>'Lebanon','LSO'=>'Lesotho','LBR'=>'Liberia','LBY'=>'Libya','LIE'=>'Liechtenstein','LTU'=>'Lithuania','LUX'=>'Luxembourg','MAC'=>'Macao SAR of China','MKD'=>'Macedonia','MDG'=>'Madagascar','MWI'=>'Malawi','MYS'=>'Malaysia','MDV'=>'Maldives','MLI'=>'Mali','MLT'=>'Malta','MHL'=>'Marshall Islands','MTQ'=>'Martinique','MRT'=>'Mauritania','MUS'=>'Mauritius','MYT'=>'Mayotte','MEX'=>'Mexico','FSM'=>'Micronesia','MDA'=>'Moldova','MCO'=>'Monaco','MNG'=>'Mongolia','MNE'=>'Montenegro','MSR'=>'Montserrat','MAR'=>'Morocco','MOZ'=>'Mozambique','MMR'=>'Myanmar','NAM'=>'Namibia','NRU'=>'Nauru','NPL'=>'Nepal','NLD'=>'Netherlands','ANT'=>'Netherlands Antilles','NCL'=>'New Caledonia','NZL'=>'New Zealand','NIC'=>'Nicaragua','NER'=>'Niger','NGA'=>'Nigeria','NIU'=>'Niue','NFK'=>'Norfolk Island','PRK'=>'North Korea','MNP'=>'Northern Marianas','NOR'=>'Norway','OMN'=>'Oman','PAK'=>'Pakistan','PLW'=>'Palau','PSE'=>'Palestine','PAN'=>'Panama','PNG'=>'Papua New Guinea','PRY'=>'Paraguay','PER'=>'Peru','PHL'=>'Philippines','PCN'=>'Pitcairn Islands','POL'=>'Poland','PRT'=>'Portugal','PRI'=>'Puerto Rico','QAT'=>'Qatar','REU'=>'Reunion','ROU'=>'Romania','RUS'=>'Russia','RWA'=>'Rwanda','BLM'=>'Saint Barthélemy','SHN'=>'Saint Helena, Ascension and Tristan da Cunha','KNA'=>'Saint Kitts and Nevis','LCA'=>'Saint Lucia','MAF'=>'Saint Martin','SPM'=>'Saint Pierre and Miquelon','VCT'=>'Saint Vincent and the Grenadines','WSM'=>'Samoa','SMR'=>'San Marino','STP'=>'São Tomé e Príncipe','SAU'=>'Saudi Arabia','SEN'=>'Senegal','SRB'=>'Serbia','CSG'=>'Serbia and Montenegro','SYC'=>'Seychelles','SLE'=>'Sierra Leone','SGP'=>'Singapore','SXM'=>'Sint Maarten','SVK'=>'Slovakia','SVN'=>'Slovenia','SLB'=>'Solomon Islands','SOM'=>'Somalia','ZAF'=>'South Africa','SGS'=>'South Georgia and the South Sandwich Islands','KOR'=>'South Korea','SSD'=>'South Sudan','ESP'=>'Spain','LKA'=>'Sri Lanka','SDN'=>'Sudan','SUR'=>'Suriname','SJM'=>'Svalbard','SWZ'=>'Swaziland','SWE'=>'Sweden','CHE'=>'Switzerland','SYR'=>'Syria','TWN'=>'Taiwan','TJK'=>'Tajikistan','TZA'=>'Tanzania','THA'=>'Thailand','BHS'=>'The Bahamas','TLS'=>'Timor-Leste','TGO'=>'Togo','TKL'=>'Tokelau','TON'=>'Tonga','TTO'=>'Trinidad and Tobago','TUN'=>'Tunisia','TUR'=>'Turkey','TKM'=>'Turkmenistan','TCA'=>'Turks and Caicos Islands','TUV'=>'Tuvalu','UGA'=>'Uganda','UKR'=>'Ukraine','ARE'=>'United Arab Emirates','GBR'=>'United Kingdom','USA'=>'United States','UMI'=>'United States Minor Outlying Islands','URY'=>'Uruguay','VIR'=>'US Virgin Islands','UZB'=>'Uzbekistan','VUT'=>'Vanuatu','VAT'=>'Vatican City','VEN'=>'Venezuela','VNM'=>'Vietnam','WLF'=>'Wallis and Futuna','ESH'=>'Western Sahara','YEM'=>'Yemen','ZMB'=>'Zambia','ZWE'=>'Zimbabwe');
$a_yesno = array('0'=>'bitte ausw&auml;hlen', 'ja'=>'ja','nein'=>'nein');
$a_sex = array('x'=>'x','m'=>'m&auml;nnlich','f'=>'weiblich');
$a_sex2 = array('0'=>'0','1'=>'1','2'=>'2');
$a_zerotwo = array('0.00'=>'0','0.20'=>'0.2','0.40'=>'0.4','0.60'=>'0.6');
$a_title = array('x'=>'bitte ausw&auml;hlen','Frau'=>'Frau','Herr'=>'Herr');
$a_stg = array ('keinen'=>'bitte ausw&auml;hlen', 'Accounting, Auditing und Taxation'=>'Accounting, Auditing und Taxation', 'Agrarwirtschaft'=>'Agrarwirtschaft', 'Automobilwirtschaft'=>'Automobilwirtschaft', 'Automotive Management'=>'Automotive Management', 'Betriebswirtschaft'=>'Betriebswirtschaft', 'Energie- und Ressourcenmanagement'=>'Energie- und Ressourcenmanagement', 'Gesundheits- und Tourismusmanagement'=>'Gesundheits- und Tourismusmanagement', 'Immobilienmanagement'=>'Immobilienmanagement', 'Immobilienwirtschaft'=>'Immobilienwirtschaft', 'International Finance'=>'International Finance', 'International Master of Landscape Architecture'=>'International Master of Landscape Architecture', 'Internationales Finanzmanagement'=>'Internationales Finanzmanagement', 'International Management'=>'International Management', 'Landschaftsarchitektur'=>'Landschaftsarchitektur', 'Landschaftsplanung und Naturschutz'=>'Landschaftsplanung und Naturschutz', 'Nachhaltiges Produktmanagement'=>'Nachhaltiges Produktmanagement', 'Pferdewirtschaft'=>'Pferdewirtschaft', 'Prozessmanagement'=>'Prozessmanagement', 'Stadtplanung'=>'Stadtplanung', 'Umweltschutz'=>'Umweltschutz', 'Unternehmensfuehrung'=>'Unternehmensfuehrung', 'Unternehmensrestrukturierung und Insolvenzmanagement'=>'Unternehmensrestrukturierung und Insolvenzmanagement', 'Volkswirtschaftslehre'=>'Volkswirtschaftslehre', 'Wirtschaftsrecht/Business Law'=>'Wirtschaftsrecht/Business Law','Nachhaltige Stadt- und Regionalentwicklung'=>'Nachhaltige Stadt- und Regionalentwicklung','Controlling'=>'Controlling');
$a_stg_kuerzel = array('0'=>'bitte ausw&auml;hlen','AAT'=>'AAT','BW'=>'BW','IFB'=>'IFB','IFM'=>'IFM','AW'=>'AW','IM'=>'IM','PW'=>'PW','PZM'=>'PZM','VWL'=>'VWL','LA'=>'LA','LPN'=>'LPN','SP'=>'SP','UW'=>'UW','IMLA'=>'IMLA','ERM'=>'ERM','GTM'=>'GTM','IMMOB'=>'IMMOB','IMMOM'=>'IMMOM','URI'=>'URI','UF'=>'UF','WR'=>'WR','AUW'=>'AUW','AUM'=>'AUM','NPM'=>'NPM','NRM'=>'NRM','NSR'=>'NSR','CON'=>'CON');
$a_stg_abschl = array('keinen'=>'bitte auswählen', 'B.Sc.'=>'B.Sc.', 'B.A.'=>'B.A.', 'M.Sc'=>'M.Sc', 'M.A.'=>'M.A.', 'MBA'=>'MBA', 'B.Eng.'=>'B.Eng.', 'M.Eng.'=>'M.Eng.', 'LL.B.'=>'LL.B.', 'LL.M'=>'LL.M');
$a_fakultaet = array('0'=>'bitte ausw&auml;hlen','FWR'=>'FWR','FAVM'=>'FAVM','FLUS'=>'FLUS','FBF'=>'FBF');
$a_rsz = array('0'=>'bitte ausw&auml;hlen','3'=>'3','4'=>'4','6'=>'6','7'=>'7','8'=>'8');
$a_stip_dauer = array('1 Semester'=>'1 Semester','2 Semester'=>'2 Semester');

$a_moegl_bew_zeitraum = array(0=>'keiner', 1=>'1 Semester',2=>'2 Semester');
$a_status =  array(1=>'Bewerbung akzeptiert',0=>'Bewerbung abgelehnt',2=>'Ausschluss Quotenregelung');
$a_f_r_form = array('0'=>'bitte ausw&auml;hlen', 'Privatperson und Einzelunternehmen'=>'Privatperson und Einzelunternehmen','Personengesellschaft'=>'Personengesellschaft','Kapitalgesellschaft'=>'Kapitalgesellschaft','Sonstige juristische Person des privaten Rechts'=>'Sonstige juristische Person des privaten Rechts','Juristische Person des &ouml;ffentlichen Rechts'=>'Juristische Person des &ouml;ffentlichen Rechts');
$a_f_r_form_k = array('0'=>'00','03'=>'03','06'=>'06','09'=>'09', '12'=>'12','15'=>'15');
$a_s_fach = array('0'=>'bitte ausw&auml;hlen','003'=>'003', '013'=>'013', '021'=>'021', '134'=>'134', '175'=>'175', '182'=>'182', '458'=>'458', '042'=>'042');
$a_gruppe = array('0'=>'bitte ausw&auml;hlen','1'=>'1','2'=>'2','3'=>'3','4'=>'4');

$foed_id = getSponsors();

$accept = array('text/html','application/pdf','image/*','audio/mp3', 'video/*');
$allowfiles = array('/image/','/text/','/pdf/');
$ext = array('.jpeg','.jpg','.gif','.bmp','.png','.txt','.pdf','.ukn');
$maxfilesize = 104857600;

// variables empty
$fieldset_v = $fieldset0 = array('Record Info' => array('hidden' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'hidden',														
														'min'=> 0,
														'max'=> 1,
														'step'=> 1,
														'value'=> 0,
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
														'value'=> 0,
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
														'value'=> 0,
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
								'options'=> $country,
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
    			array(   		    			
//    			'rechtzeitiger Eingang' =>
//						array('select' =>
//							array(	'name'=>'r_eingang_bew',
//									'options'=> $a_yesno,
//									'class'=>'sel_w1',
//									'@#db' => 'VARCHAR( 4 ) NOT NULL',								
//							)
//						),
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
						'Status' =>
						array('select' =>
									array(	'name'=>'status',
											'options' => $a_status,											
											//'value' => $status,
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
																			//'value'=> $hzb,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),
															'Durchschnittsnote Kernkompetenzf&auml;cher' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'durchschn_kern',
																			//'value'=> $durchschn_kern,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			//'value'=> $note_bachelor_c,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
												'@#db' => 'VARCHAR( 60 ) NOT NULL DEFAULT 0',								
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
									'Semester zu Beginn des Vergabezeitraumes' =>
									array('input' =>
										array(	'type'=>'number',								
												'name'=>'sem',
												//'value'=> $sem,
												'min'=> 0,
												'max'=> 20,
												'step'=> 1,
												'class'=>'in_w1',
												'@#db' => 'TINYINT( 2 )  NULL',								
										)
									),
									'Hochschulsemester' =>
									array('input' =>
										array('type'=>'number',
											'name'=>'hs',
											//'value'=>$hs,
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
												'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
																			'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																	),
																),
															'Bonus Preise' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_preis',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
															'Berufst&auml;tigkeit/Berufsausbildung' =>  
																array(	'select' =>
																	array(	'name'=>'beruf_ausbildung',
																			'options'=> $a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																	),
																),
															'Bonus Berufst&auml;tigkeit/Berufsausbildung' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_beruf_ausbildung',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
															'Praktika' =>  
																array(	'select' =>
																	array(	'name'=>'praktika',
																			'options'=> $a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																	),
																),
															'Bonus Praktika' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_praktika',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
															'Gesellschaftliches/politisches Engagement' =>  
																array(	'select' =>
																	array(	'name'=>'ges_pol_eng',
																			'options'=> $a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																	),
																),
															'Bonus Gesellschaftliches/politisches Engagement' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_ges_pol_eng',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
															'Hochschulengagement' =>  
																array(	'select' =>
																	array(	'name'=>'hs_eng',
																			'options'=> $a_yesno,
																			'class'=>'sel_w1  m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																	),
																),													
															'Bonus Hochschulengagement' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_hs_eng',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
														),
// 7th fieldset
	'Besondere pers&ouml;nliche oder famili&auml;re Umst&auml;nde' => array('Betreuung eigener Kinder' =>  
																		array(	'select' =>
																				array(	'name'=>'kind',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Bonus Betreuung eigener Kinder' =>  
																			array(	'select' =>
																				array(	'name'=>'b_kind',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Krankheiten/Behinderungen' =>  
																			array(	'select' =>
																				array(	'name'=>'krankheit',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Bonus Krankheiten/Behinderungen' =>  
																			array(	'select' =>
																				array(	'name'=>'b_krankheit',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Mitarbeit im famili&auml;ren Betrieb' =>  
																			array(	'select' =>
																				array(	'name'=>'mitarbeit_betrieb',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Bonus Mitarbeit im famili&auml;ren Betrieb' =>  
																			array(	'select' =>
																				array(	'name'=>'b_mitarbeit_betrieb',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Betreuung pflegebed&uuml;rftiger naher Angeh&ouml;riger' =>  
																			array(	'select' =>
																				array(	'name'=>'pflege_anghoeriger',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Bonus Betreuung pflegebed&uuml;rftiger naher Angeh&ouml;riger' =>  
																			array(	'select' =>
																				array(	'name'=>'b_pflege_anghoeriger',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Famili&auml;rer Hintergrund' =>  
																			array(	'select' =>
																				array(	'name'=>'fam_hintergrund',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																				),
																			),					
																		'Bonus famili&auml;rer Hintergrund' =>  
																			array(	'select' =>
																				array(	'name'=>'b_fam_hintergrund',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Migrationshintergrund' =>  
																			array(	'select' =>
																				array(	'name'=>'migration',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
																		'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
																array(	'options' => $a_gruppe,						
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
																//'value'=> $bonus_verfahren,
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
																//'value'=> $note_rangliste,
																'min'=> 0,
																'max'=> 6,
																'step'=> 0.01,
																'class'=>'in_w1',
																'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
														),
													),
													'm&ouml;glicher Bewilligungszeitraum' =>  
															array(	'select' =>
																array(	'name'=>'moegl_bew_zeitraum',
																		'class'=>'sel_w1',
																		'options'=> $a_moegl_bew_zeitraum, 
																		'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0',								
																),
															),
													'Bewilligung?' =>  
															array(	'select' =>
																array(	'name'=>'bew',
																		'options'=> $a_yesno,
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
																		'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
													'options'=> array(0 =>'bitte ausw&auml;hlen',8=>'8'),
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
													'options'=> array(0 =>'bitte ausw&auml;hlen',6=>'6'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Hochschule' =>
										array(	'select' =>
											array(	'name'=>'s_hs',
													'options'=> array('0'=>'bitte ausw&auml;hlen','6791'=>'6791', '6792'=>'6792'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
											)
										),
										'Tr&auml;gerschaft der Hochschule' =>
										array(	'select' =>
											array(	'name'=>'s_traeger_hs',
													'options'=> array(0 =>'bitte ausw&auml;hlen',2=>'2'),
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
														//'value'=>0,													
														'@#db' => 'INT( 11 ) NOT NULL DEFAULT 0',							
												)
											),
										
									),
); // end array vars empty

$fieldset_spons = array('Record Info' => array('hidden' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'hidden',
														'min'=> 0,
														'max'=> 1,
														'step'=> 1,
														'value'=> 0,
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
														'value'=> 0,
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
														'value'=> 0,
														'class'=>'in_w1',
														'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0',								
												)
											),
										),
// 1-11th fieldset 
	'F&ouml;rderer: Stammdaten' => array('F&ouml;rderer/Firma ' =>  
															array(	'input' =>
																array(	'type' => 'text',
																		'name'=>'f_foerderer',
																		//'value'=> $f_foerderer,
																		'class'=>'in_w1',																		
																		'@#db' => 'VARCHAR( 255 ) NOT NULL',								
																),
															),
											'Rechtsform ' =>
															array('select' =>
																array(	'options' => $a_f_r_form,						
																		'name'=>'f_r_form',
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 80 )  NOT NULL'							
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
															'options'=> $a_sex2,
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
														'name'=>'f_email',
														//'value'=> $f_email,
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
															'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
														'@#db' => 'DECIMAL( 6,2 ) NOT NULL DEFAULT 0',								
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
																		'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
													'options'=> array(0=>'bitte ausw&auml;hlen',8=>'8'),
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
													'options'=> array(0=>'bitte ausw&auml;hlen',6=>'6'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Hochschule' =>
										array(	'select' =>
											array(	'name'=>'f_hs',
													'options'=> array(0=>'bitte ausw&auml;hlen','7691'=>'7691','7692'=>'7692'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
											)
										),
										'Tr&auml;gerschaft der Hochschule' =>
										array(	'select' =>
											array(	'name'=>'f_traeger_hs',
													'options'=> array(0=>'bitte ausw&auml;hlen',2=>'2'),
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
														//'step'=> 0.01,
														'class'=>'in_w1',
														'@#db' => 'DECIMAL( 8,2 ) NOT NULL DEFAULT 0',								
												)
											),
										'Gesamtsumme weitergegebene zweckgebundene Mittel im Berichtsjahr' =>
											array('input' =>
												array(	'type'=>'number',								
														'name'=>'f_gebundene_m',
														//'value'=> $f_gebundene_m,
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
														'default'=> '', //$comments,
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
); // end array sponsors





$fieldset_baseQuery = array( 'Pers&ouml;nliche Angaben' => array(
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
								'options'=> $country,
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
    			array(  		    			
//    			'rechtzeitiger Eingang' =>
//						array('select' =>
//							array(	'name'=>'r_eingang_bew',
//									'options'=> $a_yesno,
//									'class'=>'sel_w1',
//									'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
//							)
//						),
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
									'@#db' => 'VARCHAR( 4 ) NOT NULL  DEFAULT 0',								
							)
						),
						'Sonstiger Grund Ausschluss aus Verfahren' =>
						array('select' =>
							array(	'name'=>'ausschluss',
									'options'=> $a_yesno,
									'class'=>'sel_w1',
									'@#db' => 'VARCHAR( 4 ) NOT NULL  DEFAULT 0',								
							)
						),
						'Status' =>
						array('select' =>
									array(	'name'=>'status',
											'options' => $a_status,											
											//'value' => $status,
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
																			//'value'=> $hzb,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
																	),
																),
															'Durchschnittsnote Kernkompetenzf&auml;cher' =>
																array(	'input' =>
																	array(	'type'=>'number',
																			'name'=>'durchschn_kern',
																			//'value'=> $durchschn_kern,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			//'value'=> $note_bachelor_c,
																			'min'=> 1,
																			'max'=> 6,
																			'step'=> 0.01,
																			'class'=>'in_w1',
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			//'value'=> $ects_d,
																			'min'=> 0,
																			'max'=> 180,
																			'step'=> 1,
																			'class'=>'in_w1',
																			'@#db' => 'INT( 3 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'INT( 3 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'INT( 3 ) NOT NULL DEFAULT 0',
																	),
																),
															'Bemerkung ECTS-Abweichung' =>
																array(	'input' =>
																	array(	'type'=>'text',
																			'name'=>'ects_abw_bemer_d',
																			//'value'=> $ects_abw_bemer_d,
																			'class'=>'in_w1',																			
																			'@#db' => 'TEXT NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
																			'@#db' => 'DECIMAL( 3,2 ) NOT NULL DEFAULT 0',
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
												'@#db' => 'VARCHAR( 60 ) NOT NULL DEFAULT 0',								
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
												'@#db' => 'VARCHAR( 60 ) NOT NULL DEFAULT 0',								
										),
									),
									'Semester zu Beginn des Vergabezeitraumes' =>
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
									'Regelstudienzeit' =>  
									array(	'select' =>
										array(	'name'=>'rsz',
												'options'=> $a_rsz,
												'class'=>'sel_w1',
												'@#db' => 'TINYINT( 2 ) NOT NULL',								
										),
									),
									'Hochschulsemester' =>
									array('input' =>
										array('type'=>'number',
										      'name'=>'hs',
										      //'value'=>$hs,
										      'min'=> 0,
										      'max'=> 30,
										      'step'=> 1,
										      'class'=>'in_w1',
										      '@#db' => 'TINYINT( 2 ) NULL'
										      )
									),
									'Verl&auml;ngerung der F&ouml;rderungsh&ouml;chstdauer aus schwerwiegenden Gr&uuml;nden?' =>  
									array(	'select' =>
										array(	'name'=>'verl_dauer',
												'options'=> $a_yesno,
												'class'=>'sel_w1',
												'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
																			'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																	),
																),
															'Bonus Preise' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_preis',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
															'Berufst&auml;tigkeit/Berufsausbildung' =>  
																array(	'select' =>
																	array(	'name'=>'beruf_ausbildung',
																			'options'=> $a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																	),
																),
															'Bonus Berufst&auml;tigkeit/Berufsausbildung' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_beruf_ausbildung',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
															'Praktika' =>  
																array(	'select' =>
																	array(	'name'=>'praktika',
																			'options'=> $a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																	),
																),
															'Bonus Praktika' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_praktika',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
															'Gesellschaftliches/politisches Engagement' =>  
																array(	'select' =>
																	array(	'name'=>'ges_pol_eng',
																			'options'=> $a_yesno,
																			'class'=>'sel_w1 m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																	),
																),
															'Bonus Gesellschaftliches/politisches Engagement' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_ges_pol_eng',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
															'Hochschulengagement' =>  
																array(	'select' =>
																	array(	'name'=>'hs_eng',
																			'options'=> $a_yesno,
																			'class'=>'sel_w1  m',
																			'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																	),
																),													
															'Bonus Hochschulengagement' =>  
																array(	'select' =>
																	array(	'name'=>'bonus_hs_eng',
																			'options'=> $a_zerotwo,
																			'class'=>'sel_w1 r m',
																			'@#db' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',								
																	),
																),
														),
// 7th fieldset
	'Besondere pers&ouml;nliche oder famili&auml;re Umst&auml;nde' => array('Betreuung eigener Kinder' =>  
																		array(	'select' =>
																				array(	'name'=>'kind',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Bonus Betreuung eigener Kinder' =>  
																			array(	'select' =>
																				array(	'name'=>'b_kind',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Krankheiten/Behinderungen' =>  
																			array(	'select' =>
																				array(	'name'=>'krankheit',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Bonus Krankheiten/Behinderungen' =>  
																			array(	'select' =>
																				array(	'name'=>'b_krankheit',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Mitarbeit im famili&auml;ren Betrieb' =>  
																			array(	'select' =>
																				array(	'name'=>'mitarbeit_betrieb',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Bonus Mitarbeit im famili&auml;ren Betrieb' =>  
																			array(	'select' =>
																				array(	'name'=>'b_mitarbeit_betrieb',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Betreuung pflegebed&uuml;rftiger naher Angeh&ouml;riger' =>  
																			array(	'select' =>
																				array(	'name'=>'pflege_anghoeriger',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Bonus Betreuung pflegebed&uuml;rftiger naher Angeh&ouml;riger' =>  
																			array(	'select' =>
																				array(	'name'=>'b_pflege_anghoeriger',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Famili&auml;rer Hintergrund' =>  
																			array(	'select' =>
																				array(	'name'=>'fam_hintergrund',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1 m',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
																				),
																			),					
																		'Bonus famili&auml;rer Hintergrund' =>  
																			array(	'select' =>
																				array(	'name'=>'b_fam_hintergrund',
																						'options'=> $a_zerotwo,
																						'class'=>'sel_w1 r m',
																						'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
																				),
																			),
																		'Migrationshintergrund' =>  
																			array(	'select' =>
																				array(	'name'=>'migration',
																						'options'=> $a_yesno,
																						'class'=>'sel_w1',
																						'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
																		'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
																array(	'options' => $a_gruppe,						
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
																'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
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
																'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
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
																'@#db' => 'DECIMAL(3,2) NOT NULL DEFAULT 0',								
														),
													),
													'm&ouml;glicher Bewilligungszeitraum' =>  
															array(	'select' =>
																array(	'name'=>'moegl_bew_zeitraum',
																		'class'=>'sel_w1',
																		'options'=> $a_moegl_bew_zeitraum, 
																		'@#db' => 'TINYINT( 1 ) NOT NULL DEFAULT 0',								
																),
															),
													'Bewilligung?' =>  
															array(	'select' =>
																array(	'name'=>'bew',
																		'options'=> $a_yesno,
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
																		'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
													'options'=> array(0 =>'bitte ausw&auml;hlen',8=>'8'),
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
													'options'=> array(0 =>'bitte ausw&auml;hlen',6=>'6'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL',								
											)
										),
										'Hochschule' =>
										array(	'select' =>
											array(	'name'=>'s_hs',
													'options'=> array('0'=>'bitte ausw&auml;hlen','6791'=>'6791', '6792'=>'6792'),
													'class'=>'sel_w1',
													'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
											)
										),
										'Tr&auml;gerschaft der Hochschule' =>
										array(	'select' =>
											array(	'name'=>'s_traeger_hs',
													'options'=> array(0 =>'bitte ausw&auml;hlen',2=>'2'),
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
														'@#db' => 'TINYINT( 2 )  NOT NULL DEFAULT 0',								
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
														'@#db' => 'TINYINT( 4 ) NOT NULL DEFAULT 0',								
												)
											),
										'Angestrebte Abschlusspr&uuml;fung' =>
											array(	'select' =>
												array(	'name'=>'s_abschl',
														//'value'=> $s_abschl,
														'options'=> array('0'=>'bitte ausw&auml;hlen','184'=>'184','284'=>'284','390'=>'390'),
														'class'=>'sel_w1',
														'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
														'@#db' => 'TINYINT( 2 ) NOT NULL DEFAULT 0',								
												)
											),
										'Baf&ouml;G Bezug?' =>  
															array(	'select' =>
																array(	'name'=>'s_bafoeg',
																		'options'=> array('0'=>'bitte ausw&auml;hlen','1'=>'1','2'=>'2'),
																		'class'=>'sel_w1',
																		'@#db' => 'VARCHAR( 4 ) NOT NULL DEFAULT 0',								
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
														//'value'=>0,													
														'@#db' => 'INT( 11 ) NOT NULL DEFAULT 0',							
												)
											),
										
									),
); // end array 
?>

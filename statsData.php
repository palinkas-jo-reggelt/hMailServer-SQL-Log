<?php
$gaugeMaxC = 4600;
$gauge100C = 3680;
$gauge75C = 2760.00;
$gauge50C = 1840.0;
$gauge25C = 920.00;
$gaugeMaxR = 700;
$gauge100R = 560;
$gauge75R = 420.00;
$gauge50R = 280.0;
$gauge25R = 140.00;
$gaugeMaxM = 830;
$gauge100M = 664;
$gauge75M = 498.00;
$gauge50M = 332.0;
$gauge25M = 166.00;
$dataArrCPD = array("{x: '2023-08-22', y: 226}","{x: '2023-08-23', y: 304}","{x: '2023-08-24', y: 326}","{x: '2023-08-25', y: 292}","{x: '2023-08-26', y: 522}","{x: '2023-08-27', y: 563}","{x: '2023-08-28', y: 616}","{x: '2023-08-29', y: 279}","{x: '2023-08-30', y: 326}","{x: '2023-08-31', y: 250}","{x: '2023-09-01', y: 223}","{x: '2023-09-02', y: 261}","{x: '2023-09-03', y: 125}","{x: '2023-09-04', y: 132}","{x: '2023-09-05', y: 250}","{x: '2023-09-06', y: 299}","{x: '2023-09-07', y: 332}","{x: '2023-09-08', y: 291}","{x: '2023-09-09', y: 213}","{x: '2023-09-10', y: 233}","{x: '2023-09-11', y: 269}","{x: '2023-09-12', y: 275}","{x: '2023-09-13', y: 253}","{x: '2023-09-14', y: 255}","{x: '2023-09-15', y: 255}","{x: '2023-09-16', y: 228}","{x: '2023-09-17', y: 142}","{x: '2023-09-18', y: 22}","{x: '2023-09-21', y: 233}","{x: '2023-09-22', y: 229}","{x: '2023-09-23', y: 229}");
$hitsArrCPD = array(226,304,326,292,522,563,616,279,326,250,223,261,125,132,250,299,332,291,213,233,269,275,253,255,255,228,142,22,233,229,229);
$dateArrCPD = array('2023-08-22','2023-08-23','2023-08-24','2023-08-25','2023-08-26','2023-08-27','2023-08-28','2023-08-29','2023-08-30','2023-08-31','2023-09-01','2023-09-02','2023-09-03','2023-09-04','2023-09-05','2023-09-06','2023-09-07','2023-09-08','2023-09-09','2023-09-10','2023-09-11','2023-09-12','2023-09-13','2023-09-14','2023-09-15','2023-09-16','2023-09-17','2023-09-18','2023-09-21','2023-09-22','2023-09-23');
$iterArrCPD = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
$iCPD = 32;
$dataArrRPD = array("{x: '2023-08-22', y: 178}","{x: '2023-08-23', y: 252}","{x: '2023-08-24', y: 307}","{x: '2023-08-25', y: 283}","{x: '2023-08-26', y: 284}","{x: '2023-08-27', y: 512}","{x: '2023-08-28', y: 567}","{x: '2023-08-29', y: 235}","{x: '2023-08-30', y: 292}","{x: '2023-08-31', y: 237}","{x: '2023-09-01', y: 212}","{x: '2023-09-02', y: 200}","{x: '2023-09-03', y: 134}","{x: '2023-09-04', y: 123}","{x: '2023-09-05', y: 197}","{x: '2023-09-06', y: 262}","{x: '2023-09-07', y: 252}","{x: '2023-09-08', y: 235}","{x: '2023-09-09', y: 187}","{x: '2023-09-10', y: 200}","{x: '2023-09-11', y: 209}","{x: '2023-09-12', y: 202}","{x: '2023-09-13', y: 213}","{x: '2023-09-14', y: 201}","{x: '2023-09-15', y: 227}","{x: '2023-09-16', y: 156}","{x: '2023-09-17', y: 107}","{x: '2023-09-18', y: 21}","{x: '2023-09-21', y: 164}","{x: '2023-09-22', y: 172}","{x: '2023-09-23', y: 227}");
$hitsArrRPD = array(178,252,307,283,284,512,567,235,292,237,212,200,134,123,197,262,252,235,187,200,209,202,213,201,227,156,107,21,164,172,227);
$dateArrRPD = array('2023-08-22','2023-08-23','2023-08-24','2023-08-25','2023-08-26','2023-08-27','2023-08-28','2023-08-29','2023-08-30','2023-08-31','2023-09-01','2023-09-02','2023-09-03','2023-09-04','2023-09-05','2023-09-06','2023-09-07','2023-09-08','2023-09-09','2023-09-10','2023-09-11','2023-09-12','2023-09-13','2023-09-14','2023-09-15','2023-09-16','2023-09-17','2023-09-18','2023-09-21','2023-09-22','2023-09-23');
$iterArrRPD = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
$iRPD = 32;
$dataArrCPH = array(108,83,68,75,50,50,49,67,63,96,92,92,101,95,98,103,82,62,42,31,31,32,40,42);
$labelArrCPH = array('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
$dataArrRPH = array(9,10,9,9,9,9,8,10,8,9,10,10,12,12,13,12,11,11,12,10,12,8,9,9);
$labelArrRPH = array('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
$mapjson = "{AE: {link: 'ip.php?search=United+Arab+Emirates', acc: 111, rej: 108, ratio: 97},AF: {link: 'ip.php?search=Afghanistan', acc: 10, rej: 9, ratio: 90},AG: {link: 'ip.php?search=Antigua+and+Barbuda', acc: 3, rej: 3, ratio: 100},AL: {link: 'ip.php?search=Albania', acc: 26, rej: 25, ratio: 96},AM: {link: 'ip.php?search=Armenia', acc: 29, rej: 27, ratio: 93},AO: {link: 'ip.php?search=Angola', acc: 5, rej: 4, ratio: 80},AR: {link: 'ip.php?search=Argentina', acc: 270, rej: 247, ratio: 91},AT: {link: 'ip.php?search=Austria', acc: 52, rej: 38, ratio: 73},AU: {link: 'ip.php?search=Australia', acc: 111, rej: 86, ratio: 77},AZ: {link: 'ip.php?search=Azerbaijan', acc: 114, rej: 112, ratio: 98},BA: {link: 'ip.php?search=Bosnia+and+Herzegovina', acc: 20, rej: 19, ratio: 95},BD: {link: 'ip.php?search=Bangladesh', acc: 168, rej: 160, ratio: 95},BE: {link: 'ip.php?search=Belgium', acc: 37, rej: 30, ratio: 81},BF: {link: 'ip.php?search=Burkina+Faso', acc: 3, rej: 2, ratio: 67},BG: {link: 'ip.php?search=Bulgaria', acc: 222, rej: 217, ratio: 98},BH: {link: 'ip.php?search=Bahrain', acc: 6, rej: 6, ratio: 100},BI: {link: 'ip.php?search=Burundi', acc: 1, rej: 1, ratio: 100},BN: {link: 'ip.php?search=Brunei', acc: 9, rej: 9, ratio: 100},BO: {link: 'ip.php?search=Bolivia', acc: 41, rej: 39, ratio: 95},BR: {link: 'ip.php?search=Brazil', acc: 1613, rej: 1571, ratio: 97},BT: {link: 'ip.php?search=Bhutan', acc: 1, rej: 1, ratio: 100},BW: {link: 'ip.php?search=Botswana', acc: 2, rej: 1, ratio: 50},BY: {link: 'ip.php?search=Belarus', acc: 28, rej: 28, ratio: 100},CA: {link: 'ip.php?search=Canada', acc: 876, rej: 751, ratio: 86},CD: {link: 'ip.php?search=DR+Congo', acc: 4, rej: 3, ratio: 75},CG: {link: 'ip.php?search=Congo+Republic', acc: 6, rej: 6, ratio: 100},CH: {link: 'ip.php?search=Switzerland', acc: 195, rej: 110, ratio: 56},CI: {link: 'ip.php?search=Ivory+Coast', acc: 14, rej: 13, ratio: 93},CL: {link: 'ip.php?search=Chile', acc: 28, rej: 24, ratio: 86},CM: {link: 'ip.php?search=Cameroon', acc: 7, rej: 7, ratio: 100},CN: {link: 'ip.php?search=China', acc: 6294, rej: 6088, ratio: 97},CO: {link: 'ip.php?search=Colombia', acc: 239, rej: 220, ratio: 92},CR: {link: 'ip.php?search=Costa+Rica', acc: 17, rej: 15, ratio: 88},CU: {link: 'ip.php?search=Cuba', acc: 1, rej: 1, ratio: 100},CV: {link: 'ip.php?search=Cabo+Verde', acc: 13, rej: 13, ratio: 100},CY: {link: 'ip.php?search=Cyprus', acc: 4, rej: 4, ratio: 100},CZ: {link: 'ip.php?search=Czechia', acc: 334, rej: 167, ratio: 50},DE: {link: 'ip.php?search=Germany', acc: 3911, rej: 1825, ratio: 47},DK: {link: 'ip.php?search=Denmark', acc: 101, rej: 61, ratio: 60},DM: {link: 'ip.php?search=Dominica', acc: 1, rej: 1, ratio: 100},DO: {link: 'ip.php?search=Dominican+Republic', acc: 62, rej: 45, ratio: 73},DZ: {link: 'ip.php?search=Algeria', acc: 44, rej: 37, ratio: 84},EC: {link: 'ip.php?search=Ecuador', acc: 28, rej: 21, ratio: 75},EE: {link: 'ip.php?search=Estonia', acc: 21, rej: 23, ratio: 110},EG: {link: 'ip.php?search=Egypt', acc: 50, rej: 37, ratio: 74},ER: {link: 'ip.php?search=Eritrea', acc: 2, rej: 2, ratio: 100},ES: {link: 'ip.php?search=Spain', acc: 257, rej: 195, ratio: 76},ET: {link: 'ip.php?search=Ethiopia', acc: 36, rej: 34, ratio: 94},FI: {link: 'ip.php?search=Finland', acc: 2203, rej: 50, ratio: 2},FJ: {link: 'ip.php?search=Fiji', acc: 1, rej: 1, ratio: 100},FR: {link: 'ip.php?search=France', acc: 524, rej: 372, ratio: 71},GA: {link: 'ip.php?search=Gabon', acc: 8, rej: 8, ratio: 100},GB: {link: 'ip.php?search=United+Kingdom', acc: 2176, rej: 3573, ratio: 164},GE: {link: 'ip.php?search=Georgia', acc: 54, rej: 168, ratio: 311},GH: {link: 'ip.php?search=Ghana', acc: 67, rej: 64, ratio: 96},GL: {link: 'ip.php?search=Greenland', acc: 1, rej: 1, ratio: 100},GM: {link: 'ip.php?search=Gambia', acc: 2, rej: 1, ratio: 50},GP: {link: 'ip.php?search=Guadeloupe', acc: 1, rej: 1, ratio: 100},GQ: {link: 'ip.php?search=Equatorial+Guinea', acc: 4, rej: 4, ratio: 100},GR: {link: 'ip.php?search=Greece', acc: 54, rej: 40, ratio: 74},GT: {link: 'ip.php?search=Guatemala', acc: 13, rej: 13, ratio: 100},GY: {link: 'ip.php?search=Guyana', acc: 1, rej: 1, ratio: 100},HK: {link: 'ip.php?search=Hong+Kong', acc: 2149, rej: 2137, ratio: 99},HN: {link: 'ip.php?search=Honduras', acc: 15, rej: 14, ratio: 93},HR: {link: 'ip.php?search=Croatia', acc: 14, rej: 5, ratio: 36},HT: {link: 'ip.php?search=Haiti', acc: 1, rej: 1, ratio: 100},HU: {link: 'ip.php?search=Hungary', acc: 3180, rej: 22, ratio: 1},ID: {link: 'ip.php?search=Indonesia', acc: 762, rej: 727, ratio: 95},IE: {link: 'ip.php?search=Ireland', acc: 52, rej: 17, ratio: 33},IL: {link: 'ip.php?search=Israel', acc: 251, rej: 252, ratio: 100},IN: {link: 'ip.php?search=India', acc: 2734, rej: 2675, ratio: 98},IQ: {link: 'ip.php?search=Iraq', acc: 59, rej: 54, ratio: 92},IR: {link: 'ip.php?search=Iran', acc: 205, rej: 202, ratio: 99},IS: {link: 'ip.php?search=Iceland', acc: 9, rej: 9, ratio: 100},IT: {link: 'ip.php?search=Italy', acc: 374, rej: 265, ratio: 71},JM: {link: 'ip.php?search=Jamaica', acc: 14, rej: 13, ratio: 93},JO: {link: 'ip.php?search=Jordan', acc: 12, rej: 10, ratio: 83},JP: {link: 'ip.php?search=Japan', acc: 558, rej: 554, ratio: 99},KE: {link: 'ip.php?search=Kenya', acc: 57, rej: 54, ratio: 95},KG: {link: 'ip.php?search=Kyrgyzstan', acc: 41, rej: 41, ratio: 100},KH: {link: 'ip.php?search=Cambodia', acc: 61, rej: 57, ratio: 93},KR: {link: 'ip.php?search=South+Korea', acc: 2056, rej: 1605, ratio: 78},KW: {link: 'ip.php?search=Kuwait', acc: 32, rej: 30, ratio: 94},KY: {link: 'ip.php?search=Cayman+Islands', acc: 1, rej: 1, ratio: 100},KZ: {link: 'ip.php?search=Kazakhstan', acc: 301, rej: 298, ratio: 99},LA: {link: 'ip.php?search=Laos', acc: 25, rej: 22, ratio: 88},LB: {link: 'ip.php?search=Lebanon', acc: 22, rej: 21, ratio: 95},LC: {link: 'ip.php?search=Saint+Lucia', acc: 2, rej: 2, ratio: 100},LK: {link: 'ip.php?search=Sri+Lanka', acc: 14, rej: 12, ratio: 86},LS: {link: 'ip.php?search=Lesotho', acc: 2, rej: 2, ratio: 100},LT: {link: 'ip.php?search=Lithuania', acc: 64, rej: 64, ratio: 100},LU: {link: 'ip.php?search=Luxembourg', acc: 3, rej: 0, ratio: 0},LV: {link: 'ip.php?search=Latvia', acc: 258, rej: 258, ratio: 100},LY: {link: 'ip.php?search=Libya', acc: 5, rej: 5, ratio: 100},MA: {link: 'ip.php?search=Morocco', acc: 155, rej: 143, ratio: 92},MC: {link: 'ip.php?search=Monaco', acc: 134, rej: 220, ratio: 164},MD: {link: 'ip.php?search=Moldova', acc: 48, rej: 46, ratio: 96},ME: {link: 'ip.php?search=Montenegro', acc: 4, rej: 4, ratio: 100},MG: {link: 'ip.php?search=Madagascar', acc: 1, rej: 1, ratio: 100},MK: {link: 'ip.php?search=North+Macedonia', acc: 25, rej: 25, ratio: 100},ML: {link: 'ip.php?search=Mali', acc: 1, rej: 1, ratio: 100},MM: {link: 'ip.php?search=Myanmar', acc: 6, rej: 5, ratio: 83},MN: {link: 'ip.php?search=Mongolia', acc: 21, rej: 18, ratio: 86},MO: {link: 'ip.php?search=Macao', acc: 29, rej: 29, ratio: 100},MR: {link: 'ip.php?search=Mauritania', acc: 1, rej: 1, ratio: 100},MT: {link: 'ip.php?search=Malta', acc: 1, rej: 1, ratio: 100},MU: {link: 'ip.php?search=Mauritius', acc: 9, rej: 9, ratio: 100},MW: {link: 'ip.php?search=Malawi', acc: 3, rej: 2, ratio: 67},MX: {link: 'ip.php?search=Mexico', acc: 584, rej: 424, ratio: 73},MY: {link: 'ip.php?search=Malaysia', acc: 69, rej: 63, ratio: 91},MZ: {link: 'ip.php?search=Mozambique', acc: 13, rej: 13, ratio: 100},NA: {link: 'ip.php?search=Namibia', acc: 3, rej: 3, ratio: 100},NE: {link: 'ip.php?search=Niger', acc: 7, rej: 7, ratio: 100},NG: {link: 'ip.php?search=Nigeria', acc: 65, rej: 61, ratio: 94},NI: {link: 'ip.php?search=Nicaragua', acc: 15, rej: 14, ratio: 93},NL: {link: 'ip.php?search=Netherlands', acc: 3969, rej: 2784, ratio: 70},NO: {link: 'ip.php?search=Norway', acc: 17, rej: 15, ratio: 88},NP: {link: 'ip.php?search=Nepal', acc: 12, rej: 9, ratio: 75},NX: {link: 'ip.php?search=NOT+FOUND', acc: 184, rej: 52, ratio: 28},NZ: {link: 'ip.php?search=New+Zealand', acc: 15, rej: 11, ratio: 73},OM: {link: 'ip.php?search=Oman', acc: 6, rej: 5, ratio: 83},PA: {link: 'ip.php?search=Panama', acc: 134, rej: 228, ratio: 170},PE: {link: 'ip.php?search=Peru', acc: 176, rej: 171, ratio: 97},PH: {link: 'ip.php?search=Philippines', acc: 92, rej: 77, ratio: 84},PK: {link: 'ip.php?search=Pakistan', acc: 314, rej: 306, ratio: 97},PL: {link: 'ip.php?search=Poland', acc: 721, rej: 257, ratio: 36},PR: {link: 'ip.php?search=Puerto+Rico', acc: 5, rej: 5, ratio: 100},PS: {link: 'ip.php?search=Palestine', acc: 14, rej: 12, ratio: 86},PT: {link: 'ip.php?search=Portugal', acc: 189, rej: 143, ratio: 76},PW: {link: 'ip.php?search=Palau', acc: 1, rej: 1, ratio: 100},PY: {link: 'ip.php?search=Paraguay', acc: 11, rej: 8, ratio: 73},QA: {link: 'ip.php?search=Qatar', acc: 10, rej: 7, ratio: 70},RE: {link: 'ip.php?search=R%c3%a9union', acc: 2, rej: 2, ratio: 100},RO: {link: 'ip.php?search=Romania', acc: 284, rej: 157, ratio: 55},RS: {link: 'ip.php?search=Serbia', acc: 124, rej: 69, ratio: 56},RU: {link: 'ip.php?search=Russia', acc: 2133, rej: 2137, ratio: 100},RW: {link: 'ip.php?search=Rwanda', acc: 5, rej: 5, ratio: 100},SA: {link: 'ip.php?search=Saudi+Arabia', acc: 66, rej: 61, ratio: 92},SC: {link: 'ip.php?search=Seychelles', acc: 17, rej: 20, ratio: 118},SD: {link: 'ip.php?search=Sudan', acc: 36, rej: 36, ratio: 100},SE: {link: 'ip.php?search=Sweden', acc: 284, rej: 209, ratio: 74},SG: {link: 'ip.php?search=Singapore', acc: 1159, rej: 1143, ratio: 99},SI: {link: 'ip.php?search=Slovenia', acc: 2, rej: 2, ratio: 100},SK: {link: 'ip.php?search=Slovakia', acc: 32, rej: 20, ratio: 63},SL: {link: 'ip.php?search=Sierra+Leone', acc: 3, rej: 3, ratio: 100},SN: {link: 'ip.php?search=Senegal', acc: 20, rej: 20, ratio: 100},SO: {link: 'ip.php?search=Somalia', acc: 3, rej: 3, ratio: 100},ST: {link: 'ip.php?search=S%c3%a3o+Tom%c3%a9+and+Pr%c3%adncipe', acc: 1, rej: 1, ratio: 100},SV: {link: 'ip.php?search=El+Salvador', acc: 4, rej: 4, ratio: 100},SY: {link: 'ip.php?search=Syria', acc: 2, rej: 2, ratio: 100},SZ: {link: 'ip.php?search=Eswatini', acc: 8, rej: 7, ratio: 88},TF: {link: 'ip.php?search=French+Southern+Territories', acc: 1, rej: 7, ratio: 700},TG: {link: 'ip.php?search=Togo', acc: 8, rej: 8, ratio: 100},TH: {link: 'ip.php?search=Thailand', acc: 2886, rej: 2843, ratio: 99},TJ: {link: 'ip.php?search=Tajikistan', acc: 34, rej: 33, ratio: 97},TN: {link: 'ip.php?search=Tunisia', acc: 57, rej: 57, ratio: 100},TR: {link: 'ip.php?search=Turkey', acc: 496, rej: 511, ratio: 103},TT: {link: 'ip.php?search=Trinidad+and+Tobago', acc: 15, rej: 15, ratio: 100},TW: {link: 'ip.php?search=Taiwan', acc: 727, rej: 716, ratio: 98},TZ: {link: 'ip.php?search=Tanzania', acc: 13, rej: 13, ratio: 100},UA: {link: 'ip.php?search=Ukraine', acc: 262, rej: 222, ratio: 85},UG: {link: 'ip.php?search=Uganda', acc: 65, rej: 64, ratio: 98},US: {link: 'ip.php?search=United+States', acc: 79882, rej: 16489, ratio: 21},UY: {link: 'ip.php?search=Uruguay', acc: 24, rej: 24, ratio: 100},UZ: {link: 'ip.php?search=Uzbekistan', acc: 176, rej: 175, ratio: 99},VE: {link: 'ip.php?search=Venezuela', acc: 88, rej: 62, ratio: 70},VI: {link: 'ip.php?search=U.S.+Virgin+Islands', acc: 2, rej: 2, ratio: 100},VN: {link: 'ip.php?search=Vietnam', acc: 1820, rej: 1762, ratio: 97},VU: {link: 'ip.php?search=Vanuatu', acc: 3, rej: 3, ratio: 100},XK: {link: 'ip.php?search=Kosovo', acc: 20, rej: 20, ratio: 100},YE: {link: 'ip.php?search=Yemen', acc: 7, rej: 3, ratio: 43},ZA: {link: 'ip.php?search=South+Africa', acc: 125, rej: 119, ratio: 95},ZM: {link: 'ip.php?search=Zambia', acc: 13, rej: 12, ratio: 92},ZW: {link: 'ip.php?search=Zimbabwe', acc: 8, rej: 7, ratio: 88}}";
$EnvFromRows = "
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=notify%40rgb-nyc.com'>notify@rgb-nyc.com</a></div>
					<div class='div-table-col right' data-column='Count'>771</div>
					<div class='div-table-col right' data-column='Percent'>27.6%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=notify%40wap.dynu.net'>notify@wap.dynu.net</a></div>
					<div class='div-table-col right' data-column='Count'>129</div>
					<div class='div-table-col right' data-column='Percent'>4.6%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=VZWMail%40ecrmemail.verizonwireless.com'>VZWMail@ecrmemail.verizonwireless.com</a></div>
					<div class='div-table-col right' data-column='Count'>46</div>
					<div class='div-table-col right' data-column='Percent'>1.6%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=noreply-dmarc-support%40google.com'>noreply-dmarc-support@google.com</a></div>
					<div class='div-table-col right' data-column='Count'>42</div>
					<div class='div-table-col right' data-column='Percent'>1.5%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=brian%40pinneo.us'>brian@pinneo.us</a></div>
					<div class='div-table-col right' data-column='Count'>42</div>
					<div class='div-table-col right' data-column='Percent'>1.5%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=bounces%2b1863534-2a37-eddie%3dpinneo.us%40sendgrid.servicetitan.com'>bounces+1863534-2a37-eddie=pinneo.us@sendgrid.servicetitan.com</a></div>
					<div class='div-table-col right' data-column='Count'>38</div>
					<div class='div-table-col right' data-column='Percent'>1.4%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=ccnyc2002%40gmail.com'>ccnyc2002@gmail.com</a></div>
					<div class='div-table-col right' data-column='Count'>32</div>
					<div class='div-table-col right' data-column='Percent'>1.1%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=bounces%2b2693180-9488-csilla%3dpinneo.us%40m.dripemail2.com'>bounces+2693180-9488-csilla=pinneo.us@m.dripemail2.com</a></div>
					<div class='div-table-col right' data-column='Count'>28</div>
					<div class='div-table-col right' data-column='Percent'>1.0%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=familysafety%40microsoft.com'>familysafety@microsoft.com</a></div>
					<div class='div-table-col right' data-column='Count'>26</div>
					<div class='div-table-col right' data-column='Percent'>0.9%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=apcupsd-users-bounces%40lists.sourceforge.net'>apcupsd-users-bounces@lists.sourceforge.net</a></div>
					<div class='div-table-col right' data-column='Count'>24</div>
					<div class='div-table-col right' data-column='Percent'>0.9%</div>
				</div>";
$EnvToRows = "
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=bpinneo%40q-rep.com'>bpinneo@q-rep.com</a></div>
					<div class='div-table-col right' data-column='Count'>772</div>
					<div class='div-table-col right' data-column='Percent'>27.7%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=eddie%40pinneo.us'>eddie@pinneo.us</a></div>
					<div class='div-table-col right' data-column='Count'>309</div>
					<div class='div-table-col right' data-column='Percent'>11.1%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=brian%40pinneo.us'>brian@pinneo.us</a></div>
					<div class='div-table-col right' data-column='Count'>306</div>
					<div class='div-table-col right' data-column='Percent'>11.0%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=admin%40wap.dynu.net'>admin@wap.dynu.net</a></div>
					<div class='div-table-col right' data-column='Count'>301</div>
					<div class='div-table-col right' data-column='Percent'>10.8%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=ed%40pinneo.us'>ed@pinneo.us</a></div>
					<div class='div-table-col right' data-column='Count'>240</div>
					<div class='div-table-col right' data-column='Percent'>8.6%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=barb%40pinneo.us'>barb@pinneo.us</a></div>
					<div class='div-table-col right' data-column='Count'>171</div>
					<div class='div-table-col right' data-column='Percent'>6.1%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=zoe%40pinneo.us'>zoe@pinneo.us</a></div>
					<div class='div-table-col right' data-column='Count'>164</div>
					<div class='div-table-col right' data-column='Percent'>5.9%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=csilla%40pinneo.us'>csilla@pinneo.us</a></div>
					<div class='div-table-col right' data-column='Count'>164</div>
					<div class='div-table-col right' data-column='Percent'>5.9%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=daniela%40pinneo.us'>daniela@pinneo.us</a></div>
					<div class='div-table-col right' data-column='Count'>65</div>
					<div class='div-table-col right' data-column='Percent'>2.3%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=dmarc%40wap.dynu.net'>dmarc@wap.dynu.net</a></div>
					<div class='div-table-col right' data-column='Count'>53</div>
					<div class='div-table-col right' data-column='Percent'>1.9%</div>
				</div>";
$ReasonAccRows = "
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Auto-Whitelisted'>Auto-Whitelisted</a></div>
					<div class='div-table-col right' data-column='Count'>14</div>
					<div class='div-table-col right' data-column='Percent'>0.0%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Client_Authenticated'>Client_Authenticated</a></div>
					<div class='div-table-col right' data-column='Count'>148</div>
					<div class='div-table-col right' data-column='Percent'>0.3%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Client_Connection'>Client_Connection</a></div>
					<div class='div-table-col right' data-column='Count'>48,679</div>
					<div class='div-table-col right' data-column='Percent'>86.6%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Incoming_Message'>Incoming_Message</a></div>
					<div class='div-table-col right' data-column='Count'>936</div>
					<div class='div-table-col right' data-column='Percent'>1.7%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=IP_RBL_Whitelisted'>IP_RBL_Whitelisted</a></div>
					<div class='div-table-col right' data-column='Count'>777</div>
					<div class='div-table-col right' data-column='Percent'>1.4%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=IP_Reserved'>IP_Reserved</a></div>
					<div class='div-table-col right' data-column='Count'>1</div>
					<div class='div-table-col right' data-column='Percent'>0.0%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Record_PTR-HELO'>Record_PTR-HELO</a></div>
					<div class='div-table-col right' data-column='Count'>5,382</div>
					<div class='div-table-col right' data-column='Percent'>9.6%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=WL-HELO'>WL-HELO</a></div>
					<div class='div-table-col right' data-column='Count'>164</div>
					<div class='div-table-col right' data-column='Percent'>0.3%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=WL-PTR'>WL-PTR</a></div>
					<div class='div-table-col right' data-column='Count'>109</div>
					<div class='div-table-col right' data-column='Percent'>0.2%</div>
				</div>";
$ReasonRejRows = "
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=AbuseIPDB'>AbuseIPDB</a></div>
					<div class='div-table-col right' data-column='Count'>68</div>
					<div class='div-table-col right' data-column='Percent'>0.1%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Blacklist_Envelope_From'>Blacklist_Envelope_From</a></div>
					<div class='div-table-col right' data-column='Count'>7</div>
					<div class='div-table-col right' data-column='Percent'>0.0%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Blacklist_Subject'>Blacklist_Subject</a></div>
					<div class='div-table-col right' data-column='Count'>3</div>
					<div class='div-table-col right' data-column='Percent'>0.0%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=CatchSpam'>CatchSpam</a></div>
					<div class='div-table-col right' data-column='Count'>303</div>
					<div class='div-table-col right' data-column='Percent'>0.5%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=CatchSpam_Record'>CatchSpam_Record</a></div>
					<div class='div-table-col right' data-column='Count'>79</div>
					<div class='div-table-col right' data-column='Percent'>0.1%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Dynamic_PTR'>Dynamic_PTR</a></div>
					<div class='div-table-col right' data-column='Count'>98</div>
					<div class='div-table-col right' data-column='Percent'>0.2%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=GeoIP'>GeoIP</a></div>
					<div class='div-table-col right' data-column='Count'>4,138</div>
					<div class='div-table-col right' data-column='Percent'>7.4%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Msg_Deleted_As_SPAM'>Msg_Deleted_As_SPAM</a></div>
					<div class='div-table-col right' data-column='Count'>130</div>
					<div class='div-table-col right' data-column='Percent'>0.2%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Msg_To_SPAM_Folder'>Msg_To_SPAM_Folder</a></div>
					<div class='div-table-col right' data-column='Count'>3</div>
					<div class='div-table-col right' data-column='Percent'>0.0%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=No-PTR'>No-PTR</a></div>
					<div class='div-table-col right' data-column='Count'>838</div>
					<div class='div-table-col right' data-column='Percent'>1.5%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Rejected_HELO'>Rejected_HELO</a></div>
					<div class='div-table-col right' data-column='Count'>473</div>
					<div class='div-table-col right' data-column='Percent'>0.8%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Rejected_IP_Range'>Rejected_IP_Range</a></div>
					<div class='div-table-col right' data-column='Count'>23</div>
					<div class='div-table-col right' data-column='Percent'>0.0%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Residential_IP'>Residential_IP</a></div>
					<div class='div-table-col right' data-column='Count'>5</div>
					<div class='div-table-col right' data-column='Percent'>0.0%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Spamhaus'>Spamhaus</a></div>
					<div class='div-table-col right' data-column='Count'>819</div>
					<div class='div-table-col right' data-column='Percent'>1.5%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Spamhaus_DBL_on_HELO'>Spamhaus_DBL_on_HELO</a></div>
					<div class='div-table-col right' data-column='Count'>103</div>
					<div class='div-table-col right' data-column='Percent'>0.2%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=Spamhaus_DBL_on_PTR'>Spamhaus_DBL_on_PTR</a></div>
					<div class='div-table-col right' data-column='Count'>12</div>
					<div class='div-table-col right' data-column='Percent'>0.0%</div>
				</div>";
$TopTenIPsRows = "
				<div class='div-table-row'>
					<div class='div-table-col' data-column='IP'><a href='./data.php?search=127.0.0.1'>127.0.0.1</a></div>
					<div class='div-table-col' data-column='Country'>LOCAL</div>
					<div class='div-table-col center' data-column='Hits'>1,633,139</div>
					<div class='div-table-col center' data-column='Percent'>89.6%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='IP'><a href='./data.php?search=185.225.74.98'>185.225.74.98</a></div>
					<div class='div-table-col' data-column='Country'>United States</div>
					<div class='div-table-col center' data-column='Hits'>5,380</div>
					<div class='div-table-col center' data-column='Percent'>0.3%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='IP'><a href='./data.php?search=89.133.250.151'>89.133.250.151</a></div>
					<div class='div-table-col' data-column='Country'>Hungary</div>
					<div class='div-table-col center' data-column='Hits'>2,923</div>
					<div class='div-table-col center' data-column='Percent'>0.2%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='IP'><a href='./data.php?search=128.245.243.105'>128.245.243.105</a></div>
					<div class='div-table-col' data-column='Country'>United States</div>
					<div class='div-table-col center' data-column='Hits'>2,847</div>
					<div class='div-table-col center' data-column='Percent'>0.2%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='IP'><a href='./data.php?search=3.227.148.255'>3.227.148.255</a></div>
					<div class='div-table-col' data-column='Country'>United States</div>
					<div class='div-table-col center' data-column='Hits'>2,300</div>
					<div class='div-table-col center' data-column='Percent'>0.1%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='IP'><a href='./data.php?search=95.216.194.37'>95.216.194.37</a></div>
					<div class='div-table-col' data-column='Country'>Finland</div>
					<div class='div-table-col center' data-column='Hits'>2,129</div>
					<div class='div-table-col center' data-column='Percent'>0.1%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='IP'><a href='./data.php?search=13.110.232.28'>13.110.232.28</a></div>
					<div class='div-table-col' data-column='Country'>United States</div>
					<div class='div-table-col center' data-column='Hits'>2,011</div>
					<div class='div-table-col center' data-column='Percent'>0.1%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='IP'><a href='./data.php?search=75.243.158.157'>75.243.158.157</a></div>
					<div class='div-table-col' data-column='Country'>United States</div>
					<div class='div-table-col center' data-column='Hits'>1,587</div>
					<div class='div-table-col center' data-column='Percent'>0.1%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='IP'><a href='./data.php?search=75.192.255.199'>75.192.255.199</a></div>
					<div class='div-table-col' data-column='Country'>United States</div>
					<div class='div-table-col center' data-column='Hits'>1,537</div>
					<div class='div-table-col center' data-column='Percent'>0.1%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='IP'><a href='./data.php?search=103.120.242.156'>103.120.242.156</a></div>
					<div class='div-table-col' data-column='Country'>Vietnam</div>
					<div class='div-table-col center' data-column='Hits'>1,292</div>
					<div class='div-table-col center' data-column='Percent'>0.1%</div>
				</div>";
$TopTenCountriesRows = "
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Country'><a href='./data.php?search=LOCAL'>LOCAL</a></div>
					<div class='div-table-col center' data-column='Hits'>1,633,139</div>
					<div class='div-table-col center' data-column='Percent'>89.6%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Country'><a href='./data.php?search=United+States'>United States</a></div>
					<div class='div-table-col center' data-column='Hits'>96,371</div>
					<div class='div-table-col center' data-column='Percent'>5.3%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Country'><a href='./data.php?search=China'>China</a></div>
					<div class='div-table-col center' data-column='Hits'>12,382</div>
					<div class='div-table-col center' data-column='Percent'>0.7%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Country'><a href='./data.php?search=Netherlands'>Netherlands</a></div>
					<div class='div-table-col center' data-column='Hits'>6,753</div>
					<div class='div-table-col center' data-column='Percent'>0.4%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Country'><a href='./data.php?search=United+Kingdom'>United Kingdom</a></div>
					<div class='div-table-col center' data-column='Hits'>5,749</div>
					<div class='div-table-col center' data-column='Percent'>0.3%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Country'><a href='./data.php?search=Germany'>Germany</a></div>
					<div class='div-table-col center' data-column='Hits'>5,736</div>
					<div class='div-table-col center' data-column='Percent'>0.3%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Country'><a href='./data.php?search=Thailand'>Thailand</a></div>
					<div class='div-table-col center' data-column='Hits'>5,729</div>
					<div class='div-table-col center' data-column='Percent'>0.3%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Country'><a href='./data.php?search=India'>India</a></div>
					<div class='div-table-col center' data-column='Hits'>5,409</div>
					<div class='div-table-col center' data-column='Percent'>0.3%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Country'><a href='./data.php?search=Hong+Kong'>Hong Kong</a></div>
					<div class='div-table-col center' data-column='Hits'>4,286</div>
					<div class='div-table-col center' data-column='Percent'>0.2%</div>
				</div>
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Country'><a href='./data.php?search=Russia'>Russia</a></div>
					<div class='div-table-col center' data-column='Hits'>4,270</div>
					<div class='div-table-col center' data-column='Percent'>0.2%</div>
				</div>";
?>

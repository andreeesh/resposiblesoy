<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);
ini_set('display_errors', 1); // change to 1 for debugging
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
date_default_timezone_set('Europe/Belgrade');
date_default_timezone_set('UTC');
include_once("./connect3.php");

if (!empty($_GET['year'])) {
    // Year given?
    $_GET['year'] = str_replace(".", "", $_GET['year']);
    $_GET['year'] = str_replace(",", "", $_GET['year']);
    $_GET['year'] = strip_tags($_GET['year']);

	if ((!is_numeric($_GET['year'])) or (!preg_match("![0-9]!s", $_GET['year'])) or (strlen($_GET['year']) !== 4) or (intval($_GET['year']) > intval(date("Y"))))
	{
		$_GET['year'] = date("Y"); 
	}
} else {
    $_GET['year'] = date("Y"); 
}

// welke tab selected?
$tab_selected = ' id="tab' . substr($_GET['year'], -1) . '"';

// Certified producers
if ($query = sqlsrv_query($connection, "
SELECT
	COMP.companyunit_name AS Company_Name,
	COMP.companyunit_country_v AS Country, 
	ISNULL(ISNULL(certnumber.AUDIT_CERT_NR_NEW,certnumber.AUDIT_CERT_NR_OLD),CERTIFICATE.certificate_number) AS CERT_Number,
	CERTIFICATE.certificate_date_start AS Datum, 
	MAX(ISNULL(certnumber.AUDIT_HECTARE,TonsLastYear.AUDIT_HECTARE)) AS Hectare,
	MAX(Volume) AS Volume,
	CERTIFICATE_STATUS,
	MAX(CRTF_withdrawal_date) With_date
FROM 
	(SELECT 
		VOL1.CompanyUnit_key, 
		VOL1.certificate_key,
		audit_key,
		year, 
		SUM(Certified_hectare) hectare,
		MAX(volume) volume 
	FROM 
		SA_DWH_MW3_VOLUMES VOL1
	WHERE 
		year = ".intval($_GET['year'])." 
	GROUP BY 
		VOL1.CompanyUnit_key, VOL1.certificate_key,audit_key,year) VOLUMES 
	INNER JOIN (SELECT * FROM SA_DWH_DIM_CERTIFICATE) CERTIFICATE ON VOLUMES.certificate_key = CERTIFICATE.certificate_key
	LEFT OUTER JOIN SA_DWH_DIM_AUDIT AUDIT ON VOLUMES.audit_key = AUDIT.AUDIT_key
	INNER JOIN SA_DWH_DIM_COMPANYUNIT COMP ON VOLUMES.CompanyUnit_key = COMP.companyunit_key
	LEFT OUTER JOIN SA_DWH_DIM_AUDIT certnumber ON certnumber.AUDIT_YEAR_CERT_DATE = ".intval($_GET['year'])." AND certnumber.AUDIT_CMPU_id_owner = COMP.CMPU_id
	LEFT OUTER JOIN SA_DWH_DIM_AUDIT TonsLastYear ON TonsLastYear.AUDIT_YEAR_CERT_DATE = ".intval($_GET['year'])." - 1 AND TonsLastYear.AUDIT_CMPU_id_owner = COMP.CMPU_id
WHERE 
	COMP.companyunit_type IN ('Producer', 'P&C')
AND 
	Volume > 0 
AND 
	ISNULL(certnumber.AUDIT_HECTARE, TonsLastYear.AUDIT_HECTARE) > 0
AND
	YEAR =  ".intval($_GET['year'])."
AND 
	CRTF_withdrawal_date IS NULL OR YEAR(CRTF_withdrawal_date) >= ".intval($_GET['year'])."    
GROUP BY
	companyunit_name,
	companyunit_country_v,
	CERTIFICATE.certificate_number,
	certnumber.AUDIT_CERT_NR_NEW,
	certnumber.AUDIT_CERT_NR_OLD,
	certificate_date_start,
	CERTIFICATE_STATUS
HAVING MAX(ISNULL(certnumber.AUDIT_HECTARE, TonsLastYear.AUDIT_HECTARE)) IS NOT NULL 
ORDER BY
	Company_Name
	   
")) 
{
	echo '<html>
	<head>
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Alegreya+Sans:300,400,500,700,800" type="text/css" />
	<link rel="stylesheet" href="'.$url.'style.css" type="text/css" />
	</head>
	<body'.$tab_selected.'>
	<ul id="tabnav">';

    for ($i = 1; $i <= intval(substr(date("Y"), -1)); $i++) {
        echo '<li class="tab'.$i.'"><a href="'.$url.'certifiedproducers_TEST.php?year=201'.$i.'">201'.$i.'</a></li>';
    }

	echo '</ul>';

	echo '<table cellpadding="0" cellspacing="0" width="530px" style="width:530px;">
		<tr class="kop" width="530px" style="width:530px;">
			<td style="width:180px;" colspan="2" align="center">Company</td>
			<td style="width:250px;" colspan="3" align="center">Certificate</td>
			<td style="width:100px;" colspan="2" align="center">Facts</td>
		</tr>
		<tr class="kop" width="530px" style="width:530px;">
			<td style="width:100px;" align="left">Name</td>
			<td style="width:80px;" align="left">Country</td>
			<td style="width:80px;" align="left">Start</td>
			<td style="width:170px;word-wrap:break-word;" align="left">Number</td>
			<td style="width:60px;word-wrap:break-word;" align="left">Current status</td>
			<td style="width:50px;" align="right">Hectares</td>
			<td style="width:50px;" align="right">Tons</td>
		</tr>';
	
	$total_hectares = "0";	
	$total_tons = "0";
	while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC) ) 
	{
		$hectare_mooi = number_format($row["Hectare"], 0, '.', ','); // 55,30, afronden 2 getallen plus komma
	$volume_mooi = number_format($row["Volume"], 0, '.', ','); // 55,30, afronden 2 getallen plus komma

	$korte_datum = strtotime($row["Datum"]);
	$kortedatum = date('Y-m-d', $korte_datum);

	echo '<tr class="sub" width="530px" style="width:530px;">
			<td style="width:100px;" align="left">'.htmlentities($row["Company_Name"]).'</td>
			<td style="width:80px;" align="left">'.$row["Country"].'</td>
			<td style="width:80px;" align="left">'.$kortedatum.'</td>
			<td style="width:170px;word-wrap:break-word;" align="left">
			<div style="width:170px;overflow:auto">'.$row["CERT_Number"].'</div>
			</td>
			<td style="width:170px;word-wrap:break-word;" align="left">
			<div style="width:60px;overflow:auto">'.$row["CERTIFICATE_STATUS"].'</div>
			</td>		
			<td style="width:50px;font-weight:bold;" align="right">'.$hectare_mooi.'</td>
			<td style="width:50px;font-weight:bold;" align="right">'.$volume_mooi.'</td>
		</tr>';
	$total_hectares = $total_hectares + $row["Hectare"];	
	$total_tons = $total_tons + $row["Volume"];
	}
	$total_hectares = number_format($total_hectares, 0, '.', ','); // 55,30, afronden 2 getallen plus komma
	$total_tons = number_format($total_tons, 0, '.', ','); // 55,30, afronden 2 getallen plus komma
	echo '<tr class="kop">
	<td colspan="5" align="left">Total</td>
	<td colspan="1" style="font-weight:bold;" align="right">'.$total_hectares.'</td>
	<td colspan="1" style="font-weight:bold;" align="right">'.$total_tons.'</td>';
} else {
	echo 'At this moment it\'s not possible to show the requested information. Sorry for any inconvenience.';
	die( print_r( sqlsrv_errors(), true));
}
echo '</tr></table></body></html>';

?>
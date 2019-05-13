<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
date_default_timezone_set('Europe/Belgrade');
include_once("./connect3.php");

// Certified Companies on Choc
if ($query = sqlsrv_query($connection, "SELECT
 companyunit_name as CMPU_name,
 companyunit_country_p as T_COUN_name,
 certificate_date_start as datum,
 certificate_date_end,
 cert.certificate_since,
 cert.certificate_number as CRTF_certificate_number,
 cert.certificate_type as T_CERT_external_key,
 certified_volume,
 certified_hectares,
 certificate_status
FROM
 SA_DWH_MW1_CERTIFICATION MW
 INNER JOIN SA_DWH_DIM_CERTIFICATE CERT ON MW.certificate_key = CERT.certificate_key
 INNER JOIN SA_DWH_DIM_COMPANYUNIT COMP ON COMP.companyunit_key = MW.companyunit_key
WHERE  cert.certificate_type = 'CoC Soy'
AND companyunit_type <> 'Certification Body' 
AND certificate_date_start <= GETDATE() AND (certificate_date_end is null or certificate_date_end > GETDATE())
ORDER BY CMPU_name asc")) 
{
	echo '<html>
	<head>
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Alegreya+Sans:300,400,500,700,800" type="text/css" />
	<link rel="stylesheet" href="'.$url.'style.css" type="text/css" />
	</head>
	<body>';
	//echo "<strong>Certified Companies on Chain Of Custody</strong>";
	echo '<table cellpadding="0" cellspacing="0" width = "90%">
		<tr class="kop">
			<td style="width:40%;" colspan = "2" align = "center">Company</td>
			<td style="width:40%;" colspan = "3" align = "center">Certificate</td>
		</tr>
		<tr class="kop">
			<td style="width:20%;" align = "left">Name</td>
			<td style="width:12%;" align = "left">Country</td>
			<td style="width:16%;" align = "left">Start</td>
			<td style="width:22%;" align = "left">Number</td>
			<td style="width:22%;" align = "left">Certificate status</td>
		</tr>';
					
	while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC) ) 
	{
	
	$korte_datum = strtotime($row["datum"]);
	$kortedatum = date('Y-m-d', $korte_datum);
	echo '<tr class="sub">
			<td style="width:20%;" align = "left">'.htmlentities($row["CMPU_name"]).'</td>
			<td style="width:12%;" align = "left">'.$row["T_COUN_name"].'</td>
			<td style="width:16%;" align = "left">'.$kortedatum.'</td>
			<td style="width:33%;" align = "left">'.$row["CRTF_certificate_number"].'</td>
			<td style="width:33%;" align = "left">'.$row["certificate_status"].'</td>
		</tr>';
	}
} else {
	echo 'At this moment it\'s not possible to show the requested information. Sorry for any inconvenience.';
}
echo '</tr></table></body></html>';

?>
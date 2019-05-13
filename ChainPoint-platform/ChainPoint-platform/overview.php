<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
date_default_timezone_set('Europe/Belgrade');
include_once("./connect.php");

// Market overview
if ($query = sqlsrv_query($connection, "SELECT 0 as id,
	'Market Trade volume 2011' as captionfield,
	ISNULL(SUM(TRNSA.TRNSA_quantity), 0) as value
FROM TRNSA_transaction TRNSA INNER JOIN
		(SELECT TRNSH_TRNSA_id, TRNSH_date_entered
		 from TRNSH_transaction_history
		 where TRNSH_actual_yn = 1) trnsh ON trnsh_trnsa_id = trnsa_id
WHERE  TRNSA.TRNSA_TRNAT_id = 1 and
		TRNSH_date_entered > '20101231' and TRNSH_date_entered < '20120101'

UNION

SELECT 1 as id,
	'Market Trade volume 2012' as captionfield,
	ISNULL(SUM(TRNSA.TRNSA_quantity), 0) as value
FROM (SELECT 0 AS COL1) DT LEFT OUTER JOIN
		TRNSA_transaction TRNSA ON col1 = col1 INNER JOIN
		(SELECT TRNSH_TRNSA_id, TRNSH_date_entered  
		 from TRNSH_transaction_history
		 where TRNSH_actual_yn = 1) trnsh ON trnsh_trnsa_id = trnsa_id
WHERE  TRNSA.TRNSA_TRNAT_id = 1 and
		TRNSH_date_entered > '20111231'

UNION

SELECT 2 as id,
	'Market Trade volume (sum of 2011 and 2012)' as captionfield,
	ISNULL(SUM(TRNSA.TRNSA_quantity), 0) as value
FROM TRNSA_transaction TRNSA
WHERE  TRNSA.TRNSA_TRNAT_id = 1

UNION

SELECT TOP 1 
3 as id, captionfield as captionfield, TRNSA_price AS value
FROM         (SELECT     TOP 1000000 'Last price traded' AS captionfield, b.TRNSA_price
                       FROM          TRNSH_transaction_history a LEFT JOIN
                                              TRNSA_transaction b ON a.TRNSH_TRNSA_id = b.TRNSA_id
                       WHERE      b.TRNSA_price IS NOT NULL
                       ORDER BY a.TRNSH_date_changed DESC) tabje
UNION

SELECT     4 as id,'Average price per credit 2011' AS captionfield, ROUND(AVG(b.TRNSA_price), 2) AS value
FROM         TRNSH_transaction_history a LEFT JOIN
                      TRNSA_transaction b ON a.TRNSH_TRNSA_id = b.TRNSA_id
WHERE     a.TRNSH_actual_yn = 1 and TRNSH_date_entered > '20101231' and trnsh_date_entered < '20120101'

UNION

SELECT     5 as id,'Average price per credit 2012' AS captionfield, isnull(ROUND(AVG(b.TRNSA_price), 2), 0) AS value
FROM         TRNSH_transaction_history a LEFT JOIN
                      TRNSA_transaction b ON a.TRNSH_TRNSA_id = b.TRNSA_id
WHERE     a.TRNSH_actual_yn = 1 and TRNSH_date_entered > '20111231'

UNION

SELECT  6 as id,
		'Average price per credit (average of 2011 and 2012)' AS captionfield,
		ROUND(AVG(b.TRNSA_price), 2) AS value
FROM    TRNSH_transaction_history a LEFT JOIN
        TRNSA_transaction b ON a.TRNSH_TRNSA_id = b.TRNSA_id
WHERE   a.TRNSH_actual_yn = 1


UNION

SELECT 7 as id,
'Direct Trade  volume 2011' as captionfield,
SUM(TRNSA.TRNSA_quantity) as value
FROM [TRNSA_transaction] TRNSA INNER JOIN
	TRNSH_transaction_history ON TRNSH_TRNSA_id = TRNSA_id AND TRNSH_actual_yn = 1
WHERE  TRNSA.TRNSA_TRNAT_id = 2 AND TRNSH_date_entered < '20120101'
GROUP BY TRNSA.TRNSA_TRNAT_id

UNION

SELECT 8 as id,
'Direct Trade  volume 2012' as captionfield,
SUM(TRNSA.TRNSA_quantity) as value
FROM [TRNSA_transaction] TRNSA INNER JOIN
	TRNSH_transaction_history ON TRNSH_TRNSA_id = TRNSA_id AND TRNSH_actual_yn = 1
WHERE  TRNSA.TRNSA_TRNAT_id = 2 AND TRNSH_date_entered < '20130101' AND TRNSH_date_entered > '20111231'
GROUP BY TRNSA.TRNSA_TRNAT_id

UNION

SELECT 9 as id,
'Direct Trade  volume (sum of 2011 and 2012)' as captionfield,
SUM(TRNSA.TRNSA_quantity) as value
FROM [TRNSA_transaction] TRNSA
WHERE  TRNSA.TRNSA_TRNAT_id=2
GROUP BY TRNSA.TRNSA_TRNAT_id

UNION

SELECT     10 as id,'Credit trading volume 2011' AS captionfield,SUM(b.TRNSA_quantity) AS value
FROM         TRNSH_transaction_history a LEFT JOIN
                      TRNSA_transaction b ON a.TRNSH_TRNSA_id = b.TRNSA_id
WHERE     a.TRNSH_actual_yn = 1 and TRNSH_date_entered < '20120101'

UNION

SELECT     11 as id,'Credit trading volume 2012' AS captionfield,SUM(b.TRNSA_quantity) AS value
FROM         TRNSH_transaction_history a LEFT JOIN
                      TRNSA_transaction b ON a.TRNSH_TRNSA_id = b.TRNSA_id
WHERE     a.TRNSH_actual_yn = 1 and TRNSH_date_entered < '20130101' and TRNSH_date_entered > '20111231'

UNION

SELECT     12 as id,'Credit trading volume (sum of 2011 and 2012)' AS captionfield,SUM(b.TRNSA_quantity) AS value
FROM         TRNSH_transaction_history a LEFT JOIN
                      TRNSA_transaction b ON a.TRNSH_TRNSA_id = b.TRNSA_id
WHERE     a.TRNSH_actual_yn = 1")) 
{
	echo '<html>
	<head>
	<link rel="stylesheet" href="'.$url.'style.css" type="text/css" />
	</head>
	<body>';
	//echo "<strong>Market overview and actual offers and bids</strong>";
	echo '<table cellpadding="0" cellspacing="0" width = "95%">
		<tr class="kop">
			<td style="width:70%;" align = "left">KPI\'s</td>
			<td style="width:25%;" align = "left">Amount</td>
		</tr>';
					
	while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC) ) 
	{
	echo '<tr class="sub">
			<td style="width:16%;" align = "left">'.htmlentities($row["captionfield"]).'</td>
			<td style="width:16%;" align = "left">'.$row["value"].'</td>
		</tr>';
	}
} else {
	echo 'At this moment it\'s not possible to show the requested information. Sorry for any inconvenience.';
}
echo '</tr></table>';


// Actual offers and bids
if ($query2 = sqlsrv_query($connection, "SELECT
  VW_TRAD_ACTUAL_OFFERS.rownum
  ,VW_TRAD_ACTUAL_OFFERS.OFFERCREDITS
  ,VW_TRAD_ACTUAL_OFFERS.OFFERPRICE
  ,VW_TRAD_ACTUAL_OFFERS.BIDCREDITS
  ,VW_TRAD_ACTUAL_OFFERS.BIDPRICE
FROM
  VW_TRAD_ACTUAL_OFFERS
WHERE VW_TRAD_ACTUAL_OFFERS.OFFERCREDITS>0
OR VW_TRAD_ACTUAL_OFFERS.BIDCREDITS>0")) 
{
	echo '<br />';
	echo "<strong>Actual offers and bids</strong>";
	echo '<table cellpadding="0" cellspacing="0" width = "95%">
	<tr class="kop">
		<td style="width:45%;" colspan = "2" align = "center">Offers</td>
		<td style="width:45%;" colspan = "2" align = "center">Bids</td>
	</tr>
	<tr class="kop">
		<td style="width:20%;" align = "left">Credits</td>
		<td style="width:20%;" align = "left">Amount (USD)</td>
		<td style="width:20%;" align = "left">Credits</td>
		<td style="width:20%;" align = "left">Amount (USD)</td>
	</tr>';
					
	while ($row2 = sqlsrv_fetch_array($query2, SQLSRV_FETCH_ASSOC) ) 
	{
	if ($row2["BIDCREDITS"] == '') {
		$row2["BIDCREDITS"] = "&nbsp;";
	}
	if ($row2["BIDPRICE"] == '') {
		$row2["BIDPRICE"] = "&nbsp;";
	}
	echo '<tr class="sub">
			<td style="width:20%;" align = "left">'.$row2["OFFERCREDITS"].'</td>
			<td style="width:20%;" align = "left">'.$row2["OFFERPRICE"].'</td>
			<td style="width:20%;" align = "left">'.$row2["BIDCREDITS"].'</td>
			<td style="width:20%;" align = "left">'.$row2["BIDPRICE"].'</td>
		</tr>';
	}
} else {
	echo 'At this moment it\'s not possible to show the requested information. Sorry for any inconvenience.';
}
echo '</tr></table></body></html>';



?>
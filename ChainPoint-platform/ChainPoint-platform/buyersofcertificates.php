<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);
ini_set('display_errors', 0); // change to 1 for debugging
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
date_default_timezone_set('Europe/Belgrade');
include_once("./connect.php");

// Number of years
// Set the number of years to be shown in the report
// e.g. when the current year is 2017 and number of years is set to 7, data from 2011 to 2017 will be shown
$number_of_years = 7;

$current_year = date('Y');
//$current_year = $current_year - 1;

$selected_filter_option = isset($_REQUEST["type_ids"]) ? $_REQUEST["type_ids"] : 'producers-to-buyers';
$selected_product_category = isset($_REQUEST["prod_cat"]) ? $_REQUEST["prod_cat"] : '0';
$query_where = null;
switch ($selected_filter_option) {
	case "producers-to-buyers": 
		$query_where = "0"; //same as default
		break;
	case "all-trades": 
		$query_where = "1";
		break;
	default: 
		$query_where = "0";
}

// Build query for report (1/5)
$sql = "
/*
    Filter options on SellingCompanyType
        0 = Producers to buyers
        1 = All traders

    Filter options on ProductCategory
        0  = All product categories
        7  = Soy products
        8  = Corn products
        9  = Non-Paraquat Soy
        10 = Non-GM Soy
        11 = Non-GM/Non-Paraquat Soy
*/

DECLARE @SellingCompanyType int = $query_where
DECLARE @ProductCategory int = $selected_product_category

SELECT 
      BuingCompanyId
    , BuingCompany
";
// Build query for report (2/5)
for ($i = $number_of_years; $i > 0; $i--) {
    $next_year = $current_year - $number_of_years + $i;
    $sql .= "    , SUM(quantity_" . $next_year . ") AS 'Purchased_credits_" . $next_year . "'
";
}
// Build query for report (3/5)
$sql .= "
FROM (SELECT 
		  CASE WHEN
				CASE WHEN u.CMUR_CMPU_id_from IS NULL THEN 0 ELSE 1 END = 1 --IsDaughter
			AND CASE WHEN d.CUPR_CMPU_id IS NULL	  THEN 0 ELSE 1 END = 0 --IncludeDaughter
				THEN u.CMUR_CMPU_id_with
			ELSE t.BuingCompanyId
		END AS BuingCompanyId
		, CASE WHEN
				CASE WHEN u.CMUR_CMPU_id_from IS NULL THEN 0 ELSE 1 END = 1 --IsDaughter
			AND CASE WHEN d.CUPR_CMPU_id IS NULL	  THEN 0 ELSE 1 END = 0 --IncludeDaughter
				THEN LTRIM(uc.CMPU_name)
			ELSE LTRIM(t.BuingCompany)
		END AS BuingCompany
";
// Build query for report (4/5)
for ($i = $number_of_years; $i > 0; $i--) {
    $next_year = $current_year - $number_of_years + $i;
    $sql .= "    , CASE WHEN t.year = " . $next_year . " THEN SUM(t.quantity) ELSE 0 END AS 'quantity_" . $next_year . "'
";
}
// Build query for report (5/5)
$sql .= "
	FROM dbo.VW_BUYERS_OF_CERTIFICATES AS t
		LEFT OUTER JOIN dbo.CMUR_company_unit_relation	 AS u ON u.CMUR_CMPU_id_from = t.BuingCompanyId AND u.CMUR_CURT_id = 42
		LEFT OUTER JOIN dbo.CUPR_company_unit_properties AS d ON d.CUPR_CMPU_id = t.BuingCompanyId		AND d.CUPR_DOMV_id = 502
		LEFT OUTER JOIN dbo.CMPU_company_unit			 AS uc ON uc.CMPU_id = u.CMUR_CMPU_id_with 

	WHERE CONVERT(nvarchar, t.SellingCompanyTypeId) LIKE CASE WHEN @SellingCompanyType = 0 THEN '45' ELSE '%' END --Filter on SellingCompanyType
      AND CONVERT(nvarchar, t.CropType) LIKE CASE WHEN @ProductCategory > 0 THEN CONVERT(nvarchar, @ProductCategory) ELSE '%' END --Filter on ProductCategory
      AND 1 = CASE WHEN CASE WHEN d.CUPR_CMPU_id IS NULL THEN 0 ELSE 1 END = 0 AND u.CMUR_CMPU_id_with = t.SellingCompanyId THEN 0 ELSE 1 END --Exclude transactions between umbrella and daughter companies

	GROUP BY t.BuingCompanyId, t.BuingCompany, t.year, u.CMUR_CMPU_id_from, u.CMUR_CMPU_id_with, d.CUPR_CMPU_id, uc.CMPU_name
	) AS TRANS

GROUP BY BuingCompanyId, BuingCompany

ORDER BY BuingCompany
";

// Execute query and build report
if ($query = sqlsrv_query($connection, $sql)) {

    for ($i = 0; $i < $number_of_years; $i++) {
        ${"total_row_" . $i} = 0;
    }

	echo '<html>
	<head>
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Alegreya+Sans:300,400,500,700,800" type="text/css" />
	<link rel="stylesheet" href="'.$url.'style.css" type="text/css" />
	</head>
	<body>';
		
	echo '<form action="" method="POST" id="form_filter">
		<span class="sub">Filter transactions from: </span>
		<select name="type_ids" onchange="document.getElementById(\'form_filter\').submit()">
			<option value="producers-to-buyers"'.($selected_filter_option == 'producers-to-buyers' ? ' selected="selected"' : '').'>Producers to buyers</option>
			<option value="all-trades"'.($selected_filter_option == 'all-trades' ? ' selected="selected"' : '').'>All trades</option>
		</select>
		<select name="prod_cat" onchange="document.getElementById(\'form_filter\').submit()">
			<option value="0"'.($selected_product_category == '0' ? ' selected="selected"' : '').'>All product categories</option>
			<option value="7"'.($selected_product_category == '7' ? ' selected="selected"' : '').'>Soy products</option>
			<option value="8"'.($selected_product_category == '8' ? ' selected="selected"' : '').'>Corn products</option>
			<option value="9"'.($selected_product_category == '9' ? ' selected="selected"' : '').'>Non-Paraquat Soy</option>
			<option value="10"'.($selected_product_category == '10' ? ' selected="selected"' : '').'>Non-GM Soy</option>
			<option value="11"'.($selected_product_category == '11' ? ' selected="selected"' : '').'>Non-GM/Non-Paraquat Soy</option>
		</select>
		</form>
		';

	echo '<table cellpadding="0" cellspacing="0" width = "90%">
        <tr class="kop">
             <td style="width:25%;" align="left">Company</td>
			 ';
            
    for ($i = $number_of_years; $i > 0; $i--) {
        $next_year = $current_year - $number_of_years + $i;
		echo '<td style="width:25%;" align = "right">Purchased credits ' . $next_year . '</td>
		';
	}
    
	echo '</tr>
	';
					
	while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
	
        echo '<tr class="sub">
                <td style="width:25%;" align = "left">'.htmlentities($row["BuingCompany"]).'</td>
				';
        
        for ($i = $number_of_years; $i > 0; $i--) {
            $next_year = $current_year - $number_of_years + $i;
            $next_row  = $number_of_years - $i;
            ${"row_" . $next_row} = $row["Purchased_credits_" . $next_year . ""];
            echo '<td style="width:25%;" data-value="' . ${"row_" . $next_row} . '" align = "right">' . number_format($row["Purchased_credits_" . $next_year . ""], 0, '.', ',') . '</td>
			';
        }
            
        echo '</tr>
		';
        
        for($i=0; $i<$number_of_years; $i++) {
            ${"total_row_" . $i} += empty(${"row_" . $i}) ? 0 : intval(${"row_" . $i});
        }
        
	}
	
	echo '<tr class="sub">
	';
    
    for ($i = $number_of_years; $i > 0; $i--) {
        $next_row = $i + 10;
        echo '<td style="width:25%;" data-value="' . ${"Anonrow_" . $next_row} . '" align = "right">' . ${"Anonrow" . $next_row}["aantal"] . '</td>
		';
    }

    echo '</tr>
	';
	
	echo '<tr class="kop">
             <td align="left">Total</td>
			 ';
            
    for($i = 0; $i < $number_of_years; $i++) {
        echo '<td style="font-weight:bold;" align="right">' . number_format(${"total_row_" . $i}, 0, '.', ',') . '</td>
		';
    }
    
    echo '</tr>
	</table>
	';
	
} else {
	echo 'At this moment it\'s not possible to show the requested information. Sorry for any inconvenience.';
}

echo '</body></html>';

?>
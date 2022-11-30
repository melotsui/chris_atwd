<?php

//$xmlDoc = simplexml_load_file('https://www.fehd.gov.hk/english/pleasant_environment/tidy_market/marketInfo.xml');
$xmlDoc = simplexml_load_file("marketInfo.xml");

if ($xmlDoc === FALSE) {
    echo "Error parsing XML";
    exit;
}

$server='localhost';
$dbuser='root';
$dbpassword = '';
$dbname = 'atwd';

$conn = new mysqli($server, $dbuser, $dbpassword, $dbname);
if ($conn->connect_error) {
    die ('Database connection failed');
}

$mID = 1;

foreach ($xmlDoc->Market as $item) {
    $Region_e = $item->Region_e;
    $Region_c = $item->Region_c;
    $District_e = $item->District_e;
    $District_c = $item->District_c;
    $Market_e = $item->Market_e;
    $Market_c = $item->Market_c;
    $Address_e = $item->Address_e;
    $Address_c = $item->Address_c;
    $Business_Hours_e = $item->Business_Hours_e;
    $Business_Hours_c = $item->Business_Hours_c;
    $Coordinate = $item->Coordinate;
    $Contact_1 = $item->Contact_1;
    $Contact_2 = $item->Contact_2;
    $Tenancy_Commodity_e = $item->Tenancy_Commodity_e;
    $Tenancy_Commodity_c = $item->Tenancy_Commodity_c;
    $nos_stall = $item->nos_stall;


    $Address_e = str_replace("'", " ", $Address_e);

	$sql = "INSERT INTO market VALUES ($mID, '$Region_e', '$Region_c', '$District_e', '$District_c', '$Market_e', '$Market_c', '$Address_e', '$Address_c', '$Business_Hours_e', '$Business_Hours_c', '$Coordinate', '$Contact_1', '$Contact_2', '$Tenancy_Commodity_e', '$Tenancy_Commodity_c', '$nos_stall')";
    
    $dbresult=$conn->query($sql);
    if ($dbresult) {
        $mID = $mID + 1;
        echo 'record inserted';
    } else {
        echo 'record insertion failed';
    }
}

$conn->close();

?>
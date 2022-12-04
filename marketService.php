<?php

class MarketService {

    function GET($parameters) {

        require_once 'db.php';

        $apiType = array_shift($parameters);
        if($apiType==='all') { // 127.0.0.1/market/index.php/market/all
            $output = array();
            $sql = "SELECT DISTINCT Market_e, Market_c, Region_e, Region_c, District_e, District_c, Address_e, Address_c, Business_Hours_e, Business_Hours_c, Coordinate, Contact_1, Contact_2 FROM market m ";
            try {
                $dbresult = $conn->query($sql);
                // successfully retrieved the records
                $output = array();
                while ($row = $dbresult->fetch_assoc()) {
                    $output[] = $row;
                }
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            } 
            catch (Exception $e) {
                $output = array();
                $output['status'] = 'error';
                $output['code'] = '1000';
                $output['message'] = 'SQL execution failure';
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        if($apiType==='field') { 
            $field = array_shift($parameters);
            if($field == 'region'){ // 127.0.0.1/market/index.php/market/field/region/Kowloon
                $region = array_shift($parameters);
                $output = array();
                $sql = "SELECT DISTINCT District_e FROM market m WHERE Region_e = '$region';";
                try {
                    $dbresult = $conn->query($sql);
                    // successfully retrieved the records
                    $output = array();
                    while ($row = $dbresult->fetch_assoc()) {
                        $output[] = $row;
                    }
                    echo json_encode($output, JSON_UNESCAPED_UNICODE);
                    exit;
                } 
                catch (Exception $e) {
                    $output = array();
                    $output['status'] = 'error';
                    $output['code'] = '1000';
                    $output['message'] = "Region $region does not exist" ;
                    echo json_encode($output, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
            // if($field == 'district'){
            //     $district = array_shift($parameters);
            //     $output = array();
            //     $sql = "SELECT DISTINCT market_e FROM market m WHERE district_e = '$district';";
            //     try {
            //         $dbresult = $conn->query($sql);
            //         // successfully retrieved the records
            //         $output = array();
            //         while ($row = $dbresult->fetch_assoc()) {
            //             $output[] = $row;
            //         }
            //         echo json_encode($output, JSON_UNESCAPED_UNICODE);
            //         exit;
            //     } 
            //     catch (Exception $e) {
            //         $output = array();
            //         $output['status'] = 'error';
            //         $output['code'] = '1000';
            //         $output['message'] = "District $district does not exist" ;
            //         echo json_encode($output, JSON_UNESCAPED_UNICODE);
            //         exit;
            //     }
            // }
            if($field == 'tc'){ // 127.0.0.1/market/index.php/market/field/tc
                $region = array_shift($parameters);
                $output = array();
                $sql = "SELECT DISTINCT Tenancy_Commodity_e, Tenancy_Commodity_c FROM market;";
                try {
                    $dbresult = $conn->query($sql);
                    // successfully retrieved the records
                    $output = array();
                    while ($row = $dbresult->fetch_assoc()) {
                        $output[] = $row;
                    }
                    echo json_encode($output, JSON_UNESCAPED_UNICODE);
                    exit;
                } 
                catch (Exception $e) {
                    $output = array();
                    $output['status'] = 'error';
                    $output['code'] = '999';
                    $output['message'] = "Region $region does not exist" ;
                    echo json_encode($output, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
        }
        if($apiType==='tc') { // 127.0.0.1/market/index.php/market/tc/market_e/NORTH POINT MARKET
            array_shift($parameters);
            $name = array_shift($parameters);
            if(!isset($name)){
                $output = array();
                $output['status'] = 'error';
                $output['code'] = '999';
                $output['message'] = "Invalid Market Name" ;
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            }
            $output = array();
            $sql = "SELECT DISTINCT Tenancy_Commodity_e, Tenancy_Commodity_c, nos_stall 
                        FROM market m WHERE Market_e = '$name';";
            try {
                $dbresult = $conn->query($sql);
                // successfully retrieved the records
                $output = array();
                while ($row = $dbresult->fetch_assoc()) {
                    $output[] = $row;
                }
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            } 
            catch (Exception $e) {
                $output = array();
                $output['status'] = 'error';
                $output['code'] = '1000';
                $output['message'] = "SQL execution failure" ;
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        
        if ($apiType==='filter') { // 127.0.0.1/market/index.php/market/filter/tc/{tc}
            // search records by Region
            array_shift($parameters);
            $tc = array_shift($parameters);
            // if (!isset($Region_e)|($Region_e=="")) {
            //     $output = array();
            //     $output['status'] = 'error';
            //     $output['code'] = '1001';
            //     $output['message'] = 'Missing Region';
            //     echo json_encode($output, JSON_UNESCAPED_UNICODE);
            //     exit;
            // }
            $sql = "SELECT DISTINCT Market_e, Region_e, District_e, Address_e, Business_Hours_e, Contact_1, Contact_2 
                    FROM market WHERE Tenancy_Commodity_e LIKE '%$tc%'";
            try {
                $dbresult = $conn->query($sql);
                // successfully retrieved the records
                $output = array();
                while ($row = $dbresult->fetch_assoc()) {
                    $output[] = $row;
                }
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            } 
            catch (Exception $e) {
                $output = array();
                $output['status'] = 'error';
                $output['code'] = '1000';
                $output['message'] = 'SQL execution failure';
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            }

        }

        if ($apiType==='Region_e') { // 127.0.0.1/market/index.php/market/Region_e/Kowloon
            // search records by Region
            $Region_e = array_shift($parameters);
            // if (!isset($Region_e)|($Region_e=="")) {
            //     $output = array();
            //     $output['status'] = 'error';
            //     $output['code'] = '1001';
            //     $output['message'] = 'Missing Region';
            //     echo json_encode($output, JSON_UNESCAPED_UNICODE);
            //     exit;
            // }
            $sql = "SELECT DISTINCT Market_e, Region_e, District_e, Address_e, Business_Hours_e, Contact_1, Contact_2 FROM market WHERE Region_e like '%$Region_e%'";
            try {
                $dbresult = $conn->query($sql);
                // successfully retrieved the records
                $output = array();
                while ($row = $dbresult->fetch_assoc()) {
                    $output[] = $row;
                }
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            } 
            catch (Exception $e) {
                $output = array();
                $output['status'] = 'error';
                $output['code'] = '1000';
                $output['message'] = 'SQL execution failure';
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            }

        } elseif ($apiType==='District_e') { // 127.0.0.1/market/index.php/market/District_e/{district_e}
           $District_e = array_shift($parameters);
        //    if (!isset($District_e)|($District_e=="")) {
        //        $output = array();
        //        $output['status'] = 'error';
        //        $output['code'] = '2001';
        //        $output['message'] = 'Missing District';
        //        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        //        exit;
        //    }
           $sql = "SELECT DISTINCT Market_e, Region_e, District_e, Address_e, Business_Hours_e, Contact_1, Contact_2 FROM market WHERE District_e like '%$District_e%'";
           try {
               $dbresult = $conn->query($sql);
               $output = array();
               while ($row = $dbresult->fetch_assoc()) {
                   $output[] = $row;
               }
               echo json_encode($output, JSON_UNESCAPED_UNICODE);
               exit;
           } 
           catch (Exception $e) {
               $output = array();
               $output['status'] = 'error';
               $output['code'] = '1000';
               $output['message'] = 'SQL execution failure';
               echo json_encode($output, JSON_UNESCAPED_UNICODE);
               exit;
           }

        } elseif ($apiType==='Market_e') { // 127.0.0.1/market/index.php/market/Market_e/Queen
            $Market_e = array_shift($parameters);
            if (!isset($Market_e)|($Market_e=="")) {
                $output = array();
                $output['status'] = 'error';
                $output['code'] = '3001';
                $output['message'] = 'Missing Market';
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            }
            $sql = "SELECT * FROM market WHERE Market_e like '%$Market_e%'";
            try {
                $dbresult = $conn->query($sql);
                $output = array();
                while ($row = $dbresult->fetch_assoc()) {
                    $output[] = $row;
                }
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            } 
            catch (Exception $e) {
                $output = array();
                $output['status'] = 'error';
                $output['code'] = '1000';
                $output['message'] = 'SQL execution failure';
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            }

        } elseif ($apiType==='Tenancy_Commodity_e') { // 127.0.0.1/market/index.php/market/Tenancy_Commodity_e/Fish
            $Tenancy_Commodity_e = array_shift($parameters);
            if (!isset($Tenancy_Commodity_e)|($Tenancy_Commodity_e=="")) {
                $output = array();
                $output['status'] = 'error';
                $output['code'] = '4001';
                $output['message'] = 'Missing Commodity';
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            }
            $sql = "SELECT * FROM market WHERE Tenancy_Commodity_e='$Tenancy_Commodity_e'";
            try {
                $dbresult = $conn->query($sql);
                $output = array();
                while ($row = $dbresult->fetch_assoc()) {
                    $output[] = $row;
                }
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            } 
            catch (Exception $e) {
                $output = array();
                $output['status'] = 'error';
                $output['code'] = '1000';
                $output['message'] = 'SQL execution failure';
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }

    function PUT() {
        $body = file_get_contents('php://input');
        $dataArray = json_decode($body, true); // true means return an array
        $mID = $dataArray['mID'];
        $districtID = $dataArray['districtID'];
        $map_type = $dataArray['map_type'];
        $name_e = $dataArray['name_e'];
        $address_e = $dataArray['address_e'];

        require_once 'db.php';

        $sql = "UPDATE facility SET districtID='{$districtID}', map_type='{$map_type}', name_e='{$name_e}', address_e='{$address_e}' WHERE mapID={$mapID}";
        try {
            $dbresult = $conn->query($sql);
            $output = array();
            $output['status'] = 'success';
            $output['message'] = "Facility with ID $mapID updated successfully";
            echo json_encode($output);
            exit;
        } 
        catch (Exception $e) {
            $output = array();
            $output['status'] = 'error';
            $output['code'] = '2005';
            $output['message'] = 'SQL execution failure: UPDATE failed';
            echo json_encode($output);
            exit;
        }
    }

    function POST() {

    }

    function DELETE($market_e) {
        //alert("DELETE function");
        $market_e = array_shift($market_e);
        if (!isset($market_e)) {
            http_response_code(400);
            $output = array();
            $output['status'] = 'error';
            $output['code'] = '5000';
            $output['message'] = 'Delete Function: Market Not found';
            echo json_encode($output, JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        require_once 'db.php';
        $sql = "DELETE FROM market WHERE market_e='$market_e'";

        try {
            $dbresult = $conn->query($sql);
            $output = array();
            $output['status'] = 'success';
            $output['message'] = "Market $market_e successfully deleted";
            echo json_encode($output, JSON_UNESCAPED_UNICODE);
            exit;
        } 
        catch (Exception $e) {
            http_response_code(400);
            $output = array();
            $output['status'] = 'error';
            $output['code'] = '1000';
            $output['message'] = 'Delete Function: SQL execution failure';
            echo json_encode($output, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
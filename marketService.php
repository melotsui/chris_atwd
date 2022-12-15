<?php

class MarketService {

    function GET($parameters) {

        require_once 'db.php';

        $apiType = array_shift($parameters);
        if($apiType==='all') { // 127.0.0.1/market/index.php/market/all
            $output = array();
            $sql = "SELECT mID, Market_e, Market_c, Region_e, Region_c, District_e, District_c, Address_e, Address_c, Business_Hours_e, Business_Hours_c, Coordinate, Contact_1, Contact_2, Tenancy_Commodity_e, Tenancy_Commodity_c, nos_stall FROM market m ";
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
                if($region == ''){
                    $sql = "SELECT DISTINCT Region_e FROM market m;";
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
                } else {
                    $sql = "SELECT DISTINCT District_e FROM market m WHERE Region_e = '$region';";
                    try {
                        $dbresult = $conn->query($sql);
                        // successfully retrieved the records
                        $output = array();
                        while ($row = $dbresult->fetch_assoc()) {
                            $output[] = $row;
                        }
                        if(count($output)>0){
                            echo json_encode($output, JSON_UNESCAPED_UNICODE);
                        } else {
                            $output = array();
                            $output['status'] = 'error';
                            $output['code'] = '1001';
                            $output['message'] = "Region '$region' does not exist" ;
                            echo json_encode($output, JSON_UNESCAPED_UNICODE);
                        }
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
                    $output['code'] = '1000';
                    $output['message'] = "SQL execution failure" ;
                    echo json_encode($output, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
        }
        if($apiType==='tc') { // 127.0.0.1/market/index.php/market/tc/market_e/NORTH POINT MARKET
            array_shift($parameters);
            $name = array_shift($parameters);
            if($name == ''){
                $output = array();
                $output['status'] = 'error';
                $output['code'] = '1002';
                $output['message'] = "Market Name can not be null" ;
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
                if(count($output) > 0){
                    echo json_encode($output, JSON_UNESCAPED_UNICODE);
                } else {
                    $output['status'] = 'error';
                    $output['code'] = '1003';
                    $output['message'] = "Invalid Market Name" ;
                    echo json_encode($output, JSON_UNESCAPED_UNICODE);
                }
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
            if (!isset($tc)|($tc=="")) {
                $output = array();
                $output['status'] = 'error';
                $output['code'] = '1007';
                $output['message'] = 'Missing Tenancy Commodity';
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            }
            $sql = "SELECT mID, Market_e, Market_c, Region_e, Region_c, District_e, District_c, Address_e, Address_c, Business_Hours_e, Business_Hours_c, Coordinate, Contact_1, Contact_2, Tenancy_Commodity_e, Tenancy_Commodity_c, nos_stall 
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
            if (!isset($Region_e)|($Region_e=="")) {
                $output = array();
                $output['status'] = 'error';
                $output['code'] = '1006';
                $output['message'] = 'Missing Region';
                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            }
            $sql = "SELECT mID, Market_e, Market_c, Region_e, Region_c, District_e, District_c, Address_e, Address_c, Business_Hours_e, Business_Hours_c, Coordinate, Contact_1, Contact_2, Tenancy_Commodity_e, Tenancy_Commodity_c, nos_stall FROM market WHERE Region_e like '%$Region_e%'";
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
           if (!isset($District_e)|($District_e=="")) {
               $output = array();
               $output['status'] = 'error';
               $output['code'] = '1005';
               $output['message'] = 'Missing District';
               echo json_encode($output, JSON_UNESCAPED_UNICODE);
               exit;
           }
           $sql = "SELECT mID, Market_e, Market_c, Region_e, Region_c, District_e, District_c, Address_e, Address_c, Business_Hours_e, Business_Hours_c, Coordinate, Contact_1, Contact_2, Tenancy_Commodity_e, Tenancy_Commodity_c, nos_stall FROM market WHERE District_e like '%$District_e%'";
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
            if (!isset($Market_e) || ($Market_e=="")) {
                $output = array();
                $output['status'] = 'error';
                $output['code'] = '1004';
                $output['message'] = 'Missing Market name';
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
        $output = array();
        $body = file_get_contents('php://input');
        $dataArray = json_decode($body, true); // true means return an array
        $mID = isset($dataArray['mID']) ? $dataArray['mID'] : '';
        if($mID == ''){
            $output['status'] = 'error';
            $output['code'] = '3002';
            $output['message'] = 'Update Function: Market ID cannot be null';
            echo json_encode($output);
            exit;
        }
        $Market_e = isset($dataArray['Market_e']) ? $dataArray['Market_e'] : '';
        if($Market_e == ''){
            $output['status'] = 'error';
            $output['code'] = '3003';
            $output['message'] = 'Update Function: Market Name (e) cannot be null';
            echo json_encode($output);
            exit;
        }
        $Market_c = $dataArray['Market_c'];
        $Region_e = $dataArray['Region_e'];
        $Region_c = $dataArray['Region_c'];
        $District_e = $dataArray['District_e'];
        $District_c = $dataArray['District_c'];
        $Address_e = $dataArray['Address_e'];
        $Address_c = $dataArray['Address_c'];
        $Business_Hours_e = $dataArray['Business_Hours_e'];
        $Business_Hours_c = $dataArray['Business_Hours_e'];
        $Coordinate = $dataArray['Coordinate'];
        $Contact_1 = $dataArray['Contact_1'];
        $Contact_2 = $dataArray['Contact_2'];
        $Tenancy_Commodity_e = $dataArray['Tenancy_Commodity_e'];
        $Tenancy_Commodity_c = $dataArray['Tenancy_Commodity_c'];
        $nos_stall = $dataArray['nos_stall'];

        require_once 'db.php';

        $sql = "UPDATE market SET Market_e='{$Market_e}', Market_c='{$Market_c}',
                 Region_e='{$Region_e}', Region_c='{$Region_c}',
                 District_e='{$District_e}', District_c='{$District_c}',
                 Address_e='{$Address_e}', Address_c='{$Address_c}',
                 Business_Hours_e='{$Business_Hours_e}', Business_Hours_c='{$Business_Hours_c}',
                 Coordinate='{$Coordinate}',
                 Contact_1='{$Contact_1}', Contact_2='{$Contact_2}',
                 Tenancy_Commodity_e='{$Tenancy_Commodity_e}', Tenancy_Commodity_c='{$Tenancy_Commodity_c}',
                 nos_stall='{$nos_stall}' WHERE mID=$mID";
        try {
            $dbresult = $conn->query($sql);
            if($conn->affected_rows > 0){
                $output['status'] = 'success';
                $output['message'] = "Market with ID $mID updated successfully";
            } else {
                $output['status'] = 'error';
                $output['code'] = '3001';
                $output['message'] = 'Update Function: Market ID Not found';
            }
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

    function POST()
    {
        $body = file_get_contents('php://input');
        $dataArray = json_decode($body, true); // true means return an array
        $mID = $dataArray['mID'];
        $Market_e = $dataArray['Market_e'];
        $Market_c = $dataArray['Market_c'];
        $Region_e = $dataArray['Region_e'];
        $Region_c = $dataArray['Region_c'];
        $District_e = $dataArray['District_e'];
        $District_c = $dataArray['District_c'];
        $Address_e = $dataArray['Address_e'];
        $Address_c = $dataArray['Address_c'];
        $Business_Hours_e = $dataArray['Business_Hours_e'];
        $Business_Hours_c = $dataArray['Business_Hours_e'];
        $Coordinate = $dataArray['Coordinate'];
        $Contact_1 = $dataArray['Contact_1'];
        $Contact_2 = $dataArray['Contact_2'];
        $Tenancy_Commodity_e = $dataArray['Tenancy_Commodity_e'];
        $Tenancy_Commodity_c = $dataArray['Tenancy_Commodity_c'];
        $nos_stall = $dataArray['nos_stall'];

        require_once 'db.php';

        $sql = "INSERT INTO market VALUES (((select max(mID) from market subquery)+1),
         '$Market_e','$Market_c','$Region_e','$Region_c','$District_e','$District_c',
         '$Address_e','$Address_c','$Business_Hours_e','$Business_Hours_c',
         '$Coordinate','$Contact_1','$Contact_2','$Tenancy_Commodity_e',
         '$Tenancy_Commodity_c',$nos_stall)";
        try {
            $dbresult = $conn->query($sql);
            $output = array();
            $output['status'] = 'success';
            $output['message'] = "Market with ID $mID inserted successfully";
            echo json_encode($output);
            exit;
        } catch (Exception $e) {
            $output = array();
            $output['status'] = 'error';
            $output['code'] = '2005';
            $output['message'] = 'SQL execution failure: UPDATE failed';
            echo json_encode($output);
            exit;
        }
    }

    function DELETE($mID) {
        //alert("DELETE function");
        $mID = array_shift($mID);
        if (!isset($mID)) {
            http_response_code(400);
            $output = array();
            $output['status'] = 'error';
            $output['code'] = '5000';
            $output['message'] = 'Delete Function: Market ID Not found';
            echo json_encode($output, JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        require_once 'db.php';
        $sql = "DELETE FROM market WHERE mID=$mID";

        try {
            $dbresult = $conn->query($sql);
            $output = array();
            if($conn->affected_rows > 0){
                $output['status'] = 'success';
                $output['message'] = "Market ID $mID successfully deleted";
            } else {
                $output['status'] = 'error';
                $output['code'] = '4001';
                $output['message'] = 'Delete Function: Market ID Not found';
            }
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
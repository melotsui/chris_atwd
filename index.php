<?php
header('Content-Type: application/json; charset=utf-8');
 class Controller {
    private $pathInfo;
    private $urlSegments;

    function __construct() {
            if (!isset($_SERVER['PATH_INFO'])) {
                    $output = array();
                    $output['status'] = 'error';
                    $output['code'] = '500';
                    $output['message'] = 'Parameter could not be blank';
                    echo json_encode($output);
                    exit;
            }
            $this->pathInfo = $_SERVER['PATH_INFO'];
            // echo $this->pathInfo;
            $this->urlSegments = explode('/', $this->pathInfo);
            // print_r($this->urlSegments);
            // echo '<br>';
            array_shift($this->urlSegments);
            // print_r($this->urlSegments);
            // print '<br>';

            $resourceName = array_shift($this->urlSegments);
            // echo $resourceName.'<br>';
            // print_r($this->urlSegments);

            $serviceName = $resourceName.'Service';  // 'abc'
            $serviceName = ucfirst($serviceName);  // 'Abc'
            // echo $serviceName.'<br>';
            $serviceFilename = $serviceName.'.php';
            // echo $serviceFilename.'<br>';

            if (!file_exists($serviceFilename)) {
                echo 'No such resource';
                exit;
            } else {
                // echo 'Calling service provider';
                require_once $serviceFilename;
                $provider = new $serviceName;

                $method = $_SERVER['REQUEST_METHOD'];
                $provider->$method($this->urlSegments);
            }
    }
    
 }

 $controller = new Controller();
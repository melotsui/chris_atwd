<?php
$server = 'localhost';
$dbuser = 'root';
$dbpassword = '';
$dbname = 'atwd_chris';

$conn = new mysqli($server, $dbuser, $dbpassword, $dbname);
if ($conn->connect_error) {
    die ('Database connection failed');
}
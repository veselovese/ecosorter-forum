<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "sorter";

$connect = mysqli_connect($host, $username, $password, $database);
if (mysqli_connect_errno()) echo mysqli_connect_error(); 

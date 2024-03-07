<?php

session_start();

$servername = "localhost";
$db_username = "root";
$db_password = "";
$database_name = "real_estate";

$connect = mysqli_connect($servername, $db_username, $db_password, $database_name);

if (!$connect) {
    die("Failed connection!");
}

function ddebug($x)
{
    echo "<pre>";
    print_r($x);
    echo "</pre>";
}
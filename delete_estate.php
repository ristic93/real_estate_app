<?php

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $estate_id = $_POST['estate_id'];

    $sql = "DELETE FROM real_estates WHERE estate_id = ?";
    $run = $connect->prepare($sql);
    $run->bind_param("i", $estate_id);
    $message = "";

    if($run->execute()) {
        $message = "Nekretnina je obrisana";
    } else {
        $message = "Nekretnina nije obrisana";
    }

    $_SESSION['success_message'] = $message;
    header('location: admin_dashboard.php');
    exit();
}
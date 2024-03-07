<?php

require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['estate_id'])) {
    $estate_id = $_POST['estate_id'];
    $estate_name = $_POST['estate_name'];
    $estate_address = $_POST['estate_address'];
    $estate_price = $_POST['estate_price'];

    // Update the real estate record in the database
    $sql = "UPDATE `real_estates` SET 
            `estate_name` = '$estate_name',
            `estate_address` = '$estate_address',
            `estate_price` = '$estate_price'
            WHERE `estate_id` = $estate_id";

    if ($connect->query($sql) === true) {
        $_SESSION['success_message'] = 'Nekretnina je uspesno izmenjena';
    } else {
        $_SESSION['error_message'] = 'Greska prilikom izmene nekretnine: ' . $connect->error;
    }

    $connect->close();
}

header('location: admin_dashboard.php');
exit();
?>

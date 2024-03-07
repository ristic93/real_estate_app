<?php

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $agent_id = $_POST['agent'];
    $estate_id = $_POST['estate'];

    $sql = "UPDATE real_estates SET agent_id = ? WHERE estate_id = ?";
    $run = $connect->prepare($sql);
    $run->bind_param("ii", $agent_id, $estate_id);

    $run->execute();

    $_SESSION['success_message'] = "Agentu je uspesno dodeljena nekretnina";

    header('location: admin_dashboard.php');
    exit();
}
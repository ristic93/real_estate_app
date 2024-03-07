<?php

require_once 'config.php';
require_once 'fpdf/fpdf.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $estate_name = $_POST['estate_name'];
    $estate_address = $_POST['estate_address'];
    $estate_price = $_POST['estate_price'];
    $photo_path = $_POST['photo_path'];
    $agent_id = 0;
    $access_card_pdf_path = "";

    $sql = "INSERT INTO real_estates 
    (estate_name, estate_address, estate_price, photo_path, agent_id, access_card_pdf_path)
    VALUES (?, ?, ?, ?, ?, ?)";

    $run = $connect->prepare($sql);
    $run->bind_param("ssisis", $estate_name, $estate_address, $estate_price, $photo_path, $agent_id, $access_card_pdf_path);
    $run->execute();

    $estate_id = $connect->insert_id;

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(40, 10, 'Karta nekretnine');
    $pdf->Ln();
    $pdf->Cell(40, 10, 'ID Nekretnine: ' . $estate_id);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Ime: ' . $estate_name);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Adresa: ' . $estate_address);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Cena: ' . $estate_price);

    $filename = 'access_cards/access_card_' . $estate_id . '.pdf';
    $pdf->Output('F', $filename);

    $sql = "UPDATE real_estates SET access_card_pdf_path = '$filename' WHERE estate_id = $estate_id";
    $connect->query($sql);
    $connect->close();

    $_SESSION['success_message'] = 'Nekretnina je uspesno dodata';
    header('location: admin_dashboard.php');
    exit();
}

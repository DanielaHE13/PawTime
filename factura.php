<?php
session_start();
require("phpqrcode/qrlib.php");
require("fpdf/fpdf.php");

require("logica/Administrador.php");
require("logica/Paseo.php");
require("logica/Paseador.php");
require("logica/Perro.php");

$id = $_SESSION["id"];
$idP = $_GET["idPaseo"];

$paseo = new Paseo($idP);
$paseo->consultar();

$paseador = new Paseador($paseo->getPaseador_idPaseador());
$paseador->consultar();

$perro = new Perro($paseo->getPerro_idPerro());
$perro->consultar();

$pdf = new FPDF('P', 'mm', array(120, 200));
$pdf->AddPage();
$pdf->SetMargins(8, 8, 8);

$pdf->Image("imagen/logo.png", 10, 10, 20);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(95, 4, "PAWTIME", 0, 1, 'R');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(95, 4, "Calle 123, Ciudad Patitas", 0, 1, 'R');
$pdf->Cell(95, 4, "paseos@pawtime.com", 0, 1, 'R');
$pdf->Cell(95, 4, "Tel: 300-123-4567", 0, 1, 'R');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(20, 5, "Factura No:", 0, 0, 'L');
$pdf->Cell(70, 5, $idP, 0, 1, 'L');
$pdf->Cell(22, 5, "Fecha y Hora: ", 0, 0, 'L');
$pdf->Cell(70, 5, $paseo->getHora_fin(), 0, 1, 'L');
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(100, 5, "Datos del Paseo", 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30, 6, "Fecha:", 0, 0, 'L');
$pdf->Cell(70, 6, $paseo->getFecha(), 0, 1, 'L');
$pdf->Cell(30, 6, "Inicio:", 0, 0, 'L');
$pdf->Cell(70, 6, $paseo->getHora_inicio(), 0, 1, 'L');
$pdf->Cell(30, 6, "Fin:", 0, 0, 'L');
$pdf->Cell(70, 6, $paseo->getHora_fin(), 0, 1, 'L');
$pdf->Ln(3);
$pdf->Line(10, $pdf->GetY(), 110, $pdf->GetY());
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(100, 5, "Paseador", 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30, 6, "Nombre:", 0, 0, 'L');
$pdf->Cell(70, 6, utf8_decode($paseador->getNombre() . " " . $paseador->getApellido()), 0, 1, 'L');
$pdf->Ln(3);
$pdf->Line(10, $pdf->GetY(), 110, $pdf->GetY());
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(100, 5, "Perro", 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30, 6, "Nombre:", 0, 0, 'L');
$pdf->Cell(70, 6, $perro->getNombre(), 0, 1, 'L');
$pdf->Ln(3);
$pdf->Line(10, $pdf->GetY(), 110, $pdf->GetY());
$pdf->Ln(3);
$tarifa = "$ " . number_format($paseador->getTarifa(), 0, ',', '.');
$precioTotal = "$ " . number_format($paseo->getPrecio_total(), 0, ',', '.');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 6, "Tarifa:", 0, 0, 'L');
$pdf->Cell(70, 6, $tarifa, 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(100, 6, "Precio Total:", 0, 0, 'L');
$pdf->Cell(0, 6, $precioTotal, 0, 1, 'R');

$pdf->Ln(2);
$dir = "imagen/qr/" . $idP . ".png";
if (!file_exists($dir)) {
    $texto ="Factura generada el: " . date("Y-m-d H:i:s") . "\n" .
        "Paseador: " . utf8_decode($paseador->getNombre() . " " . $paseador->getApellido()) . "\n" .
    "Perro: " . $perro->getNombre() . "\n" .
    "Fecha Paseo: ".$paseo ->getFecha() . "\n" .
    "Horario: " . $paseo->getHora_inicio() . " - " . $paseo->getHora_fin() . "\n" .
    "Precio total: ".$paseo ->getPrecio_total();
    QRcode::png(mb_convert_encoding($texto, 'UTF-8'), $dir);
}
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(100, 6, utf8_decode("Confirma tu factura"), 0, 1, 'C');
$pdf->Image($dir, ($pdf->GetPageWidth() - 40) / 2, $pdf->GetY(), 40);


$pdf->Ln(45);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(100, 5, "Gracias por confiar en PawTime", 0, 1, 'C');

$pdf->Output();
?>

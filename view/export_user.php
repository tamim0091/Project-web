<?php
require_once '../controller/UserController.php';
require_once '../vendor/autoload.php';

$userId = $_POST['id'] ?? null;

if (!$userId) {
    die('Invalid user ID');
}

$utilisateursC = new UserController();
$user = $utilisateursC->showUser($userId);

if (!$user) {
    die('User not found');
}

require_once '../vendor/setasign/fpdf/fpdf.php';

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'User Information', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

$pdf->Cell(50, 10, 'ID:', 1);
$pdf->Cell(0, 10, $user['id'], 1, 1);

$pdf->Cell(50, 10, 'Full Name:', 1);
$pdf->Cell(0, 10, $user['FullName'], 1, 1);

$pdf->Cell(50, 10, 'Email:', 1);
$pdf->Cell(0, 10, $user['Email'], 1, 1);

$pdf->Cell(50, 10, 'Phone Number:', 1);
$pdf->Cell(0, 10, $user['PhoneNumber'], 1, 1);

$pdf->Cell(50, 10, 'Gender:', 1);
$pdf->Cell(0, 10, $user['Gender'], 1, 1);

$pdf->Cell(50, 10, 'Role:', 1);
$pdf->Cell(0, 10, $user['Role'], 1, 1);

$pdf->Output('D', "user_{$user['id']}.pdf");
exit();
?>

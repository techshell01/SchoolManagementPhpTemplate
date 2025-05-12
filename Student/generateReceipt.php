
<?php
// ob_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// include '../Includes/dbcon.php';

// require('tfpdf.php');  

// if (isset($_GET['payment_id'])) {
//     $payment_id = $_GET['payment_id'];

//     $stmt = $conn->prepare("SELECT * FROM payments WHERE payment_id = ?");
//     $stmt->bind_param("i", $payment_id);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $row = $result->fetch_assoc();

//     if ($row) {
//         // Start creating the PDF
//         $pdf = new tFPDF();
//         $pdf->AddPage();

//         // Header
//         $pdf->SetFont('Arial','B',14);
//         $pdf->Cell(0,8,"Heritage Day School",0,1,'C');
//         $pdf->SetFont('Arial','',11);
//         $pdf->Cell(0,6,"Nagarukhra,Barasat Para,Haringhata,Nadia,West Bengal",0,1,'C');
//         $pdf->Cell(0,6,"Office: 7364916702/9064109172",0,1,'C');
//         // $pdf->Cell(0,6,"Email: christchurchschool@ccghs.in / christchurchschoold@gmail.com",0,1,'C');
//         $pdf->Image('img/logo/schoolLogo.png',95, $pdf->GetY(), 20);
//         $pdf->Ln(5);

//         // Fee Receipt title
//         $pdf->SetFont('Arial','B',14);
//         $pdf->Cell(0,39,"Fee Receipt",0,1,'C');
//         $pdf->Ln(-8);

//         // Student Info Table
//         $pdf->SetFont('Arial','B',11);
//         // $pdf->Cell(33,8,'Transaction ID',1);
//         $pdf->Cell(30,8,'Registration No',1);
//         $pdf->Cell(35,8,'Student Name',1);
//         $pdf->Cell(33,8,'Payment Date',1);
//         $pdf->Cell(33,8,'Payment Mode',1);
//         $pdf->Cell(25,8,'Class',1);
//         $pdf->Cell(34,8,'Session',1);
//         $pdf->Ln();
//         $pdf->SetFont('Arial','',11);
//         $pdf->Cell(30, 8, $row['regId'], 1);
//         $pdf->Cell(35, 8, $row['studentName'], 1);
//         // Using DateTime class to get only the date
//         $createdAt = $row['created_at'];
//         $dateTime = new DateTime($createdAt);
//         $date = $dateTime->format('Y-m-d'); // Extract only the date (YYYY-MM-DD)
//         // Displaying only the date
//         $pdf->Cell(33, 8, $date, 1);  // Display date only (without time)
//         $pdf->Cell(33, 8, $row['payment_mode'], 1);
//         $pdf->Cell(25, 8, $row['class'], 1);
//         $pdf->Cell(34, 8, $row['session'], 1);
//         $pdf->Ln(10);


//         // Fee Details (if you have them in the database or from another source)
//         // $pdf->SetFont('Arial','B',12);
//         $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
//         $pdf->SetFont('DejaVu','',14);
//         $pdf->SetX(20);
//         $pdf->Cell(100,8,'Fee Type','TB',0);
//         $pdf->Cell(50,8,'Amount (₹)','TB',0);
//         $pdf->Ln();

//         // Example Fee Data (replace this with actual fee data)
//         // $fees = [
//         //     'Tuition Fee' => 5000,
//         //     'Sports Fee' => 1000,
//         //     'Library Fee' => 300
//         // ];

//         $total = 0;
//         foreach ($fees as $type => $amount) {
//             $pdf->SetX(20);
//             $pdf->Cell(100,8,$type,'TB',0);
//             $pdf->Cell(50,8,'₹ ' . number_format($amount, 2),'TB',0);
//             $pdf->Ln();
//             $total += $amount;
//         }

//         // Total Row
//         $pdf->SetX(20);
//         $pdf->Cell(100,8,'Total','TB',0);
//         $pdf->Cell(50,8,'₹ ' . number_format($total, 2),'TB',0);
//         $pdf->Ln(10);

//         // Dynamically sized Total Paid box
//         $pdf->SetX(70);
//         $totalText = 'Total Fees Paid: ₹ ' . number_format($total, 2);
//         $textWidth = $pdf->GetStringWidth($totalText) + 10;
//         $pdf->Cell($textWidth, 10, $totalText, 1, 0, 'C');
//         $pdf->Ln(15);

//         // Output PDF directly to the browser
//         ob_clean();
//         $pdf->Output('D', 'receipt.pdf');  // 'D' will trigger download
//         exit(); // Ensure script ends after outputting PDF
//     } else {
//         echo "No record found for payment ID: $payment_id";
//     }
// } else {
//     echo "Invalid request. No payment ID provided.";
// }
?>


<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../Includes/dbcon.php';
require('tfpdf.php');  

if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    // Payment record fetch karna
    $stmt = $conn->prepare("SELECT * FROM payments WHERE payment_id = ?");
    $stmt->bind_param("s", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $pdf = new tFPDF();
        $pdf->AddPage();

        // Header - School name & address
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,8,"Heritage Day School",0,1,'C');
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(0,6,"Nagarukhra,Barasat Para,Haringhata,Nadia,West Bengal",0,1,'C');
        $pdf->Cell(0,6,"Office: 7364916702/9064109172",0,1,'C');

        // School logo
        $pdf->Image('img/logo/schoolLogo.png',88, $pdf->GetY(), 35,35);
        $pdf->Ln(10);

        // Receipt heading
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,55,"Fee Receipt",0,1,'C');
        $pdf->Ln(-12);

        // Student Information Table
        // $pdf->SetX(15);
        // $pdf->SetFont('Arial','B',11);
        // $pdf->Cell(38, 8, 'Transaction ID', 1, 0, 'C');
        // $pdf->Cell(30,8,'Registration No',1, 0, 'C');
        // $pdf->Cell(30,8,'Student Name',1, 0, 'C');
        // $pdf->Cell(30,8,'Payment Date',1, 0, 'C');
        // $pdf->Cell(40, 8, 'Payment Mode', 1, 0, 'C');
        // $pdf->Cell(15,8,'Class',1, 0, 'C');
        // // $pdf->Cell(25,8,'Session',1, 0, 'C');
        // $pdf->Ln();
        // $pdf->SetX(15);
        // $pdf->SetFont('Arial','',11);
        // $pdf->Cell(38, 8, $row['payment_id'], 1,0, 'C');
        // $pdf->Cell(30, 8, $row['regId'], 1,0, 'C');
        // // $pdf->Cell(30, 8, $row['studentName'], 1,0, 'C');
        // $pdf->MultiCell(30, 8, $row['studentName'], 1, 'C');
        // $createdAt = new DateTime($row['created_at']);
        // $pdf->Cell(30, 8, $createdAt->format('Y-m-d'), 1,0, 'C');
        // $pdf->Cell(40, 8, ucfirst($row['payment_mode']), 1,0, 'C');
        // $pdf->Cell(15, 8, $row['class'], 1,0, 'C');
        // // $pdf->Cell(25, 8, $row['session'], 1,0, 'C');
        // $pdf->Ln(10);


        $pdf->SetX(15);
$pdf->SetFont('Arial','B',11);
// Header
$pdf->Cell(38, 8, 'Transaction ID', 1, 0, 'C');
$pdf->Cell(30,8,'Registration No',1, 0, 'C');
$pdf->Cell(30,8,'Student Name',1, 0, 'C');
$pdf->Cell(30,8,'Payment Date',1, 0, 'C');
$pdf->Cell(40, 8, 'Payment Mode', 1, 0, 'C');
$pdf->Cell(15,8,'Class',1, 0, 'C');
$pdf->Ln();

$pdf->SetFont('Arial','',11);
$startY = $pdf->GetY(); // Y start
$lineHeight = 8;

// Student Name cell (MultiCell)
$pdf->SetXY(83, $startY); // X=83 because 38+30+15
$pdf->MultiCell(30, $lineHeight, $row['studentName'], 1, 'C');

// Get height of that cell (could be 8 or 16)
$currentY = $pdf->GetY();
$cellHeight = $currentY - $startY;

// Other cells
$pdf->SetXY(15, $startY);
$pdf->Cell(38, $cellHeight, $row['payment_id'], 1, 0, 'C');
$pdf->Cell(30, $cellHeight, $row['regId'], 1, 0, 'C');

$pdf->SetXY(113, $startY); // after studentName
$createdAt = new DateTime($row['created_at']);
$pdf->Cell(30, $cellHeight, $createdAt->format('Y-m-d'), 1,0, 'C');
$pdf->Cell(40, $cellHeight, ucfirst($row['payment_mode']), 1,0, 'C');
$pdf->Cell(15, $cellHeight, $row['class'], 1,0, 'C');

$pdf->Ln(25);

        // Fee Type + Amount Section
        $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
        $pdf->SetFont('DejaVu','',14);
        $pdf->SetX(30);
        $pdf->Cell(100,8,'Fee Type','TB',0);
        $pdf->Cell(50,8,'Amount (₹)','TB',0);
        $pdf->Ln();

        // Amount fetch and display
        $type = !empty($row['payment_type']) ? ucfirst($row['payment_type']) : 'N/A';
        $paymentMode = !empty($row['payment_mode']) ? ucfirst($row['payment_mode']) : 'N/A';
        $amount = $row['amount'];
        $total = $amount;

        $pdf->SetX(30);
        $pdf->Cell(100,8, $type ,'TB',0);
        $pdf->Cell(50,8, '₹ ' . number_format($amount, 2),'TB',0);
        $pdf->Ln();

        // Total Row
        $pdf->SetX(30);
        $pdf->Cell(100,8,'Total','TB',0);
        $pdf->Cell(50,8,'₹ ' . number_format($total, 2),'TB',0);
        $pdf->Ln(10);

        // Total Paid Box
        $pdf->SetX(70);
        $totalText = 'Total Fees Paid: ₹ ' . number_format($total, 2);
        $textWidth = $pdf->GetStringWidth($totalText) + 10;
        $pdf->Cell($textWidth, 10, $totalText, 1, 0, 'C');
        $pdf->Ln(15);

        ob_clean(); // Clean output buffer
        $pdf->Output('D', '');  // Trigger download
        exit();

    } else {
        echo "No record found for payment ID: $payment_id";
    }
} else {
    echo "Invalid request. No payment ID provided.";
}
?>

<?php
require_once('tcpdf/tcpdf.php'); // Adjust the path based on your directory structure
include 'connect_db.php';


function fetchPaymentData($conn, $start_date, $end_date)
{
    
    $sql = "SELECT payment_id, order_id, customer_id, payment_date, amount_paid, payment_mode FROM payment WHERE payment.payment_date BETWEEN '$start_date' AND '$end_date'";

    $sql .= " ORDER BY payment_date ASC";
    $result = mysqli_query($conn, $sql);

    $data = array();

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            $data[] = array(
                'payment_id' => $row['payment_id'],
                'order_id' => $row['order_id'],
                'customer_id' => $row['customer_id'],
                'payment_date' => date('F j, Y', strtotime($row['payment_date'])),
                'amount_paid' => $row['amount_paid'],
                'payment_mode' => $row['payment_mode'],
            );
        }
    }

    return $data;
}

// Get filtered data
$start_date = mysqli_real_escape_string($conn, isset($_GET['start_date']) ? $_GET['start_date'] : '');
$end_date = mysqli_real_escape_string($conn, isset($_GET['end_date']) ? $_GET['end_date'] : '');


$data = fetchPaymentData($conn, $start_date, $end_date);

// TCPDF settings
$pdf = new TCPDF();
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->AddPage();

// Add a table to the PDF
$html = '<table border="1">
            <tr>
                <th>Reference No.</th>
                <th>Order ID</th>
                <th>Name</th>
                <th>Payment Date</th>
                <th>Amount Paid</th>
                <th>Payment Mode</th>
            </tr>';

foreach ($data as $row) {
    $html .= "<tr>
                <td>{$row['payment_id']}</td>
                <td>{$row['order_id']}</td>
                <td>{$row['customer_id']}</td>
                <td>{$row['payment_date']}</td>
                <td>{$row['amount_paid']}</td>
                <td>{$row['payment_mode']}</td>
            </tr>";
}

$html .= '</table>';

// Output the PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('payment_history.pdf', 'I'); // 'I' to open the PDF in the browser

?>

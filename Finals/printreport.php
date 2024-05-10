<?php
ob_start();
require_once('tcpdf/tcpdf.php');
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reportType = $_POST['reportType'];

    $pdf = new TCPDF();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 16);
    $pdf->Cell(0, 15, 'The Book Haven', 0, 1, 'C');

    $reportQuery = "";

    if ($reportType == 'total') {
        $reportQuery = "SELECT SUM(total_amount) AS total_sales FROM orders";
    } elseif ($reportType == 'by_product') {
        $reportQuery = "SELECT b.BookID, b.Title, SUM(o.total_amount) AS total_sales
            FROM books b
            JOIN orders o ON b.BookID = o.product_id
            GROUP BY b.BookID, b.Title";
    } elseif ($reportType == 'by_category') {
        $reportQuery = "SELECT
        g.BookGenre AS Genre,
        COALESCE(SUM(o.order_quantity), 0) AS total_quantity,
        COALESCE(SUM(o.total_amount), 0) AS total_amount
    FROM
        genre g
    LEFT JOIN
        books b ON g.BookGenre = b.Genre
    LEFT JOIN
        orders o ON b.BookID = o.product_id
    GROUP BY
        g.BookGenre";

    } elseif ($reportType == 'product_inventory') {
        $reportQuery = "SELECT b.BookID, b.Title, b.quantity AS available_stocks, SUM(b.quantity) OVER () AS total_stocks FROM books b";
    } elseif ($reportType == 'registered_customers') {
        $reportQuery = "SELECT UserID, FName, LName, address, phone, email
            FROM users";
    } elseif ($reportType == 'all_orders') {
        $reportQuery = "SELECT order_id, customer_id, order_date, order_quantity, total_amount
            FROM orders";
    } elseif ($reportType == 'borrow') {
        $reportQuery = "SELECT id, book_id, customer_name, borrow_date, due_date, penalty_paid, returned
        FROM borrow";
    }

    $reportResult = mysqli_query($con, $reportQuery);

    if (!$reportResult) {
        die("Error retrieving report: " . mysqli_error($con));
    }

    renderTableHeaders($pdf, $reportType);

    while ($row = mysqli_fetch_assoc($reportResult)) {
        renderTableData($pdf, $row, $reportType);
    }

    renderTableFooter($pdf, $reportType);
    $pdf->Output('sales_report.pdf', 'D');
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Haven Sales Report</title>
    <link rel="stylesheet" href="css/printreport.css">
    <link rel="icon" href="Image/logo.ico">
    <style>
        
    </style>
</head>

<body>

    <h1>Admin Sales Report</h1>
    <div class="report-container">
        <form method="post" action="">
            <div class="form-group">
                <label for="reportType">Select Report Type:</label>
                <select name="reportType" id="reportType" required>
                    <option value="total">Total Sales</option>
                    <option value="by_product">Products Sales</option>
                    <option value="by_category">Category Sales</option>
                    <option value="borrow">Borrow Status</option>
                    <option value="product_inventory">Books Inventory</option>
                    <option value="registered_customers">Registered Customers</option>
                    <option value="all_orders">Orders</option>

                </select>
            </div>
            <div class="generate-report-button">
                <button type="submit">
                    Generate Report
                </button>
            </div>
        </form>
    </div>
</body>

</html>
<?php
function renderTableHeaders($pdf, $reportType)
{
    $pdf->SetFont('helvetica', '', 8);

    switch ($reportType) {
        case 'total':
            $pdf->Cell(0, 10, 'Total Sales', 0, 1, 'C');
            break;
        case 'by_product':
            $pdf->Cell(0, 10, 'Products Sales', 0, 1, 'C');
            $pdf->Cell(25, 10, 'Product ID', 1, 0, 'C');
            $pdf->Cell(120, 10, 'Product Name', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Total Sales', 1, 1, 'C');
            break;
        case 'by_category':
            $pdf->Cell(0, 10, 'Category Sales', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Sales by Category', 0, 1, 'C');
            $pdf->Cell(50, 10, 'Genre', 1, 0, 'C');
            $pdf->Cell(50, 10, 'Total Quantity Sold', 1, 0, 'C');
            $pdf->Cell(50, 10, 'Total Amount Sold', 1, 1, 'C');
            break;
        case 'all_orders':
            $pdf->Cell(0, 10, 'Orders', 0, 1, 'C');
            $pdf->Cell(25, 10, 'Order ID', 1, 0, 'C');
            $pdf->Cell(25, 10, 'Customer ID', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Order Date', 1, 0, 'C');
            $pdf->Cell(25, 10, 'Quantity', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Total Amount', 1, 1, 'C');
            break;
        case 'product_inventory':
            $pdf->Cell(0, 10, 'Books Inventory', 0, 1, 'C');
            $pdf->Cell(20, 5, 'Product ID', 1, 0, 'C');
            $pdf->Cell(150, 5, 'Product Name', 1, 0, 'C');
            $pdf->Cell(25, 5, 'Available Stocks', 1, 1, 'C');
            break;
        case 'registered_customers':
            $pdf->Cell(0, 10, 'Registered Customers', 0, 1, 'C');
            $pdf->Cell(20, 5, 'Customer ID', 1, 0, 'C');
            $pdf->Cell(30, 5, 'First Name', 1, 0, 'C');
            $pdf->Cell(30, 5, 'Last Name', 1, 0, 'C');
            $pdf->Cell(40, 5, 'Address', 1, 0, 'C');
            $pdf->Cell(25, 5, 'Contact', 1, 0, 'C');
            $pdf->Cell(40, 5, 'Email', 1, 1, 'C');
            break;
        case 'borrow':
            $pdf->Cell(20, 10, 'ID', 1, 0, 'C');
            $pdf->Cell(20, 10, 'Book ID', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Customer Name', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Borrow Date', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Due Date', 1, 0, 'C');
            $pdf->Cell(20, 10, 'Penalty Paid', 1, 0, 'C');
            $pdf->Cell(20, 10, 'Returned', 1, 1, 'C');
            break;
    }
}

function renderTableData($pdf, $row, $reportType)
{
    switch ($reportType) {
        case 'total':
            $pdf->Cell(150, 10, 'Total Sales: PHP', 1, 0, 'C');
            $pdf->Cell(20, 10, $row['total_sales'], 1, 0, 'C');
            break;
        case 'by_product':
            $pdf->Cell(25, 10, $row['BookID'], 1, 0, 'C');
            $pdf->Cell(120, 10, $row['Title'], 1, 0, 'L');
            $pdf->Cell(40, 10, 'PHP ' . number_format($row['total_sales'], 2), 1, 1, 'C');
            break;
        case 'by_category':
            $pdf->Cell(50, 10, $row['Genre'], 1, 0, 'C');
            $pdf->Cell(50, 10, $row['total_quantity'], 1, 0, 'C');
            $pdf->Cell(50, 10, 'PHP ' . number_format($row['total_amount'], 2), 1, 1, 'C');
            break;
        case 'all_orders':
            $pdf->Cell(25, 10, $row['order_id'], 1, 0, 'C');
            $pdf->Cell(25, 10, $row['customer_id'], 1, 0, 'C');
            $pdf->Cell(40, 10, $row['order_date'], 1, 0, 'C');
            $pdf->Cell(25, 10, $row['order_quantity'], 1, 0, 'C');
            $pdf->Cell(40, 10, 'PHP ' . number_format($row['total_amount'], 2), 1, 1, 'C');
            break;
        case 'product_inventory':
            $pdf->Cell(20, 5, $row['BookID'], 1, 0, 'C');
            $pdf->Cell(150, 5, $row['Title'], 1, 0, 'C');
            $pdf->Cell(25, 5, $row['available_stocks'], 1, 1, 'C');
            break;
        case 'registered_customers':
            $pdf->Cell(20, 5, $row['UserID'], 1, 0, 'C');
            $pdf->Cell(30, 5, $row['FName'], 1, 0, 'C');
            $pdf->Cell(30, 5, $row['LName'], 1, 0, 'C');
            $pdf->Cell(40, 5, $row['address'], 1, 0, 'C');
            $pdf->Cell(25, 5, $row['phone'], 1, 0, 'C');
            $pdf->Cell(40, 5, $row['email'], 1, 1, 'C');
            break;
        case 'borrow':
            $pdf->Cell(20, 10, $row['id'], 1, 0, 'C');
            $pdf->Cell(20, 10, $row['book_id'], 1, 0, 'C');
            $pdf->Cell(40, 10, $row['customer_name'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['borrow_date'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['due_date'], 1, 0, 'C');
            $pdf->Cell(20, 10, $row['penalty_paid'], 1, 0, 'C');
            $pdf->Cell(20, 10, $row['returned'], 1, 1, 'C');
            break;
    }
}

function renderTableFooter($pdf, $reportType)
{
    $pdf->SetFont('helvetica', '', 8);

    switch ($reportType) {
        case 'total':
            break;
        case 'by_product':
            break;
        case 'by_category':
            break;
        case 'all_orders':
            break;
        case 'product_inventory':
            $pdf->Cell(20, 5, '', 1, 0, 'C');
            $pdf->Cell(150, 5, 'ssss', 1, 0, 'C');
            $pdf->Cell(25, 5, '', 1, 1, 'C');
            break;
        case 'registered_customers':
            break;
    }
}
?>
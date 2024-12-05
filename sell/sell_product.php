<?php
include('../db.php');  // Ensure the database connection
require('../fpdf/fpdf.php');  // Include FPDF library


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $wholesaler_id = $_POST['wholesaler_id'];
    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];
    $total_price = 0;

    // Fetch customer details
    $customer_sql = "SELECT owner_name, shipping_address, email_id FROM customers WHERE id = ?";
    $customer_stmt = $conn->prepare($customer_sql);
    $customer_stmt->bind_param('i', $wholesaler_id);
    $customer_stmt->execute();
    $customer_stmt->bind_result($customer_name, $shipping_address, $contact);
    $customer_stmt->fetch();
    $customer_stmt->close();

    // Start a transaction
    $conn->begin_transaction();
    try {
        // Sale processing
        $sale_items = [];
        for ($i = 0; $i < count($product_ids); $i++) {
            $product_id = $product_ids[$i];
            $quantity_sold = $quantities[$i];

            // Get product price and quantity
            $product_sql = "SELECT name, price, quantity FROM products WHERE id = ?";
            $product_stmt = $conn->prepare($product_sql);
            $product_stmt->bind_param('i', $product_id);
            $product_stmt->execute();
            $product_stmt->bind_result($product_name, $price, $available_quantity);
            $product_stmt->fetch();
            $product_stmt->close();

            if ($available_quantity >= $quantity_sold) {
                $item_total = $quantity_sold * $price;
                $total_price += $item_total;

                // Update product quantity
                $update_sql = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param('ii', $quantity_sold, $product_id);
                $update_stmt->execute();
                $update_stmt->close();

                // Insert sale record
                $sale_sql = "INSERT INTO sales (product_id, quantity_sold, wholesaler_id) VALUES (?, ?, ?)";
                $sale_stmt = $conn->prepare($sale_sql);
                $sale_stmt->bind_param('iii', $product_id, $quantity_sold, $wholesaler_id);
                $sale_stmt->execute();
                $sale_stmt->close();

                // Store sale details for receipt
                $sale_items[] = [
                    'name' => $product_name,
                    'quantity' => $quantity_sold,
                    'price' => $price,
                    'total' => $item_total
                ];
            } else {
                throw new Exception("Not enough stock for product: $product_name.");
            }
        }

        // Commit the transaction
        $conn->commit();

        // Generate PDF receipt
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Receipt header
        $pdf->Cell(190, 10, "Receipt", 1, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(190, 10, "Customer: $customer_name", 0, 1);
        $pdf->Cell(190, 10, "Shipping Address: $shipping_address", 0, 1);
        $pdf->Cell(190, 10, "Contact: $contact", 0, 1);
        $pdf->Ln(10);

        // Receipt table
        $pdf->Cell(90, 10, 'Product', 1);
        $pdf->Cell(30, 10, 'Quantity', 1);
        $pdf->Cell(30, 10, 'Price', 1);
        $pdf->Cell(40, 10, 'Total', 1);
        $pdf->Ln();

        foreach ($sale_items as $item) {
            $pdf->Cell(90, 10, $item['name'], 1);
            $pdf->Cell(30, 10, $item['quantity'], 1);
            $pdf->Cell(30, 10, $item['price'], 1);
            $pdf->Cell(40, 10, $item['total'], 1);
            $pdf->Ln();
        }

        $pdf->Cell(150, 10, 'Total Amount', 1);
        $pdf->Cell(40, 10, $total_price, 1);

        // Save PDF file
        $receipt_file = "../receipts/receipt_" . time() . ".pdf";
        $pdf->Output('F', $receipt_file);
        header('Location: sales_records.php');
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }

    // Close the connection
    $conn->close();
}
?>

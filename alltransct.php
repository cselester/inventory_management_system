<?php
include 'db.php';

$query = "
    SELECT 
        p.name AS product_name,
        p.quantity AS current_stock,
        s.quantity_sold,
        s.sale_date,
        'Product Sold' AS transaction_type
    FROM sales s
    JOIN products p ON s.product_id = p.id

    UNION ALL

    SELECT 
        p.name AS product_name,
        p.quantity AS current_stock,
        NULL AS quantity_sold,
        p.created_at AS sale_date,
        'Product Added' AS transaction_type
    FROM products p
    ORDER BY sale_date DESC;
";

$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->get_result();
$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }

        button {
    background-color:#007bff; /* Green color for back button */
    color: #fff;
    padding: 12px 25px;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
    display: inline-block;
    margin-bottom: 20px;
    width: auto; /* Keep the home button with natural width */
}

button:hover {
    background-color: #0056b3; /* Darker green on hover */
    transform: scale(1.05); /* Slight scaling effect on hover */
}

button:active {
    background-color: #004085; /* Even darker green when clicked */
}

    </style>
</head>
<body>
    <div class="container">
    <a href="index.php"><button class="home">Back to home</button></a>
        <h1>All Transactions</h1>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= htmlspecialchars($transaction['product_name']) ?></td>
                        <td><?= htmlspecialchars($transaction['transaction_type']) ?></td>
                        <td>
                            <?= $transaction['transaction_type'] === 'Product Sold' ? 
                                htmlspecialchars($transaction['quantity_sold']) : 
                                htmlspecialchars($transaction['current_stock']) ?>
                        </td>
                        <td><?= htmlspecialchars($transaction['sale_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

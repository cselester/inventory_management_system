<?php
include 'db.php'; // Ensure this file connects to your database

$query = "SELECT * FROM products";
$result = $conn->query($query);

$output = '';
while ($row = $result->fetch_assoc()) {
    $output .= '
    <tr>
        <td>' . $row['id'] . '</td>
        <td>' . $row['name'] . '</td>
        <td>' . $row['quantity'] . '</td>
        <td>' . $row['price'] . '</td>
        <td>' . $row['description'] . '</td>
        <td><button onclick="editProduct(' . $row['id'] . ')">Edit</button></td>
    </tr>';
}

echo $output;

$conn->close();
?>

<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $amountOwed = $_POST['amount_owed'];
    $createdBy = $_SESSION['admin_id']; // Admin who created the debtor

    try {
        // Insert debtor information
        $stmt = $pdo->prepare('INSERT INTO debtors (full_name, address, email, phone, amount_owed, created_by) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$fullName, $address, $email, $phone, $amountOwed, $createdBy]);
        
        $debtorId = $pdo->lastInsertId(); // Get the last inserted debtor ID

        // Insert initial payment record (optional)
        if ($amountOwed > 0) {
            $stmt = $pdo->prepare('INSERT INTO payments (debtor_id, amount, payment_date, remarks) VALUES (?, ?, NOW(), ?)');
            $stmt->execute([$debtorId, $amountOwed, 'Initial amount owed']);
        }

        echo "Debtor added successfully!";
    } catch (PDOException $e) {
        echo "Error adding debtor: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Debtor</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add New Debtor</h1>
    <form method="post">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <textarea name="address" placeholder="Address" required></textarea>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="number" name="amount_owed" placeholder="Amount Owed" required min="0" step="0.01">
        <button type="submit">Add Debtor</button>
    </form>
    <a href="index.php">Back to Dashboard</a>
</body>
</html>
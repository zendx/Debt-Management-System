<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$debtorId = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM debtors WHERE id = ?');
$stmt->execute([$debtorId]);
$debtor = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle payment addition or credit deduction
    if (isset($_POST['amount'])) {
        // Adding a payment
        $amount = $_POST['amount'];
        $remarks = $_POST['remarks'];

        // Get current amount owed
        $stmt = $pdo->prepare('SELECT amount_owed FROM debtors WHERE id = ?');
        $stmt->execute([$debtorId]);
        $debtorData = $stmt->fetch();

        $currentAmountOwed = $debtorData['amount_owed'] ?? 0;

        // Insert payment record
        $stmt = $pdo->prepare('INSERT INTO payments (debtor_id, amount, payment_date, remarks) VALUES (?, ?, NOW(), ?)');
        $stmt->execute([$debtorId, $amount, $remarks]);

        // Calculate excess if amount paid is higher than amount owed
        if ($amount > $currentAmountOwed) {
            $excess = $amount - $currentAmountOwed;

            // Insert credit for the excess amount
            $stmt = $pdo->prepare('INSERT INTO credits (debtor_id, amount, credit_date, remarks) VALUES (?, ?, NOW(), ?)');
            $stmt->execute([$debtorId, $excess, 'Excess payment credited']);

            // Update the amount owed to zero
            $stmt = $pdo->prepare('UPDATE debtors SET amount_owed = 0 WHERE id = ?');
            $stmt->execute([$debtorId]);
        } else {
            // Update the amount owed
            $newAmountOwed = $currentAmountOwed - $amount;
            $stmt = $pdo->prepare('UPDATE debtors SET amount_owed = ? WHERE id = ?');
            $stmt->execute([$newAmountOwed, $debtorId]);
        }
    }

    if (isset($_POST['deduct_amount'])) {
        // Deducting from credits
        $deductAmount = $_POST['deduct_amount'];
        $deductRemarks = $_POST['deduct_remarks'];

        // Insert deduction record
        $stmt = $pdo->prepare('INSERT INTO credits (debtor_id, amount, credit_date, remarks) VALUES (?, ?, NOW(), ?)');
        $stmt->execute([$debtorId, -$deductAmount, $deductRemarks]);

        header("Location: debtor.php?id=$debtorId");
        exit;
    }
}

// Fetch payments for this debtor
$stmt = $pdo->prepare('SELECT * FROM payments WHERE debtor_id = ?');
$stmt->execute([$debtorId]);
$payments = $stmt->fetchAll();

// Fetch total credits for this debtor
$stmt = $pdo->prepare('SELECT SUM(amount) AS total_credits FROM credits WHERE debtor_id = ?');
$stmt->execute([$debtorId]);
$creditInfo = $stmt->fetch();
$totalCredits = $creditInfo['total_credits'] ?? 0; // Default to 0 if no credits

// Fetch credit records for this debtor
$stmt = $pdo->prepare('SELECT * FROM credits WHERE debtor_id = ? ORDER BY credit_date DESC');
$stmt->execute([$debtorId]);
$creditRecords = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Debtor</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Debtor: <?php echo htmlspecialchars($debtor['full_name']); ?></h1>
    
    <h2>Total Credits: <?php echo htmlspecialchars(number_format($totalCredits, 2)); ?></h2>

    <h2>Payments</h2>
    <table>
        <tr>
            <th>Amount</th>
            <th>Date</th>
            <th>Remarks</th>
        </tr>
        <?php foreach ($payments as $payment): ?>
        <tr>
            <td><?php echo htmlspecialchars(number_format($payment['amount'], 2)); ?></td>
            <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
            <td><?php echo htmlspecialchars($payment['remarks']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Add Payment</h2>
    <form method="post">
        <input type="number" name="amount" placeholder="Amount" required>
        <input type="text" name="remarks" placeholder="Remarks">
        <button type="submit">Add Payment</button>
    </form>

    <h2>Deduct from Credits</h2>
    <form method="post">
        <input type="number" name="deduct_amount" placeholder="Amount to Deduct" required>
        <input type="text" name="deduct_remarks" placeholder="Remarks">
        <button type="submit">Deduct Credits</button>
    </form>

    <h2>Credit Deduction Records</h2>
    <table>
        <tr>
            <th>Amount Deducted</th>
            <th>Date</th>
            <th>Remarks</th>
        </tr>
        <?php foreach ($creditRecords as $credit): ?>
        <tr>
            <td><?php echo htmlspecialchars(number_format($credit['amount'], 2)); ?></td>
            <td><?php echo htmlspecialchars($credit['credit_date']); ?></td>
            <td><?php echo htmlspecialchars($credit['remarks']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="index.php">Back</a>
</body>
</html>

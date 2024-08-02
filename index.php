<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch all debtors, their total payments, total credits, and the most recent remark
$stmt = $pdo->prepare('SELECT d.*, 
                       COALESCE(SUM(p.amount), 0) AS total_paid,
                       COALESCE((SELECT SUM(c.amount) FROM credits c WHERE c.debtor_id = d.id), 0) AS total_credits,
                       (SELECT remarks FROM payments WHERE debtor_id = d.id ORDER BY payment_date DESC LIMIT 1) AS latest_remark 
                       FROM debtors d 
                       LEFT JOIN payments p ON d.id = p.debtor_id 
                       GROUP BY d.id');
$stmt->execute();
$debtors = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Debtor Dashboard</h1>
    <table>
        <tr>
            <th>Full Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Amount Owed</th>
            <th>Total Credits</th>
            <th>Latest Remark</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($debtors as $debtor): ?>
        <tr>
            <td><?php echo htmlspecialchars($debtor['full_name']); ?></td>
            <td><?php echo htmlspecialchars($debtor['address']); ?></td>
            <td><?php echo htmlspecialchars($debtor['email']); ?></td>
            <td><?php echo htmlspecialchars($debtor['phone']); ?></td>
            <td><?php echo htmlspecialchars(number_format($debtor['amount_owed'], 2)); ?></td>
            <td><?php echo htmlspecialchars(number_format($debtor['total_credits'], 2)); ?></td>
            <td><?php echo htmlspecialchars($debtor['latest_remark'] ? $debtor['latest_remark'] : 'No remarks'); ?></td>
            <td><a href="debtor.php?id=<?php echo $debtor['id']; ?>">Manage</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="add_debtor.php">Add New Debtor</a>
</body>
</html>

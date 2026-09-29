<?php
session_start();
// Include your database connection file (e.g., db.php)
// require_once 'db.php'; 

// Optional: Add admin authentication check here
// if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
//     header("Location: login.php");
//     exit();
// }

try {
    // Fetch orders ordered by most recent first
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY order_date DESC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Could not fetch orders: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Orders</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; font-size: 14px; }
        th { background-color: #f8f9fa; color: #333; }
        .status-pending { color: #d97706; font-weight: bold; }
        .status-processing { color: #2563eb; font-weight: bold; }
        .status-delivered { color: #16a34a; font-weight: bold; }
        img.proof { width: 50px; height: auto; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <h2>Customer Orders Management</h2>
    
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="9" style="text-align: center;">No orders found.</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($order['order_id']) ?></td>
                            <td>
                                <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($order['email']) ?><br>
                                <small><?= htmlspecialchars($order['phone']) ?></small>
                            </td>
                            <td>
                                <?= htmlspecialchars($order['city']) ?><br>
                                <small><?= htmlspecialchars($order['delivery_address']) ?></small>
                            </td>
                            <td><?= nl2br(htmlspecialchars($order['items_text'])) ?></td>
                            <td>$<?= number_format($order['total_amount'], 2) ?></td>
                            <td>
                                <?= strtoupper(htmlspecialchars($order['payment_method'])) ?>
                                <?php if (!empty($order['screenshot_path'])): ?>
                                    <br><a href="<?= htmlspecialchars($order['screenshot_path']) ?>" target="_blank">
                                        <img src="<?= htmlspecialchars($order['screenshot_path']) ?>" class="proof" alt="Payment Proof">
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-<?= strtolower($order['order_status']) ?>">
                                    <?= ucfirst(htmlspecialchars($order['order_status'])) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($order['order_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
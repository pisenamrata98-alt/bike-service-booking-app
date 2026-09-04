<?php
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT * FROM service_appointments WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    die("Appointment record not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-card">
        <h1 class="app-title" style="color: #4ade80;">✔ Confirmed</h1>
        <p class="app-subtitle">Your appointment ticket has been issued</p>

        <div style="margin-bottom: 20px;">
            <p style="color:#94a3b8; font-size:13px;">Ticket No:</p>
            <p style="font-size:18px; font-weight:700; color:#38bdf8;"><?php echo htmlspecialchars($data['ticket_no']); ?></p>
        </div>

        <div style="margin-bottom: 12px; font-size:14px;"><strong>Name:</strong> <?php echo htmlspecialchars($data['customer_name']); ?></div>
        <div style="margin-bottom: 12px; font-size:14px;"><strong>Phone:</strong> <?php echo htmlspecialchars($data['contact_no']); ?></div>
        <div style="margin-bottom: 12px; font-size:14px;"><strong>Plate No:</strong> <?php echo htmlspecialchars($data['plate_number']); ?></div>
        <div style="margin-bottom: 12px; font-size:14px;"><strong>Service:</strong> <?php echo htmlspecialchars($data['service_selected']); ?></div>
        <div style="margin-bottom: 12px; font-size:14px;"><strong>Date:</strong> <?php echo htmlspecialchars($data['appointment_date']); ?></div>
        <div style="margin-bottom: 20px; font-size:16px; color:#38bdf8;"><strong>Total Cost:</strong> ₹<?php echo number_format($data['price_amount'], 2); ?></div>

        <button onclick="window.print()" class="action-btn">Print Ticket</button>
        <a href="index.php" class="link-btn">Book Another Service</a>
    </div>
</body>
</html>
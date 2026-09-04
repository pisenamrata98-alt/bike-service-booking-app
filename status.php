<?php
require_once 'db.php';

$booking_data = null;
$search_query = "";
$err_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = trim($_POST['search_query']);

    if (!empty($search_query)) {
        $stmt = $conn->prepare("SELECT * FROM service_appointments WHERE ticket_no = ? OR plate_number = ? LIMIT 1");
        $stmt->bind_param("ss", $search_query, $search_query);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $booking_data = $row;
        } else {
            $err_msg = "No booking found with Ticket or Plate Number: " . htmlspecialchars($search_query);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Booking Status</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-card">
        <h1 class="app-title">Track Appointment</h1>
        <p class="app-subtitle">Enter your Ticket No. or Vehicle Plate No.</p>

        <form method="POST" style="margin-bottom: 20px;">
            <div class="field-group">
                <label>Ticket No or Plate No</label>
                <input type="text" name="search_query" placeholder="e.g., TKT123456 or MH28AB1234" value="<?php echo htmlspecialchars($search_query); ?>" required>
            </div>
            <button type="submit" class="action-btn">Search Status</button>
        </form>

        <?php if ($err_msg): ?>
            <p style="color: #f87171; font-size: 13px; text-align: center; margin-bottom: 16px;"><?php echo $err_msg; ?></p>
        <?php endif; ?>

        <?php if ($booking_data): ?>
            <div style="background: #0f172a; padding: 16px; border-radius: 8px; border: 1px solid #334155;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <span style="font-size: 13px; color: #94a3b8;">Ticket: <strong><?php echo htmlspecialchars($booking_data['ticket_no']); ?></strong></span>
                    <span class="badge badge-<?php echo $booking_data['booking_status']; ?>"><?php echo $booking_data['booking_status']; ?></span>
                </div>
                <div style="font-size: 14px; margin-bottom: 6px;"><strong>Name:</strong> <?php echo htmlspecialchars($booking_data['customer_name']); ?></div>
                <div style="font-size: 14px; margin-bottom: 6px;"><strong>Vehicle:</strong> <?php echo htmlspecialchars($booking_data['plate_number']); ?></div>
                <div style="font-size: 14px; margin-bottom: 6px;"><strong>Service:</strong> <?php echo htmlspecialchars($booking_data['service_selected']); ?></div>
                <div style="font-size: 14px; margin-bottom: 6px;"><strong>Date:</strong> <?php echo htmlspecialchars($booking_data['appointment_date']); ?></div>
                <div style="font-size: 14px; color: #38bdf8;"><strong>Cost:</strong> ₹<?php echo number_format($booking_data['price_amount'], 2); ?></div>
            </div>
        <?php endif; ?>

        <a href="index.php" class="link-btn">← Back to Booking Page</a>
    </div>
</body>
</html>
<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_authenticated'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE service_appointments SET booking_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
}

if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM service_appointments WHERE id = ?");
    $stmt->bind_param("i", $del_id);
    $stmt->execute();
    header("Location: admin.php");
    exit();
}

$query = "SELECT * FROM service_appointments ORDER BY appointment_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="display: block;">

    <div class="admin-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h1 class="app-title">Appointments Management</h1>
            <a href="logout.php" style="color:#f87171; text-decoration:none; font-size:14px;">Logout</a>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Plate No</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['ticket_no']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['plate_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['service_selected']); ?></td>
                            <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                            <td>₹<?php echo number_format($row['price_amount'], 2); ?></td>
                            <td><span class="badge badge-<?php echo $row['booking_status']; ?>"><?php echo $row['booking_status']; ?></span></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <select name="status" onchange="this.form.submit()" style="padding:4px; font-size:12px;">
                                        <option value="Pending" <?php if($row['booking_status']=='Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Confirmed" <?php if($row['booking_status']=='Confirmed') echo 'selected'; ?>>Confirmed</option>
                                        <option value="Completed" <?php if($row['booking_status']=='Completed') echo 'selected'; ?>>Completed</option>
                                        <option value="Cancelled" <?php if($row['booking_status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                                <a href="admin.php?delete_id=<?php echo $row['id']; ?>" style="color:#f87171; text-decoration:none; margin-left:8px; font-size:12px;" onclick="return confirm('Delete record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" style="text-align:center;">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
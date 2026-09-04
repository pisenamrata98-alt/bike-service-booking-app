<?php
require_once 'db.php';
$today_date = date('Y-m-d');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = trim($_POST['customer_name']);
    $contact_no = trim($_POST['contact_no']);
    $plate_number = strtoupper(trim($_POST['plate_number']));
    $service_selected = trim($_POST['service_selected']);
    $appointment_date = $_POST['appointment_date'];

    $rates = [
        "Full Inspection" => 1500,
        "Engine Oil Change" => 800,
        "Brake Replacement" => 1200,
        "Detailing & Polish" => 600
    ];

    $price_amount = isset($rates[$service_selected]) ? $rates[$service_selected] : 0;
    $ticket_no = "TKT" . rand(100000, 999999);

    if (!empty($customer_name) && !empty($contact_no) && !empty($plate_number) && !empty($service_selected) && !empty($appointment_date)) {
        $stmt = $conn->prepare("INSERT INTO service_appointments (ticket_no, customer_name, contact_no, plate_number, service_selected, appointment_date, price_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssd", $ticket_no, $customer_name, $contact_no, $plate_number, $service_selected, $appointment_date, $price_amount);

        if ($stmt->execute()) {
            $new_id = $stmt->insert_id;
            $stmt->close();
            header("Location: confirmation.php?id=" . $new_id);
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Service Booking</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-card">
        <h1 class="app-title">Vehicle Service</h1>
        <p class="app-subtitle">Book your service appointment online</p>

        <form method="POST">
            <div class="field-group">
                <label>Customer Name</label>
                <input type="text" name="customer_name" required>
            </div>
            <div class="field-group">
                <label>Contact Number</label>
                <input type="tel" name="contact_no" pattern="[0-9]{10}" required>
            </div>
            <div class="field-group">
                <label>Vehicle Plate Number</label>
                <input type="text" name="plate_number" style="text-transform: uppercase;" required>
            </div>
            <div class="field-group">
                <label>Service Type</label>
                <select name="service_selected" id="serviceSelect" onchange="calculateCost()" required>
                    <option value="" data-cost="0">-- Select Option --</option>
                    <option value="Full Inspection" data-cost="1500">Full Inspection (₹1,500)</option>
                    <option value="Engine Oil Change" data-cost="800">Engine Oil Change (₹800)</option>
                    <option value="Brake Replacement" data-cost="1200">Brake Replacement (₹1,200)</option>
                    <option value="Detailing & Polish" data-cost="600">Detailing & Polish (₹600)</option>
                </select>
            </div>

            <div id="costBox" class="price-box">
                Estimated Price: <span id="costVal">₹0</span>
            </div>

            <div class="field-group">
                <label>Date of Service</label>
                <input type="date" name="appointment_date" min="<?php echo $today_date; ?>" required>
            </div>

            <button type="submit" class="action-btn">Book Appointment</button>
        </form>

        <a href="status.php" class="link-btn" style="color: #38bdf8; margin-bottom: 8px;">🔍 Check Booking Status</a>
<a href="login.php" class="link-btn">Admin Login →</a>
    </div>

    <script>
        function calculateCost() {
            var select = document.getElementById("serviceSelect");
            var option = select.options[select.selectedIndex];
            var cost = option.getAttribute("data-cost");
            var box = document.getElementById("costBox");

            if (cost > 0) {
                document.getElementById("costVal").textContent = "₹" + cost;
                box.style.display = "block";
            } else {
                box.style.display = "none";
            }
        }
    </script>
</body>
</html>
<?php
session_start();
include '../database/dbconnect.php';

// Fetch all bookings with user details
$sql = "SELECT 
            b.*, 
            u.full_name,
            u.email,
            u.phone_number
        FROM bookings b
        LEFT JOIN users u ON b.user_id = u.id
        ORDER BY b.booking_date DESC";
try {
    $result = $conn->query($sql);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            background: #f4f6f9;
        }

        .content-wrapper {
            flex: 1;
            margin-left: 250px; /* Same as sidebar width */
            padding: 20px;
            min-height: 100vh;
            background: #f4f6f9;
        }

        .status-pending { color: #f39c12; }
        .status-confirmed { color: #2ecc71; }
        .status-cancelled { color: #e74c3c; }
        
        .action-buttons button {
            padding: 8px 15px;
            margin: 2px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .cancel-btn { 
            background: #e74c3c; 
            color: white; 
        }
        .delete-btn { 
            background: #34495e; 
            color: white; 
        }
        
        .action-buttons button:hover {
            opacity: 0.8;
        }
        
        .booking-details {
            background: #f8f9fa;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            line-height: 1.4;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 500;
            color: #2c3e50;
            border-bottom: 2px solid #ddd;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert.success {
            background: #d4edda;
            color: #155724;
        }
        .alert.error {
            background: #f8d7da;
            color: #721c24;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar h1 {
            color: #2c3e50;
            font-size: 1.5rem;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="content-wrapper">
        <div class="navbar">
            <h1>Booking Management</h1>
            <a href="../database/logout.php" class="logout-btn">Logout</a>
        </div>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert <?php echo $_SESSION['message_type']; ?>">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <h2>All Bookings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Guest Details</th>
                        <th>Room Details</th>
                        <th>Stay Details</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($booking = $result->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td>#<?php echo $booking['booking_id']; ?></td>
                            <td>
                                <div class="booking-details">
                                    <strong><?php echo htmlspecialchars($booking['full_name']); ?></strong><br>
                                    Email: <?php echo htmlspecialchars($booking['email']); ?><br>
                                    Phone: <?php echo htmlspecialchars($booking['phone_number']); ?>
                                </div>
                            </td>
                            <td>
                                <div class="booking-details">
                                    Type: <?php echo htmlspecialchars($booking['room_type']); ?><br>
                                    Room ID: <?php echo $booking['room_id']; ?><br>
                                    Persons: <?php echo $booking['persons']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="booking-details">
                                    Date: <?php echo date('d M Y', strtotime($booking['booking_date'])); ?><br>
                                    Duration: <?php echo $booking['days']; ?> days
                                </div>
                            </td>
                            <td>
                                <div class="booking-details">
                                    ₹<?php echo number_format($booking['total_price'], 2); ?>
                                </div>
                            </td>
                            <td class="action-buttons">
                                <button class="cancel-btn" onclick="cancelBooking(<?php echo $booking['booking_id']; ?>)">
                                    Cancel
                                </button>
                                <button class="delete-btn" onclick="deleteBooking(<?php echo $booking['booking_id']; ?>)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function cancelBooking(bookingId) {
            if(confirm('Are you sure you want to cancel this booking?')) {
                window.location.href = 'update_booking.php?booking_id=' + bookingId + '&action=cancel';
            }
        }

        function deleteBooking(bookingId) {
            if(confirm('Are you sure you want to delete this booking? This action cannot be undone!')) {
                window.location.href = 'update_booking.php?booking_id=' + bookingId + '&action=delete';
            }
        }
    </script>
</body>
</html>

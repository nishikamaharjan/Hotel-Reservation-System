<?php
session_start();


// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'hrs');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch statistics for total users and total bookings
$totalUsersQuery = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$totalUsers = $totalUsersQuery->fetch_assoc()['total_users'];

$totalBookingsQuery = $conn->query("SELECT COUNT(*) AS total_bookings FROM bookings");
$totalBookings = $totalBookingsQuery->fetch_assoc()['total_bookings'];

// Fetch recent bookings (room data will be fetched within the loop)
$recentBookingsQuery = $conn->query("SELECT * FROM bookings ORDER BY booking_date DESC LIMIT 5");



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="dashboard.css" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <h2 style = "color:blue; background-color:white;" >Admin Panel</h2>
        <a href="#">Dashboard</a>
        <a href="manage_rooms.php">Manage Rooms</a>
        <a href="manage_bookings.php">Manage Bookings</a>
        <a href="user_management.php">User Management</a>
        <!-- Add New User Button -->
        <a href="#" onclick="openModal()">Add New User</a>
    </div>

    <div class="dashboard-content">
        <!-- Navbar -->
        <div class="navbar">
            <h1 style ="color:green; background-color:white">Welcome, Admin</h1>
            <a href="../database/logout.php" class="logout-btn">Logout</a>
        </div>

        <!-- Dashboard Cards -->
        <div class="cards">
            <div class="card">
                <div class="icon">🏨</div>
                <h3>Total Rooms</h3>
                <p>5</p> 
            </div>
            <div class="card">
                <div class="icon">📅</div>
                <h3>Total Bookings</h3>
                <p><?php echo $totalBookings; ?></p>
            </div>
            <div class="card">
                <div class="icon">👥</div>
                <h3>Total Users</h3>
                <p><?php echo $totalUsers; ?></p>
            </div>

        </div>



        <!-- Booking Table -->
        <div class="table-container">
            <h2>Recent Bookings</h2>
            <table>
                <tr>
                    <th>Booking ID</th>
                    <th>User</th>
                    <th>Room</th> <!-- Display the room type -->
                    <th>Days</th>
                    <th>Persons</th>
                    <th>Date</th>
                    <th>Total Price</th>
                </tr>
                <?php
                // Display the recent bookings from the database
                while ($booking = $recentBookingsQuery->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$booking['booking_id']}</td>";
                    echo "<td>{$booking['user_id']}</td>";
                    echo "<td>{$booking['room_type']}</td>"; // Display room type
                    echo "<td>{$booking['days']}</td>";
                    echo "<td>{$booking['persons']}</td>";
                    echo "<td>{$booking['booking_date']}</td>";
                    echo "<td>{$booking['total_price']}</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>

    <!-- Modal (Popup) for Add User -->
    <div class="modal" id="userModal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form action="adduser.php" method="POST">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="phone_number" placeholder="Phone Number" required>
                <input type="date" name="dob" placeholder="Date of Birth" required>
                <select name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <select name="role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
                <button type="submit">Add User</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('userModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('userModal').style.display = "none";
        }

        // Close the modal if the user clicks outside the modal
        window.onclick = function(event) {
            if (event.target == document.getElementById('userModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>

<?php
// Close the connection after all queries are executed
$conn->close();
?>

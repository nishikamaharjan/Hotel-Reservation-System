<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
        Dashboard
    </a>
    <a href="manage_rooms.php" class="<?php echo $current_page == 'manage_rooms.php' ? 'active' : ''; ?>">
        Manage Rooms
    </a>
    <a href="manage_bookings.php" class="<?php echo $current_page == 'manage_bookings.php' ? 'active' : ''; ?>">
        Manage Bookings
    </a>
    <a href="user_management.php" class="<?php echo $current_page == 'user_management.php' ? 'active' : ''; ?>">
        User Management
    </a>
</div>

<style>
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
    height: 100vh;
    background: #2c3e50;
    padding: 20px;
    color: white;
    z-index: 1000;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    padding: 10px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 5px;
}

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 12px 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.sidebar a:hover, .sidebar a.active {
    background: #34495e;
    transform: translateX(10px);
}

.admin-actions {
    margin-top: 30px;
}

.add-btn {
    background: #3498db;
    color: white;
    padding: 12px;
    margin-bottom: 10px;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    transition: all 0.3s ease;
}

.add-btn:hover {
    background: #2980b9;
}
</style> 
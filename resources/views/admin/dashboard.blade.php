<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            display: flex;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            padding: 15px;
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
        }

        /* Navbar Styles */
        .navbar {
            background-color: #007bff;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .brand {
            font-size: 24px;
            font-weight: bold;
        }

        .navbar .user-info {
            font-size: 18px;
        }

        .navbar .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .navbar .logout-btn:hover {
            background-color: #c82333;
        }

        /* Active link */
        .sidebar .active {
            background-color: #007bff;
        }

        /* Page Content */
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Dashboard Section */
        .dashboard {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .dashboard-item {
            flex: 1;
            padding: 20px;
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 8px;
        }

        .dashboard-item h3 {
            margin: 0;
            font-size: 24px;
        }

        .dashboard-item p {
            margin: 10px 0;
            font-size: 18px;
        }

        .dashboard-item:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <!-- Sidebar and Main Layout -->
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="#" class="active">Dashboard</a>
            <a href="#">Users</a>
            <a href="#">Categories</a>
            <a href="#">Products</a>
            <a href="#">Orders</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            <div class="navbar">
                <div class="brand">Admin Dashboard</div>
                <div class="user-info">
                    <span>Welcome, Admin</span>
                    <button class="logout-btn" onclick="logout()">Logout</button>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="content">
                <h2>Dashboard</h2>

                <!-- Dashboard Statistics -->
                <div class="dashboard">
                    <div class="dashboard-item">
                        <h3>150</h3>
                        <p>Users</p>
                    </div>
                    <div class="dashboard-item">
                        <h3>30</h3>
                        <p>Categories</p>
                    </div>
                    <div class="dashboard-item">
                        <h3>120</h3>
                        <p>Products</p>
                    </div>
                    <div class="dashboard-item">
                        <h3>75</h3>
                        <p>Orders</p>
                    </div>
                </div>

                <!-- Detailed Section (Content for other pages like Users, Categories, etc.) -->
                <div id="contentSection">
                    <!-- Content will be dynamically loaded here based on sidebar navigation -->
                    <p>Select a section from the sidebar to manage.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle the sidebar navigation
        $('.sidebar a').on('click', function(e) {
            e.preventDefault();
            $('.sidebar a').removeClass('active');
            $(this).addClass('active');

            var section = $(this).text().toLowerCase();

            // Dynamically update the content section based on the selected page
            if (section === 'dashboard') {
                $('#contentSection').html('<p>Here is the dashboard content.</p>');
            } else if (section === 'users') {
                $('#contentSection').html('<p>Manage your users here.</p>');
            } else if (section === 'categories') {
                $('#contentSection').html('<p>Manage your product categories here.</p>');
            } else if (section === 'products') {
                $('#contentSection').html('<p>Manage your products here.</p>');
            } else if (section === 'orders') {
                $('#contentSection').html('<p>Manage your orders here.</p>');
            }
        });

        // Handle logout (for now, just an alert)
        function logout() {
            // Ideally, you'd make an AJAX call here to log the user out
            alert('Logged out successfully!');
            window.location.href = "/"; // Redirect to the home or login page
        }
    </script>
</body>

</html>

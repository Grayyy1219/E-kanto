<header>
    <a href="admin.php"><img src="<?php echo $logo ?>" width="50" alt="Logo"></a>
    <div class="logoname"><a href="#"><strong>Admin Page</strong></a></div>
    <div class="header">
        <h4>Customer Page</h4>
        <a class="a" href="admin.php#page">Page</a>
        <a class="a" href="blockuser.php">Users</a><br>
        <h4>Books</h4>
        <a class="a" href="admin.php#inventory">Inventory</a>
        <a class="a" href="admin.php#dashboard">Fast & Slow</a>
        <a class="a" href="paymenthistory.php">Payment</a><br>
        <h4>General</h4>
        <a class="a" href="printreport.php">Reports</a>
        <a onclick='showSettingsPopup()' class="a" href="#settings">Settings</a>
    </div>
    <?php include 'apopups.php'; ?>
    <a class="a" id="logout" href="logout.php">Log out</a>
</header>
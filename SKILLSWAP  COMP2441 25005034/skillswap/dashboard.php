<?php
require "includes/auth.php";
requireLogin();
?>

<?php include "includes/header.php"; ?>

<h2>Dashboard</h2>

<div class="card">

    <h3>Welcome back,
    <?php echo clean($_SESSION['full_name']); ?> 👋</h3>

    <p>
        Manage your skills, requests and profile from your dashboard.
    </p>

    <p>
        <strong>Your Role:</strong>
        <?php echo clean($_SESSION['role']); ?>
    </p>

</div>

<div class="dashboard-buttons">

    <a class="dashboard-btn" href="skills.php">
        View Skills
    </a>

    <a class="dashboard-btn" href="add_skill.php">
        Add Skill
    </a>

    <a class="dashboard-btn" href="my_requests.php">
        My Requests
    </a>

    <a class="dashboard-btn" href="manage_requests.php">
    Manage Requests
</a>
<?php if ($_SESSION['role'] === 'admin'): ?>
    <a class="dashboard-btn" href="admin_users.php">
        Admin Users
    </a>
<?php endif; ?>


    <a class="dashboard-btn logout-btn" href="logout.php">
        Logout
    </a>

</div>

<?php include "includes/footer.php"; ?>
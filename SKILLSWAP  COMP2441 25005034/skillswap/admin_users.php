<?php
require "config/db.php";
require "includes/auth.php";

requireAdmin();

$stmt = $pdo->prepare("SELECT user_id, full_name, email, role, created_at FROM users ORDER BY created_at DESC");
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "includes/header.php"; ?>

<h2>Admin - Users</h2>

<p>
    <a href="dashboard.php">Dashboard</a>
    <a href="skills.php">Skills</a>
</p>

<?php foreach ($users as $user): ?>

<div class="card">
    <h3><?php echo clean($user['full_name']); ?></h3>

    <p><strong>Email:</strong> <?php echo clean($user['email']); ?></p>
    <p><strong>Role:</strong> <?php echo clean($user['role']); ?></p>
    <p><strong>Joined:</strong> <?php echo clean($user['created_at']); ?></p>
</div>

<?php endforeach; ?>

<?php include "includes/footer.php"; ?>
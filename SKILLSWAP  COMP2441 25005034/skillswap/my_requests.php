<?php
require "config/db.php";
require "includes/auth.php";

requireLogin();

$stmt = $pdo->prepare("
    SELECT requests.*, skills.skill_title, users.full_name
    FROM requests
    JOIN skills ON requests.skill_id = skills.skill_id
    JOIN users ON requests.requester_id = users.user_id
    WHERE requests.requester_id = ?
    ORDER BY requests.created_at DESC
");

$stmt->execute([$_SESSION['user_id']]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "includes/header.php"; ?>

<h2>My Requests</h2>

<p>
    <a href="skills.php">Available Skills</a>
    <a href="dashboard.php">Dashboard</a>
</p>

<?php if (count($requests) === 0): ?>
    <p>You have not requested any skills yet.</p>
<?php endif; ?>

<?php foreach ($requests as $request): ?>
    <div class="card">
        <h3><?php echo clean($request['skill_title']); ?></h3>
        <p><strong>Message:</strong> <?php echo clean($request['message']); ?></p>
        <p><strong>Status:</strong> <?php echo clean($request['status']); ?></p>
        <p><strong>Requested by:</strong> <?php echo clean($request['full_name']); ?></p>
    </div>
<?php endforeach; ?>

<?php include "includes/footer.php"; ?>
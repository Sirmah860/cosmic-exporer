<?php
require "config/db.php";
require "includes/auth.php";

requireLogin();

if (isset($_GET['action']) && isset($_GET['id'])) {

    $request_id = $_GET['id'];
    $action = $_GET['action'];

    if ($action === "accepted" || $action === "rejected") {

        $update = $pdo->prepare("
            UPDATE requests
            SET status = ?
            WHERE request_id = ?
        ");

        $update->execute([$action, $request_id]);
    }

    header("Location: manage_requests.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT requests.*, skills.skill_title, users.full_name
    FROM requests
    JOIN skills ON requests.skill_id = skills.skill_id
    JOIN users ON requests.requester_id = users.user_id
    WHERE skills.user_id = ?
    ORDER BY requests.created_at DESC
");

$stmt->execute([$_SESSION['user_id']]);

$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "includes/header.php"; ?>

<h2>Manage Requests</h2>

<p>
    <a href="dashboard.php">Dashboard</a>
    <a href="skills.php">Skills</a>
</p>

<?php if (count($requests) === 0): ?>
    <p>No requests received yet.</p>
<?php endif; ?>

<?php foreach ($requests as $request): ?>

<div class="card">

    <h3><?php echo clean($request['skill_title']); ?></h3>

    <p>
        <strong>Requested By:</strong>
        <?php echo clean($request['full_name']); ?>
    </p>

    <p>
        <strong>Message:</strong>
        <?php echo clean($request['message']); ?>
    </p>

    <p>
        <strong>Status:</strong>
        <?php echo clean($request['status']); ?>
    </p>

    <?php if ($request['status'] === 'pending'): ?>

        <a href="manage_requests.php?action=accepted&id=<?php echo $request['request_id']; ?>">
            Accept
        </a>

        <a href="manage_requests.php?action=rejected&id=<?php echo $request['request_id']; ?>">
            Reject
        </a>

    <?php endif; ?>

</div>

<?php endforeach; ?>

<?php include "includes/footer.php"; ?>
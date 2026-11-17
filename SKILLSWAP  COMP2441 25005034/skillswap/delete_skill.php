<?php
require "config/db.php";
require "includes/auth.php";

requireLogin();

if (!isset($_GET['id'])) {
    header("Location: skills.php");
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM skills WHERE skill_id = ?");
$stmt->execute([$id]);

$skill = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$skill) {
    die("Skill not found.");
}

if ($skill['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$delete = $pdo->prepare("DELETE FROM skills WHERE skill_id = ?");
$delete->execute([$id]);

header("Location: skills.php");
exit;
?>
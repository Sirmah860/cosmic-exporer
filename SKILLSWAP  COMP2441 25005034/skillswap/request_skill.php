<?php
require "config/db.php";
require "includes/auth.php";
require "includes/csrf.php";

requireLogin();

if (!isset($_GET['id'])) {
    header("Location: skills.php");
    exit;
}

$skill_id = $_GET['id'];
$error = "";

$stmt = $pdo->prepare("SELECT * FROM skills WHERE skill_id = ?");
$stmt->execute([$skill_id]);
$skill = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$skill) {
    die("Skill not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!checkToken($_POST['csrf_token'])) {
        $error = "Invalid security token.";
    } else {
        $message = trim($_POST['message']);

        if (empty($message)) {
            $error = "Message is required.";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO requests (skill_id, requester_id, message)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$skill_id, $_SESSION['user_id'], $message]);

            header("Location: my_requests.php");
            exit;
        }
    }
}
?>

<?php include "includes/header.php"; ?>

<h2>Request Skill</h2>

<p><strong>Skill:</strong> <?php echo clean($skill['skill_title']); ?></p>

<?php if ($error): ?>
    <p style="color:red;"><?php echo clean($error); ?></p>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo createToken(); ?>">

    <label>Message</label>
    <textarea name="message" placeholder="Write why you want to learn this skill"></textarea>

    <button type="submit">Send Request</button>
</form>

<p><a href="skills.php">Back to Skills</a></p>

<?php include "includes/footer.php"; ?>
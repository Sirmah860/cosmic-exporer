<?php
require "config/db.php";
require "includes/auth.php";
require "includes/csrf.php";


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

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!checkToken($_POST['csrf_token'])) {
        $error = "Invalid CSRF token.";
    } else {

        $title = trim($_POST['skill_title']);
        $category = trim($_POST['category']);
        $description = trim($_POST['description']);
        $availability = trim($_POST['availability']);

        if (empty($title) || empty($category) || empty($description) || empty($availability)) {
            $error = "All fields are required.";
        } else {

            $update = $pdo->prepare("
                UPDATE skills
                SET skill_title=?, category=?, description=?, availability=?
                WHERE skill_id=?
            ");

            $update->execute([
                $title,
                $category,
                $description,
                $availability,
                $id
            ]);

            header("Location: skills.php");
            exit;
        }
    }
}
?>

<?php include "includes/header.php"; ?>

<h2>Edit Skill</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo clean($error); ?></p>
<?php endif; ?>

<form method="POST">

    <input type="hidden" name="csrf_token"
    value="<?php echo createToken(); ?>">

    <label>Skill Title</label>
    <input type="text" name="skill_title"
    value="<?php echo clean($skill['skill_title']); ?>">

    <label>Category</label>
    <input type="text" name="category"
    value="<?php echo clean($skill['category']); ?>">

    <label>Description</label>
    <textarea name="description"><?php echo clean($skill['description']); ?></textarea>

    <label>Availability</label>
    <input type="text" name="availability"
    value="<?php echo clean($skill['availability']); ?>">

    <br><br>

    <button type="submit">Update Skill</button>

</form>

<?php include "includes/footer.php"; ?>
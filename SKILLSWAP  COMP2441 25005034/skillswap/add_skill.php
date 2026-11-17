<?php
require "config/db.php";
require "includes/auth.php";
require "includes/csrf.php";


requireLogin();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!checkToken($_POST['csrf_token'])) {
        $error = "Invalid security token.";
    } else {
        $title = trim($_POST['skill_title']);
        $category = trim($_POST['category']);
        $description = trim($_POST['description']);
        $availability = trim($_POST['availability']);

        if (empty($title) || empty($category) || empty($description) || empty($availability)) {
            $error = "All fields are required.";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO skills (user_id, skill_title, category, description, availability)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $_SESSION['user_id'],
                $title,
                $category,
                $description,
                $availability
            ]);

            header("Location: skills.php");
            exit;
        }
    }
}
?>

<?php include "includes/header.php"; ?>

<h2>Add a Skill</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo clean($error); ?></p>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo createToken(); ?>">

    <label>Skill Title</label>
    <input type="text" name="skill_title">

    <label>Category</label>
    <input type="text" name="category">

    <label>Description</label>
    <textarea name="description"></textarea>

    <label>Availability</label>
    <input type="text" name="availability" placeholder="Example: Weekends, evenings">

    <br><br>
    <button type="submit">Save Skill</button>
</form>

<p><a href="skills.php">Back to Skills</a></p>

<?php include "includes/footer.php"; ?>
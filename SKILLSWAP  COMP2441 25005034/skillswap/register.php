<?php
require "config/db.php";
require "includes/csrf.php";


$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!checkToken($_POST['csrf_token'])) {
        $error = "Invalid security token.";
    } else {
        $fullName = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (empty($fullName) || empty($email) || empty($password)) {
            $error = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email.";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            try {
                $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$fullName, $email, $hashedPassword]);

                header("Location: login.php");
                exit;
            } catch (PDOException $e) {
                $error = "Email already exists.";
            }
        }
    }
}
?>
<?php include "includes/header.php"; ?>
<h2>Create SkillSwap Account</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo createToken(); ?>">

    <label>Full Name</label><br>
    <input type="text" name="full_name"><br><br>

    <label>Email</label><br>
    <input type="email" name="email"><br><br>

    <label>Password</label><br>
    <input type="password" name="password"><br><br>

    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login</a></p>
<?php include "includes/footer.php"; ?>
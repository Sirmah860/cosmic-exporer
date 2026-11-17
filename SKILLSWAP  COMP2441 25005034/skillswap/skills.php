<?php
require "config/db.php";
require "includes/auth.php";

requireLogin();

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$stmt = $pdo->prepare("
    SELECT skills.*, users.full_name
    FROM skills
    JOIN users ON skills.user_id = users.user_id
    WHERE skill_title LIKE ? OR category LIKE ?
    ORDER BY skills.created_at DESC
");

$searchTerm = "%$search%";

$stmt->execute([$searchTerm, $searchTerm]);

$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<?php include "includes/header.php"; ?>

<h2>Available Skills</h2>
<form method="GET">

    <input type="text"
           name="search"
           placeholder="Search skills or category"
           value="<?php echo clean($search); ?>">

    <button type="submit">Search</button>

</form>

<p>
    <a href="dashboard.php">Dashboard</a> |
    <a href="add_skill.php">Add Skill</a> |
    <a href="logout.php">Logout</a>
</p>

<?php if (count($skills) === 0): ?>
    <p>No skills have been added yet.</p>
<?php endif; ?>

<?php foreach ($skills as $skill): ?>
    <div class="card">
        <h3><?php echo clean($skill['skill_title']); ?></h3>

        <p><strong>Category:</strong> <?php echo clean($skill['category']); ?></p>
        <p><strong>Description:</strong> <?php echo clean($skill['description']); ?></p>
        <p><strong>Availability:</strong> <?php echo clean($skill['availability']); ?></p>
        <p><strong>Posted by:</strong> <?php echo clean($skill['full_name']); ?></p>
    <?php if ($skill['user_id'] != $_SESSION['user_id']): ?>
    <a href="request_skill.php?id=<?php echo $skill['skill_id']; ?>">Request Skill</a>
<?php endif; ?>
        <?php if ($skill['user_id'] == $_SESSION['user_id'] || $_SESSION['role'] === 'admin'): ?>
            <a href="edit_skill.php?id=<?php echo $skill['skill_id']; ?>">Edit</a> |
            <a href="delete_skill.php?id=<?php echo $skill['skill_id']; ?>"
               onclick="return confirm('Are you sure you want to delete this skill?');">
               Delete
            </a>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<?php include "includes/footer.php"; ?>
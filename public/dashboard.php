<?php
session_start();
require_once '../config/Database.php';
require_once '../classes/PasswordGenerator.php';
require_once '../classes/PasswordManager.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['key'])) {
    header("Location: login.php");
    exit();
}

$db = (new Database())->connect();
$manager = new PasswordManager($db, $_SESSION['key']);
$message = "";

// Handle add password
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['site_name'])) {
    $site = $_POST['site_name'];
    $password = $_POST['custom_password'] ?: PasswordGenerator::generate(
        $_POST['length'], $_POST['lower'], $_POST['upper'], $_POST['nums'], $_POST['specials']
    );
    if ($manager->savePassword($_SESSION['user_id'], $site, $password)) {
        $message = "✅ Password saved successfully.";
    } else {
        $message = "❌ Failed to save password.";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$passwords = $manager->getPasswords($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Dashboard</title>
</head>
<body>
    <h2>🔐 Welcome, User #<?= $_SESSION['user_id'] ?></h2>
    <p><a href="?logout=1">🚪 Logout</a></p>

    <?php if ($message): ?>
        <p style="color: green;"><?= $message ?></p>
    <?php endif; ?>

    <hr>
    <h3>🧪 Generate or Add New Password</h3>
    <form method="post">
        Site/App Name: <input name="site_name" required><br>
        Custom Password (optional): <input name="custom_password"><br>
        Or generate one:<br>
        Length: <input name="length" type="number" value="9"><br>
        Lowercase: <input name="lower" type="number" value="2"><br>
        Uppercase: <input name="upper" type="number" value="3"><br>
        Numbers: <input name="nums" type="number" value="2"><br>
        Specials: <input name="specials" type="number" value="2"><br>
        <input type="submit" value="Save Password">
    </form>

    <hr>
    <h3>📄 Your Saved Passwords</h3>
    <table border="1">
        <tr>
            <th>Site</th>
            <th>Password</th>
            <th>Saved At</th>
        </tr>
        <?php foreach ($passwords as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['site_name']) ?></td>
                <td><?= htmlspecialchars($manager->decrypt($row['password'])) ?></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

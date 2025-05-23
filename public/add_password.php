<?php
session_start();
require_once '../config/Database.php';
require_once '../classes/PasswordManager.php';
require_once '../classes/PasswordGenerator.php';

$db = (new Database())->connect();
$manager = new PasswordManager($db, $_SESSION['key']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $site = $_POST['site_name'];
    $password = $_POST['custom_password'] ?: PasswordGenerator::generate(
        $_POST['length'], $_POST['lower'], $_POST['upper'], $_POST['nums'], $_POST['specials']
    );
    if ($manager->savePassword($_SESSION['user_id'], $site, $password)) {
        echo "Password saved.";
    } else {
        echo "Failed to save.";
    }
}
?>

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

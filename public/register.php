<?php
require_once '../config/Database.php';
require_once '../classes/User.php';

$db = (new Database())->connect();
$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($user->register($username, $password)) {
        echo "User registered successfully.";
    } else {
        echo "Registration failed.";
    }
}
?>

<form method="post">
    Username: <input name="username" required><br>
    Password: <input name="password" type="password" required><br>
    <input type="submit" value="Register">
</form>

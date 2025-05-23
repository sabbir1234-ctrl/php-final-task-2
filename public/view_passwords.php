<?php
session_start();
require_once '../config/Database.php';
require_once '../classes/PasswordManager.php';

$db = (new Database())->connect();
$manager = new PasswordManager($db, $_SESSION['key']);
$passwords = $manager->getPasswords($_SESSION['user_id']);
?>

<h2>Saved Passwords</h2>
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

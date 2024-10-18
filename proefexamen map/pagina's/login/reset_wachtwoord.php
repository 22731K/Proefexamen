<?php

require 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()');
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_password = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET wachtwoord = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?');
        $stmt->execute([$new_password, $user['id']]);
        echo "Uw wachtwoord is succesvol gewijzigd.";
        exit;
    } else if (!$user) {
        echo "Ongeldige of verlopen token.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wachtwoord Resetten</title>
</head>
<body>
<h2>Wachtwoord Resetten</h2>
<form method="POST" action="">
    <label>Nieuw wachtwoord:</label>
    <input type="password" name="wachtwoord" required><br>

    <button type="submit">Reset Wachtwoord</button>
</form>
</body>
</html>

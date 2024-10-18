<?php

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare('SELECT * FROM login WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(50));
        $expire = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $stmt = $pdo->prepare('UPDATE login SET reset_token = ?, reset_expires = ? WHERE email = ?');
        $stmt->execute([$token, $expire, $email]);

        $reset_link = "http://yourdomain.com/reset_wachtwoord.php?token=" . $token;
        // Stuur een email naar de gebruiker met deze link
        echo "Er is een email verstuurd naar $email met verdere instructies.";
    } else {
        echo "Geen account gevonden met dit emailadres.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wachtwoord Vergeten</title>
</head>
<body>
<h2>Wachtwoord Vergeten</h2>
<form method="POST" action="">
    <label>Email:</label>
    <input type="email" name="email" required><br>

    <button type="submit">Stuur reset link</button>
</form>
</body>
</html>

<?php
session_start();
require "database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $wachtwoord = $_POST['wachtwoord'];

    $stmt = $pdo->prepare('SELECT * FROM login WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($wachtwoord, $user['wachtwoord'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['naam'] = $user['naam'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Ongeldige inloggegevens';
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen - Verkiezingen</title>
</head>
<body>
    <h2>Inloggen</h2>
    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Wachtwoord:</label>
        <input type="password" name="wachtwoord" required><br>

        <button type="submit">Inloggen</button>
    </form>
    <a href="wachtwoord_vergeten.php">Wachtwoord vergeten?</a>
</body>
</html>

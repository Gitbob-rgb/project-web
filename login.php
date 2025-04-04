<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
    $stmt->execute(['email' => $_POST['email']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['motdepasse'], $user['motdepasse'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        header("Location: dashboard.php");
    } else {
        echo "Identifiants incorrects.";
    }
}
?>

<form method="POST">
    Email: <input type="email" name="email"><br>
    Mot de passe: <input type="password" name="motdepasse"><br>
    <button type="submit">Se connecter</button>
</form>

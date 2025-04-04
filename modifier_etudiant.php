<?php
require 'auth.php';
checkAccess(['admin', 'pilote']);

require 'config.php';

$id = $_GET['id'];
// Récupérer les informations du compte étudiant
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);

    // Mettre à jour le compte étudiant
    $stmt = $pdo->prepare("UPDATE utilisateurs SET email = ?, motdepasse = ? WHERE id = ?");
    $stmt->execute([$email, $motdepasse, $id]);

    header("Location: liste_etudiants.php");
}

?>

<form method="POST">
    Email: <input type="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required><br>
    Nouveau mot de passe: <input type="password" name="motdepasse" required><br>
    <button type="submit">Mettre à jour</button>
</form>

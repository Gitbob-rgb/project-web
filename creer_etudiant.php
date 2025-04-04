<?php
require 'auth.php';
checkAccess(['admin', 'pilote']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
    $role = 'etudiant';  // Rôle "étudiant"

    // Insérer un nouveau compte étudiant dans la base de données
    require 'config.php';
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, motdepasse, role) VALUES (?, ?, ?)");
    $stmt->execute([$email, $motdepasse, $role]);

    header("Location: liste_etudiants.php");  // Rediriger vers la liste des étudiants
}

?>

<form method="POST">
    Email: <input type="email" name="email" required><br>
    Mot de passe: <input type="password" name="motdepasse" required><br>
    <button type="submit">Créer un étudiant</button>
</form>

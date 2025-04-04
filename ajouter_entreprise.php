<?php
require 'auth.php';
checkAccess(['admin', 'pilote']);

require 'EntrepriseManager.php';
$manager = new EntrepriseManager($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manager->ajouter($_POST['nom'], $_POST['adresse'], $_POST['email']);
    header("Location: liste_entreprises.php");
}
?>

<form method="POST">
    Nom: <input type="text" name="nom"><br>
    Adresse: <input type="text" name="adresse"><br>
    Email: <input type="email" name="email"><br>
    <button type="submit">Ajouter</button>
</form>

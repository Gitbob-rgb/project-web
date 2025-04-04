<?php
require 'auth.php';
checkAccess(['admin', 'pilote']);

require 'EntrepriseManager.php';
$manager = new EntrepriseManager($pdo);

$entreprise = $manager->get($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manager->modifier($_GET['id'], $_POST['nom'], $_POST['adresse'], $_POST['email']);
    header("Location: liste_entreprises.php");
}
?>

<form method="POST">
    Nom: <input type="text" name="nom" value="<?= $entreprise['nom'] ?>"><br>
    Adresse: <input type="text" name="adresse" value="<?= $entreprise['adresse'] ?>"><br>
    Email: <input type="email" name="email" value="<?= $entreprise['email'] ?>"><br>
    <button type="submit">Modifier</button>
</form>

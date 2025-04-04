<?php
require 'auth.php';
checkAccess(['admin']);

require 'EntrepriseManager.php';
$manager = new EntrepriseManager($pdo);

if (isset($_GET['id'])) {
    $manager->supprimer($_GET['id']);
}

header("Location: liste_entreprises.php");

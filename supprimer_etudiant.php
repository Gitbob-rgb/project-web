<?php
require 'auth.php';
checkAccess(['admin', 'pilote']);

require 'config.php';

$id = $_GET['id'];

// Supprimer le compte étudiant
$stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);

header("Location: liste_etudiants.php");
?>

<?php
require 'auth.php';
checkAccess(['admin']);

require 'config.php';

$id = $_GET['id'];

// Supprimer le compte pilote
$stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);

header("Location: liste_pilotes.php");
?>

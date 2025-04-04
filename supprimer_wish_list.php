<?php
require 'auth.php';
checkAccess(['admin','etudiant']);  // L'utilisateur doit être un étudiant

require 'config.php';

$offre_stage_id = $_GET['offre_stage_id']; // L'ID de l'offre de stage passé en paramètre
$utilisateur_id = $_SESSION['user_id'];  // L'ID de l'utilisateur connecté

// Supprimer l'offre de la wish list
$stmt = $pdo->prepare("DELETE FROM wish_list WHERE utilisateur_id = ? AND offre_stage_id = ?");
$stmt->execute([$utilisateur_id, $offre_stage_id]);

header("Location: wish_list.php"); // Rediriger vers la page wish list après suppression
?>

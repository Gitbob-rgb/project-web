<?php
require 'auth.php';
checkAccess(['admin', 'pilote', 'etudiant']);

$offre_stage_id = $_GET['offre_stage_id'];
$utilisateur_id = $_SESSION['user_id']; // ID de l'utilisateur connecté

require 'config.php';

// Supprimer la postulation de l'étudiant pour cette offre
$stmt = $pdo->prepare("DELETE FROM wish_list WHERE utilisateur_id = ? AND offre_stage_id = ?");
$stmt->execute([$utilisateur_id, $offre_stage_id]);

header("Location: offres_postulees.php"); // Rediriger après suppression
?>

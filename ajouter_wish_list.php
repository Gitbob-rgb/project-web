<?php
require 'auth.php';
checkAccess(['admin','etudiant']);  // L'utilisateur doit être un étudiant

require 'config.php';

// Vérifier si l'ID de l'offre de stage est passé en paramètre
if (isset($_GET['offre_stage_id'])) {
    $offre_stage_id = $_GET['offre_stage_id'];
    $utilisateur_id = $_SESSION['user_id'];  // L'ID de l'étudiant connecté

    // Vérifier si l'offre est déjà dans la wish list
    $stmt = $pdo->prepare("SELECT * FROM wish_list WHERE utilisateur_id = ? AND offre_stage_id = ?");
    $stmt->execute([$utilisateur_id, $offre_stage_id]);

    // Si l'offre n'est pas déjà dans la wish list, on l'ajoute
    if ($stmt->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO wish_list (utilisateur_id, offre_stage_id, date_ajout) VALUES (?, ?, ?)");
        $stmt->execute([$utilisateur_id, $offre_stage_id, date('Y-m-d')]);

        echo "<p>L'offre a été ajoutée à votre wish list.</p>";
    } else {
        echo "<p>Vous avez déjà ajouté cette offre à votre wish list.</p>";
    }
} else {
    echo "<p>Aucune offre sélectionnée.</p>";
}

echo "<a href='liste_offres_stage.php'>Retour à la liste des offres</a>";
?>

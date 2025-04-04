<?php
require 'auth.php';
checkAccess(['admin']);

require 'config.php';

// L'ID de l'étudiant et l'ID de l'offre de stage
$utilisateur_id = $_GET['utilisateur_id'];
$offre_stage_id = $_GET['offre_id'];

// Récupérer les informations de la postulation
$stmt = $pdo->prepare("SELECT c.nom, c.prenom, c.lettre_motivation, c.cv, o.titre AS offre_titre, e.nom AS entreprise_nom
                       FROM candidatures c
                       JOIN offres_stage o ON c.offre_stage_id = o.id
                       JOIN entreprises e ON o.entreprise_id = e.id
                       WHERE c.utilisateur_id = ? AND c.offre_stage_id = ?");
$stmt->execute([$utilisateur_id, $offre_stage_id]);
$postulation = $stmt->fetch();

if ($postulation) {
    echo "<h2>Détails de la postulation</h2>";
    echo "<p><strong>Nom :</strong> " . htmlspecialchars($postulation['nom']) . "</p>";
    echo "<p><strong>Prénom :</strong> " . htmlspecialchars($postulation['prenom']) . "</p>";
    echo "<p><strong>Offre de stage :</strong> " . htmlspecialchars($postulation['offre_titre']) . "</p>";
    echo "<p><strong>Entreprise :</strong> " . htmlspecialchars($postulation['entreprise_nom']) . "</p>";

    // Liens pour télécharger la lettre de motivation et le CV
    echo "<p><strong>Lettre de motivation :</strong> <a href='" . htmlspecialchars($postulation['lettre_motivation']) . "' download>Télécharger</a></p>";
    echo "<p><strong>CV :</strong> <a href='" . htmlspecialchars($postulation['cv']) . "' download>Télécharger</a></p>";
} else {
    echo "<p>Aucune postulation trouvée pour cette offre.</p>";
}
?>

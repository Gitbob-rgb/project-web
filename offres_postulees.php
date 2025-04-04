<?php
require 'auth.php';
checkAccess(['admin', 'pilote', 'etudiant']);

$utilisateur_id = $_SESSION['user_id']; // L'ID de l'utilisateur connecté

// Récupérer toutes les offres auxquelles l'étudiant a postulé
require 'config.php';
$stmt = $pdo->prepare("SELECT o.titre, o.description, o.date_debut, o.date_fin, e.nom AS entreprise_nom
                        FROM wish_list w
                        JOIN offres_stage o ON w.offre_stage_id = o.id
                        JOIN entreprises e ON o.entreprise_id = e.id
                        WHERE w.utilisateur_id = ?");
$stmt->execute([$utilisateur_id]);

echo "<h2>Offres de stage auxquelles vous avez postulé</h2>";

echo "<table border='1'>
        <tr><th>Titre</th><th>Description</th><th>Entreprise</th><th>Date de début</th><th>Date de fin</th></tr>";

while ($row = $stmt->fetch()) {
    echo "<tr>
            <td>" . htmlspecialchars($row['titre']) . "</td>
            <td>" . htmlspecialchars($row['description']) . "</td>
            <td>" . htmlspecialchars($row['entreprise_nom']) . "</td>
            <td>" . htmlspecialchars($row['date_debut']) . "</td>
            <td>" . htmlspecialchars($row['date_fin']) . "</td>
          </tr>";
}

echo "</table>";
?>

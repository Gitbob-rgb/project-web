<?php
require 'auth.php';
checkAccess(['admin']);

require 'config.php';

$utilisateur_id = $_GET['id'];  // L'ID de l'étudiant passé dans l'URL

// Récupérer les informations de l'étudiant
$stmt = $pdo->prepare("SELECT email FROM utilisateurs WHERE id = ?");
$stmt->execute([$utilisateur_id]);
$utilisateur = $stmt->fetch();

echo "<h2>Détails de l'étudiant : " . htmlspecialchars($utilisateur['email']) . "</h2>";

// Récupérer les entreprises que l'étudiant a notées
$stmt = $pdo->prepare("SELECT e.nom AS entreprise_nom, n.note, o.id AS offre_id
                       FROM notations n
                       JOIN entreprises e ON e.id = n.entreprise_id
                       JOIN offres_stage o ON e.id = o.entreprise_id
                       WHERE n.utilisateur_id = ?");
$stmt->execute([$utilisateur_id]);

    echo "<table border='1'>
            <tr><th>Entreprise</th><th>Note</th><th>Offres postulées</th></tr>";

    while ($row = $stmt->fetch()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['entreprise_nom']) . "</td>
                <td>" . htmlspecialchars($row['note']) . " étoiles</td>
                <td><a href='details_postulation.php?utilisateur_id=" . $utilisateur_id . "&offre_id=" . $row['offre_id'] . "'>Voir la postulation</a></td>
              </tr>";
    }

    echo "</table>";

?>

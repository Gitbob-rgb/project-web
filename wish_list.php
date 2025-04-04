
<?php
require 'auth.php';
checkAccess(['admin','etudiant']);  // L'utilisateur doit être un étudiant

require 'config.php';

// L'ID de l'utilisateur connecté
$utilisateur_id = $_SESSION['user_id'];

// Récupérer les offres de stage dans la wish list de l'utilisateur
$stmt = $pdo->prepare("SELECT o.id, o.titre, o.description, o.date_debut, o.date_fin,o.specialite, e.nom AS entreprise_nom
                       FROM wish_list w
                       JOIN offres_stage o ON w.offre_stage_id = o.id
                       JOIN entreprises e ON o.entreprise_id = e.id
                       WHERE w.utilisateur_id = ?");
$stmt->execute([$utilisateur_id]);

echo "<h2>Ma Wish List</h2>";

if ($stmt->rowCount() > 0) {
    echo "<table border='1'>
            <tr><th>Titre</th><th>Description</th><th>Entreprise</th><th>Date de début</th><th>Date de fin</th><th>Spécialité</th><th>Actions</th></tr>";

            while ($row = $stmt->fetch()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['titre']) . "</td>
                        <td>" . htmlspecialchars($row['description']) . "</td>
                        <td>" . htmlspecialchars($row['date_debut']) . "</td>
                        <td>" . htmlspecialchars($row['date_fin']) . "</td>
                        <td>" . htmlspecialchars($row['entreprise_nom']) . "</td>
                        <td>" . htmlspecialchars($row['specialite']) . "</td>
                <td>
                    <a href='supprimer_wish_list.php?offre_stage_id=" . $row['id'] . "' onclick='return confirm(\"Supprimer de la wish list ?\")'>Supprimer</a>
                </td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>Vous n'avez aucune offre dans votre wish list.</p>";
}
?>

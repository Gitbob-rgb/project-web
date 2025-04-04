
<?php
require 'auth.php';
checkAccess(['admin', 'pilote', 'etudiant']);

require 'config.php';

// Nombre d'éléments par page
$itemsPerPage = 10;

// Calcul de la page actuelle
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Récupérer le terme de recherche (optionnel)
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

// Récupérer les offres de stage avec le nom de l'entreprise
$sql = "SELECT o.*, e.nom AS entreprise_nom FROM offres_stage o
        LEFT JOIN entreprises e ON e.id = o.entreprise_id";

if (!empty($searchTerm)) {
    $sql .= " WHERE o.titre LIKE :searchTerm";
}

$sql .= " LIMIT :offset, :limit"; // Limiter les résultats à 10 par page

$stmt = $pdo->prepare($sql);
if (!empty($searchTerm)) {
    $stmt->execute([':searchTerm' => '%' . $searchTerm . '%', ':offset' => $offset, ':limit' => $itemsPerPage]);
} else {
    $stmt->execute([':offset' => $offset, ':limit' => $itemsPerPage]);
}

echo "<h2>Liste des offres de stage</h2>";

echo '<form method="GET">
        <input type="text" name="search" placeholder="Rechercher par titre" value="' . htmlspecialchars($searchTerm) . '">
        <button type="submit">Rechercher</button>
      </form>';

echo "<table border='1'>
        <tr><th>Titre</th><th>Description</th><th>Date de début</th><th>Date de fin</th><th>Entreprise</th><th>Spécialité</th><th>Actions</th><th>Ajouter à la wish list</th></tr>";

while ($row = $stmt->fetch()) {
    echo "<tr>
            <td>" . htmlspecialchars($row['titre']) . "</td>
            <td>" . htmlspecialchars($row['description']) . "</td>
            <td>" . htmlspecialchars($row['date_debut']) . "</td>
            <td>" . htmlspecialchars($row['date_fin']) . "</td>
            <td>" . htmlspecialchars($row['entreprise_nom']) . "</td>
            <td>" . htmlspecialchars($row['specialite']) . "</td>
            <td>
                <a href='modifier_offre_stage.php?id=" . $row['id'] . "'>Modifier</a> |
                <a href='supprimer_offre_stage.php?id=" . $row['id'] . "' onclick='return confirm(\"Supprimer ?\")'>Supprimer</a>
            </td>
            <td><a href='ajouter_wish_list.php?offre_stage_id=" . $row['id'] . "'>Ajouter à la wish list</a>
            <td><a href='postuler.php?offre_stage_id=" . $row['id'] . "'>Postuler</a></td>
          </tr>";
}

echo "</table>";

// Calcul du nombre total d'offres
$stmt = $pdo->prepare("SELECT COUNT(*) FROM offres_stage");
$stmt->execute();
$totalItems = $stmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// Afficher les liens de pagination
echo "<div>";
for ($i = 1; $i <= $totalPages; $i++) {
    echo "<a href='liste_offres_stage.php?page=$i&search=" . urlencode($searchTerm) . "'>$i</a> ";
}
echo "</div>";
?>

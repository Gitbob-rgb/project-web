<?php
require 'auth.php';
checkAccess(['admin']);

require 'config.php';

// Nombre d'éléments par page
$itemsPerPage = 10;

// Calcul de la page actuelle
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}



$sql = "SELECT u.id, u.email, e.id AS entreprise_id, e.nom AS entreprise_nom, n.note 
        FROM utilisateurs u
        LEFT JOIN notations n ON u.id = n.utilisateur_id
        LEFT JOIN entreprises e ON e.id = n.entreprise_id
        WHERE u.role = 'pilote'";

if (!empty($searchTerm)) {
    $sql .= " AND u.email LIKE :searchTerm";
}

$sql .= " LIMIT :offset, :limit"; // Limiter les résultats à 10 par page

$stmt = $pdo->prepare($sql);
if (!empty($searchTerm)) {
    $stmt->execute([':searchTerm' => '%' . $searchTerm . '%', ':offset' => $offset, ':limit' => $itemsPerPage]);
} else {
    $stmt->execute([':offset' => $offset, ':limit' => $itemsPerPage]);
}

echo "<h2>Liste des comptes pilotes</h2>";

// Formulaire de recherche
echo '<form method="GET">
        <input type="text" name="search" placeholder="Rechercher par email" value="' . htmlspecialchars($searchTerm) . '">
        <button type="submit">Rechercher</button>
      </form>';

echo "<table border='1'>
        <tr><th>Email</th><th>Entreprise</th><th>Note</th><th>Actions</th></tr>";
        echo " <tr>
        <a href='creer_pilote.php'>➕ Ajouter</a>
        </tr>";
while ($row = $stmt->fetch()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['entreprise_nom']) . "</td>
                <td>" . htmlspecialchars($row['note']) . " étoiles</td>
                <td>
                    <a href='modifier_pilote.php?id=" . $row['id'] . "'>Modifier</a> |
                    <a href='supprimer_pilote.php?id=" . $row['id'] . "' onclick='return confirm(\"Supprimer ce compte ?\")'>Supprimer</a>
                </td>
              </tr>";

}

echo "</table>";

// Calcul du nombre total de pilotes
$stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE role = 'pilote'");
$stmt->execute();
$totalItems = $stmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// Afficher les liens de pagination
echo "<div>";
for ($i = 1; $i <= $totalPages; $i++) {
    echo "<a href='liste_pilotes.php?page=$i&search=" . urlencode($searchTerm) . "'>$i</a> ";
}
echo "</div>";
?>





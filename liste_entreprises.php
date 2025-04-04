
<?php
require 'auth.php';
checkAccess(['admin', 'pilote', 'etudiant']);
require 'EntrepriseManager.php';
$manager = new EntrepriseManager($pdo);
$entreprises = $manager->getAll();
require 'config.php';

// Nombre d'éléments par page
$itemsPerPage = 10;

// Calcul de la page actuelle
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Récupérer le terme de recherche
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

// Récupérer les entreprises
$sql = "SELECT * FROM entreprises";
if (!empty($searchTerm)) {
    $sql .= " WHERE nom LIKE :searchTerm";
}
$sql .= " LIMIT :offset, :limit"; // Limiter les résultats à 10 par page

$stmt = $pdo->prepare($sql);
if (!empty($searchTerm)) {
    $stmt->execute([':searchTerm' => '%' . $searchTerm . '%', ':offset' => $offset, ':limit' => $itemsPerPage]);
} else {
    $stmt->execute([':offset' => $offset, ':limit' => $itemsPerPage]);
}

echo "<h2>Liste des entreprises</h2>";
echo " <tr>
    <a href='ajouter_entreprise.php'>➕ Ajouter</a>
    </tr>";
echo '<form method="GET">
        <input type="text" name="search" placeholder="Rechercher par nom" value="' . htmlspecialchars($searchTerm) . '">
        <button type="submit">Rechercher</button>
      </form>';

echo "<table border='1'>
        <tr><th>Nom</th><th>Adresse</th><th>Email</th><th>Actions</th></tr>";

while ($row = $stmt->fetch()) {
    echo "<tr>
            <td>" . htmlspecialchars($row['nom']) . "</td>
            <td>" . htmlspecialchars($row['adresse']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>

            <td>
                <a href='modifier_entreprise.php?id=" . $row['id'] . "'>Modifier</a> |
                <a href='supprimer_entreprise.php?id=" . $row['id'] . "' onclick='return confirm(\"Supprimer ?\")'>Supprimer</a>
                <a href='afficher_entreprise.php?id=" . $row['id'] . "'>Détails</a>
            </td>
          </tr>";
}

echo "</table>";

// Calcul du nombre total d'entreprises
$stmt = $pdo->prepare("SELECT COUNT(*) FROM entreprises");
$stmt->execute();
$totalItems = $stmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// Afficher les liens de pagination
echo "<div>";
for ($i = 1; $i <= $totalPages; $i++) {
    echo "<a href='liste_entreprises.php?page=$i&search=" . urlencode($searchTerm) . "'>$i</a> ";
}
echo "</div>";
?>

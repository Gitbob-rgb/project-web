<?php
require 'auth.php';
checkAccess(['admin', 'pilote']);

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

// Récupérer les étudiants
$sql = "SELECT u.id, u.email, 
               (SELECT COUNT(*) FROM wish_list WHERE utilisateur_id = u.id) AS nombre_postulations
        FROM utilisateurs u WHERE u.role = 'etudiant'";
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

echo "<h2>Liste des comptes étudiants</h2>";

echo '<form method="GET">
        <input type="text" name="search" placeholder="Rechercher par email" value="' . htmlspecialchars($searchTerm) . '">
        <button type="submit">Rechercher</button>
      </form>';

echo "<table border='1'>
        <tr><th>Email</th><th>Nombre de postulations</th><th>Actions</th><th>Détails</th></tr>";

while ($row = $stmt->fetch()) {
    echo "<tr>
            <td>" . htmlspecialchars($row['email']) . "</td>
            <td>" . htmlspecialchars($row['nombre_postulations']) . "</td>
            <td>
                <a href='modifier_etudiant.php?id=" . $row['id'] . "'>Modifier</a> |
                <a href='supprimer_etudiant.php?id=" . $row['id'] . "' onclick='return confirm(\"Supprimer ce compte ?\")'>Supprimer</a>
            </td>
            <td><a href='affiche_etudiant.php?id=" . $row['id'] . "'>Détails</a></td>
          </tr>";
}

echo "</table>";

// Pagination
$stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE role = 'etudiant'");
$stmt->execute();
$totalItems = $stmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

echo "<div>";
for ($i = 1; $i <= $totalPages; $i++) {
    echo "<a href='liste_etudiants.php?page=$i&search=" . urlencode($searchTerm) . "'>$i</a> ";
}
echo "</div>";
?>

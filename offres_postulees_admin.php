<?php
require 'auth.php';
checkAccess(['admin']);  // L'admin doit être authentifié

require 'config.php';

// Récupérer le terme de recherche (email)
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

// Préparer la requête pour récupérer les utilisateurs correspondant à l'email
$sql = "SELECT u.id, u.email FROM utilisateurs u WHERE u.email LIKE :searchTerm AND u.role = 'etudiant'";
$stmt = $pdo->prepare($sql);
$stmt->execute([':searchTerm' => '%' . $searchTerm . '%']);

echo "<h2>Rechercher un étudiant pour voir ses offres postulées</h2>";

// Formulaire de recherche
echo '<form method="GET">
        <input type="text" name="search" placeholder="Rechercher par email" value="' . htmlspecialchars($searchTerm) . '">
        <button type="submit">Rechercher</button>
      </form>';

echo "<h3>Résultats de la recherche</h3>";

if ($stmt->rowCount() > 0) {
    while ($user = $stmt->fetch()) {
        echo "<p><a href='offres_postulees_admin.php?user_id=" . $user['id'] . "'>" . htmlspecialchars($user['email']) . "</a></p>";
    }
} else {
    echo "<p>Aucun utilisateur trouvé.</p>";
}

// Si un utilisateur a été sélectionné
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    
    // Récupérer toutes les offres auxquelles cet utilisateur a postulé
    $stmt = $pdo->prepare("SELECT o.titre, o.description, o.date_debut, o.date_fin, e.nom AS entreprise_nom
                           FROM wish_list w
                           JOIN offres_stage o ON w.offre_stage_id = o.id
                           JOIN entreprises e ON o.entreprise_id = e.id
                           WHERE w.utilisateur_id = ?");
    $stmt->execute([$user_id]);

    // Afficher les offres postulées
    $user_stmt = $pdo->prepare("SELECT email FROM utilisateurs WHERE id = ?");
    $user_stmt->execute([$user_id]);
    $user = $user_stmt->fetch();
    
    echo "<h3>Offres postulées par " . htmlspecialchars($user['email']) . "</h3>";

    if ($stmt->rowCount() > 0) {
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
    } else {
        echo "<p>Aucune offre postulée.</p>";
    }
}
?>

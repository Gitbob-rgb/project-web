<?php
require 'auth.php';
checkAccess(['admin', 'pilote']);

require 'OffreStageManager.php';
$manager = new OffreStageManager($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manager->ajouter($_POST['titre'], $_POST['description'], $_POST['date_debut'], $_POST['date_fin'], $_POST['entreprise_id'], $_POST['specialite']);
    header("Location: liste_offres_stage.php");
}
?>

<form method="POST">
    Titre: <input type="text" name="titre"><br>
    Description: <textarea name="description"></textarea><br>
    Date de début: <input type="date" name="date_debut"><br>
    Date de fin: <input type="date" name="date_fin"><br>
    Entreprise: <select name="entreprise_id">
        <?php
        // Récupérer les entreprises disponibles
        $stmt = $pdo->query("SELECT * FROM entreprises");
        while ($row = $stmt->fetch()) {
            echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nom']) . "</option>";
        }
        ?>
    </select><br>
    Spécialité: <input type="text" name="specialite"><br>
    <button type="submit">Ajouter</button>
</form>

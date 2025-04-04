<?php
require 'auth.php';
checkAccess(['admin', 'pilote']);

require 'OffreStageManager.php';
$manager = new OffreStageManager($pdo);

$offre_stage = $manager->get($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manager->modifier($_GET['id'], $_POST['titre'], $_POST['description'], $_POST['date_debut'], $_POST['date_fin'], $_POST['entreprise_id'], $_POST['specialite']);
    header("Location: liste_offres_stage.php");
}
?>

<form method="POST">
    Titre: <input type="text" name="titre" value="<?= htmlspecialchars($offre_stage['titre']) ?>"><br>
    Description: <textarea name="description"><?= htmlspecialchars($offre_stage['description']) ?></textarea><br>
    Date de début: <input type="date" name="date_debut" value="<?= htmlspecialchars($offre_stage['date_debut']) ?>"><br>
    Date de fin: <input type="date" name="date_fin" value="<?= htmlspecialchars($offre_stage['date_fin']) ?>"><br>
    Entreprise: <select name="entreprise_id">
        <?php
        // Récupérer les entreprises disponibles
        $stmt = $pdo->query("SELECT * FROM entreprises");
        while ($row = $stmt->fetch()) {
            $selected = $row['id'] == $offre_stage['entreprise_id'] ? 'selected' : '';
            echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['nom']) . "</option>";
        }
        ?>
    </select><br>
    Spécialité: <input type="text" name="specialite" value="<?= htmlspecialchars($offre_stage['specialite']) ?>"><br>
    <button type="submit">Modifier</button>
</form>

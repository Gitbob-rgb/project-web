<?php
require 'auth.php';
checkAccess(['etudiant']);  // L'utilisateur doit être un étudiant

require 'config.php';

$offre_stage_id = $_GET['offre_stage_id'];  // ID de l'offre de stage
$utilisateur_id = $_SESSION['user_id'];  // ID de l'étudiant connecté

// Vérifier si l'utilisateur a déjà postulé à cette offre
$stmt_check = $pdo->prepare("SELECT * FROM candidatures WHERE utilisateur_id = ? AND offre_stage_id = ?");
$stmt_check->execute([$utilisateur_id, $offre_stage_id]);
$alreadyApplied = $stmt_check->fetch();

if ($alreadyApplied) {
    echo "<p style='color: red;'>Vous avez déjà postulé à cette offre de stage.</p>";
} else {
    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];

        // Vérification et téléchargement des fichiers (lettre de motivation et CV)
        $lettreMotivation = $_FILES['lettre_motivation'];
        $cv = $_FILES['cv'];

        // Vérifier la taille des fichiers (max 2 Mo)
        if ($lettreMotivation['size'] > 2 * 1024 * 1024 || $cv['size'] > 2 * 1024 * 1024) {
            echo "<p style='color:red;'>Les fichiers ne doivent pas dépasser 2 Mo.</p>";
        } else {
            // Vérifier les extensions des fichiers (PDF, PNG, JPEG, DOC, DOCX)
            $allowedExtensions = ['pdf', 'png', 'jpeg', 'jpg', 'doc', 'docx'];
            $lettreExt = pathinfo($lettreMotivation['name'], PATHINFO_EXTENSION);
            $cvExt = pathinfo($cv['name'], PATHINFO_EXTENSION);

            if (!in_array(strtolower($lettreExt), $allowedExtensions) || !in_array(strtolower($cvExt), $allowedExtensions)) {
                echo "<p style='color:red;'>Les extensions autorisées sont .pdf, .png, .jpeg, .doc, .docx.</p>";
            } else {
                // Déplacer les fichiers téléchargés vers le dossier "uploads"
                $lettreMotivationPath = 'uploads/' . uniqid() . '.' . $lettreExt;
                $cvPath = 'uploads/' . uniqid() . '.' . $cvExt;

                move_uploaded_file($lettreMotivation['tmp_name'], $lettreMotivationPath);
                move_uploaded_file($cv['tmp_name'], $cvPath);

                // Insérer la candidature dans la base de données
                $stmt = $pdo->prepare("INSERT INTO candidatures (utilisateur_id, offre_stage_id, nom, prenom, lettre_motivation, cv, date_postulation)
                                       VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$utilisateur_id, $offre_stage_id, $nom, $prenom, $lettreMotivationPath, $cvPath, date('Y-m-d H:i:s')]);

                echo "<p style='color:green;'>Votre candidature a été envoyée avec succès.</p>";
            }
        }
    }

    // Affichage du formulaire de postulation
    echo "<h2>Postuler à l'offre de stage</h2>";

    echo '<form method="POST" enctype="multipart/form-data">
            <label>Nom :</label>
            <input type="text" name="nom" required><br>

            <label>Prénom :</label>
            <input type="text" name="prenom" required><br>

            <label>Lettre de motivation :</label>
            <input type="file" name="lettre_motivation" accept=".pdf,.png,.jpeg,.doc,.docx" required><br>

            <label>CV :</label>
            <input type="file" name="cv" accept=".pdf,.png,.jpeg,.doc,.docx" required><br>

            <button type="submit">Envoyer la candidature</button>
          </form>';
}
?>

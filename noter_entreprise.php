<?php

require 'config.php';
require 'auth.php';
checkAccess(['admin', 'pilote', 'etudiant']);

$entreprise_id = $_GET['id']; // ID de l'entreprise à noter

// Vérifier si l'utilisateur a déjà voté pour cette entreprise
$stmt = $pdo->prepare("SELECT * FROM notations WHERE entreprise_id = ? AND utilisateur_id = ?");
$stmt->execute([$entreprise_id, $_SESSION['user_id']]);
$note_exist = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = $_POST['note'];

    if ($note >= 1 && $note <= 5) {
        if ($note_exist) {
            // Si l'utilisateur a déjà voté, mettre à jour la note
            $stmt = $pdo->prepare("UPDATE notations SET note = ? WHERE entreprise_id = ? AND utilisateur_id = ?");
            $stmt->execute([$note, $entreprise_id, $_SESSION['user_id']]);
        } else {
            // Si l'utilisateur n'a pas encore voté, insérer une nouvelle note
            $stmt = $pdo->prepare("INSERT INTO notations (entreprise_id, utilisateur_id, note) VALUES (?, ?, ?)");
            $stmt->execute([$entreprise_id, $_SESSION['user_id'], $note]);
        }
        header("Location: afficher_entreprise.php?id=$entreprise_id");
    } else {
        $error = "La note doit être entre 1 et 5.";
    }
}

?>

<h2>Noter l'entreprise</h2>

<form method="POST">
    <label>Note (1 à 5) :</label>
    <input type="number" name="note" min="1" max="5" required><br>
    <button type="submit">Soumettre la note</button>
</form>

<?php
if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
}
?>

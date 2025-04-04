<?php
require 'auth.php';
checkAccess(['admin',, 'pilote']);

require 'config.php';

$entreprise_id = $_GET['id'];

// Récupérer les informations de l'entreprise
$stmt = $pdo->prepare("SELECT * FROM entreprises WHERE id = ?");
$stmt->execute([$entreprise_id]);
$entreprise = $stmt->fetch();

// Calculer la moyenne des notes
$stmt = $pdo->prepare("SELECT AVG(note) AS moyenne FROM notations WHERE entreprise_id = ?");
$stmt->execute([$entreprise_id]);
$moyenne = $stmt->fetch()['moyenne'];

?>

<h2><?= htmlspecialchars($entreprise['nom']) ?></h2>
<p>Adresse: <?= htmlspecialchars($entreprise['adresse']) ?></p>
<p>Email: <?= htmlspecialchars($entreprise['email']) ?></p>

<h3>Moyenne des notes</h3>
<p>Note moyenne : <?= $moyenne ? number_format($moyenne, 2) : 'Pas encore de votes' ?></p>


    <a href="noter_entreprise.php?id=<?= $entreprise_id ?>">Donner une note</a>


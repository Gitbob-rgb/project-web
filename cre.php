<?php
require 'config.php';

$email = 'admin@web4all.fr';
$motdepasse = password_hash('admin123', PASSWORD_DEFAULT);
$role = 'admin';

$stmt = $pdo->prepare("INSERT INTO utilisateurs (email, motdepasse, role) VALUES (?, ?, ?)");
$stmt->execute([$email, $motdepasse, $role]);

echo "Admin créé.";
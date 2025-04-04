<?php
require_once 'config.php';

class EntrepriseManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function ajouter($nom, $adresse, $email) {
        $stmt = $this->pdo->prepare("INSERT INTO entreprises (nom, adresse, email) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $adresse, $email]);
    }

    public function modifier($id, $nom, $adresse, $email) {
        $stmt = $this->pdo->prepare("UPDATE entreprises SET nom=?, adresse=?, email=? WHERE id=?");
        $stmt->execute([$nom, $adresse, $email, $id]);
    }

    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM entreprises WHERE id=?");
        $stmt->execute([$id]);
    }

    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM entreprises WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM entreprises");
        return $stmt->fetchAll();
    }
}
?>

<?php
require_once 'config.php';

class WishListManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function ajouter($utilisateur_id, $offre_stage_id) {
        $stmt = $this->pdo->prepare("INSERT INTO wish_list (utilisateur_id, offre_stage_id, date_ajout) VALUES (?, ?, ?)");
        $stmt->execute([$utilisateur_id, $offre_stage_id, date('Y-m-d')]);
    }

    public function supprimer($utilisateur_id, $offre_stage_id) {
        $stmt = $this->pdo->prepare("DELETE FROM wish_list WHERE utilisateur_id=? AND offre_stage_id=?");
        $stmt->execute([$utilisateur_id, $offre_stage_id]);
    }

    public function getAll($utilisateur_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM wish_list WHERE utilisateur_id=?");
        $stmt->execute([$utilisateur_id]);
        return $stmt->fetchAll();
    }
}
?>

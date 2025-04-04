<?php
require_once 'config.php';

class OffreStageManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function ajouter($titre, $description, $date_debut, $date_fin, $entreprise_id, $specialite) {
        $stmt = $this->pdo->prepare("INSERT INTO offres_stage (titre, description, date_debut, date_fin, entreprise_id, specialite) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $description, $date_debut, $date_fin, $entreprise_id, $specialite]);
    }

    public function modifier($id, $titre, $description, $date_debut, $date_fin, $entreprise_id, $specialite) {
        $stmt = $this->pdo->prepare("UPDATE offres_stage SET titre=?, description=?, date_debut=?, date_fin=?, entreprise_id=?, specialite=? WHERE id=?");
        $stmt->execute([$titre, $description, $date_debut, $date_fin, $entreprise_id, $specialite, $id]);
    }

    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM offres_stage WHERE id=?");
        $stmt->execute([$id]);
    }

    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM offres_stage WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll($searchTerm = '') {
        $sql = "SELECT * FROM offres_stage";
        if (!empty($searchTerm)) {
            $sql .= " WHERE specialite LIKE :searchTerm OR titre LIKE :searchTerm";
        }
        $stmt = $this->pdo->prepare($sql);
        if (!empty($searchTerm)) {
            $stmt->execute([':searchTerm' => '%' . $searchTerm . '%']);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }
}
?>


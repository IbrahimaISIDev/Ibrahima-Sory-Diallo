<?php


namespace App\Interfaces;

interface PromotionRepositoryInterface
{
    public function getAllPromotions();
    public function getPromotionById($id);
    public function createPromotion(array $promotionDetails);
    public function updatePromotion($id, array $newDetails);
    public function deletePromotion($id);
    public function getPromotionEncours();
    public function addReferentielToPromotion($promotionId, $referentielId);
    public function removeReferentielFromPromotion($promotionId, $referentielId);
    public function getPromotionStats($id);
    public function cloturerPromotion($id);
    public function findByLibelle($libelle); // Ajout de cette méthode
}
<?php
namespace App\Interfaces;

use App\Enums\EtatPromotion;

interface PromotionServiceInterface
{
    public function createPromotion(array $data);
    public function updatePromotion($id, array $data);
    public function changePromotionStatus($id, EtatPromotion $newStatus);
    public function getPromotionStats($id);
    public function cloturerPromotion($id);
    public function getAllPromotions();
    public function getPromotionById($id);
    public function updatePromotionReferentiels($promotionId, array $referentielIds);
    public function getPromotionEncours();

}
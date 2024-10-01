<?php

namespace App\Http\Controllers;

use App\Enums\EtatPromotion;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePromotionRequest;
use App\Interfaces\PromotionServiceInterface;

class PromotionController extends Controller
{
    protected $promotionService;

    public function __construct(PromotionServiceInterface $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function index()
    {
        return response()->json($this->promotionService->getAllPromotions());
    }

    public function store(Request $request)
    {
        return $this->promotionService->createPromotion($request->all());
    }

    public function show($id)
    {
        return response()->json($this->promotionService->getPromotionById($id));
    }

    public function update(Request $request, $id)
    {
        $promotion = $this->promotionService->updatePromotion($id, $request->all());
        return response()->json($promotion);
    }

    public function changeStatus($id, Request $request)
    {
        // Valide l'entrée pour s'assurer que l'état est l'une des valeurs autorisées
        $request->validate([
            'etat' => 'required|in:actif,inactif,cloturer', // Ajoutez toutes les valeurs possibles ici
        ]);

        // Convertir la chaîne en instance d'énumération
        $newStatus = EtatPromotion::from($request->input('etat')); // Cela va lever une exception si l'état n'est pas valide

        $promotion = $this->promotionService->changePromotionStatus($id, $newStatus);
        return response()->json($promotion);
    }



    public function getStats($id)
    {
        $stats = $this->promotionService->getPromotionStats($id);
        return response()->json($stats);
    }

    public function cloturer($id)
    {
        $result = $this->promotionService->cloturerPromotion($id);
        if ($result) {
            return response()->json(['message' => 'La promotion a été clôturée avec succès.']);
        }
        return response()->json(['message' => 'La promotion ne peut pas être clôturée.'], 400);
    }

    public function updateReferentiels($id, Request $request)
    {
        $request->all([
            'referentiels' => 'required|array',
            'referentiels.*' => 'exists:referentiels,id',
        ]);

        $result = $this->promotionService->updatePromotionReferentiels($id, $request->input('referentiels'));

        return response()->json($result);
    }

    public function getPromotionEncours()
    {
        $promotion = $this->promotionService->getPromotionEncours();
        return response()->json($promotion);
    }
}

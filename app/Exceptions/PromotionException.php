<?php

namespace App\Exceptions;

use Exception;

class PromotionException extends Exception
{
    protected $data;

    public function __construct($message = "", $code = 0, $data = null, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public static function promotionListed($promotionData)
    {
        return new self("Listes des promotions affichées avec succès", 201, $promotionData);
    }

    public static function promotionOneListed($promotionData)
    {
        return new self("Promotion affichée avec succès", 201, $promotionData);
    }

    public static function promotionCreated($promotionData)
    {
        return new self("Promotion créée avec succès", 201, $promotionData);
    }

    public static function promotionUpdated($promotionData)
    {
        return new self("Promotion mise à jour avec succès", 200, $promotionData);
    }

    public static function promotionDeleted($promotionId)
    {
        return new self("Promotion supprimée avec succès", 200, ['id' => $promotionId]);
    }

    public static function promotionNotFound($promotionId)
    {
        return new self("Promotion non trouvée", 404, ['id' => $promotionId]);
    }

    public static function invalidPromotionData($errors)
    {
        return new self("Données de promotion invalides", 422, $errors);
    }
}

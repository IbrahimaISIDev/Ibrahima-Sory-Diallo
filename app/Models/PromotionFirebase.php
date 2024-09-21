<?php

namespace App\Models;

use App\Interfaces\PromotionFirebaseInterface;

class PromotionFirebase extends FirebaseModel implements PromotionFirebaseInterface
{
    protected $path = 'promotions';

    public function findByName($name)
    {
        $promotions = $this->all();
        return array_filter($promotions, function($promotion) use ($name) {
            return $promotion['name'] === $name;
        });
    }
}
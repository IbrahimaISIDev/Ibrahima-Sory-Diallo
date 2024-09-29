<?php

namespace App\Interfaces;

interface PromotionFirebaseInterface
{
    public function findByName($name);
    //public function scopeEncours($query);
    public function getEncours();
    public function calculerDuree();
    public function calculerDateFin();
    public function __get($name);

}   
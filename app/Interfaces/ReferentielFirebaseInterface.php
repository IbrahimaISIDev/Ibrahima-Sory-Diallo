<?php

namespace App\Interfaces;

interface ReferentielFirebaseInterface
{
    public function findByCode($code);
    public function updateReferentiel($id, array $newDetails);
}
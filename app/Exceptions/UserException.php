<?php

namespace App\Exceptions;

use Exception;

class UserException extends Exception
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

    public static function userListed($userData)
    {
        return new self("Listes des utilisateurs affichés avec succès", 201, $userData);
    }

    public static function userOneListed($userData)
    {
        return new self("Utilisateur afficher avec succès", 201, $userData);
    }

    public static function userCreated($userData)
    {
        return new self("Utilisateur créé avec succès", 201, $userData);
    }

    public static function userUpdated($userData)
    {
        return new self("Utilisateur mis à jour avec succès", 200, $userData);
    }

    public static function userDeleted($userId)
    {
        return new self("Utilisateur supprimé avec succès", 200, ['id' => $userId]);
    }

    public static function userNotFound($userId)
    {
        return new self("Utilisateur non trouvé", 404, ['id' => $userId]);
    }

    public static function invalidUserData($errors)
    {
        return new self("Données utilisateur invalides", 422, $errors);
    }

    public static function learnerCreated($learnerData)
    {
        return new self("Apprenant créé avec succès", 201, $learnerData);
    }

    public static function learnerListCreated($count)
    {
        return new self("Liste d'apprenants importé avec succès", 201, ['count' => $count]);
    }
}
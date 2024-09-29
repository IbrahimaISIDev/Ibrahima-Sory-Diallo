<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use App\Models\ReferentielFirebase;

class FirebaseService
{
    protected $database;

    public function __construct()
    {
        // Spécifiez le chemin du fichier de clé de service et l'URI de la base de données
        $factory = (new Factory)
            ->withServiceAccount('/home/dev/Documents/gestion_scolaire/storage/firebase/app-gestion-pedagogique-firebase-adminsdk-pg06l-6570ed71c6.json')
            ->withDatabaseUri('https://app-gestion-pedagogique-default-rtdb.firebaseio.com');

        // Initialisation de la base de données Firebase
        $this->database = $factory->createDatabase();
    }



    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getReferentielById($id)
    {
        return $this->database->getReference("referentiels/$id")->getValue();
    }
    public function addCompetenceToReferentiel($referentielId, $competence)
    {
        $this->database->getReference("referentiels/$referentielId/competences")->push($competence);
    }

    public function removeCompetenceFromReferentiel($referentielId, $competenceId)
    {
        $this->database->getReference("referentiels/$referentielId/competences/$competenceId")->remove();
    }
    // Récupérer le dernier ID du référentiel
    public function getLastReferentielId()
    {
        $lastId = $this->database->getReference('last_referentiel_id')->getValue();
        return $lastId ?? 0; // Retourne 0 si aucun ID n'a encore été créé
    }

    // Mettre à jour le dernier ID du référentiel
    public function updateLastReferentielId($id)
    {
        $this->database->getReference('last_referentiel_id')->set($id);
    }

    public function createReferentielWithId($data, $id)
    {
        $this->database->getReference("referentiels/$id")->set($data);
    }

    public function softDeleteReferentiel($id)
    {
        $referentiel = $this->database->getReference("referentiels/$id")->getValue();
        if (!$referentiel) {
            return null; // Retourner null si le référentiel n'existe pas
        }

        // Marquer le référentiel comme supprimé en ajoutant une date à deleted_at
        $this->database->getReference("referentiels/$id/deleted_at")->set(now()->toISOString());
        return $referentiel; // Retourner le référentiel supprimé pour confirmation
    }


    public function updateReferentiel($id, array $newDetails)
    {
        // Obtenez le référentiel en utilisant le modèle
        return (new ReferentielFirebase())->updateReferentiel($id, $newDetails);
    }

    // Méthodes supplémentaires peuvent être ajoutées ici...
}

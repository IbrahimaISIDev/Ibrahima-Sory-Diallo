<?php

namespace App\Jobs;

use App\Models\User;
use App\Facades\UserFirebase;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\CloudStorageServiceFactory;

class UploadProfilePictureJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected $userMysqlId;
    protected $userFirebaseId;
    protected $photoPath;

    public function __construct($userMysqlId, $userFirebaseId, $photoPath)
    {
        $this->userMysqlId = $userMysqlId;
        $this->userFirebaseId = $userFirebaseId;
        $this->photoPath = $photoPath;
    }

    public function handle()
{
    try {
        // Vérifiez si le fichier existe avant de procéder
        if (!file_exists($this->photoPath)) {
            throw new \Exception("Le fichier image n'existe pas à ce chemin : {$this->photoPath}");
        }

        $userMysql = User::findOrFail($this->userMysqlId);
        
        // Créez une instance d'UploadedFile
        $photo = new \Illuminate\Http\UploadedFile(
            $this->photoPath, 
            basename($this->photoPath)
        );

        // Débogage via les logs au lieu de echo
        Log::info("Chemin de la photo: {$this->photoPath}");
        Log::info("Instance UploadedFile: " . print_r($photo, true));

        $cloudStorageService = CloudStorageServiceFactory::make();
        $cloudUrl = $cloudStorageService->uploadImage($photo, $userMysql->id);

        // Mettez à jour l'URL de la photo dans la base de données MySQL
        $userMysql->update(['photo' => $cloudUrl]);

        // Mettez à jour l'URL de la photo dans Firebase
        $userFirebaseInstance = UserFirebase::find($this->userFirebaseId);
        $userFirebaseInstance->update(['photo' => $cloudUrl]);

        Log::info("Image téléchargée avec succès sur le cloud pour l'utilisateur ID : {$userMysql->id}");
    } catch (\Exception $e) {
        // Capturer toutes les exceptions et les enregistrer dans les logs
        Log::error('Erreur lors du téléchargement de l\'image vers le cloud : ' . $e->getMessage());
    }
}

}

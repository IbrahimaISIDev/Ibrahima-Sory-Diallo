<?php

namespace App\Services;

use GuzzleHttp\Client;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;

class FirebaseAuthService
{
    protected $auth;
    protected $firebaseApiKey;
    protected $client;

    public function __construct()
    {
        $this->firebaseApiKey = (new Factory)->withServiceAccount(config('services.firebase.credentials.api_key'));
        $this->client = new Client();
    }

    public function login(Request $request)
    {
        try {
            $email = $request->input('email') ?? null;
            $password = $request->input('password') ?? null;

            if (!$email || !$password) {
                throw new Exception("Email ou mot de passe manquant.");
            }

            Log::info("Tentative de connexion avec l'email : $email");

            $response = $this->client->post('https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=' . $this->firebaseApiKey, [
                'json' => [
                    'email' => $email,
                    'password' => $password,
                    'returnSecureToken' => true,
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);

            if (!isset($body['idToken'])) {
                Log::error('Erreur Firebase : ' . json_encode($body));
                throw new Exception('Échec de l\'authentification. Vérifiez vos identifiants.');
            }

            $idToken = $body['idToken'];
            $refreshToken = $body['refreshToken'];
            $userInfo = $this->getUserInfo($idToken);

            return [
                'success' => true,
                'token' => $idToken,
                'refresh_token' => $refreshToken,
                'user' => $userInfo,
            ];
        } catch (Exception $e) {
            Log::error('Erreur d\'authentification Firebase: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Échec de l\'authentification via Firebase: ' . $e->getMessage(),
            ];
        }
    }

    protected function getUserInfo($idToken)
    {
        $response = $this->client->post('https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=' . $this->firebaseApiKey, [
            'json' => [
                'idToken' => $idToken,
            ],
        ]);

        $body = json_decode((string) $response->getBody(), true);

        if (!isset($body['users'][0])) {
            throw new Exception('Impossible de récupérer les informations de l\'utilisateur.');
        }

        return $body['users'][0];
    }

    public function logout(string $token)
    {
        return response()->json(['message' => 'Déconnexion réussie.'], 200);
    }

    public function refresh(string $refreshToken)
    {
        return null;
    }
}
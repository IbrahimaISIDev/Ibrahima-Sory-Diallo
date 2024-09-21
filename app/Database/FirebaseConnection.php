<?php

namespace App\Database;

use Illuminate\Database\Connection;
use Kreait\Firebase\Factory;

class FirebaseConnection extends Connection
{
    protected $database;

    public function __construct(array $config)
    {
        try {
            $factory = (new Factory())
                ->withDatabaseUri($config['database'])
                ->withServiceAccount($config['credentials']);
            $this->database = $factory->createDatabase();
            $this->useDefaultPostProcessor();
        } catch (\Exception $e) {
            dd('Firebase Connection Error: ' . $e->getMessage());
        }
    }

    public function getDatabase()
    {
        return $this->database;
    }
}
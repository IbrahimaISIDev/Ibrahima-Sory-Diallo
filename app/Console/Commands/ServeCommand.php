<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ServeCommand extends Command
{
    protected $signature = 'serve';
    protected $description = 'Serve the application on the PHP development server';

    public function handle()
    {
        $port = config('server.port', 3000);
        $host = config('server.host', '127.0.0.1');

        $this->info("le server est activé dans l'url suivant: http://{$host}:{$port}");

        $process = new Process(['php', '-S', "{$host}:{$port}", '-t', 'public']);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });
    }
}
<?php

namespace App\Logging;

use App\Models\Log;
use Illuminate\Support\Facades\Log as FacadesLog;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class DatabaseLogger
{
    public function __invoke(array $config)
    {
        return new Logger('database', [new DatabaseHandler()]);
    }
}

class DatabaseHandler extends AbstractProcessingHandler
{
    protected function write(LogRecord $record): void
    {
        try {
            Log::create([
                'level' => $record->level->getName(),
                'context' => $record->channel ?? 'app',
                'message' => $record->message,
                'extra' => $record->context ?? [],
            ]);
        } catch (\Throwable $e) {
            // Hindari infinite loop kalau DB gagal
            FacadesLog::channel('single')->error('Database log gagal: ' . $e->getMessage());
        }
    }
}

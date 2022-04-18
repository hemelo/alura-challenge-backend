<?php 

namespace App\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CsvLogger 
{
    private Logger $logger;
    
    public function __construct()
    {
        $this->logger = new Logger(static::class);
        $this->logger->pushHandler(new StreamHandler(storage_path('logs/csv_uploads.log'), Logger::WARNING));
    }

    public function __invoke()
    {
        return $this->logger;
    }
}

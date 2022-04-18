<?php

namespace App\Listeners;

use App\Events\CsvWasValidatedEvent;
use App\Log\CsvLogger;
use App\Models\Csv;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Transferencia;
use Carbon\Carbon;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Validator;

class LogCsvOnDatabaseListener
{
    public $log; 

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $logger = new CsvLogger;
        $this->log = $logger();
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CsvWasValidatedEvent  $event
     * @return void
     */
    public function handle(CsvWasValidatedEvent $event)
    {
        $csv = new Csv();
        $csv->user()->associate($event->user)->save();
        $csv->transferencias()->saveMany($event->transfers);
        $this->log->info('The csv file uploaded by' . $event->user->name . 'has been read and inserted into database!');
    }
}

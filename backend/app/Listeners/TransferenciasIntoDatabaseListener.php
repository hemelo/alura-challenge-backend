<?php

namespace App\Listeners;

use App\Events\CsvWasValidatedEvent;
use App\Log\TransferenciasLogger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Transferencia;
use Carbon\Carbon;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Validator;

class TransferenciasIntoDatabaseListener
{
    public $log; 

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $logger = new TransferenciasLogger;
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
        $this->log->info('Starting to import all rows from validated csv into database \'Transferencias\'');

        foreach($event->data as $row)
        {
            $transferencia = new Transferencia();
            
            foreach($event->headers as $header)
            {   
                /*
                if($header != 'created_at') 
                {
                    $transferencia[$header] = $event->data[$header];
                }
                else
                {
                    $transferencia->created_at = Carbon::today();
                }*/
                $transferencia[$header] = $event->data[$header];
            }

            $transferencia->save(['timestamps' => false]);
            $event->transfers[] = $transferencia;
        }

        $this->log->info('Finished to import all' . $event->data->count() . 'rows');
    }
}

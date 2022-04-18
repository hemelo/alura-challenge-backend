<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\CsvWasValidatedEvent::class => [
            \App\Listeners\TransferenciasIntoDatabaseListener::class,
            \App\Listeners\LogCsvOnDatabaseListener::class,
            \App\Listeners\RemoveTemporaryCsvFromStorageListener::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}

<?php

namespace App\Events;

class CsvWasValidatedEvent extends Event
{
    public $csv;
    public $headers;
    public $user;
    public $transfers;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($csv_data, $user, array $headers)
    {   
        $this->headers = $headers;
        $this->user = $user;
        $this->data = $csv_data;
        $this->transfers = array();
    }
}

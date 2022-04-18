<?php

namespace Tests;

use App\Models\Csv;
use App\Models\Transferencia;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CsvIndexTest extends TestCase
{
    use DatabaseMigrations;
    
    protected function setUp() : void
    {
        parent::setUp();
        $user = User::factory()->create();
        $csv = Csv::factory()->for($user)->create();
    }

    /**
    * Test route csv index
    *
    * @return void
    */
    public function testCsvIndex()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);
        $response = $this->get('/api/csv');
        $response->seeStatusCode(200);
    }

    /**
    * Test route csv index with filter
    *
    * @return void
    */
    public function testCsvIndexWithUsuarioFilter()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);

        $response = $this->get('/api/csv?usuario=teste@gmail.com');
        $response->seeStatusCode(200);
    }

     /**
    * Test route csv index with filter
    *
    * @return void
    */
    public function testCsvIndexWithNonExistentUsuarioFilter()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);

        $response = $this->get('/api/csv?usuario=aleatorio@gmail.com');
        $response->seeStatusCode(204);
    }
}

<?php

namespace Tests;

use App\Models\Csv;
use App\Models\Transferencia;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TransferenciasIndexTest extends TestCase
{
    use DatabaseMigrations;
    
    protected function setUp() : void
    {
        parent::setUp();
        $user = User::factory()->create();
        $csv = Csv::factory()->for($user)->create();
        $transferencias = Transferencia::factory()->for($csv)->create();
    }

    /**
    * Test route transferencias index
    *
    * @return void
    */
    public function testTransferenciasIndex()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);

        $response = $this->get('/api/transferencias');

        $response->seeStatusCode(200);
    }

    /**
    * Test route transferencias index with filter
    *
    * @return void
    */
    public function testTransferenciasIndexWithBancoFilter()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);

        $response = $this->get('/api/transferencias?banco=BRADESCO');
        $response->seeStatusCode(200);

        $response = $this->get('/api/transferencias?banco=ITAU');
        $response->seeStatusCode(204);
    }

    /**
    * Test route transferencias index with filter
    *
    * @return void
    */
    public function testTransferenciasIndexWithAgenciaFilter()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);

        $response = $this->get('/api/transferencias?agencia=0001');
        $response->seeStatusCode(200);

        $response = $this->get('/api/transferencias?agencia=8322');
        $response->seeStatusCode(204);
    }

    /**
    * Test route transferencias index with filter
    *
    * @return void
    */
    public function testTransferenciasIndexWithClienteFilter()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);

        $response = $this->get('/api/transferencias?cliente=0000-1');
        $response->seeStatusCode(200);

        $response = $this->get('/api/transferencias?cliente=8931-2');
        $response->seeStatusCode(204);
    }

    /**
    * Test route transferencia show
    *
    * @return void
    */
    public function testTransferenciaShow()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);

        $response = $this->get('/api/transferencias/1');
        $response->seeStatusCode(200);
        
        $response->seeJsonContains([
            'banco_origem' => 'BRADESCO',
            'agencia_origem' => '0001',
            'conta_origem' => '0000-1',
            'banco_destino' => 'BANCO DO BRASIL',
            'agencia_destino' => '0001',
            'conta_destino' => '0000-1',
            'valor_transferido' => '8000',
        ]);
        
        $response = $this->get('/api/transferencias/1230123912032');
        $response->seeStatusCode(404);
    }
}

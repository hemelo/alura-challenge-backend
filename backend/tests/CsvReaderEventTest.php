<?php

namespace Tests;

use App\Contracts\Transferencia as TransferenciaContract;
use App\Events\CsvWasValidatedEvent;
use App\Listeners\LogCsvOnDatabaseListener;
use App\Listeners\RemoveTemporaryCsvFromStorageListener;
use App\Listeners\TransferenciasIntoDatabaseListener;
use App\Models\Csv;
use App\Models\Transferencia;
use App\Models\User;
use App\Services\CsvReader;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CsvReaderEventTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * Test csv to array
    *
    * @return void
    */
    public function testCsvToArray()
    {
        $path = Storage::path('tests/test.csv');
        $headers = new TransferenciaContract;
        $csv = (new CsvReader($path, $headers()))->fetchToArray();

        $json = json_encode($csv[0]);
        $json2 = json_encode([
            'banco_origem' => 'BANCO DO BRASIL',
            'agencia_origem' => '0001',
            'conta_origem' => '00001-1',
            'banco_destino' => 'BANCO BRADESCO',
            'agencia_destino' => '0001',
            'conta_destino' => '00001-1',
            'valor_transferido' => '8000',
            'created_at' => '2022-01-01T07:30:00'
        ]);

        $this->assertEquals(count($csv), CsvReader::lineCount($path));
        $this->assertJsonStringEqualsJsonString($json, $json2);
    }

    public function testNoFileUpload()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);
        $this->withoutEvents();

        $response = $this->call('POST', '/api/csv', [], [], []);

        $this->assertResponseStatus(422);
        $this->seeJsonContains([
            'A CSV file is required.'
        ]);
    }

    public function testValidCsvUpload()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);
        $this->withoutEvents();

        $uploadedFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            Storage::path('tests/test.csv'),
            'test.csv',
            'text/csv',
            null,
            true,
        );

        $response = $this->call('POST', '/api/csv', [], [], [ 'csv' => $uploadedFile ]);

        $this->assertResponseStatus(200);
        $this->seeJsonContains([
            'success' => 'Nice. Your CSV file is valid and will be inserted into database.'
        ]);
    }

    public function testInvalidDataCsvUpload()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);
        $this->withoutEvents();

        $uploadedFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            Storage::path('tests/invalid_data.csv'),
            'invalid_data.csv',
            'text/csv',
            null,
            true
        );

        $response = $this->call('POST', '/api/csv', [], [], [ 'csv' => $uploadedFile ]);

        $this->assertResponseStatus(409);
        $this->seeJsonContains([
            'invalid_data' => 'Your csv file contains invalid data.'
        ]);
    }

    public function testInvalidFormatCsvUpload()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);
        $this->withoutEvents();

        $uploadedFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            Storage::path('tests/invalid_format.csv'),
            'invalid_format.csv',
            'text/csv',
            null,
            true
        );

        $response = $this->call('POST', '/api/csv', [], [], [ 'csv' => $uploadedFile ]);

        $this->assertResponseStatus(409);
        $this->seeJsonContains([
            'invalid_format' => 'Your csv file has wrong format.'
        ]);
    }
}

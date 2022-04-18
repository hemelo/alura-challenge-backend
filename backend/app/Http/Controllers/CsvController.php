<?php

namespace App\Http\Controllers;

use App\Events\CsvWasValidatedEvent;
use App\Contracts\Transferencia as TransferenciaContract;
use App\Log\CsvLogger;
use App\Models\Csv;
use App\Models\Transferencia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Services\CsvReader;
use Exception;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    public $log;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $logger = new CsvLogger();
        $this->log = $logger();
    }

    public function index(Request $request)
    {
        $csv = Csv::latest()
            ->with('user:id,name,email')
            ->filter(request(['usuario']))
            ->paginate(10)
            ->withQueryString()
            ->toArray();

        $exclude_keys = ['updated_at', 'user_id'];
        for ($i = 0; $i < count($csv['data']); $i++) 
        {
            foreach ($exclude_keys as $exclude) 
            {
                unset($csv['data'][$i][$exclude]);
            }
        }

        if(count($csv['data']) > 0)
        {
            return response()->json($csv, 200);
        }
        else 
        {
            return response()->json('Not found', 204);
        }
    }

    public function store(Request $request)
    {   
        if(! $request->hasFile('csv'))
        {
            return response()->json(['A CSV file is required.'], 422);
        }

        Storage::putFileAs('', $request->file('csv'), 'temp.csv');
        $path = Storage::path('temp.csv');
        $headers = new TransferenciaContract;

        try
        {
            $csv = (new CsvReader($path, $headers()))->fetchToArray();

            $this->log->info('Validating csv uploaded by' . $request->user());

            foreach($csv as $row)
            {
                $validator = Validator::make($row, $headers->validation());
                
                if ($validator->fails()) 
                {
                    $this->log->error('Detected invalid data on csv. Stopping processing of csv uploaded by' . $request->user());
                    return response()->json(['invalid_data' => 'Your csv file contains invalid data.'], 409);
                }
            }

            $this->log->info('Csv is being imported to database on background');
            Event::dispatch(new CsvWasValidatedEvent($csv, $request->user(), $headers()));

            return response()->json(['success' => 'Nice. Your CSV file is valid and will be inserted into database.'], 200);
        }
        catch(Exception $e)
        {
            return response()->json(['invalid_format' => 'Your csv file has wrong format.'], 409);
        }
    }

    public function show(Request $request, $id)
    {   
        
    }
}

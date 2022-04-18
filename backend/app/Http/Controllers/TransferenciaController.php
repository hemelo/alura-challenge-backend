<?php

namespace App\Http\Controllers;

use App\Contracts\Transferencia as TransferenciaContract;
use App\Models\Transferencia;
use Illuminate\Http\Request;

class TransferenciaController extends Controller
{
    public $contract; 

    public function __construct()
    {
        $this->contract = (new TransferenciaContract())->jsonKeys();   
    }

    public function index(Request $request)
    {
        $transferencias = Transferencia::latest()
            
            ->filter(request([
                'banco', 
                'agencia', 
                'cliente', 
                'banco_origem',
                'banco_destino',
                'agencia_origem',
                'agencia_destino',
                'cliente_origem',
                'cliente_destino',
            ]))
            
            ->paginate(10)
            ->withQueryString()
            ->toArray();
        
        $exclude_keys = ['updated_at', 'csv_id'];
        for ($i = 0; $i < count($transferencias['data']); $i++) 
        {
            foreach ($exclude_keys as $exclude) 
            {
                unset($transferencias['data'][$i][$exclude]);
            }
        }   
        
        if(count($transferencias['data']) > 0)
        {
            return response()->json($transferencias, 200);
        }
        else 
        {
            return response()->json('Not found', 204);
        }
        
    }

    public function show($id)
    {   
        $transferencia = Transferencia::findOrFail($id)->get($this->contract);
        return response()->json($transferencia);
    }
}

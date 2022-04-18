<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model 
{
    use HasTimestamps, HasFactory;
    
    protected $table = 'transferencias_bancarias';
    
    
    protected $fillable = [
        'banco_origem',
        'agencia_origem',
        'conta_origem',
        'banco_destino',
        'agencia_destino',
        'conta_destino',
        'valor_transferido',
        'created_at'
    ];

    public function scopeFilter($queryBuilder, array $filter) 
    { 
        $queryBuilder->when($filter['banco'] ?? false, fn($queryBuilder, $parameter) =>
            $queryBuilder->where('banco_origem', 'like', '%' . $parameter . '%')
                ->orWhere('banco_destino', 'like', '%' . $parameter . '%')
        );
        
        $queryBuilder->when($filter['agencia'] ?? false, fn($queryBuilder, $parameter) =>
            $queryBuilder->where('agencia_origem', '=', $parameter)
                ->orWhere('agencia_destino', '=', $parameter)
        );
        
        $queryBuilder->when($filter['cliente'] ?? false, fn($queryBuilder, $parameter) =>
            $queryBuilder->where('conta_origem', '=', $parameter)
                ->orWhere('conta_destino', '=', $parameter)
        );

        $queryBuilder->when($filter['banco_origem'] ?? false, fn($queryBuilder, $parameter) =>
            $queryBuilder->where('banco_origem', 'like', '%' . $parameter . '%')
        );

        $queryBuilder->when($filter['banco_destino'] ?? false, fn($queryBuilder, $parameter) =>
            $queryBuilder->where('banco_destino', 'like', '%' . $parameter . '%')
        );

        $queryBuilder->when($filter['agencia_origem'] ?? false, fn($queryBuilder, $parameter) =>
            $queryBuilder->where('agencia_origem', '=', $parameter)
        );
        
        $queryBuilder->when($filter['agencia_destino'] ?? false, fn($queryBuilder, $parameter) =>
            $queryBuilder->where('agencia_destino', '=', $parameter)
        );
    }

    public function csv()
    {
        return $this->belongsTo(Csv::class);
    }
}
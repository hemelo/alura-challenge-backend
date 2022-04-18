<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Csv extends Model 
{
    use HasTimestamps, HasFactory;
    
    protected $table = 'csv_uploads';

    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i'
    ];

    public function scopeFilter($queryBuilder, array $filter) 
    { 
        $queryBuilder->when($filter['usuario'] ?? false, fn($queryBuilder, $parameter) =>
            $queryBuilder->whereHas('user', fn($queryBuilder) => 
                $queryBuilder->where('email', $parameter)
            )
        );
    }

    public function transferencias()
    {
        return $this->hasMany(Transferencia::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUpdatedAtColumn()
    {
        //Do-nothing
    }
}
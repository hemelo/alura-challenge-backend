<?php

namespace Database\Factories;

use App\Models\Transferencia;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransferenciaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transferencia::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'banco_origem' => 'BRADESCO',
            'agencia_origem' => '0001',
            'conta_origem' => '0000-1',
            'banco_destino' => 'BANCO DO BRASIL',
            'agencia_destino' => '0001',
            'conta_destino' => '0000-1',
            'valor_transferido' => '8000',
            'created_at' => Carbon::now()
        ];
    }
}

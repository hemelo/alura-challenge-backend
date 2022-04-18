<?php

namespace Database\Factories;

use App\Models\Csv;
use App\Models\Transferencia;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CsvFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Csv::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [];
    }
}

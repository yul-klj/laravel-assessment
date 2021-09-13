<?php

namespace Database\Factories;

use App\Models\Export;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Export::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => 1,
            'type' => 'CSV',
            'fields' => 'title,author',
            'location' => null
        ];
    }
}

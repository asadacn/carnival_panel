<?php

namespace Database\Factories;

use App\Models\HotspotClient;
use Illuminate\Database\Eloquent\Factories\Factory;

class HotspotClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HotspotClient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
        'contact' => $this->faker->word,
        'cable' => $this->faker->word,
        'cable_owner' => $this->faker->word,
        'onu_mac' => $this->faker->word,
        'onu_owner' => $this->faker->word,
        'adrress' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}

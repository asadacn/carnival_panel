<?php

namespace Database\Factories;

use App\Models\CardSeller;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardSellerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CardSeller::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'seller' => $this->faker->word,
        'contact' => $this->faker->word,
        'store_title' => $this->faker->word,
        'address' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}

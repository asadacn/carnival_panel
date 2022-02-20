<?php

namespace Database\Factories;

use App\Models\SMS_TEMPALTE;
use Illuminate\Database\Eloquent\Factories\Factory;

class SMS_TEMPALTEFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SMS_TEMPALTE::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word,
        'sms_template' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}

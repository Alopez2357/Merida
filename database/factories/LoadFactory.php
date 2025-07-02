<?php

namespace Database\Factories;

use App\Models\Source;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->uuid,
            'file'=>$this->faker->uuid.'.xls',
            'headline'=>$this->faker->paragraph,
            'type_id'=>Type::all()->random(),
            'user_id'=>User::all()->random(),
            'source_id'=>Source::all()->random()
        ];
    }
}

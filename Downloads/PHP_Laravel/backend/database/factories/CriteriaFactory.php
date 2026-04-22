<?php

namespace Database\Factories;

use App\Models\Criteria;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CriteriaFactory extends Factory
{
    protected $model = Criteria::class;

    public function definition()
    {
        $answerTypes = ['yes_no', 'score', 'text', 'multiple_choice'];
        $statuses = ['active', 'inactive'];
        
        return [
            'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            'code' => strtoupper($this->faker->unique()->bothify('???-##')),
            'name' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'weight' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement($statuses),
            'answer_type' => $this->faker->randomElement($answerTypes),
            'options' => $this->faker->optional()->words(3),
            'order' => $this->faker->numberBetween(1, 100),
            'reference' => $this->faker->optional()->url(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function active()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function yesNoType()
    {
        return $this->state(fn (array $attributes) => [
            'answer_type' => 'yes_no',
            'options' => null,
        ]);
    }

    public function scoreType()
    {
        return $this->state(fn (array $attributes) => [
            'answer_type' => 'score',
            'options' => null,
        ]);
    }
}
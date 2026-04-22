<?php

namespace Database\Factories;

use App\Models\AssessmentFile;
use App\Models\Server;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFileFactory extends Factory
{
    protected $model = AssessmentFile::class;

    public function definition()
    {
        $statuses = ['draft', 'published', 'archived'];
        $priorities = ['low', 'medium', 'high', 'critical'];
        $fileTypes = ['document', 'spreadsheet', 'pdf', 'image'];
        
        return [
            'title' => $this->faker->sentence(3),
            'content' => $this->faker->paragraphs(3, true),
            'server_id' => Server::inRandomOrder()->first()->id ?? 1,
            'created_by' => User::inRandomOrder()->first()->id ?? 1,
            'status' => $this->faker->randomElement($statuses),
            'version' => 1,
            'file_path' => $this->faker->optional()->filePath(),
            'file_type' => $this->faker->randomElement($fileTypes),
            'file_size' => $this->faker->numberBetween(1024, 10485760),
            'tags' => $this->faker->words(3),
            'due_date' => $this->faker->optional()->dateTimeBetween('+1 week', '+1 month'),
            'priority' => $this->faker->randomElement($priorities),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }

    public function published()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    public function withVersion($version = 2)
    {
        return $this->state(fn (array $attributes) => [
            'version' => $version,
        ]);
    }

    public function withDueDate()
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
        ]);
    }
}
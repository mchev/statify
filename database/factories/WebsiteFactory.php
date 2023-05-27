<?php

namespace Database\Factories;

use App\Models\Website;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebsiteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Website::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Default',
            'domain' => 'default.com',
            'team_id' => 1,
        ];
    }
}

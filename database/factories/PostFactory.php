<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'excerpt' => $this->faker->paragraph(),
            'content' => collect($this->faker->paragraphs(mt_rand(4, 8)))
                ->map(fn($paragraph) => "<p>{$paragraph}</p>")
                ->implode("\n"),
            'featured_image_path' => 'https://picsum.photos/1200/630?random=' . $this->faker->numberBetween(1, 1000),
            'featured_image_alt' => $this->faker->sentence(6),
            'is_draft' => $this->faker->boolean(30),
            'published_at' => $this->faker->dateTimeBetween('-2 months', '+1 month'),
            'meta' => [
                'title' => $title,
                'description' => $this->faker->sentence(12),
            ],
        ];
    }

    public function drafted(): self
    {
        return $this->state(fn () => [
            'is_draft' => true,
            'published_at' => null,
        ]);
    }

    public function published(): self
    {
        return $this->state(fn () => [
            'is_draft' => false,
            'published_at' => now(),
        ]);
    }
}


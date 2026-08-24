<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_read_sermon_cta_for_khutbah_type()
    {
        $post = Post::factory()->make([
            'title' => 'Test Khutbah Title',
            'slug' => 'test-khutbah-title',
            'type' => 'khutbah',
            'published_at' => now(),
        ]);

        $this->view('landing::front.partials.post-card', ['post' => $post])
            ->assertSee('اقرأ الخطبة')
            ->assertSee(route('khutab.show', $post->slug));
    }

    public function test_renders_read_article_cta_for_post_type()
    {
        $post = Post::factory()->make([
            'title' => 'Test Post Title',
            'slug' => 'test-post-title',
            'type' => 'post',
            'published_at' => now(),
        ]);

        $this->view('landing::front.partials.post-card', ['post' => $post])
            ->assertSee('اقرأ المقال')
            ->assertSee(route('post.show', $post->slug));
    }
}

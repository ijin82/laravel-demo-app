<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_only_visible_posts(): void
    {
        Post::factory()->create(['title' => 'Опубликованный пост']);
        Post::factory()->hidden()->create(['title' => 'Скрытый пост']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Опубликованный пост')
            ->assertDontSee('Скрытый пост');
    }

    public function test_index_is_paginated(): void
    {
        Post::factory()->count(7)->create();

        $this->get('/')
            ->assertOk()
            ->assertViewHas('posts', fn ($posts): bool => $posts->count() === 6 && $posts->total() === 7);

        $this->get('/?page=2')
            ->assertOk()
            ->assertViewHas('posts', fn ($posts): bool => $posts->count() === 1);
    }

    public function test_visible_post_page_is_available(): void
    {
        $post = Post::factory()->create(['title' => 'Открытая запись']);

        $this->get(route('posts.show', $post))
            ->assertOk()
            ->assertSee('Открытая запись');
    }

    public function test_hidden_post_page_returns_not_found(): void
    {
        $post = Post::factory()->hidden()->create();

        $this->get(route('posts.show', $post))->assertNotFound();
    }

    public function test_tag_page_shows_only_visible_posts_with_that_tag(): void
    {
        $tag = Tag::factory()->create(['name' => 'Архитектура']);

        $visible = Post::factory()->create(['title' => 'Видимая заметка']);
        $hidden = Post::factory()->hidden()->create(['title' => 'Скрытая заметка']);

        $visible->tags()->attach($tag);
        $hidden->tags()->attach($tag);

        $this->get(route('tags.show', $tag))
            ->assertOk()
            ->assertSee('Видимая заметка')
            ->assertDontSee('Скрытая заметка');
    }
}

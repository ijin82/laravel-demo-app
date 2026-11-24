<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('admin.posts.index'))->assertRedirect(route('login'));
        $this->get(route('admin.tags.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_create_a_post_with_tags(): void
    {
        $tag = Tag::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('admin.posts.store'), [
                'title' => 'Новый пост',
                'body' => 'Текст нового поста.',
                'is_visible' => '1',
                'tags' => [$tag->id],
            ])
            ->assertRedirect(route('admin.posts.index'))
            ->assertSessionHas('status');

        $post = Post::where('title', 'Новый пост')->firstOrFail();

        $this->assertTrue($post->is_visible);
        $this->assertDatabaseHas('post_tag', [
            'post_id' => $post->id,
            'tag_id' => $tag->id,
        ]);
    }

    public function test_post_title_and_body_are_required(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.posts.store'), ['title' => '', 'body' => ''])
            ->assertSessionHasErrors(['title', 'body']);

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_admin_can_hide_a_post_with_the_visibility_toggle(): void
    {
        $post = Post::factory()->create(['is_visible' => true]);

        $this->actingAs(User::factory()->create())
            ->patch(route('admin.posts.visibility', $post), ['is_visible' => 0])
            ->assertRedirect();

        $this->assertFalse($post->refresh()->is_visible);
    }

    public function test_admin_can_update_a_post(): void
    {
        $post = Post::factory()->create(['title' => 'Старое название']);
        $tag = Tag::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.posts.update', $post), [
                'title' => 'Новое название',
                'body' => 'Обновлённый текст.',
                'is_visible' => '0',
                'tags' => [$tag->id],
            ])
            ->assertRedirect(route('admin.posts.index'));

        $post->refresh();

        $this->assertSame('Новое название', $post->title);
        $this->assertFalse($post->is_visible);
        $this->assertTrue($post->tags->contains($tag));
    }

    public function test_admin_can_delete_a_post(): void
    {
        $post = Post::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_admin_can_create_rename_and_delete_a_tag(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.tags.store'), ['name' => 'Инструменты'])
            ->assertRedirect(route('admin.tags.index'));

        $tag = Tag::where('name', 'Инструменты')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.tags.update', $tag), ['name' => 'Инструменты и среда'])
            ->assertRedirect(route('admin.tags.index'));

        $this->assertSame('Инструменты и среда', $tag->refresh()->name);

        $post = Post::factory()->create();
        $post->tags()->attach($tag);

        $this->actingAs($admin)
            ->delete(route('admin.tags.destroy', $tag))
            ->assertRedirect(route('admin.tags.index'));

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
        $this->assertDatabaseMissing('post_tag', ['tag_id' => $tag->id]);
    }

    public function test_tag_names_must_be_unique(): void
    {
        Tag::factory()->create(['name' => 'Код']);

        $this->actingAs(User::factory()->create())
            ->post(route('admin.tags.store'), ['name' => 'Код'])
            ->assertSessionHasErrors('name');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Show every post, including hidden ones.
     */
    public function index(): View
    {
        return view('admin.posts.index', [
            'posts' => Post::query()
                ->with('tags')
                ->latest('created_at')
                ->latest('id')
                ->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a post.
     */
    public function create(): View
    {
        return view('admin.posts.create', [
            'post' => new Post(['is_visible' => true]),
            'tags' => $this->tags(),
        ]);
    }

    /**
     * Store a new post.
     */
    public function store(PostRequest $request): RedirectResponse
    {
        $post = Post::create($request->validatedPostData());
        $post->tags()->sync($request->validatedTagIds());

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Пост создан.');
    }

    /**
     * Show the form for editing a post.
     */
    public function edit(Post $post): View
    {
        $post->load('tags');

        return view('admin.posts.edit', [
            'post' => $post,
            'tags' => $this->tags(),
        ]);
    }

    /**
     * Update an existing post.
     */
    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $post->update($request->validatedPostData());
        $post->tags()->sync($request->validatedTagIds());

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Пост обновлён.');
    }

    /**
     * Switch the visibility flag without opening the edit form.
     */
    public function toggleVisibility(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'is_visible' => ['required', 'boolean'],
        ]);

        $post->update(['is_visible' => (bool) $validated['is_visible']]);

        return back()->with('status', $post->is_visible ? 'Пост опубликован.' : 'Пост скрыт.');
    }

    /**
     * Delete a post.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Пост удалён.');
    }

    /**
     * @return Collection<int, Tag>
     */
    private function tags(): Collection
    {
        return Tag::query()->orderBy('name')->get();
    }
}

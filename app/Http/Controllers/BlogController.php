<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BlogController extends Controller
{
    /**
     * Show the paginated list of published posts.
     */
    public function index(): View
    {
        return view('posts.index', [
            'posts' => Post::query()
                ->visible()
                ->with('tags')
                ->latest('created_at')
                ->latest('id')
                ->paginate(6),
            'tags' => $this->tagsWithCounts(),
            'activeTag' => null,
        ]);
    }

    /**
     * Show a single published post.
     */
    public function show(Post $post): View
    {
        abort_unless($post->is_visible, 404);

        $post->load('tags');

        return view('posts.show', [
            'post' => $post,
            'tags' => $this->tagsWithCounts(),
        ]);
    }

    /**
     * Show published posts filtered by a tag.
     */
    public function tag(Tag $tag): View
    {
        return view('posts.index', [
            'posts' => $tag->posts()
                ->visible()
                ->with('tags')
                ->latest('created_at')
                ->latest('id')
                ->paginate(6),
            'tags' => $this->tagsWithCounts(),
            'activeTag' => $tag,
        ]);
    }

    /**
     * @return Collection<int, Tag>
     */
    private function tagsWithCounts(): Collection
    {
        return Tag::query()
            ->withCount(['posts' => fn (Builder $query) => $query->visible()])
            ->orderBy('name')
            ->get();
    }
}

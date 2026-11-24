<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TagController extends Controller
{
    /**
     * Show all tags together with their post counts.
     */
    public function index(): View
    {
        return view('admin.tags.index', [
            'tags' => Tag::query()
                ->withCount('posts')
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Store a new tag.
     */
    public function store(TagRequest $request): RedirectResponse
    {
        Tag::create($request->validated());

        return redirect()
            ->route('admin.tags.index')
            ->with('status', 'Тег создан.');
    }

    /**
     * Rename an existing tag.
     */
    public function update(TagRequest $request, Tag $tag): RedirectResponse
    {
        $tag->update($request->validated());

        return redirect()
            ->route('admin.tags.index')
            ->with('status', 'Тег обновлён.');
    }

    /**
     * Delete a tag together with its relations.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('status', 'Тег удалён.');
    }
}

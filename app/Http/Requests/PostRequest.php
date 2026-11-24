<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'tags' => ['array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }

    /**
     * @return array{title: string, body: string, is_visible: bool}
     */
    public function validatedPostData(): array
    {
        return [
            'title' => $this->string('title')->trim()->value(),
            'body' => $this->string('body')->trim()->value(),
            'is_visible' => $this->boolean('is_visible'),
        ];
    }

    /**
     * @return array<int, int>
     */
    public function validatedTagIds(): array
    {
        return array_map('intval', $this->validated('tags', []));
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends StoreCategoryRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($this->category->id)
            ],
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    if ($value == $this->category->id) {
                        $fail('A category cannot be its own parent.');
                    }

                    // Prevent circular references
                    $children = $this->category->children;
                    $this->preventCircularReference($children, $value, $fail);
                },
            ],
        ]);
    }

    /**
     * Prevent circular references in category hierarchy
     */
    protected function preventCircularReference($categories, $parentId, $fail): void
    {
        if (!$categories) {
            return;
        }

        foreach ($categories as $category) {
            if ($category->id == $parentId) {
                $fail('Cannot set parent as it would create a circular reference.');
            }
            $this->preventCircularReference($category->children, $parentId, $fail);
        }
    }
}

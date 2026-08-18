{{-- resources/views/admin/categories/form.blade.php --}}
<form id="category-form" action="{{ $action }}" method="POST" class="space-y-4">
    @csrf
    @if($category->exists)
        @method('PUT')
    @endif

    <!-- Name -->
    <div>
        <label for="name" class="mb-2 block text-sm font-semibold">{{ __('Name') }}</label>
        <input type="text" name="name" id="name" class="nc-control"
               value="{{ old('name', $category->name) }}">
        @error('name') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="mb-2 block text-sm font-semibold">{{ __('Description') }}</label>
        <textarea name="description" id="description" rows="4" 
                  class="nc-control min-h-28 py-3">{{ old('description', $category->description) }}</textarea>
        @error('description') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Parent Category -->
    <div>
        <label for="parent_id" class="mb-2 block text-sm font-semibold">{{ __('Parent Category') }}</label>
        <select name="parent_id" id="parent_id" class="nc-select">
            <option value="">{{ __('None') }}</option>
            @foreach($parentCategories as $id => $name)
                <option value="{{ $id }}" {{ old('parent_id', $category->parent_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        @error('parent_id') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Active Switch -->
    <div class="flex items-center space-x-2">
        <div class="relative inline-block w-12 h-6">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   class="peer absolute w-0 h-0 opacity-0"
                   {{ old('is_active', $category->is_active) ? 'checked' : '' }} />
            <label for="is_active" class="block bg-gray-300 peer-checked:bg-green-400 w-12 h-6 rounded-full cursor-pointer transition-colors duration-300"></label>
            <span class="pointer-events-none absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full shadow transform transition-transform duration-300 peer-checked:translate-x-6"></span>
        </div>
        <label for="is_active" class="text-sm text-gray-900 dark:text-gray-100">{{ __('Active') }}</label>
    </div>

    <!-- Submit & Cancel -->
    <div class="flex flex-wrap justify-end gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">
        <a href="{{ route('admin.categories.index') }}" class="nc-btn-secondary">{{ __('Cancel') }}</a>
        <button type="submit" class="nc-btn-primary"><x-icon name="check" width="16" height="16" />{{ $submitButtonText }}</button>
    </div>
</form>

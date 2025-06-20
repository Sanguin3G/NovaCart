{{-- resources/views/admin/categories/form.blade.php --}}
<form id="category-form" action="{{ $action }}" method="POST" class="space-y-4">
    @csrf
    @if($category->exists)
        @method('PUT')
    @endif

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @if($category->exists)
        <input type="hidden" name="_method" value="PUT">
    @endif

    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium">{{ __('Name') }}</label>
        <input type="text" name="name" id="name" class="mt-1 block w-full border rounded px-2 py-1"
               value="{{ old('name', $category->name) }}">
        @error('name') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="block text-sm font-medium">{{ __('Description') }}</label>
        <textarea name="description" id="description" rows="4" 
                  class="mt-1 block w-full border rounded px-2 py-1">{{ old('description', $category->description) }}</textarea>
        @error('description') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Parent Category -->
    <div>
        <label for="parent_id" class="block text-sm font-medium">{{ __('Parent Category') }}</label>
        <select name="parent_id" id="parent_id" class="mt-1 block w-full border rounded px-2 py-1">
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
    <div class="flex justify-end space-x-2">
        <x-button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">{{ $submitButtonText }}</x-button>
        <x-button href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">{{ __('Cancel') }}</x-button>
    </div>
</form> 
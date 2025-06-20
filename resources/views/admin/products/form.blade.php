{{-- resources/views/admin/products/form.blade.php --}}
<form id="product-form" action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
    @if($product->exists)
        @method('PUT')
    @endif

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @if($product->exists)
        <input type="hidden" name="_method" value="PUT">
    @endif

    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium">{{ __('Name') }}</label>
        <input type="text" name="name" id="name" class="mt-1 block w-full border rounded px-2 py-1"
               value="{{ old('name', $product->name) }}" />
        @error('name') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="block text-sm font-medium">{{ __('Description') }}</label>
        <textarea name="description" id="description" rows="4"
                  class="mt-1 block w-full border rounded px-2 py-1">{{ old('description', $product->description) }}</textarea>
        @error('description') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Price & Stock -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="price" class="block text-sm font-medium">{{ __('Price') }}</label>
            <input type="number" step="0.01" name="price" id="price" class="mt-1 block w-full border rounded px-2 py-1"
                   value="{{ old('price', $product->price) }}" />
            @error('price') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="stock" class="block text-sm font-medium">{{ __('Stock') }}</label>
            <input type="number" name="stock" id="stock" class="mt-1 block w-full border rounded px-2 py-1"
                   value="{{ old('stock', $product->stock) }}" />
            @error('stock') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Category -->
    <div>
        <label for="category_id" class="block text-sm font-medium">{{ __('Category') }}</label>
        <select name="category_id" id="category_id"
                class="mt-1 block w-full border rounded px-2 py-1">
            @foreach($categories as $id => $name)
                <option value="{{ $id }}" {{ old('category_id', $product->category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Image Upload -->
    <div>
        <label for="image_url" class="block text-sm font-medium">{{ __('Image URL') }}</label>
        <input
            type="url"
            name="image_url"
            id="image_url"
            class="mt-1 block w-full border rounded px-2 py-1"
            value="{{ old('image_url', $product->image_url) }}"
        />
        @error('image_url') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
        @if($product->exists && $product->image_url)
            <img
                id="image-preview"
                src="{{ $product->image_url }}"
                alt="Current Image"
                class="mt-2 h-32 w-auto rounded cursor-pointer"
                data-modal-open="image-modal"
            />
        @else
            <img
                id="image-preview"
                alt="Preview"
                class="mt-2 h-32 w-auto rounded cursor-pointer"
                data-modal-open="image-modal"
                style="display:none;"
            />
        @endif
        <!-- Image Modal -->
        <dialog id="image-modal" style="padding:0; background:transparent; border:none; box-shadow:none;">
            <img
                id="modal-image"
                src="{{ old('image_url', $product->image_url) }}"
                alt="Full Size Image"
                class="max-w-full max-h-[80vh]"
            />
        </dialog>
    </div>

    <!-- Active Switch -->
    <div class="flex items-center space-x-2">
        <div class="relative inline-block w-12 h-6">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   class="peer absolute w-0 h-0 opacity-0"
                   {{ old('is_active', $product->is_active) ? 'checked' : '' }} />
            <label for="is_active" class="block bg-gray-300 peer-checked:bg-green-400 w-12 h-6 rounded-full cursor-pointer transition-colors duration-300"></label>
            <span class="pointer-events-none absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full shadow transform transition-transform duration-300 peer-checked:translate-x-6"></span>
        </div>
        <label for="is_active" class="text-sm text-gray-900 dark:text-gray-100">{{ __('Active') }}</label>
    </div>

    <!-- Submit & Cancel -->
    <div class="flex justify-end space-x-2">
        <x-button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">{{ $submitButtonText }}</x-button>
        <x-button href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">{{ __('Cancel') }}</x-button>
    </div>
</form>

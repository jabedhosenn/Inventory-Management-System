<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update Product') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body py-4">
                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('admin.updateproduct', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="product_description" class="form-label">Product Description</label>
                            <textarea class="form-control" id="product_description" name="product_description" rows="3" required>{{ $product->product_description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="product_price" class="form-label">Product Price</label>
                            <input type="number" class="form-control" id="product_price" name="product_price" value="{{ $product->product_price }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                        <a href="{{ route('admin.viewproduct', ['id' => $product->id]) }}" class="btn btn-secondary">View Product</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

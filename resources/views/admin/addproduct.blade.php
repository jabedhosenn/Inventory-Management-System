<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body py-4">
                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <h5 class="card-title">Add New Product</h5>
                    <form action="{{ route('admin.createproduct') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="product_image" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="product_image" name="product_image"
                                accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="product_description" class="form-label">Product Description</label>
                            <textarea class="form-control" id="product_description" name="product_description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="product_price" class="form-label">Product Price</label>
                            <input type="number" class="form-control" id="product_price" name="product_price"
                                min="0" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="product_quantity" class="form-label">Product Quantity</label>
                            <input type="number" class="form-control" id="product_quantity" name="product_quantity"
                                min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Product Category</label>
                            <select class="form-select" id="category_name" name="category_name" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option>{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="supplier_name" class="form-label">Supplier Name</label>
                            <select class="form-select" id="supplier_name" name="supplier_name" required>
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option>{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Product</button>
                        <a href="{{ route('admin.viewproduct') }}" class="btn btn-secondary">View Products</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

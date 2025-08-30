<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('View Product') }}
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
                    <h5 class="card-title">Product List</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->product_description }}</td>
                                    <td>{{ $product->product_price }}</td>
                                    <td>
                                        <a href="{{ route('admin.editproduct', $product->id) }}" class="btn btn-sm btn-primary">Update</a>
                                        <form action="{{ route('admin.deleteproduct', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this product?');" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        <a href="{{ route('admin.addproduct') }}" class="btn btn-primary">Add New Product</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

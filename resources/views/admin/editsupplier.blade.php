<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update Supplier') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form action="{{ route('admin.updatesupplier', $supplier->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Supplier Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $supplier->supplier_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">{{ __('Phone') }}</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ $supplier->phone }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ $supplier->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">{{ __('Address') }}</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required>{{ $supplier->address }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Update Supplier') }}</button>
                        <a href="{{ route('admin.viewsupplier') }}" class="btn btn-secondary">{{ __('View Suppliers') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

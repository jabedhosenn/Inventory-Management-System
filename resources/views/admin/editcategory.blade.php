<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update Category') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-lg border-0 mt-4 rounded-3">
                <div class="card-body p-4">

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Update Form --}}
                    <form action="{{ route('admin.updatecategory', $category->id) }}" method="POST" class="row g-3 align-items-end mt-2">
                        @csrf

                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">{{ __('Category Name') }}</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg rounded-3"
                                   placeholder="Enter category name" value="{{ $category->category_name }}" required>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 shadow-sm">
                                <i class="bi bi-pencil-square me-1"></i> {{ __('Update Category') }}
                            </button>
                        </div>

                        <div class="col-md-3">
                            <a href="{{ route('admin.viewcategory') }}" class="btn btn-secondary btn-lg w-100 rounded-3 shadow-sm">
                                <i class="bi bi-eye me-1"></i> {{ __('View Categories') }}
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

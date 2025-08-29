<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Category') }}
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

                    {{-- Category Form --}}
                    <form action="{{ route('admin.createaddcategory') }}" method="POST" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Category Name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success">
                            {{ __('Add Category') }}
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

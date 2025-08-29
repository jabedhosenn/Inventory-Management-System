<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-5">
    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h3 class="mb-3 text-primary">Welcome to the Admin Dashboard</h3>
                <p class="text-muted fs-5">
                    Use the navigation menu to manage categories and other resources efficiently.
                </p>
                <a href="{{ route('admin.createaddcategory') }}" class="btn btn-success mt-3">
                    Manage Categories
                </a>
                <a href="{{ route('admin.viewcategory') }}" class="btn btn-info mt-3">
                    View Categories
                </a>
            </div>
        </div>
    </div>
</div>

</x-app-layout>

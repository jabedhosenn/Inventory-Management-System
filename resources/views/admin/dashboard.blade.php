<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body text-center p-5">
                    <!-- Dashboard Heading -->
                    <h2 class="mb-3 fw-bold text-primary">
                        <i class="bi bi-speedometer2 me-2"></i> Admin Dashboard
                    </h2>
                    <p class="text-muted fs-5 mb-4">
                        Manage categories, suppliers, and other resources efficiently using the options below.
                    </p>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                        <a href="{{ route('admin.createaddcategory') }}"
                            class="btn btn-success btn-lg px-4 rounded-3 shadow-sm">
                            <i class="bi bi-folder-plus me-1"></i> Manage Categories
                        </a>
                        <a href="{{ route('admin.viewcategory') }}"
                            class="btn btn-info btn-lg px-4 rounded-3 shadow-sm">
                            <i class="bi bi-folder2-open me-1"></i> View Categories
                        </a>
                        <a href="{{ route('admin.addsupplier') }}"
                            class="btn btn-primary btn-lg px-4 rounded-3 shadow-sm">
                            <i class="bi bi-person-plus me-1"></i> Manage Supplier
                        </a>
                        <a href="{{ route('admin.viewsupplier') }}"
                            class="btn btn-warning btn-lg px-4 rounded-3 shadow-sm text-white">
                            <i class="bi bi-people me-1"></i> View Suppliers
                        </a>
                        <a href="{{ route('admin.addproduct') }}"
                            class="btn btn-secondary btn-lg px-4 rounded-3 shadow-sm">
                            <i class="bi bi-box-seam me-1"></i> Manage Products
                        </a>
                        <a href="{{ route('admin.viewproduct') }}"
                            class="btn btn-secondary btn-lg px-4 rounded-3 shadow-sm">
                            <i class="bi bi-box-seam me-1"></i> View Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>

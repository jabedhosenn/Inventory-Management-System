<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Supplier') }}
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

                    <form action="{{ route('admin.createsupplier') }}" method="POST"
                        class="p-4 border rounded-3 shadow-sm bg-white">
                        @csrf
                        <div class="row g-4">
                            {{-- Supplier Name --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Supplier Name</label>
                                <input type="text" name="name" id="name"
                                    class="form-control form-control-lg rounded-3" placeholder="Enter supplier name"
                                    required>
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Phone</label>
                                <input type="text" name="phone" id="phone"
                                    class="form-control form-control-lg rounded-3" placeholder="Enter supplier phone"
                                    required>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" id="email"
                                    class="form-control form-control-lg rounded-3" placeholder="Enter supplier email"
                                    required>
                            </div>

                            {{-- Address --}}
                            <div class="col-md-6">
                                <label for="address" class="form-label fw-semibold">Address</label>
                                <input type="text" name="address" id="address"
                                    class="form-control form-control-lg rounded-3" placeholder="Enter supplier address"
                                    required>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-lg w-100 rounded-3 shadow-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Add Supplier
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('admin.viewsupplier') }}"
                                    class="btn btn-primary btn-lg w-100 rounded-3 shadow-sm">
                                    <i class="bi bi-eye me-1"></i> View Suppliers
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>


{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

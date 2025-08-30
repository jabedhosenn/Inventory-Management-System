<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('View Supplier') }}
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
                    {{-- Category Table --}}
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Supplier Name') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Address') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td>{{ $supplier->id }}</td>
                                    <td>{{ $supplier->supplier_name }}</td>
                                    <td>{{ $supplier->phone }}</td>
                                    <td>{{ $supplier->email }}</td>
                                    <td>{{ $supplier->address }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('admin.editsupplier', $supplier->id) }}"
                                            class="btn btn-primary btn-sm me-3">{{ __('Update') }}</a>

                                        <form action="{{ route('admin.deletesupplier', $supplier->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this supplier?');"
                                                class="btn btn-danger btn-sm">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    <div>
                        <a href="{{ route('admin.addsupplier') }}" class="btn btn-success">
                            {{ __('Add Supplier') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

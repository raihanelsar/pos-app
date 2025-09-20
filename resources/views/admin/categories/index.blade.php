@extends('layouts.app')
@section('title', 'Categories')
@section('content')
<section class="row mt-2">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Categories
                    <div class="float-end">
                        <button type="button" class="btn btn-primary btn-sm" aria-label="Tambah kategori" data-bs-toggle="modal" data-bs-target="#categoryModal">
                            <i class="mdi mdi-plus-circle-outline me-1"></i> Add Category
                        </button>
                    </div>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-dark" id="tableCategories">
                        <thead>
                            <tr>
                                <th style="width:60px">#</th>
                                <th>Category Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $c)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $c->category_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Category Modal --}}
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Category Name</label>
                        <input id="category_name" type="text" name="category_name" class="form-control" value="{{ old('category_name') }}" required>
                        @error('category_name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    // initialize DataTable for dashboard look
    $(function(){
        if ($.fn.DataTable) {
            $('#tableCategories').DataTable({
                paging: true,
                searching: true,
                info: false,
                lengthChange: false,
                pageLength: 10,
                language: { search: "", searchPlaceholder: "Search..." }
            });
        }
    });
</script>
@endsection

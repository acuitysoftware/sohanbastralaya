@extends('pages.layouts.app')

@section('content')
    <div class="content">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Cart</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-xl-8">
                                <form method="post" action="{{route('import.data')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Product Image</label>
                                        <input type="file" id="example-fileinput" class="form-control" name="file">
                                        @error('image') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>

                        </div>


                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div><!-- end col -->

        </div>
    </div>
@endsection
@section('script')
@endsection

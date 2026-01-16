@extends('pages.layouts.app')

@section('content')
<div class="content">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">User List</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <livewire:admin.sub-admin.sub-admin-list/>
    
</div>

@endsection      
@section('script')

@endsection
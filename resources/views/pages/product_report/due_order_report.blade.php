@extends('pages.layouts.app')

@section('content')
<div class="content">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Due Amount Report</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <livewire:admin.product-report.due-order-report/>
</div>

@endsection      
@section('script')

@endsection
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
    <livewire:admin.product.product-cart/>
</div>
@endsection      
@section('script')

@endsection
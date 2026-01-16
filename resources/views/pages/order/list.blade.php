@extends('pages.layouts.app')

@section('content')
<div class="content">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Order List</h4>
            </div>
        </div>
    </div>
    
    
    <livewire:admin.order.order-list wire:key="1"/>
    <livewire:admin.order.order-sub-list wire:key="2"/>
    
</div>

@endsection      
@section('script')

@endsection
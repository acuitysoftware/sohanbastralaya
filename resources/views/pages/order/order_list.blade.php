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
    <!-- end page title -->
    <livewire:admin.order.order-list/>
    
</div>

@endsection      
@section('script')
<script type="text/javascript">

    window.addEventListener('show-order-view-form', event => {
        //alert('okk');
            $('#orderView').modal('show');
        });
</script>
@endsection
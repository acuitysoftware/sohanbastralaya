@extends('pages.layouts.app')

@section('content')
<div class="content">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Customer View</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <livewire:admin.customer.customer-view :contact="$contact"/>
    
</div>

@endsection      
@section('script')
<script type="text/javascript">
    
</script>
@endsection
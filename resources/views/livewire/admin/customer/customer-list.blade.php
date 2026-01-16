<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-xl-9">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <input class="form-control" type="text"  placeholder="Search..." wire:model="searchPhone">
                                </div>
                            </div>
                            
                            <div class="col-auto">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-danger" wire:click="resetSearch">All</button>                                                
                                </div>
                            </div>


                        </div>
                    </div>
                    @if(Auth::user()->type=='A')
                    <div class="col-xl-3">
                        <div class="row align-items-center justify-content-xl-end mt-xl-0 mt-2">
                            
                                <!-- <label for="status-select" class="me-2">Status</label> -->
                                <select class="form-select w-auto me-2 mb-3" wire:model="storeUser">
                                    <option value="" disabled>Select Store</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                                                                       
                        </div>
                    </div><!-- end col-->
                    @endif
                </div>
                <div wire:loading wire:target="storeUser">
                    <div class="loader_sectin" id="loader_section" >
                        <div class="loader_overlay"></div>
                        <div id="loader" class="center" ></div>
                    </div>                
                </div>
                <div wire:loading wire:target="resetSearch">
                    <div class="loader_sectin" id="loader_section" >
                        <div class="loader_overlay"></div>
                        <div id="loader" class="center" ></div>
                    </div> 
                </div>
                <div wire:loading wire:target="loadMore">
                    <div class="loader_sectin" id="loader_section" >
                        <div class="loader_overlay"></div>
                        <div id="loader" class="center" ></div>
                    </div> 
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Sl No.</th>
                                <th>Customer Name</th>
                                <th>Contact No.</th>
                                <th>Order Date</th>
                                <th>Order Total</th>
                                @if(Auth::user()->type=='A')
                                    <th>Purchase Price</th>
                                    <th>Profit</th>
                                @endif
                                <th>Action</th>
                            </tr>

                        </thead>
                        <tbody>
                        	@if(count($customers)>0)
                        	@foreach($customers as $key=>$row)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$row->customer_name}}</td>
                                <td>{{$row->customer_phone}}</td>
                                <td>{{date('d/m/Y',strtotime($row->order_date)) }}</td>
                                <td>{{$row->total_selling_price-$row->total_discount}}</td>
                                @if(Auth::user()->type=='A')
                                    <td>{{$row->total_purchase_price}}</td>
                                    <td>{{number_format(($row->total_selling_price-($row->total_purchase_price+$row->total_discount)),2)}}</td>
                                @endif
                                <td style="white-space: nowrap;">
                                    @if(Auth::user()->type=='A' || in_array('customer-view', Auth::user()->permissions()->pluck('permission')->toArray()))
                                    <!-- <a href="javascript:void(0);" class="action-icon" wire:click="viewCustomer({{$row->customer_phone}})"><i class="mdi mdi-eye"></i></a> -->
                                    <a href="{{route('customer_view',$row->customer_phone)}}" class="action-icon"><i class="mdi mdi-eye"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                            	<td colspan="8" class="align-center">No records available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($customers->hasMorePages())
                    <button wire:click.prevent="loadMore" class="btn btn-primary">Load more</button>
                @endif
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->


</div>

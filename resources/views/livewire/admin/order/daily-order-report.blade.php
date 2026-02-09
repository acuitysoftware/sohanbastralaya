<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-xl-9">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <input class="form-control" type="date" wire:model.lazy="startDate" placeholder="Date From">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <input class="form-control" type="date" wire:model.lazy="endDate" placeholder="Date From">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <button class="btn btn-danger" wire:click="resetSearch">All</button>                                                
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->type=='A')
                    <div class="col-xl-3">
                        <div class="row align-items-center justify-content-xl-end mt-xl-0 mt-2">
                            
                                <!-- <label for="status-select" class="me-2">Status</label> -->
                                <select class="form-select w-auto me-2" wire:model="storeUser"> 
                                    <option value="" disabled="">Select Store</option>
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
                                <th>Date</th>
                                <th>Selling Price</th>
                                @if(Auth::user()->type=='A')
                                    <th>Purchase Price</th>
                                    <th>Profit</th> 
                                @endif       
                            </tr>

                        </thead>
                        <tbody>
                            @if(count($orders)>0)
                                @php
                                    $total_selling_price = 0;
                                    $total_purchase_price = 0;
                                    $total_profit = 0;
                                @endphp
                            @foreach($orders as $key=>$row)
                                @php
                                    $total_selling_price+=$total_selling_data[$key]->total_price;
                                    $total_purchase_price+=$row->total_purchase_price;
                                    $total_profit+=($row->sub_total-($row->total_purchase_price+$row->total_discount));
                                @endphp
                                <tr>
                                    <td>{{date('d/m/Y',strtotime($row->order_date)) }}</td>
                                    <td>{{env('CURRENCY','₹')}}{{number_format($total_selling_data[$key]->total_price,2)}}</td>
                                    <!-- <td>{{number_format($row->total_selling_price-$row->total_discount,2)}}</td> -->
                                    @if(Auth::user()->type=='A')
                                    <td>{{env('CURRENCY','₹')}}{{number_format($row->total_purchase_price,2)}}</td>
                                    <td>{{env('CURRENCY','₹')}}{{number_format(($row->sub_total-($row->total_purchase_price+$row->total_discount)),2)}}</td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td>{{env('CURRENCY','₹')}}{{number_format(ceil($total_selling_price),2)}}</td>
                                @if(Auth::user()->type=='A')
                                <td>{{env('CURRENCY','₹')}}{{number_format(ceil($total_purchase_price),2)}}</td>
                                <td>{{env('CURRENCY','₹')}}{{number_format(ceil($total_profit),2)}}</td>
                                @endif
                            </tr>
                            @else
                            <tr>
                                <td colspan="4" class="text-center">No records available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{-- @if($orders->hasMorePages())
                    <button wire:click.prevent="loadMore" class="btn btn-primary">Load more</button>
                @endif --}}
                 {{ $orders->links() }}
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            	<div class="row mb-2" style="background-color: #009CEC;">
                    <div class="col-xl-9">
	            		<h4 class="customer_view_title" id="primary-header-modalLabel">Orders of {{@$customer_details[0]->customer_name}}</h4>
	            	</div>
	            	<div class="col-xl-3 mt-2">
	            		<a href="{{route('customer_list')}}" type="button" class="btn btn-danger" data-bs-dismiss="modal" style="float: right;">Back</a > 
	            	</div>
	            </div>
            	<div class="table-responsive">

                    <h4>Card Details</h4>
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap" id="products-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Card Number</th>
                                <th>Total Points</th>
                                <th>Card Issue Date</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{@$card_details->card_number}}</td>
                                <td>{{@$total_credit_points}}</td>
                                <td>{{@$card_details->add_date}}</td>
                                <td>
                                    <button type="button" class="btn btn-warning" wire:click="viewmemberships({{@$card_details->contact?$card_details->contact:'0'}})">View</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap" id="products-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Order Total</th>
                                <th>Actual Selling Price</th>
                                @if(Auth::user()->type=='A')
                                <th>Purchase Price</th>
                                <th>Profit</th>
                                @endif
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($customer_details)>0)
                            @foreach($customer_details as $key=>$row)
                            <tr>
                                <td>{{$row->order_id}}</td>
                                <td>{{$row->order_date}}</td>
                                <td>{{$row->subtotal}}</td>
                                @php
                                $purchase_price =0.00;
                                $total_discount =0.00;
                                foreach($row->productDetails as $value)
                                {
                                    $purchase_price+=$value->total_purchase_price;
                                    $total_discount+=$value->total_discount;
                                }
                                @endphp
                                <td>{{$row->subtotal-$total_discount}}</td>
                                @if(Auth::user()->type=='A')
                                    <td>{{$purchase_price}}</td>
                                    <td>{{($row->subtotal-$total_discount) -$purchase_price}}</td>
                                @endif
                                <td style="white-space: nowrap;"><a href="javascript:void(0);" class="action-icon" wire:click="viewOrder({{$row->order_id}})"><i class="mdi mdi-eye"></i></a></td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- View Memberships -->
<div wire:ignore.self id="showMembership" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="viewmembershipsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">View Memberships</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <span class="badge badge-danger-lighten p-1 font-14">Card expires in : {{$expiry_date_count}} days</span>
                <span class="badge badge-primary-lighten p-1 font-14">Total Available Credit : {{@$total_credit_points}}</span>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Amount</th>
                                <th>Credit</th>
                                <th>Debit</th>
                            </tr>

                        </thead>
                        <tbody>
                            @if(count($memberships)>0)
                            @foreach($memberships as $membership)
                            <tr>
                                <td>{{$membership->order_id}}</td>
                                <td>{{$membership->order->order_date}}</td>
                                <td>{{$membership->grand_amt}}</td>
                                <td>{{$membership->credit_points}}</td>
                                <td>{{$membership->debit_point}}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr><td colspan="5">No data available</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- View Modal -->
<div wire:ignore.self id="orderView" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="viewmodalLabel" aria-hidden="true" style="z-index: 99999;"> 
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">View</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">


                <div id="print_data" class="w-100 mt-3 mb-3">

                    <div class="table-responsive">
                        <h3 class="mb-1">Details</h3>
                        <table class="table table-bordered table-centered w-100 dt-responsive nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Phone No</th>
                                    <th>Order ID</th>
                                    <th>Order Date</th> 
        
                                </tr>
    
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{@$orderDetails->customer_name}}</td>         
                                    <td>{{@$orderDetails->customer_address}}</td>         
                                    <td>{{@$orderDetails->customer_email}}</td>         
                                    <td>{{@$orderDetails->customer_phone}}</td>         
                                    <td>{{@$orderDetails->order_id}}</td>         
                                    <td>{{@$orderDetails->order_date}}</td>         
                                </tr>
                            </tbody>
                        </table>
                    </div>





                    <div class="table-responsive">
                        <h3 class="mb-1">Product Details</h3>
                        <table class="table table-bordered table-centered w-100 dt-responsive nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Actual Selling Price</th>
                                    @if(Auth::user()->type=='A')
                                        <th>Total Purchase Price</th>
                                        <th>Net Profit</th>
                                    @endif        
                                </tr>
    
                            </thead>
                            <tbody>
                                <tr>
                                    @if(isset($orderDetails->productDetails))
                                    @php
                                    $purchase_price =0.00;
                                    $total_discount =0.00;
                                    foreach($orderDetails->productDetails as $value)
                                    {
                                        $purchase_price+=$value->total_purchase_price;
                                        $total_discount+=$value->total_discount;
                                    }
                                    @endphp
                                    <td>{{@$orderDetails->subtotal-@$total_discount}}</td>
                                        @if(Auth::user()->type=='A')
                                            <td>{{@$purchase_price}}</td>
                                            <td>{{(@$orderDetails->subtotal-(@$total_discount+$purchase_price))}}</td>
                                        @endif
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-bordered table-centered w-100 dt-responsive nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Code</th>
                                    @if(Auth::user()->type=='A')
                                        <th>Purchase Price</th>
                                    @endif
                                    <th>Selling Price</th>
                                    <th>Discount</th>
                                    <th>Actual Selling Price</th>
                                    @if(Auth::user()->type=='A')
                                        <th>Profit</th>
                                        <th>Percentage Profit</th>
                                    @endif
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
    
                            </thead>
                            <tbody>
                                @if(count($viewOrder)>0)
                                @foreach($viewOrder as $key=>$row)
                                <tr>

                                    <td>{{@$row->product_name}}</td>
                                    <td>{{@$row->product_code}}</td>
                                    @if(Auth::user()->type=='A')
                                        <td>{{@$row->purchase_price}}</td>
                                    @endif
                                    <td>{{@$row->selling_price}}</td>
                                    <td>{{@$row->discount}}</td>
                                    <td>{{@$row->selling_price-@$row->discount }}</td>
                                    @if(Auth::user()->type=='A')
                                        <td>{{@$row->profit }}</td>
                                        <td>{{@$row->profit_percentage }}%</td>
                                    @endif
                                    <td>{{@$row->qty}}</td>
                                    <td>{{@$row->subtotal}}</td>
                                    <td><button type="button" class="btn btn-warning" wire:click="returnOrder({{$row->id}})">Return</button></td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if(isset($orderDetails))
                    @if(count(@$orderDetails->returnProducts))
                    <!-- <div style="display: flex;align-items: end;justify-content: flex-end;margin: 15px -7 0;"><button type="submit" id="save_btn" class="btn btn-primary" onclick="PrintReturnDiv()">Print Return </button></div> -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-centered w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="7" style="background-color: #009CEC;">Return Products</th>
                                </tr>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Code</th>
                                    <th>Selling Price</th>
                                    <th>Discount</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach(@$orderDetails->returnProducts as $details)
                                <tr>
                                    <td>{{@$details->product_name}}</td>
                                    <td>{{@$details->product_code}}</td>
                                    <td>{{@$details->selling_price}}</td>
                                    <td>{{@$details->discount}}</td>
                                    <td>{{@$details->selling_price-@$details->discount }}</td>
                                    <td>{{@$details->qty}}</td>
                                    <td>{{@$details->price*@$details->qty}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    @endif
    
                    <div class="table-responsive">
                        <div class="d-flex align-items-center justify-content-end">
                            <table class="table w-auto table-dark">
                                <tbody>
                                    @if(isset($orderDetails->productDetails))
                                    @php
                                    $discount=0.00;
                                    if(@$orderDetails->discount_type == 'dis_amt'){
                                        $discount = @$orderDetails->discount_amt;
                                    }
                                    else{

                                        $discount = @$orderDetails->perctge_amt;
                                    }                
                                    @endphp
                                    <tr>
                                        <th>Discount Amt :</th>
                                        <td>{{$discount}}</td>
                                    </tr>
                                    <tr>
                                        <th>Grand Total :</th>
                                        <td>Rs {{$orderDetails->subtotal-$discount}}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                



            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- My Modal -->
<div wire:ignore.self id="returnProduct" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="return-modalLabel" aria-hidden="true" style="z-index: 999999;">    
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">Return Product</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form wire:submit.prevent="saveReturnOrder">
                <input type="hidden" wire:model.defer="return_order_id">
            <div class="modal-body">
                <span class="badge badge-outline-danger p-1 font-16 mb-1">Order Id - {{@$returnOrder->order_id}}</span>
                <div class="row mt-2">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" placeholder="Product Name" wire:model.defer="product_name" readonly>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">Product Code</label>
                        <input type="text" class="form-control" placeholder="Product Code" wire:model.defer="product_code" readonly>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">Quantity</label>
                        <input type="text" class="form-control" placeholder="Quantity" wire:model.defer="product_qty">
                        @error('product_qty') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">Selling Price</label>
                        <input type="text" class="form-control" placeholder="Selling Price" wire:model.defer="product_selling_price" readonly>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>



        </div>
    </div>
</div>

<!-- /.modal -->
<script type="text/javascript">
    

function PrintReturnDiv() {
    var divToReturnPrint = document.getElementById('print_return_data');
    var popupWin = window.open('', '_blank', 'width=900,height=650');
    popupWin.document.open();
    popupWin.document.write('<html><body onload="window.print()">' + divToReturnPrint.innerHTML + '</html>');
    popupWin.document.close();

}
</script>
</div>

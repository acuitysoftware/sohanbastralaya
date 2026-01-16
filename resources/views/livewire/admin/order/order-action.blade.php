@if(Auth::user()->type=='A' || in_array('order-view', Auth::user()->permissions()->pluck('permission')->toArray()))
	<a href="javascript:void(0);" title="View" class="action-icon" wire:click="viewOrder({{$order_id}})"><i class="mdi mdi-eye"></i></a>
	<!-- My Modal -->
<div wire:ignore.self id="orderView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modalLabel" aria-hidden="true"> 
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">Order Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="row ">
                    <div class="col-xl-8">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <button type="button" onclick="PrintDiv()" class="btn btn-primary">Print Details</button>                                                
                                    <button type="button" class="btn btn-danger">Cancel</button> 
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-4">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-end justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary">Whole Seller</button>                                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="print_data" class="w-100 mt-3 mb-5" >
                    <table style="width: 300px; border-spacing: 0px; margin: 0 auto;">
                        <tbody>
                            <tr>
                                <td style="text-align: center;">
                                    <p style="margin: 5px 0px; font-size: 18px; color: #000;font-weight: 600; text-transform: uppercase;">{{$setting->site_title}}</p>
                                    <p style="margin: 5px 0px; font-size: 16px; color: #000;">Madhanmohan Pur Bazar,</p>
                                    <p style="margin: 5px 0px; font-size: 16px; color: #000;">Ramanagar, kulpi</p>
                                    <p style="margin: 5px 0px; font-size: 16px; color: #000;">Pin 743347</p>
                                    <p style="margin: 5px 0px; font-size: 16px; color: #000;">Phone: {{$setting->contact_no}}</p>
                                    <p style="margin: 5px 0px; font-size: 15px; color: #000;">Email: {{$setting->site_mail}}</p>
                                    <p style="margin: 5px 0px; font-size: 15px; color: #000; text-transform: uppercase;">Order Details </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table style="width: 100%; border-spacing: 0px;">
                                        <tbody>
                                            <tr>
                                                <td style="font-size: 12px; color: #000; padding: 10px 0px 0;">Bill No : {{@$orderDetails->order_id}}</td>
                                                <td style="text-align: right; font-size: 12px; color: #000; padding: 10px 0px 0;">
                                                    Date : {{date('Y-m-d',strtotime(@$orderDetails->order_date)) }}                                     
                                                    <p style="padding: 0px;margin:0px;">{{date('h:i',strtotime(@$orderDetails->order_time)) }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 12px; color: #000; text-transform: uppercase;  border-top: 1px dashed #ccc; padding: 10px 0px 0;     border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <p style="padding: 0px;margin:0;">{{@$orderDetails->customer_name}}</p>
                                                    <p style="padding: 0px;margin:0px;">@if(@$orderDetails->customer_phone){{@$orderDetails->customer_phone}} @endif</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table style="width: 100%; border-spacing: 0px; font-size:12px;">
                                        <tbody>
                                            <tr>
                                                <td style="text-align: left; width:50%;">
                                                    <strong>Item Name</strong>
                                                </td>
                                                <td style="text-align: center; width:10%;">
                                                    <strong>Qty</strong>  
                                                </td>
                                                <td style="text-align: center; width:0%;">
                                                </td>
                                                <td style="text-align: center; width:20%;">
                                                    <strong>Rate</strong> 
                                                </td>
                                                <td style="text-align: right; width:20%;">
                                                    <strong>Amount</strong>
                                                </td>
                                            </tr>
                                            @if(isset($orderDetails))
                                            @foreach(@$orderDetails->productDetails as $key=>$product)
                                            <tr>
                                                <td style="text-align: left; padding: 5px 0px; ">
                                                    {{$product->product_name}}                    
                                                </td>
                                                <td style="text-align: center; padding: 5px 0px; ">
                                                    {{$product->qty}}                    
                                                </td>
                                                <td style="text-align: center; padding: 5px 0px; ">
                                                </td>
                                                <td style="text-align: center; padding: 5px 0px; ">
                                                    {{$product->selling_price}}
                                                </td>
                                                <td style="text-align: right; padding: 5px 0px; ">
                                                    {{$product->selling_price *$product->qty}}                    
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                            <tr>
                                                <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <strong>Sub Total: </strong>
                                                </td>
                                                <td style="text-align: center; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <!--Sub Total: -->
                                                </td>
                                                <td style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" >
                                                    {{@$orderDetails->subtotal}}                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <strong>Discount Amount</strong>
                                                </td>
                                                <td style="text-align: center; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                </td>
                                                <td style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" >
                                                     @if(@$orderDetails->discount_type == 'dis_amt')
                                                    {{@$orderDetails->discount_amt}}     @else                
                                                    {{@$orderDetails->perctge_amt}}
                                                    @endif                   
                                                </td>
                                            </tr>
                                            @if(isset($orderDetails) && count($orderDetails->productDetails)>0)
                                            @if(@$orderDetails->wallet == 1)
                                            <tr>
                                                <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <strong>Wallet Debit</strong>
                                                </td>
                                                <td style="text-align: center; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <!--Sub Total: -->
                                                </td>
                                                <td style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" >
                                                    {{@$orderDetails->wallet_discount}}                         
                                                </td>
                                            </tr>
                                            @endif
                                            @endif
                                            @if(isset($orderDetails) && count($orderDetails->productDetails)>0)
                                            @if(@$orderDetails->return_id != '')
                                            <tr>
                                                <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <strong>Return Amount ({{@$orderDetails->return_id}})</strong>
                                                </td>
                                                <td style="text-align: center; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <!--Sub Total: -->
                                                </td>
                                                <td style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" >
                                                    {{@$orderDetails->return_amt}}                         
                                                </td>
                                            </tr>
                                            @endif
                                            @endif

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table style="width: 100%; border-spacing: 0px; margin-bottom: 20px;">
                                        <tbody>
                                            <tr>
                                                <td style="font-size: 15px; color: #000; padding: 7px 0px;  border-bottom: 1px dashed #ccc;" colspan="2"> <strong>Total :</strong></td>
                                                <td style="font-size: 15px; color: #000; padding: 7px 0px; text-align: right; border-bottom: 1px dashed #ccc; font-weight: 600; ">
                                                    @if(isset($orderDetails) && count($orderDetails->productDetails)>0){{@$orderDetails->total_amount}}
                                                    @else
                                                    0.00
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 12px; color: #000; padding: 7px 0px;">
                                    <strong>Note</strong> : We exchange products within 7 days between 10: 30am to 12:30pm from the date of purchase.
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 12px; color: #000; padding: 7px 0px; text-align: center;">
                                    Our Store open 7 days a week
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 18px; color: #000; padding: 7px 0px; text-align: center;">
                                    Thank you
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap" id="products-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Product Name</th>
                                <th>Code</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Discount</th>
                                <th>Actual Selling Price</th>
                                <th>Profit</th>
                                <th>Percentage Profit</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>&nbsp;</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($orderDetails))
                            @if(@$orderDetails->productDetails != '[]')
                            @foreach(@$orderDetails->productDetails as $orderDetails)
                            <tr>
                                <td>{{@$orderDetails->product_name}}</td>
                                <td>{{@$orderDetails->product_code}}</td>
                                <td>{{@$orderDetails->purchase_price}}</td>
                                <td>{{@$orderDetails->selling_price}}</td>
                                <td>{{@$orderDetails->discount}}</td>
                                <td>{{@$orderDetails->selling_price-@$orderDetails->discount }}</td>
                                <td>{{@$orderDetails->profit }}</td>
                                <td>{{@$orderDetails->profit_percentage }}%</td>
                                <td>{{@$orderDetails->qty}}</td>
                                <td>{{@$orderDetails->subtotal-@$orderDetails->total_discount}}</td>
                                <td><button type="button" class="btn btn-warning" wire:click="returnOrder({{$orderDetails->id}})">Return</button> </td>
                            </tr>
                            @endforeach
                            @endif
                            @endif
                        </tbody>
                    </table>
                </div>

                @if(isset($orderDetails))
                @if(isset($orderDetails->returnProducts))
                <div style="display: flex;align-items: end;justify-content: flex-end;margin: 15px -7 0;"><button type="submit" id="save_btn" class="btn btn-primary" onclick="PrintReturnDiv()">Print Return </button></div>
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
                                <td>{{@$details->price}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @endif
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endif
@if(Auth::user()->type=='A' || in_array('order-delete', Auth::user()->permissions()->pluck('permission')->toArray()))
	<a href="javascript:void(0);" class="action-icon" id="warning" title="Delete" wire:click="deleteAttempt({{ $order_id }})"><i class="mdi mdi-delete"></i></a>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive mt-5 ">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap" id="products-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Products Name</th>
                                <th>Code</th>
                                <th>Selling Price</th>
                                <th>Qty</th>
                                <th>Sub Total</th>
                                <th>Discount <!-- (Max Discount upto {{$setting->discount_percentage}}%) --></th>
                                <th>Total Discount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $max_discount =0;
                            @endphp
                        	@if(count($cartItems)>0)
                        	@foreach($cartItems as $key=>$row)
                            <tr>
                                <td>
                                	@if(isset($row->product->gallery))
                                    <a data-fancybox="gallery" href="{{asset('storage/app/public/product_image/'.$row->product->gallery->gallery_image) }}">
                                        <img src="{{asset('storage/app/public/product_image/'.$row->product->gallery->gallery_image) }}" alt="contact-img"
                                            title="contact-img" class="rounded me-3" height="48" />
                                    </a>
                                    @else
                                    <img src="{{asset('public/assets/images/no_image.png') }}" alt="contact-img"
                                            title="contact-img" class="rounded me-3" height="48" />
                                    @endif
                                </td>
                                <td>{{$row->product->name}}</td>
                                <td>{{$row->product->product_code}}</td>
                                <td>{{$row->selling_price}}</td>
                                <td>
                                	<div class="plusminus">
                                        <button class="btn btn-primary" type="button" wire:click="decrementQuantity({{$row->id}},{{$key}})" style="margin: 0;">-</button>
                                        <input  type="text" wire:model="cart_quantity.{{$key}}" >                <button class="btn btn-primary" type="button" wire:click="incrementQuantity({{$row->id}},{{$key}})" style="margin: 0;">+</button>
                                    </div>
                                </td>
                                <td>{{$row->selling_price*$row->quantity}}</td>
                                <td style="display: flex;">
                                    @if($row->product->is_discount)
                                        @if($row->product->discount_type == 'Flat')
                                        @php

                                        $max_discount+=($row->product->discount_amt*$row->quantity);
                                        @endphp

                                        <p>{{number_format($row->product->discount_amt*$row->quantity)}}</p>
                                        @else
                                        <p>{{number_format($row->product->discount_amt)}}%={{((($row->selling_price*$row->product->discount_amt)/100)*$row->quantity)}}
                                        </p>
                                        @php
                                            $max_discount+=((($row->selling_price*$row->product->discount_amt)/100)*$row->quantity);
                                        @endphp
                                        @endif
                                    @else
                                    <p>{{$setting->discount_percentage}}%={{((($row->selling_price*$setting->discount_percentage)/100)*$row->quantity)}}</p>
                                    @php
                                        $max_discount+=((($row->selling_price*$setting->discount_percentage)/100)*$row->quantity);
                                    @endphp
                                    @endif
                                    <input  type="text" wire:model="discount.{{$key}}" style="height: 35px; margin-left: 10px;"> 
                                </td>
                                <td>{{$cartItems[$key]->total_discount}}</td>
                                <td style="white-space: nowrap;">
                                    <a href="javascript:void(0);" class="action-icon" id="warning" title="Delete"><i class="mdi mdi-delete" wire:click="deleteAttempt({{ $row->id }})"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                            	<td colspan="9" class="align-center">No records available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="totl-dv">

                <div>
                    <label>Return Product :</label>
                    <input type="checkbox" wire:model="return" id="use_return">
                    @if($return == 1)
                    <div id="return_drop" >
                        <select id="return_order" wire:model="return_order_id">
                            <option value="">Select Order ID</option>
                            @if(count($returnProducts)>0)
                            @foreach($returnProducts as $returnProduct)
                                <option value="{{$returnProduct->order_id}}">{{$returnProduct->order_id}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @endif
                </div>
               
                <div class="fl-ttl">
                    <div style="display: flex;align-items: center;">
                    <label class="tl-dv" style="margin: 0;">Sub Total:</label>
                        <span id="subtotal_span">
                            {{number_format($sub_total,2)}}                       
                        </span>
                        <input type="hidden" id="subtotal" name="subtotal" value="400">

                    </div>
                    <div style="display: flex;align-items: center;">
                         @if($discount_type == 0)
                        <p><strong>Max Discount upto : {{$setting->discount_percentage}}%</strong></p>
                        @else
                        @php
                        $current_discount = (((float)$sub_total*$setting->discount_percentage)/100);
                        @endphp
                        <p><strong>Max Discount upto : {{number_format($max_discount,2)}} </strong></p>
                        @endif
                	</div>

                	<!-- <label>Discount :</label> -->
                       <!-- <input type="radio"  style="margin: 4px 5px;" wire:click="discount_type(0)" @if($discount_type == '0') checked="" @endif>Percentage
                       <input type="radio" style="margin: 4px 5px;" wire:click="discount_type(1)" @if($discount_type == '1') checked="" @endif>Amount -->
                              
                       @if($discount_type == 0)
                        <div >
                            <label>Percent :</label>
                            <input type="text" style="width:50px;text-align:center;" wire:model="cart_percentage" readonly=""><br>
                            <label>Percentage Amount :</label>
                            <span  style="margin: 15px 0px;">{{$total_cart_discount}}</span>
                        </div>
                        @endif
                        @if($discount_type == 1)
                        <div  style="margin: 15px 0px;">
                            <label>Discount Amount:</label>
                            <input type="text" style="width:50px;text-align:center;" wire:model="total_cart_discount" readonly="">        
                        </div>
                        @endif

                        <div id="discount_amt" style="margin: 15px 0px;">
                            <label>Return Amount <span id="return_id"></span>:</label>
                            <input type="text"  wire:model="return_discount" style="width:50px;text-align:center;margin-left: 10px;" readonly="">        
                        </div>
                        @if($credit_points > $setting->min_points_wallet && $total_amount > $setting->min_price_wallet)
	                    <div id="wallet_div" >
    	                    <div>Available Credit : <span id="aval_credit">{{$credit_points}}</span></div>
    	                        <label>Wallet :</label>
    	                         <input type="checkbox" wire:model="wallet" id="use_wallet" value="" onclick="check_wallet(392);">
    	                        @if($wallet == 1)
    	                        <div id="allow_points" ><label>Use Wallet <span id="allwed_pt">({{$available_points}} points)</span>: <span id="remain_allow_points">{{$available_points}}</span></label></div>
    	                           
    	                       <div style="margin: 15px 0;" id="points_discnt"> <label>Wallet Discount</label>
    	                       <input type="text" id="wallet_discount" wire:model="debit_points" value="" style="width:50px;text-align:center;"></div>
                               @endif
    	            
	                    </div>
                        @endif

	                    <div style="display: flex;align-items: center;justify-content: space-between;font-size: 30px;background: #009CEC; color: #fff ;padding: 5px 15px;margin: 15px 0;">
	                                <label style="margin: 0;">Total:</label>
	                            <div id="total_span">{{number_format($total_amount,2)}}</div>
	                            <input type="hidden" id="order_total_amt" name="order_total_amt" value="392">
	                            <input type="hidden" id="grand_total" value="392">

	                            <input type="hidden" id="wallet_amt" name="wallet_amt" value="">
	                            <input type="hidden" id="hid_return_id" name="hid_return_id" value="">


	                    </div>  
                    </div>
                </div>
                <div>
                    <form wire:submit.prevent="orderPlaced">
                    <div class="row  mt-5">
                        @if($total_amount>=1000 || $card_number)
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Card Number</label>
                                <input type="text" class="form-control" placeholder="Card Number" wire:model.defer="card_number" maxlength="16">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Total Points</label>
                                <input type="text" class="form-control" placeholder="Total Points" wire:model.defer="credit_points" readonly="">
                            </div>
                        @endif
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" placeholder="Customer Name" wire:model.defer="customer_name">
                            @error('customer_name') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Customer Email</label>
                            <input type="text" class="form-control" placeholder="Customer Email" wire:model.defer="customer_email" >
                            @error('customer_email') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" placeholder="Phone Number" wire:model="customer_phone" maxlength="10">
                            @error('customer_phone') <span class="text-danger error">{{ $message }}</span>@enderror
                            <ul id="serch_result" class="product_search customer_search"  @if(count($customer_phones)=='0') style="display: none" @endif>
                                @if(count($customer_phones)>0)
                                @foreach($customer_phones as $value)
                                <li wire:click="getCustomer({{$value->customer_phone}})"><a href="javascript:void(0)">{{$value->customer_phone}}</a></li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Customer Address</label>
                            <textarea class="form-control" placeholder="Customer Address" wire:model="customer_address"></textarea>
                        </div>

                    </div>

                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div> 
                    </form>
                </div>                                       

            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->

    <!-- My Modal -->
<div wire:ignore.self id="productOrder" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modalLabel" aria-hidden="true"> 
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">Order Details</h4>
                <button type="button" class="btn-close modalClose" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="row ">
                    <div class="col-xl-8">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <button type="button" onclick="PrintDiv()" class="btn btn-primary">Print Details</button>                                                
                                    <button type="button" data-bs-dismiss="modal"  class="btn btn-danger modalClose" >Cancel</button> 
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





                <div id="print_data" class="w-100 mt-3 mb-5" id="print_data">
                    <table style="width: 300px; border-spacing: 0px; margin: 0 auto;">
                        <tbody>
                            <tr>
                                @if($storeUser == '1')
                                <td style="text-align: center;">
                                    <p style="margin: 5px 0px; font-size: 18px; color: #000;font-weight: 600; text-transform: uppercase;">{{$setting->site_title}}</p>
                                    <p style="margin: 5px 0px; font-size: 16px; color: #000;">Madhanmohan Pur Bazar,</p>
                                    <p style="margin: 5px 0px; font-size: 16px; color: #000;">Ramanagar, kulpi</p>
                                    <p style="margin: 5px 0px; font-size: 16px; color: #000;">Pin 743347</p>
                                    <p style="margin: 5px 0px; font-size: 16px; color: #000;">Phone: {{$setting->contact_no}}</p>
                                    <p style="margin: 5px 0px; font-size: 15px; color: #000;">Email: {{$setting->site_mail}}</p>
                                    <p style="margin: 5px 0px; font-size: 15px; color: #000; text-transform: uppercase;">Order Details </p>
                                </td>
                                @else
                                <td style="text-align: center;">
                                    <p style="margin: 5px 0px; font-size: 18px; color: #000;font-weight: 600; text-transform: uppercase;">{{$setting->site_title}}</p>
                                    <p style="margin: 5px 0px; font-size: 16px; color: #000;">12 No-Naskarer Chak Bazar,</p>
                                    <p style="margin: 5px 0px; font-size: 16px; color: #000;"> Madrasa More, Kakdwip</p>
                                    <p style="margin: 5px 0px; font-size: 16px; color: #000;">Pin 743347</p>
                                    <p style="margin: 5px 0px; font-size: 16px; color: #000;">Phone: {{$setting->contact_no}}</p>
                                    <p style="margin: 5px 0px; font-size: 15px; color: #000;">Email: {{$setting->site_mail}}</p>
                                    <p style="margin: 5px 0px; font-size: 15px; color: #000; text-transform: uppercase;">Order Details </p>
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <td>
                                    <table style="width: 100%; border-spacing: 0px;">
                                        <tbody>
                                            <tr>
                                                <td style="font-size: 12px; color: #000; padding: 10px 0px 0;">Bill No : {{@$viewOrder->order_id}}</td>
                                                <td style="text-align: right; font-size: 12px; color: #000; padding: 10px 0px 0;">
                                                    Date : {{date('Y-m-d',strtotime(@$viewOrder->order_date)) }}                                     
                                                    <p style="padding: 0px;margin:0px;">{{date('h:i',strtotime(@$viewOrder->order_time)) }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 12px; color: #000; text-transform: uppercase;  border-top: 1px dashed #ccc; padding: 10px 0px 0;     border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <!-- Sp00021845<br> -->
                                                    <p style="padding: 0px;margin:0;">{{@$viewOrder->customer_name}}</p>
                                                    <p style="padding: 0px;margin:0px;">@if(@$viewOrder->customer_phone){{@$viewOrder->customer_phone}} @endif</p>
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

                                            @php
                                                $sub_total = 0.00;
                                                $discount = 0.00;
                                            @endphp
                                            @if(isset($viewOrder->productDetails))
                                            @foreach($viewOrder->productDetails as $key=>$product)
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
                                                    {{number_format(($product->selling_price *$product->qty),2)}}                    
                                                </td>
                                                @php
                                                    $sub_total+=($product->selling_price *$product->qty);
                                                    $discount+=($product->discount *$product->qty);
                                                @endphp
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
                                                    {{number_format(@$sub_total,2)}}                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <strong>Discount Amount</strong>
                                                </td>
                                                <td style="text-align: center; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <!--Sub Total: -->
                                                </td>
                                                <td style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" >
                                                   <!-- @if(@$viewOrder->discount_type == 'dis_amt')
                                                    {{@$viewOrder->discount_amt}}     @else                
                                                    {{@$viewOrder->perctge_amt}}
                                                    @endif   -->  
                                                    {{$discount}}                      
                                                </td>
                                            </tr>
                                            @if(@$viewOrder->wallet == 1)
                                            <tr>
                                                <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <strong>Wallet Debit</strong>
                                                </td>
                                                <td style="text-align: center; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <!--Sub Total: -->
                                                </td>
                                                <td style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" >
                                                    {{number_format(@$viewOrder->wallet_discount,2)}}                         
                                                </td>
                                            </tr>
                                            @endif
                                            @if(@$viewOrder->return_id != '')
                                            <tr>
                                                <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <strong>Return Amount ({{@$viewOrder->return_id}})</strong>
                                                </td>
                                                <td style="text-align: center; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" colspan="2">
                                                    <!--Sub Total: -->
                                                </td>
                                                <td style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;" >
                                                    {{number_format(@$viewOrder->return_amt,2)}}                         
                                                </td>
                                            </tr>
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
                                                <td style="font-size: 15px; color: #000; padding: 7px 0px; text-align: right; border-bottom: 1px dashed #ccc; font-weight: 600; "><!-- @if(isset($viewOrder)){{@$viewOrder->total_amount}}
                                                    @else
                                                    0.00
                                                    @endif -->
                                                    {{number_format(($sub_total-($discount+(float)@$viewOrder->wallet_discount+@$viewOrder->return_amt)),2)}}
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
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<a href="{{route('add_to_cart')}}" class="btn btn-primary btn-lg cart-btn " role="button" title="" data-toggle="tooltip" data-original-title="cart">
      <i class="uil-shopping-cart-alt" id="cart">@if(isset($count_cart_item)) {{$count_cart_item}} @endif</i>
  </a>

<script type="text/javascript">
    function PrintDiv() {
    var divToPrint = document.getElementById('print_data');
    var popupWin = window.open('', '_blank', 'width=900,height=650');
    popupWin.document.open();
    popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
    popupWin.document.close();

}
</script>
</div>
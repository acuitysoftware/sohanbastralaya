<div class="row">
    <div class="col-12">
         @php
                    function convert_numbers_to_indian_format($number)
                    {
                        $formattedAmount = number_format($number);
                        $decimal = (string) ($number - floor($number));
                        $money = floor($number);
                        $length = strlen($money);
                        $delimiter = '';
                        $money = strrev($money);
                        for ($i = 0; $i < $length; $i++) {
                            if (($i == 3 || ($i > 3 && ($i - 1) % 2 == 0)) && $i != $length) {
                                $delimiter .= ',';
                            }
                            $delimiter .= $money[$i];
                        }

                        $formattedAmount = strrev($delimiter);
                        $decimal = preg_replace('/0\./i', '.', $decimal);
                        $decimal = substr($decimal, 0, 3);

                        if ($decimal != '0') {
                            $formattedAmount = $formattedAmount . $decimal;
                        } else {
                            $formattedAmount = $formattedAmount . '.00';
                        }
                        return $formattedAmount;
                    }
                @endphp
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
                                    <input class="form-control" type="date" name="date" placeholder="Date From" wire:model.lazy="dateForm">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <input class="form-control" type="date" name="date" placeholder="Date To" wire:model.lazy="dateTo">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-danger" wire:click="resetSearch">All</button>                                                
                                </div>
                            </div>
                            {{-- <div class="col-auto">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-danger" wire:click="exportCsv">Export</button>                                                
                                </div>
                            </div> --}}
                            


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
                                <th>Numbers of Orders</th>
                                <th>Total Amount</th>
                                <th>Action</th>
                            </tr>

                        </thead>
                        <tbody>
                            
                        	@if(count($customers)>0)
                        	@foreach($customers as $key=>$row)
                            @php
                                    $item = ($customers->perPage() * ($customers->currentPage() - 1)) + ($key+ 1);
                                 @endphp
                                    <tr>
                                        <td>{{ $item }}</td>
                                <td>{{$row->customer_name}}</td>
                                <td>{{$row->customer_phone}}</td>
                                @php
                                $count_item =0;
                                if($storeUser == "1"){

                                    $count_item = \App\Models\ProductOrderDetails::where('customer_phone', $row->customer_phone)->count();
                                    }
                                    else{
                                    $count_item = \App\Models\ProductOrderDetails2::where('customer_phone', $row->customer_phone)->count();

                                }
                                @endphp
                                <td>{{$count_item}}</td>
                               {{--  <td>{{env('CURRENCY','₹')}}{{convert_numbers_to_indian_format($row->total_amount-$row->total_discount)}}</td> --}}
                                <td>{{env('CURRENCY','₹')}}{{convert_numbers_to_indian_format($row->total_amount)}}</td>
                                <td style="white-space: nowrap;">
                                    @if(Auth::user()->type=='A' || in_array('customer-view', Auth::user()->permissions()->pluck('permission')->toArray()))
                                    <a href="javascript:void(0);" class="action-icon" wire:click="viewCustomer({{$row->customer_phone}})"><i class="mdi mdi-eye"></i></a> 
                                   {{--  <a href="{{route('customer.details',$row->customer_phone)}}?perNo={{$perPage}}" class="action-icon"><i class="mdi mdi-eye"></i></a> --}}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                            	<td colspan="4" class="text-center">No records available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{-- @if($customers->hasMorePages())
                    <button wire:click.prevent="loadMore" class="btn btn-primary">Load more</button>
                @endif --}}
                 {{ $customers->links() }}
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
<!-- My Modal -->
<div wire:ignore.self id="showCustomerDetails" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="return-modalLabel" aria-hidden="true">    
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">Orders of {{@$customer_details[0]->customer_name}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
          
            <div class="modal-body">
               
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
                                <td>@if(@$card_details->add_date){{date('d/m/Y', strtotime(@$card_details->add_date))}}@endif</td>
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
                                <td>{{date('d/m/Y', strtotime($row->order_date))}}</td>
                                @php
                                $subtotal = 0;
                                $sub_total =0.00;
                                $purchase_price =0.00;
                                $total_discount =0.00;
                                foreach($row->productDetails as $value)
                                {
                                    $purchase_price+=$value->total_purchase_price;
                                    $subtotal+=($value->selling_price*$value->qty);
                                    $total_discount+=$value->total_discount;
                                }
                                $sub_total = $subtotal-$total_discount;
                                @endphp
                                <td>{{env('CURRENCY','₹')}}{{convert_numbers_to_indian_format($sub_total)}}</td>
                                <td>{{env('CURRENCY','₹')}}{{convert_numbers_to_indian_format($sub_total)}}</td>
                                @if(Auth::user()->type=='A')
                                    <td>{{env('CURRENCY','₹')}}{{convert_numbers_to_indian_format($purchase_price)}}</td>
                                    <td>{{env('CURRENCY','₹')}}{{convert_numbers_to_indian_format(($sub_total) -$purchase_price)}}</td>
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

<!-- /.modal -->

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
                                    <td>{{date('d/m/Y', strtotime(@$orderDetails->order_date))}}</td>         
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
                                    $subtotal =0.00;
                                    $purchase_price =0.00;
                                    $total_discount =0.00;
                                    foreach($orderDetails->productDetails as $value)
                                    {
                                         $subtotal+=($value->selling_price*$value->qty);
                                        $purchase_price+=$value->total_purchase_price;
                                        $total_discount+=$value->total_discount;
                                    }
                                    @endphp
                                    <td>{{env('CURRENCY','₹')}}{{@convert_numbers_to_indian_format($subtotal-@$total_discount)}}</td>
                                        @if(Auth::user()->type=='A')
                                            <td>{{env('CURRENCY','₹')}}{{@convert_numbers_to_indian_format($purchase_price)}}</td>
                                            <td>{{env('CURRENCY','₹')}}{{(@convert_numbers_to_indian_format($subtotal-(@$total_discount+$purchase_price)))}}</td>
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
                                        <td>{{env('CURRENCY','₹')}}{{@convert_numbers_to_indian_format($row->purchase_price)}}</td>
                                    @endif
                                    <td>{{env('CURRENCY','₹')}}{{@convert_numbers_to_indian_format($row->selling_price)}}</td>
                                    <td>{{env('CURRENCY','₹')}}{{@convert_numbers_to_indian_format($row->discount)}}</td>
                                    <td>{{env('CURRENCY','₹')}}{{@convert_numbers_to_indian_format($row->selling_price-@$row->discount) }}</td>
                                    @if(Auth::user()->type=='A')
                                        <td>{{env('CURRENCY','₹')}}{{@convert_numbers_to_indian_format($row->profit) }}</td>
                                        <td>{{@$row->profit_percentage }}%</td>
                                    @endif
                                    <td>{{@$row->qty}}</td>
                                    <td>{{env('CURRENCY','₹')}}{{@convert_numbers_to_indian_format($row->subtotal)}}</td>
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
                                    <td>{{env('CURRENCY','₹')}}{{@convert_numbers_to_indian_format($details->selling_price)}}</td>
                                    <td>{{env('CURRENCY','₹')}}{{@convert_numbers_to_indian_format($details->discount)}}</td>
                                    <td>{{env('CURRENCY','₹')}}{{@convert_numbers_to_indian_format($details->selling_price-@$details->discount) }}</td>
                                    <td>{{@$details->qty}}</td>
                                    <td>{{env('CURRENCY','₹')}}{{@convert_numbers_to_indian_format($details->price*@$details->qty)}}</td>
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
                                        $subtotal =0.00;
                                        $purchase_price =0.00;
                                        $total_discount =0.00;
                                        foreach($orderDetails->productDetails as $value)
                                        {
                                            $subtotal+=($value->selling_price*$value->qty);
                                            $purchase_price+=$value->total_purchase_price;
                                            $total_discount+=$value->total_discount;
                                        }
                                        @endphp
                                    <tr>
                                        <th>Discount Amt :</th>
                                        <td>{{env('CURRENCY','₹')}}{{convert_numbers_to_indian_format($total_discount)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Grand Total :</th>
                                        <td>{{env('CURRENCY','₹')}} {{convert_numbers_to_indian_format($subtotal-$total_discount)}}</td>
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

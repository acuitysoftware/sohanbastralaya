<div class="row">
	<div class="col-12">
		<div class="card">
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
			<div class="card-body">


				<div class="row mb-2">
					<div class="col-xl-9">
						<div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
							<div class="col-auto">
								<div class="mb-3">
									<input class="form-control" type="date" wire:model="dateForm" placeholder="Date From">
								</div>
							</div>
							<div class="col-auto">
								<div class="mb-3">
									<input class="form-control" type="date" wire:model="dateTo" placeholder="Date From">
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
                                <select class="form-select w-auto me-2" wire:model="storeUser">
                                    <option value="" disabled="" >Select Store</option>
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
                                @endif      
								<th>Action</th>
							</tr>

						</thead>
						<tbody>
                            @php
                                $total_sell_price =0;
                                $total_purchase_price =0;
                            @endphp
							@if(count($orders)>0)
                        	@foreach($orders as $key=>$row)
                            @php
                                $total_sell_price+=(float)$row->total_selling_price;
                                $total_purchase_price+=(float)$row->total_purchase_price;
                            @endphp
							<tr>
								<td>{{ date('d/m/Y h:i', strtotime($row->date)) }}</td>
								<td>{{env('CURRENCY','₹')}}{{$row->total_selling_price}}</td>
                                @if(Auth::user()->type=='A')
								    <td>{{env('CURRENCY','₹')}}{{$row->total_purchase_price}}</td>
                                @endif
								<td style="white-space: nowrap;"><a href="javascript:void(0);" class="action-icon"  wire:click="viewOrders('{{$row->date}}')"><i class="mdi mdi-eye"></i>View</a></td>
							</tr>
							@endforeach
                            <tr>
                                <td>Total</td>
                                <td>{{env('CURRENCY','₹')}}{{convert_numbers_to_indian_format($total_sell_price)}}</td>
                                @if(Auth::user()->type=='A')
                                <td>{{env('CURRENCY','₹')}}{{convert_numbers_to_indian_format($total_purchase_price)}}</td>
                                @endif
                                 <td></td>
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

	<!-- My Modal -->
<div wire:ignore.self id="stockReport" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title">Stock View</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">



                    <div class="col-xl-12">
                        <form class="row align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <button type="button" class="btn btn-danger mb-1" onclick="PrintDiv()"><i class="mdi mdi-printer me-1"></i>Print</button>
                            </div>
                            <div class="col-auto">
                                <button type="button"  data-bs-dismiss="modal" class="btn btn-secondary mb-1">Back</button>
                            </div>
                        </form>                            
                    </div>


                <div class="table-responsive mt-2">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Product Name</th>
                                <th>Image</th>
                                <th>Code</th>
                                <th>Quantity</th>
                                <th>Selling Price</th>
                                @if(Auth::user()->type=='A')
                                    <th>Purchase Price</th>
                                @endif
                            </tr>

                        </thead>
                        <tbody>
                        	@if(count($orderList)>0)
                        	@foreach($orderList as $key=>$row)
                            <tr>
                                <td>{{$row->product->name}}</td>
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
                                <td>{{$row->product->product_code}}</td>

                                <td>{{$row->quantity}}</td>
                                <td>{{env('CURRENCY','₹')}}{{$row->product->selling_price}}</td>
                                @if(Auth::user()->type=='A')
                                    <td>{{env('CURRENCY','₹')}}{{$row->product->purchase_price}}</td>
                                @endif
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>


            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="print_data" class="w-100 mt-3 mb-5" style="display: none">
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
                                    <table style="width: 100%; border-spacing: 0px; font-size:12px;">
                                        <tbody>
                                            <tr>
                                                <td style="text-align: left; width:40%;">
                                                    <strong>Product Name</strong>
                                                </td>
                                                <td style="text-align: center; width:20%;">
                                                    <strong>Product Code</strong>  
                                                </td>
                                                <td style="text-align: center; width:20%;">
                                                    <strong>Qty</strong>  
                                                </td>
                                                <td style="text-align: center; width:20%;">
                                                    <strong>Selling Price</strong> 
                                                </td>
                                            </tr>
                                            @if(count($orderList)>0)
                                            @foreach($orderList as $key=>$row)
                                            <tr>
                                                <td style="text-align: left; padding: 5px 0px; ">
                                                      {{$row->product->name}}
                                                </td>
                                                 <td style="text-align: left; padding: 5px 0px; ">
                                                      {{$row->product->product_code}}
                                                </td>
                                                <td style="text-align: left; padding: 5px 0px; ">
                                                      {{$row->product->quantity?$row->quantity:'Out of Stock'}}
                                                </td>
                                                <td style="text-align: left; padding: 5px 0px; ">
                                                      {{$row->product->selling_price}}
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif

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
<script type="text/javascript">
    function PrintDiv() {
    var divToPrint = document.getElementById('print_data');
    var popupWin = window.open('', '_blank', 'width=900,height=650');
    popupWin.document.open();
    popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
    popupWin.document.close();

}
</script>
</div><!-- end row -->

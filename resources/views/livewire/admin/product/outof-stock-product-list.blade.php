<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-xl-8">
                        <form class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            @if(Auth::user()->type=='A')
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <!-- <label for="status-select" class="me-2">Status</label> -->
                                    <select class="form-select" wire:model="storeUser">
                                        <option value="" selected>Select Store</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                                       
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
                            <div class="col-auto">
                                <label for="inputPassword2" class="visually-hidden">Search</label>
                                <input type="search" class="form-control" id="inputPassword2"  wire:model="searchName" placeholder="Search...">
                            </div>
                        </form>                            
                    </div>
                    <div class="col-xl-4">
                        <div class="text-end mt-xl-0 mt-2">
                            <button type="button" class="btn btn-danger mb-2" onclick="PrintDiv()" ><i class="mdi mdi-printer me-1"></i>Print</button>
                            
                        </div>
                    </div><!-- end col-->
                </div>




                <div class="table-responsive">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap"
                        id="products-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>SL No.</th>
                                <th>Image</th>
                                <th>Products Name</th>
                                <th>Item Code</th>
                                <th>Code</th>
                                <th>Total Qty</th>
                               {{--  <th>Available Qty</th> --}}
                                <th>Selling Price</th>
                                @if(Auth::user()->type=='A')
                                <th>Purchase Price</th>
                                @endif        
                            </tr>


                        </thead>
                        <tbody>
                        	@if(count($products)>0)
                        	@foreach($products as $key=>$row)
                           @php
                                    $item = ($products->perPage() * ($products->currentPage() - 1)) + ($key+ 1);
                                 @endphp
                                    <tr>
                                        <td>{{ $item }}</td>
                                <td>
                                	@if(isset($row->gallery))
                                    <a data-fancybox="gallery" href="{{asset('storage/app/public/product_image/'.$row->gallery->gallery_image) }}">
                                        <img src="{{asset('storage/app/public/product_image/'.$row->gallery->gallery_image) }}" alt="contact-img"
                                            title="contact-img" class="rounded me-3" height="48" />
                                    </a>
                                    @else
                                        <img src="{{asset('public/assets/images/no_image.png') }}" alt="contact-img"
                                            title="contact-img" class="rounded me-3" height="48" />
                                    @endif
                                </td>
                                <td>{{$row->name}}</td>
                                <td>{{$row->bar_code}}</td>
                                <td>{{$row->product_code}}</td>
                                <td>{{$row->product_quantities_sum_quantity}}</td>
                             {{--    <td>{{$row->quantity?$row->quantity:'Out of Stock'}}</td>  --}}
                                <td>{{env('CURRENCY','₹')}}{{$row->selling_price}}</td> 
                                @if(Auth::user()->type=='A')
                                <td>{{env('CURRENCY','₹')}}{{$row->purchase_price}}</td> 
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr>
                            	<td colspan="8" class="text-center">No records available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
               {{--  @if($products->hasMorePages())
                    <button wire:click.prevent="loadMore" class="btn btn-primary">Load more</button>
                @endif --}}
                 {{ $products->links() }}
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <div id="print_data" class="w-100 mt-3 mb-5" style="display: none">
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
                                    <table style="width: 100%; border-spacing: 0px; font-size:12px;">
                                        <tbody>
                                            <tr>
                                                <td style="text-align: left; width:50%;">
                                                    <strong>Item Name</strong>
                                                </td>
                                                <td style="text-align: center; width:25%;">
                                                    <strong>Qty</strong>  
                                                </td>
                                                <td style="text-align: center; width:25%;">
                                                    <strong>Rate</strong> 
                                                </td>
                                            </tr>
                                            @if(count($products)>0)
                                            @foreach($products as $key=>$row)
                                            <tr>
                                                <td style="text-align: left; padding: 5px 0px; ">
                                                      {{$row->name}}
                                                </td>
                                                <td style="text-align: left; padding: 5px 0px; ">
                                                      {{$row->quantity?$row->quantity:'Out of Stock'}}
                                                </td>
                                                <td style="text-align: left; padding: 5px 0px; ">
                                                      {{$row->selling_price}}
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
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">


				<div class="row">
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
								<input class="form-control" type="search" wire:model="searchName" placeholder="Search...">
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
								<th>Product Name</th>
								<th>Code</th>
								<th>Quantity Sold</th>
								<th>Selling Price</th>
							</tr>

						</thead>
						<tbody>
							@if(count($products)>0)
                        	@foreach($products as $key=>$row)
							<tr>
								<td>{{$row->name}}</td>
								<td>{{$row->product_code}}</td>
								<td>{{$row->product_orders_sum_qty}}</td>
								<td>{{env('CURRENCY','₹')}}{{$row->selling_price}}</td>
							</tr>
							@endforeach
                            @else
                            <tr>
                            	<td colspan="12" class="text-center">No records available</td>
                            </tr>
                            @endif
						</tbody>
					</table>
				</div>
				{{-- @if($products->hasMorePages())
                    <button wire:click.prevent="loadMore" class="btn btn-primary">Load more</button>
                @endif --}}
				 {{ $products->links() }}
			</div> <!-- end card-body -->
		</div> <!-- end card -->
	</div><!-- end col -->
    </div><!-- end row -->
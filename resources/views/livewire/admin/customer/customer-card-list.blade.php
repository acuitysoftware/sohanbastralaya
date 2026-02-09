<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-xl-9">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <input class="form-control" type="text"  placeholder="Search Phone" wire:model="searchPhone">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <input class="form-control" type="text"  placeholder="Search Card" wire:model="searchCard">
                                </div>
                            </div>

                            <div class="col-auto">
								<div class="mb-3">
									<input class="form-control" type="date" wire:model.lazy="dateForm" placeholder="Date From">
								</div>
							</div>
							<div class="col-auto">
								<div class="mb-3">
									<input class="form-control" type="date" wire:model.lazy="dateTo" placeholder="Date From">
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
                                <th>Customer Name</th>
                                <th>Order Date</th>
                                <th>Card Number</th>
                                <th>Card Expiry Date</th>
                                <th>Customer Phone</th>
                                <th>Available Points</th>
                                <th>Action</th>
                            </tr>

                        </thead>
                        <tbody>
                        	@if(count($customers)>0)
                        	@foreach($customers as $key=>$row)
                            <tr>
                                <td>{{$row->customer_name}}</td>
                                <td>{{$row->order_date}}</td>
                                <td>{{$row->cardData?$row->cardData->card_number:''}}</td>
                                <td>{{$row->cardData?$row->cardData->expiry_date:''}}</td>
                                <td>{{$row->customer_phone}}</td>
                                <td>
                                    @php
                                        $total_point = 0.00;
                                        if(count($row->membershipsCards))
                                        {
                                           $total_point= ($row->membershipsCards()->sum('credit_points')-$row->membershipsCards()->sum('debit_point'));
                                        }

                                    @endphp
                                    {{$total_point}}
                                </td>
                                <td style="white-space: nowrap;">
                                    @if(Auth::user()->type=='A' || in_array('customers-card-view', Auth::user()->permissions()->pluck('permission')->toArray()))
                                    <a href="javascript:void(0);" class="action-icon" wire:click="viewmemberships({{@$row->customer_phone}})"><i class="mdi mdi-eye"></i></a>
                                    @endif
                                    @if(Auth::user()->type=='A' || in_array('customers-card-edit', Auth::user()->permissions()->pluck('permission')->toArray()))
                                    <a href="javascript:void(0);" class="action-icon" wire:click="editMemberships({{@$row->customer_phone}})"><i class="mdi mdi-square-edit-outline"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                            	<td colspan="7" class="text-center">No records available</td>
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

    <!-- View Memberships -->
<div wire:ignore.self id="showMembership" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="viewmembershipsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">View Memberships</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <span class="badge badge-danger-lighten p-1 font-14">Card expires in : {{@$expiry_date_count}} days</span>
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
                                <td>{{env('CURRENCY','₹')}}{{$membership->grand_amt}}</td>
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

<!-- Edit Modal -->
<div wire:ignore.self id="editMembershipCard" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">Edit</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form wire:submit.prevent="save">
            <div class="modal-body">
                <div class="row mt-2 mb-2">
                    <input type="hidden" wire:model.defer="contact">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Card Number</label>
                        <input type="text" class="form-control" placeholder="Card Number" wire:model.defer="card_number" maxlength="16">
                        @error('card_number') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Customer Phone</label>
                        <input type="text" class="form-control" placeholder="Customer Phone"  wire:model.defer="contact_no">
                        @error('contact_no') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                <div class="row mb-2">
                    <div class="col-xl-12">
						<div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
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
									<input type="search" class="form-control" id="inputPassword2" wire:model="searchName" placeholder="Search...">                                                
								</div>
							</div>


						</div>
					</div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap"
                        id="products-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Employee Name</th>
                                <th>Shift</th>
                                <th>Date</th>
                                <th>Login Time</th>
                                <th>Logout Time</th>
                                <!-- <th>Action</th>     -->    
                            </tr>
                        </thead>
                        <tbody>
                        	@if(count($logins)>0)
                        	@foreach($logins as $key=>$data)
                            <tr>
                                <td>{{$data->user->name}}</td>
                                <td>{{$data->shift==1?'Morning':'Evening'}}</td>
                                <td>{{date('d/m/Y',strtotime($data->date)) }}</td>                                                    
                                <td>{{date('h:i A',strtotime($data->login_time)) }}</td>
                                <td>@if($data->logout_time){{date('h:i A',strtotime($data->logout_time)) }}@endif</td>
                                <!-- <td style="white-space: nowrap;">
                                    
                                    @if(Auth::user()->type=='A' || in_array('user-delete', Auth::user()->permissions()->pluck('permission')->toArray()))
                                    <a href="javascript:void(0);" wire:click="deleteAttempt({{ $data->id }})" class="action-icon" id="warning"><i class="mdi mdi-delete"></i></a>
                                    @endif
                                </td> -->
                            </tr>
                            @endforeach
                            @else
                            	<tr>
	                                <td colspan="5" style="text-align: center;">No records available</td>
	                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <!-- Add User -->

</div><!-- end row -->



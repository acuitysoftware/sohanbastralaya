<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                <div class="row mb-2">
                    <div class="col-xl-8">
                                                    
                    </div>
                    <div class="col-xl-4">
                        <div class="text-end mt-xl-0 mt-2">
                            @if(Auth::user()->type=='A' || in_array('user-delete', Auth::user()->permissions()->pluck('permission')->toArray()))
                            <button type="button" class="btn btn-info mb-2 me-2" wire:click=deleteMultiUser()><i class="mdi mdi-delete"></i>Delete Selected Record</button>
                            @endif
                            @if(Auth::user()->type=='A' || in_array('user-add', Auth::user()->permissions()->pluck('permission')->toArray()))
                            <button type="button" class="btn btn-danger mb-2" wire:click="addUser(0)"><i class="mdi mdi-plus me-1"></i>Add User</button>
                            @endif                                                
                        </div>
                    </div><!-- end col-->
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap"
                        id="products-datatable">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" class="form-check-input dt-checkboxes"></th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>        
                            </tr>
                        </thead>
                        <tbody>
                        	@if(count($users)>0)
                        	@foreach($users as $key=>$user)
                            <tr>
                                <td><input type="checkbox" wire:model="deleteIds" value="{{$user->id}}" class="form-check-input dt-checkboxes"></td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->username}}</td>                                                    
                                <td>{{$user->email}}</td>
                                <td><a href="javascript::void(0)"><span class="badge bg-{{ $user->status == 1 ? 'success' : 'danger' }}"  wire:click="changeStatusConfirm({{ $user->id }})">{{ $user->status == 1 ? 'Active' : 'Inactive' }}</span> </a></td>
                                <td style="white-space: nowrap;">
                                    @if(Auth::user()->type=='A' || in_array('user-permission', Auth::user()->permissions()->pluck('permission')->toArray()))
                                    <a href="javascript:void(0);" class="action-icon" wire:click="accessMenu({{$user->id}})"><i class="mdi mdi-lock"></i></a>
                                    @endif
                                    @if(Auth::user()->type=='A' || in_array('user-edit', Auth::user()->permissions()->pluck('permission')->toArray()))
                                    <a href="javascript:void(0);" class="action-icon" wire:click="editUser({{$user->id}})"><i class="mdi mdi-square-edit-outline"></i></a>
                                    @endif
                                    @if(Auth::user()->type=='A' || in_array('user-delete', Auth::user()->permissions()->pluck('permission')->toArray()))
                                    <a href="javascript:void(0);" wire:click="deleteAttempt({{ $user->id }})" class="action-icon" id="warning"><i class="mdi mdi-delete"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <!-- Add User -->
<div wire:ignore.self id="addUserModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    	@if($type == 'Add')
    	<form wire:submit.prevent="createUser">
    	@else
    	<form wire:submit.prevent="updateUser">
    	@endif
    	<input type="hidden" wire:model.defer="state.id" >

        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">{{$type}} User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">

                <div class="row mt-2 mb-2">

                    <div class="mb-3 col-md-6">
                        <label class="form-label fs-4 text-dark">Name</label>
                        <input type="text" class="form-control" placeholder="Enter Name" wire:model.defer="state.name">
                        @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>


                    <div class="mb-3 col-md-6">
                        <label class="form-label fs-4 text-dark">Email</label>
                        <input type="text" class="form-control" placeholder="Enter Email" wire:model.defer="state.email">
                        @error('email') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fs-4 text-dark">DOB</label>
                        <input type="date" class="form-control" wire:model.defer="state.dob">
                        @error('dob') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fs-4 text-dark">Username</label>
                        <input type="text" class="form-control" placeholder="Enter Username" wire:model.defer="state.username">
                        @error('username') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label fs-4 text-dark">Password</label>
                        <input type="password" class="form-control" placeholder="Enter Password" wire:model.defer="state.password">
                        @error('password') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label fs-4 text-dark">Gender</label>
                        <select class="form-select" wire:model.defer="state.gender">
                            <option value="" selected="">Select Gender</option>
                            <option value="M" >Male</option>
                            <option value="F">Female</option>
                        </select>
                        @error('gender') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label fs-4 text-dark">Store User</label>
                        <select class="form-select" wire:model.defer="state.store">
                            <option value="">Select User</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                        @error('store') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>


                    <div class="mb-3 col-md-6">
                        <label class="form-label fs-4 text-dark">Feature Image</label>
                        <input type="file" id="example-fileinput" class="form-control" wire:model.defer="state.profile_image" accept="image/*">
                        @error('profile_image') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>

				@if(isset($state['profile_path']))
					<div class="mb-3 col-md-6">
                        <img src="{{asset('storage/app/public/profile_image/'.$state['profile_path']) }}" height="100px" >
                    </div>
				@endif


                    <div class="mb-3 col-md-12">
                        <label class="form-label fs-4 text-dark">Access Menu</label>

                        <div class="form-radio-success">
                            @foreach($permissions as $key=>$row)
                            <div class="form-check form-check-inline mb-2">
                                <input type="checkbox" class="form-check-input" id="AM{{$key}}" wire:model="state.permission_name.{{$row->id}}">
                                <label class="form-check-label" for="AM{{$key}}" >{{$row->name}} </label>
                            </div>
                            @endforeach
                        </div>
                        @error('permission_name') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>


                    <div class="col-md-12">
                        <label class="form-label fs-4 text-dark">Status</label>

                        <div class=" form-radio-success">
                            <div class="form-check form-check-inline mb-2">
                                <input type="radio" id="customRadio1" wire:model="state.status" class="form-check-input" value="1">
                                <label class="form-check-label" for="customRadio1">Active</label>
                            </div>
                            <div class="form-check form-check-inline mb-2">
                                <input type="radio" id="customRadio2" wire:model="state.status" class="form-check-input" value="0">
                                <label class="form-check-label" for="customRadio2">Inactive</label>
                            </div>
                        </div>
                            @error('status') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>

                </div>          
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>


        </div><!-- /.modal-content -->
    	</form>
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- Access User -->
<div  wire:ignore.self id="userAccessMenu" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">Give Access</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form wire:submit.prevent="updateUserAccessMenu">
                <input type="hidden" wire:model="{{$user_id}}">
            <div class="modal-body">

                <div class="row mt-2 mb-2">
                    @if(count($meneList)>0)
                    @foreach($meneList as $key=>$menu)
                    <div class="mb-3 col-md-12">
                        <label class="form-label fs-4 text-dark"><input type="checkbox" wire:model="userMenuList.{{$menu->id}}" class="form-check-input me-1">{{$menu->name}}</label>
                        @if(count($menu->subMenu)>0)
                        <div class="form-radio-success">
                            @foreach($menu->subMenu as $sub_key=>$sub_menu)
                            <div class="form-check form-check-inline mb-2">
                                <input type="checkbox" wire:model="userSubMenuList.{{$sub_menu->id}}" class="form-check-input" id="AM1">
                                <label class="form-check-label" for="AM1">{{$sub_menu->name}}</label>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @endif



                </div>          
            </div>


            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->
</div><!-- end row -->



<?php

namespace App\Http\Livewire\Admin\SubAdmin;

use Validator;
use Hash;
use App\Http\Livewire\Traits\AlertMessage;
use App\Models\User;
use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Traits\WithSorting;
use Livewire\WithFileUploads;

class SubAdminList extends Component
{
	use WithPagination, WithFileUploads;
    use WithSorting;
    use AlertMessage;
    public  $state=[], $type='edit', $deleteIds=[], $permissions, $editUser, $meneList=[], $subMenuList=[], $userMenuList, $userSubMenuList, $user_id, $store;
    protected $paginationTheme = 'bootstrap';
	protected $listeners = ['deleteConfirm', 'changeStatus','deleteConfirmUsers'];
	public function mount()
    {
      
        $this->permissions = Permission::where('parent_id', 0)->get();
    }
    public function getRandomColor()
    {
        $arrIndex = array_rand($this->badgeColors);
        return $this->badgeColors[$arrIndex];
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function search()
    {
        $this->resetPage();
    }
    public function resetSearch()
    {
       
    }

    public function render()
    {
        $userQuery = User::where('type', 'S');
        return view('livewire.admin.sub-admin.sub-admin-list', [
            'users' => $userQuery->where('type', 'S')
                ->orderBy($this->sortBy, $this->sortDirection)
                ->get()
        ]);
    }

    public function addUser()
    {
    	$user = new User;
    	$this->state['id'] = '';
    	$this->state['name'] = '';
    	$this->state['email'] = '';
    	$this->state['username'] = '';
    	$this->state['dob'] = '';
        $this->state['store'] = '';
    	$this->state['status'] = "";
        $this->state['permission_name'] = [];
    	$this->state['gender'] = '';
    	$this->state['profile_image'] = '';
    	$this->state['profile_path'] = null;
    	$this->state['password'] = '';
    	$this->type = 'Add';
        $this->userMenuList[3] = true;
        $this->userSubMenuList[11] = true;
        $this->dispatchBrowserEvent('show-user-add-form');
    }
	
    public function editUser($id)
    {
    	$this->editUser = User::find($id);
        //dd(in_array(1, $this->editUser->permissions()->pluck('permission_id')->toArray()));
    	$this->state['id'] = $this->editUser->id;
    	$this->state['name'] = $this->editUser->name;
    	$this->state['email'] = $this->editUser->email;
    	$this->state['username'] = $this->editUser->username;
    	$this->state['store'] = $this->editUser->store;
        $this->state['dob'] = $this->editUser->dob;
        $this->state['dob'] = $this->editUser->dob;
        $this->state['password'] = null;
    	$this->state['status'] = $this->editUser->status;
    	$this->state['gender'] = $this->editUser->gender;
    	$this->state['profile_path'] = $this->editUser->profile_image;
    	$this->type = 'Edit';

        if(isset($this->editUser->permissions)){
        $old_permission  ='';
        $this->state['permission_name']= null;
        $old_permission = $this->editUser->permissions()->pluck('id')->toArray();
            if(count( $old_permission))
            {
                
                foreach ($old_permission as $key => $value) {
                    $this->state['permission_name'][$value] = $value;
                }
            }
        }
        else{
            $this->state['permission_name'] = '';
        }
        /*dump($this->editUser);
        dump($this->editUser->permissions[0]);
        dd($this->state['permission_name']);*/
        $this->dispatchBrowserEvent('show-user-add-form');

    }

    public function accessMenu($id)
    {
        $user = User::find($id);
        $this->user_id= $user->id;
        $this->meneList = Permission::with('subMenu')->where('parent_id', 0)->get();
        if(isset($user->permissions)){
        $old_permission  ='';
        $this->userMenuList= null;
        $this->userMenuList[3] = true;
        $this->userSubMenuList[11] = true;
        $old_permission = $user->permissions()->pluck('id')->toArray();
            if(count( $old_permission))
            {
                
                foreach ($old_permission as $key => $value) {
                    //dump($value);
                    if($value<8)
                    {

                        $this->userMenuList[$value] = true;
                    }elseif($value>7){

                        $this->userSubMenuList[$value] = true;
                    }
                }
            }
        }
        else{
            $this->userMenuList = '';
        }
                    //dump($this->userMenuList);
                    //dump($this->userSubMenuList);
        //dd('ok');
        $this->dispatchBrowserEvent('show-user-access-menu');
    }

    public function updatedUserMenuList()
    {
        foreach ($this->userMenuList as $key => $value) 
        {
            if($value == false)
            {
                $data = Permission::where('parent_id', $key)->get();
                foreach ($data as $key2 => $value2) {
                    $this->userSubMenuList[$value2->id] = false;
                }
            }
        }
    }
    public function updatedUserSubMenuList()
    {
        foreach ($this->userSubMenuList as $key => $value) 
        {
            if($value == true)
            {
                $data = Permission::find($key);
                if($data->parent_id != 0)
                {
                    $data2 = Permission::find($data->parent_id);
                    $this->userMenuList[$data2->id]=true;
                }
            }
        }
    }

    public function updateUserAccessMenu()
    {
        $user = User::find($this->user_id);
        $perm = [];
        foreach ($this->userMenuList as $key => $value) 
        {
            if($value){

                $perm[] = $key;
            }
        }
        if($this->userSubMenuList)
        {
               foreach ($this->userSubMenuList as $key => $value) 
               {
                    if($value)
                    {

                    $perm[] = $key;
                } 
            }
        }
        
        $user->permissions()->sync($perm);
        
        $msgAction = 'Permission change successfully';
        $this->showToastr("success",$msgAction);

        return redirect()->route('sub-admins.index');
    }

    public function createUser()
    {
    	Validator::make($this->state,[
    		'name' => 'required',
    		'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,4}$/ix|max:255|unique:users',
    		'username' => 'required||unique:users',
    		'password' => 'required|min:6',
    		'gender' => 'required',
            'permission_name' => 'required',
    		'dob' => 'required',
            'store' => 'required',
    		'profile_image' => 'required',
    		'status' => 'required',
        ])->validate();

        $profile_pic = $this->state['profile_image'];
        $filename = time() . '-' . rand(1000, 9999) . '.' . $profile_pic->getClientOriginalExtension();
        $profile_pic->storeAs("public/profile_image", $filename);
        
        $insertedUser = User::create([
        	'name' => $this->state['name'],
        	'email' => $this->state['email'],
        	'username' => $this->state['username'],
        	'dob' => $this->state['dob'],
            'store' => $this->state['store'],
        	'gender' => $this->state['gender'],
        	'status' => $this->state['status'],
        	'password' => Hash::make($this->state['password']),
        	'profile_image' => $filename,
        	'type' => 'S',
        	'reg_date' => date('Y-m-d'),
        ]);
        $perm =[3,11];
        foreach ($this->state['permission_name'] as $key => $value) {
            if($value){

                $perm[] = $key;
            }
        }
        $insertedUser->permissions()->attach($perm);

       
        $msgAction = 'Sub Admin Add Successfully';
    	$this->showToastr("success",$msgAction);

    	return redirect()->route('sub-admins.index');
    }

    public function updateUser()
    {
        $user = User::find($this->state['id']);
    	Validator::make($this->state,[
    		'name' => 'required',
    		'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,4}$/ix', 'max:255|unique:users,email'.$user->id,
    		'username' => 'required||unique:users,username,'.$user->id,
    		'permission_name' => 'required',
            'password' => 'nullable|min:6',
            'gender' => 'required',
    		'dob' => 'required',
            'store' => 'required',
    		'profile_image' => 'nullable',
    		'status' => 'required',
        ])->validate();


    	if (isset($this->state['profile_image']) && !is_string($this->state['profile_image'])) {
	        $profile_pic = $this->state['profile_image'];
	        $filename = time() . '-' . rand(1000, 9999) . '.' . $profile_pic->getClientOriginalExtension();
	        $profile_pic->storeAs("public/profile_image", $filename);
	        if($user->profile_image)
            {
                @unlink(storage_path('app/public/profile_image/' . $user->profile_image));
            }
    	}
    	else{
    		$filename = $user->profile_image;
    	}
        $status = "0";
        if(isset($this->state['status']))
        {
       		$status = "1";
	    }
        $user->update([
        	'name' => $this->state['name'],
        	'email' => $this->state['email'],
        	'username' => $this->state['username'],
        	'dob' => $this->state['dob'],
            'store' => $this->state['store'],
        	'gender' => $this->state['gender'],
        	'status' => $this->state['status'],
        	'profile_image' => $filename,
        ]);
        if($this->state['password'])
        {
            $user->update(['password' => Hash::make($this->state['password'])]);
        }
        $perm= [];
       foreach ($this->state['permission_name'] as $key => $value) {
            if($value){
                $perm[] = $key;
            }
        }
        if(count($perm))
        {
            $user->permissions()->sync($perm);
        }

        $msgAction = 'Sub Admin Update Successfully';
    	$this->showToastr("success",$msgAction);

    	return redirect()->route('sub-admins.index');
    }


    public function deleteConfirm($id)
    {
        $userDelete = User::find($id['id']);
        if(count($userDelete->orders) || count($userDelete->orders_new_db)){
           $this->showModal('error', 'Error', 'Sub Admin has many orders.'); 
        }else{
            User::destroy($id);
        $this->showModal('success', 'Success', 'Sub Admin has been deleted successfully');
        }
    }
    public function deleteAttempt($id)
    {
        $this->showConfirmation("warning", 'Are you sure?', "You won't be able to recover this Sub Admin!", 'Yes, delete!', 'deleteConfirm', ['id' => $id]); 
    }

    public function changeStatusConfirm($id)
    {
        $this->showConfirmation("warning", 'Are you sure?', "Do you want to change this status?", 'Yes, Change!', 'changeStatus', ['ids' => $id]); 
    }

    public function changeStatus(User $user)
    {
        $user->update(['status' => ($user->status == "1") ? "0" : "1"]);
        $this->showModal('success', 'Success', 'Sub Admin status has been changed successfully');
    }

    public function deleteMultiUser()
    {
        if(count($this->deleteIds) == "0"){
             $this->showToastr('error', 'Please select user', false);
        }else{

            $this->showConfirmation("warning", 'Are you sure?', "You won't be able to recover this Sub Admins!", 'Yes, delete!', 'deleteConfirmUsers', ['id' => $this->deleteIds]);
        }
    }

    public function deleteConfirmUsers($ids)
    {
    	foreach ($ids as $key => $value) {
    		$users = User::whereIn('id', $value)->delete();
    	}
    	$this->showModal('success', 'Success', 'Deleted successfully');
    }
    
}

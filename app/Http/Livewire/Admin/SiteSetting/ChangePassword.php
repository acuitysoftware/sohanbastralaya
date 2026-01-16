<?php

namespace App\Http\Livewire\Admin\SiteSetting;

use Str;
use Hash;
use Auth;
use Validator;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Traits\AlertMessage;

class ChangePassword extends Component
{
	use AlertMessage;

	public $password, $old_password, $password_confirmation, $user;

	public function mount()
	{
        $this->user =Auth::user();
	}

	public function save()
	{
		$this->validate([
            'old_password' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ]);
        if (Hash::check($this->old_password, $this->user->password)) {
            $this->user->password = Hash::make($this->password);
            $this->user->save();


            $msgAction = 'Password Change Successfully';
	        $this->showToastr("success", $msgAction);
	        return redirect()->route('dashboard');

        } else {
        	$msgAction = 'Invalid old password';
	        $this->showToastr("error", $msgAction);
	        return redirect()->route('change_password');
        }
	}
    public function render()
    {
        return view('livewire.admin.site-setting.change-password');
    }
}

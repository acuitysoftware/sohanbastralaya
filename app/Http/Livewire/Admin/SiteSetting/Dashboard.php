<?php

namespace App\Http\Livewire\Admin\SiteSetting;

use Validator;
use Hash;
use App\Http\Livewire\Traits\AlertMessage;
use App\Models\User;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Traits\WithSorting;
use Livewire\WithFileUploads;

class Dashboard extends Component
{
	use WithPagination, WithFileUploads;
    use WithSorting;
    use AlertMessage;
    public $site_logo,$site_title,$target_purchase,$target_points,$min_price_wallet,$min_points_wallet,$expire_days,$min_purchase,$min_purchase_points,$meta_desc,$meta_keys,$admin_mail,$site_link,$des_dept,$facebook,$address,$twitter,$carry_charge,$discount_percentage,$min_points,$one_points,$google_plus,$instragram_link,$copyright_link,$contact_no,$second_contact_no,$google_api_key,$site_mail,$setting;
    public  $state=[], $type='edit', $deleteIds=[];

	protected $listeners = ['deleteConfirm', 'changeStatus','deleteConfirmUsers'];
	public function mount()
    {
        $this->setting = Setting::first();
        $this->fill($this->setting);
    }

    public function saveTab1()
    {
        $data = $this->validate([
            'site_title' => 'required',
            'copyright_link' => 'required',
            'site_mail' => 'required',
            'site_link' => 'required',
            'site_logo' => 'required',
        ]);

        if (isset($this->site_logo) && !is_string($this->site_logo)) 
        {
            $img = $this->site_logo;
            $filename = time() . '-' . rand(1000, 9999) . '.' . $img->getClientOriginalExtension();
            $img->storeAs("public/site_settings", $filename);
            $fileName = 'site_settings/'.$filename;
            if(isset($this->setting->site_logo))
            {
                @unlink(storage_path('app/public/' . $this->setting->site_logo));
            }
        }
        else{
            $fileName = $this->setting->site_logo;
        }
        $this->setting->update($data);
        $this->setting->update(['site_logo' => $fileName]);
        $this->showToastr("success",'Site setting updated successfully');

        return redirect()->route('dashboard');
    }

    public function saveTab2()
    {
        $data = $this->validate([
            'contact_no' => 'required|numeric|digits_between:10,12',
        ]);
        $this->setting->update($data);
        $this->showToastr("success",'Site setting updated successfully');

        return redirect()->route('dashboard');
    }
    public function saveTab3()
    {
        if (isset($this->site_logo) && !is_string($this->site_logo)) 
        {
            $img = $this->site_logo;
            $filename = time() . '-' . rand(1000, 9999) . '.' . $img->getClientOriginalExtension();
            $img->storeAs("public/site_settings", $filename);
            $fileName = 'site_settings/'.$filename;
            if(isset($this->setting->site_logo))
            {
                @unlink(storage_path('app/public/' . $this->setting->site_logo));
            }
        }
        else{
            $fileName = $this->setting->site_logo;
        }
        $this->setting->update(['site_logo' => $fileName]);
        $this->showToastr("success",'Site setting updated successfully');

        return redirect()->route('dashboard');
    }
    public function saveTab4()
    {
        $data = $this->validate([
            'target_purchase' => 'required|numeric',
            'min_price_wallet' => 'required|numeric',
            'one_points' => 'required|numeric',
            'target_points' => 'required|numeric',
            'min_points_wallet' => 'required|numeric',
            'min_points' => 'required|numeric',
            'expire_days' => 'required|numeric',
            'min_purchase' => 'required|numeric',
            'min_purchase_points' => 'required|numeric',
            'carry_charge' => 'required|numeric',
            'discount_percentage' => 'required|numeric',
        ]);

        
        $this->setting->update($data);
        $this->showToastr("success",'Site setting updated successfully');

        return redirect()->route('dashboard');
    }
    
    public function render()
    {
        return view('livewire.admin.site-setting.dashboard');
    }
}

<?php

namespace App\Http\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class AdminSettingComponent extends Component
{
    public $email;
    public $phone;
    public $phone2;
    public $address;
    public $map;
    public $twitter;
    public $facebook;
    public $pinterest;
    public $instagram;
    public $youtube;

    protected $rules = [
        'email' => 'required|email',
        'phone' => 'required',
        'phone2' => 'required',
        'address' => 'required',
        'map' => 'required',
        'twitter' => 'required',
        'facebook' => 'required',
        'pinterest' => 'required',
        'instagram' => 'required',
        'youtube' => 'required'
    ];
 
    protected $messages = [
        'required' => ':attribute không được để trống.',
        'email' => ':attribute không đúng định dạng email!'
    ];
 
    protected $validationAttributes = [
        'email' => 'Địa chỉ email',
        'phone' => 'Số điện thoại',
        'phone2' => 'Số điện thoại 2',
        'address' => 'Địa chỉ',
        'map' => 'Liên kết bản đồ',
        'twitter' => 'Tài khoản Twitter',
        'facebook' => 'Tài khoản Facebook',
        'pinterest' => 'Tài khoản Pinterest',
        'instagram' => 'Tài khoản Instagram',
        'youtube' => 'Kênh Youtube'
    ];

    public function mount()
    {
        $setting = Setting::find(1);
        if($setting){
            $this->email = $setting->email;
            $this->phone = $setting->phone;
            $this->phone2 = $setting->phone2;
            $this->address = $setting->address;
            $this->map = $setting->map;
            $this->twitter = $setting->twitter;
            $this->facebook = $setting->facebook;
            $this->pinterest = $setting->pinterest;
            $this->instagram = $setting->instagram;
            $this->youtube = $setting->youtube;
        }
    }

    public function updated($fields)
    {   
        $this->validateOnly($fields, $this->rules, $this->messages, $this->validationAttributes);
    }

    public function saveSetting()
    {
        $this->validate($this->rules, $this->messages, $this->validationAttributes);

        $setting = Setting::find(1);
        if(!$setting){
            $setting = new Setting();
        }

        $setting->email = $this->email;
        $setting->phone = $this->phone;
        $setting->phone2 = $this->phone2;
        $setting->address = $this->address;
        $setting->map = $this->map;
        $setting->twitter = $this->twitter;
        $setting->facebook = $this->facebook;
        $setting->pinterest = $this->pinterest;
        $setting->instagram = $this->instagram;
        $setting->youtube = $this->youtube;

        $setting->save();
        session()->flash('message', 'Setting has been saved successfully!');
    }

    public function render()
    {
        return view('livewire.admin.admin-setting-component')->layout('layouts.base');
    }
}

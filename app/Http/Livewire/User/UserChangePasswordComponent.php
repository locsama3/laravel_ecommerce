<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserChangePasswordComponent extends Component
{
    public $current_password;
    public $new_password;
    public $password_confirmation;

    protected $rules = [
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed|different:current_password',
        'password_confirmation' => 'required'
    ];
 
    protected $messages = [
        'required' => ':attribute không được để trống.',
        'min' => ':attribute phải lớn hơn 8 ký tự',
        'confirmed' => ':attribute và xác nhận không khớp với nhau',
        'different' => ':attribute phải khác mật khẩu cũ'
    ];
 
    protected $validationAttributes = [
        'current_password' => 'Mật khẩu hiện tại',
        'new_password' => 'Mật khẩu mới',
        'password_confirmation' => 'Xác nhận mật khẩu'
    ];

    public function updated($fields)
    {   
        $this->validateOnly($fields, $this->rules, $this->messages, $this->validationAttributes);
    }

    public function changePassword()
    {
        $this->validate($this->rules, $this->messages, $this->validationAttributes);

        if(Hash::check($this->current_password, Auth::user()->password)){
            $user = User::findOrFail(Auth::user()->id);
            $user->password = Hash::make($this->new_password);  
            $user->save();
            session()->flash('success_message', "Password has been changed successfully!");
        }else{
            session()->flash('error_message', "Password does not match!");
        }
    }

    public function render()
    {
        return view('livewire.user.user-change-password-component')->layout('layouts.base');
    }
}

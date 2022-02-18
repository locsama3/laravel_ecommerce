<?php

namespace App\Http\Livewire;

use App\Models\Setting;
use App\Models\Contact;
use Livewire\Component;

class ContactComponent extends Component
{
    public $name;
    public $email;
    public $phone;
    public $content;

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'content' => 'required'
    ];
 
    protected $messages = [
        'required' => ':attribute không được để trống.',
        'email' => ':attribute không đúng định dạng email!'
    ];
 
    protected $validationAttributes = [
        'name' => 'Tên danh mục',
        'email' => 'Địa chỉ email',
        'phone' => 'Số điện thoại',
        'content' => 'Nội dung'
    ];

    public function updated($fields)
    {   
        $this->validateOnly($fields, $this->rules, $this->messages, $this->validationAttributes);
    }

    public function sendMessage()
    {
        $this->validate($this->rules, $this->messages, $this->validationAttributes);

        $contact = new Contact();
        $contact->name = $this->name;
        $contact->email = $this->email;
        $contact->phone = $this->phone;
        $contact->content = $this->content;

        $contact->save();
        session()->flash('message', 'Thank you! Your message has been sent successfully!'); 

        $contact = $this->reset();       
    }
    public function render()
    {
        $setting = Setting::find(1);
        return view('livewire.contact-component',[
            'setting' => $setting
        ])->layout('layouts.base');
    }
}

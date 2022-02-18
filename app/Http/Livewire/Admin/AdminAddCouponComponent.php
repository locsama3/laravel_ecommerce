<?php

namespace App\Http\Livewire\Admin;

use App\Models\Coupon;
use Livewire\Component;

class AdminAddCouponComponent extends Component
{
    public $code;
    public $type;
    public $value;
    public $cart_value;
    public $expiry_date;

    public function updated($fields)
    {   
        $this->validateOnly($fields,
            [
                'code' => 'required|unique:coupons',
                'type' => 'required',
                'value' => 'required|numeric',
                'cart_value' => 'required|numeric',
                'expiry_date' => 'required'
            ],
            [
                'required' => ':attribute không được để trống.',
                'unique' => ':attribute này đã tồn tại. Vui lòng nhập lại.',
                'numeric' => ':attribute phải là số!'
            ],
            [
                'code' => 'Mã khuyến mãi',
                'type' => 'Loại khuyến mãi',
                'value' => 'Giá trị mã',
                'cart_value' => 'Giá trị đơn hàng',
                'expiry_date' => 'Ngày hết hạn'
            ]
        );
    }

    public function storeCoupon()
    {
        $this->validate(
            [
                'code' => 'required|unique:coupons',
                'type' => 'required',
                'value' => 'required|numeric',
                'cart_value' => 'required|numeric',
                'expiry_date' => 'required'
            ],
            [
                'required' => ':attribute không được để trống.',
                'unique' => ':attribute này đã tồn tại. Vui lòng nhập lại.',
                'numeric' => ':attribute phải là số!'
            ],
            [
                'code' => 'Mã khuyến mãi',
                'type' => 'Loại khuyến mãi',
                'value' => 'Giá trị mã',
                'cart_value' => 'Giá trị đơn hàng',
                'expiry_date' => 'Ngày hết hạn'
            ]
        );

        $coupon = new Coupon();
        $coupon->code = $this->code;
        $coupon->type = $this->type;
        $coupon->value = $this->value;
        $coupon->cart_value = $this->cart_value;
        $coupon->expiry_date = $this->expiry_date;
        $coupon->save();

        session()->flash('message', 'Coupon has been created successfully!');

        $coupon = $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.admin-add-coupon-component')->layout('layouts.base');
    }
}

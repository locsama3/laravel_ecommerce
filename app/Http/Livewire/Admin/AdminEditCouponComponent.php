<?php

namespace App\Http\Livewire\Admin;

use App\Models\Coupon;
use Livewire\Component;

class AdminEditCouponComponent extends Component
{
    public $code;
    public $type;
    public $value;
    public $cart_value;
    public $expiry_date;

    public $coupon_id;

    public function mount($coupon_id)
    {
        $coupon = Coupon::find($coupon_id);
        $this->code = $coupon->code;
        $this->type = $coupon->type;
        $this->value = $coupon->value;
        $this->cart_value = $coupon->cart_value;
        $this->expiry_date = $coupon->expiry_date;
        $this->coupon_id = $coupon->id;
    }

    public function updated($fields)
    {   
        $this->validateOnly($fields,
            [
                'code' => 'required',
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

    public function updateCoupon()
    {
        $this->validate(
            [
                'code' => 'required',
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

        $coupon = Coupon::find($this->coupon_id);
        $coupon->code = $this->code;
        $coupon->type = $this->type;
        $coupon->value = $this->value;
        $coupon->cart_value = $this->cart_value;
        $coupon->expiry_date = $this->expiry_date;
        $coupon->save();

        session()->flash('message', 'Coupon has been updated successfully!');

        return redirect()->route('admin.coupons');
    }

    public function render()
    {
        return view('livewire.admin.admin-edit-coupon-component')->layout('layouts.base');
    }
}

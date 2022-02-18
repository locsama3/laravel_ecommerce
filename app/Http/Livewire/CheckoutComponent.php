<?php

namespace App\Http\Livewire;

use App\Mail\OrderMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;
use App\Models\Transaction;
use Cart;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Stripe;

class CheckoutComponent extends Component
{   
    public $ship_to_different;

    public $firstname;
    public $lastname;
    public $email;
    public $mobile;
    public $line1;
    public $line2;
    public $city;
    public $province;
    public $country;
    public $zipcode;

    public $s_firstname;
    public $s_lastname;
    public $s_email;
    public $s_mobile;
    public $s_line1;
    public $s_line2;
    public $s_city;
    public $s_province;
    public $s_country;
    public $s_zipcode;

    public $payment_mode;

    public $thankyou;

    public $card_no;
    public $exp_month;
    public $exp_year;
    public $cvc;

    protected $rules = [
        'firstname' => 'required',
        'lastname' => 'required',
        'email' => 'required|email',
        'mobile' => 'required|numeric',
        'line1' => 'required',
        'city' => 'required',
        'province' => 'required',
        'country' => 'required',
        'zipcode' => 'required',
        'payment_mode' => 'required'
    ];
 
    protected $messages = [
        'required' => ':attribute không được để trống.',
        'numeric' => ':attribute không đúng định dang!',
        'email' => ':attribute không đúng định dạng email!'
    ];
 
    protected $validationAttributes = [
        'firstname' => 'Tên',
        'lastname' => 'Họ',
        'email' => 'Địa chỉ email',
        'mobile' => 'Số điện thoại',
        'line1' => 'Địa chỉ',
        'city' => 'Xã/Phường',
        'province' => 'Tỉnh/Thành phố',
        'country' => 'Quốc gia',
        'zipcode' => 'Mã bưu điện',
        'payment_mode' => 'Phương thức thanh toán'
    ];

    protected $s_rules = [
        's_firstname' => 'required',
        's_lastname' => 'required',
        's_email' => 'required|email',
        's_mobile' => 'required|numeric',
        's_line1' => 'required',
        's_city' => 'required',
        's_province' => 'required',
        's_country' => 'required',
        's_zipcode' => 'required'
    ];
 
    protected $s_messages = [
        'required' => ':attribute không được để trống.',
        'numeric' => ':attribute không đúng định dang!',
        'email' => ':attribute không đúng định dạng email!'
    ];
 
    protected $s_validationAttributes = [
        's_firstname' => 'Tên',
        's_lastname' => 'Họ',
        's_email' => 'Địa chỉ email',
        's_mobile' => 'Số điện thoại',
        's_line1' => 'Địa chỉ',
        's_city' => 'Xã/Phường',
        's_province' => 'Tỉnh/Thành phố',
        's_country' => 'Quốc gia',
        's_zipcode' => 'Mã bưu điện'
    ];

    protected $c_rules = [
        'card_no' => 'required|numeric',
        'exp_month' => 'required|numeric',
        'exp_year' => 'required|numeric',
        'cvc' => 'required|numeric'
    ];
 
    protected $c_messages = [
        'required' => ':attribute không được để trống.',
        'numeric' => ':attribute không đúng định dang!'
    ];
 
    protected $c_validationAttributes = [
        'card_no' => 'Mã thẻ',
        'exp_month' => 'Tháng',
        'exp_year' => 'Năm',
        'cvc' => 'CVC'
    ];

    public function updated($fields)
    {   
        $this->validateOnly($fields, $this->rules, $this->messages, $this->validationAttributes);

        if($this->ship_to_different){
            $this->validateOnly($fields, $this->s_rules, $this->s_messages, $this->s_validationAttributes);
        }

        if($this->payment_mode == 'card'){
            $this->validateOnly($fields, $this->c_rules, $this->c_messages, $this->c_validationAttributes);
        }
    }

    public function placeOrder()
    {
        $this->validate($this->rules, $this->messages, $this->validationAttributes);

        if($this->payment_mode == 'card'){
            $this->validate($this->c_rules, $this->c_messages, $this->c_validationAttributes);
        }

        $order = new Order();

        $order->user_id = Auth::user()->id;
        $order->subtotal = session()->get('checkout')['subtotal'];
        $order->discount = session()->get('checkout')['discount'];
        $order->tax = session()->get('checkout')['tax'];
        $order->total = session()->get('checkout')['total'];
        $order->first_name = $this->firstname;
        $order->last_name = $this->lastname;
        $order->email = $this->email;
        $order->mobile = $this->mobile;
        $order->line1 = $this->line1;
        $order->line2 = $this->line2;
        $order->city = $this->city;
        $order->province = $this->province;
        $order->country = $this->country;
        $order->zipcode = $this->zipcode;
        $order->status = 'ordered';
        $order->is_shipping_different = $this->ship_to_different ? 1 : 0;

        $order->save();

        foreach(Cart::instance('cart')->content() as $item){
            $orderItem = new OrderItem();

            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->save();
        }

        if($this->ship_to_different){
            $this->validate($this->s_rules, $this->s_messages, $this->s_validationAttributes);

            $shipping = new Shipping();
            $shipping->order_id = $order->id;
            $shipping->first_name = $this->s_firstname;
            $shipping->last_name = $this->s_lastname;
            $shipping->email = $this->s_email;
            $shipping->mobile = $this->s_mobile;
            $shipping->line1 = $this->s_line1;
            $shipping->line2 = $this->s_line2;
            $shipping->city = $this->s_city;
            $shipping->province = $this->s_province;
            $shipping->country = $this->s_country;
            $shipping->zipcode = $this->s_zipcode;
            $shipping->save();
        }

        if($this->payment_mode == 'cod'){
            $this->makeTransaction($order->id, 'pending');
            $this->resetCart();
        }
        else if($this->payment_mode == 'card'){
            $stripe = Stripe::make(env('STRIPE_KEY'));

            try{
                $token = $stripe->tokens()->create([
                    'card' => [
                        'number' => $this->card_no,
                        'exp_month' => $this->exp_month,
                        'exp_year' => $this->exp_year,
                        'cvc' => $this->cvc
                    ]
                ]);

                if(!isset($token['id'])){
                    session()->flash('stripe_error', 'The stripe token was not generated correctly!');
                    $this->thankyou = 0;
                }

                $customer = $stripe->customers()->create([
                    'name' => $this->firstname . ' ' . $this->lastname,
                    'email' => $this->email,
                    'phone' => $this->mobile,
                    'address' => [
                        'line1' => $this->line1,
                        'postal_code' => $this->zipcode,
                        'city' => $this->city,
                        'state' => $this->province,
                        'country' => $this->country
                    ],
                    'shipping' => [
                        'name' => $this->firstname . ' ' . $this->lastname,
                        'address' => [
                            'line1' => $this->line1,
                            'postal_code' => $this->zipcode,
                            'city' => $this->city,
                            'state' => $this->province,
                            'country' => $this->country
                        ],
                    ],
                    'source' => $token['id']
                ]);

                $charge = $stripe->charges()->create([
                    'customer' => $customer['id'],
                    'currency' => 'USD',
                    'amount' => session()->get('checkout')['total'],
                    'description' => 'Payment for order no ' . $order->id
                ]);

                if($charge['status'] == 'succeeded'){
                    $this->makeTransaction($order->id, 'approved');
                    $this->resetCart();
                }else{
                    session()->flash('stripe_error', 'Error in transaction!');
                    $this->thankyou = 0;
                }
            } catch(Exception $e) {
                session()->flash('stripe_error', $e->getMessage());
                $this->thankyou = 0;
            }
        }
        
        $this->sendOrderComfirmationMail($order);
    }

    public function resetCart()
    {
        $this->thankyou = 1;

        Cart::instance('cart')->destroy();
        session()->forget('checkout');
    }

    public function makeTransaction($order_id, $status)
    {
        $transaction = new Transaction();
        $transaction->user_id = Auth::user()->id;
        $transaction->order_id = $order_id;
        $transaction->mode = $this->payment_mode;
        $transaction->status = $status;
        $transaction->save();
    }

    public function sendOrderComfirmationMail($order)
    {
        Mail::to($order->email)->send(new OrderMail($order));
    }

    public function verifyForCheckout()
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }
        else if($this->thankyou){
            return redirect()->route('thankyou');
        }
        else if(!session()->get('checkout')){
            return redirect()->route('product.cart');
        }
    }

    public function render()
    {
        $this->verifyForCheckout();
        return view('livewire.checkout-component')->layout('layouts.base');
    }
}

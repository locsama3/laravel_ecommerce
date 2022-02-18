<?php

namespace App\Http\Livewire\User;

use App\Models\OrderItem;
use App\Models\Review;  
use Livewire\Component;

class UserReviewComponent extends Component
{   
    public $order_item_id;

    public $rating;
    public $comment;

    protected $rules = [
        'rating' => 'required',
        'comment' => 'required'
    ];
 
    protected $messages = [
        'required' => ':attribute không được để trống.'
    ];
 
    protected $validationAttributes = [
        'rating' => 'Đánh giá sao',
        'comment' => 'Bình luận'
    ];

    public function mount($order_item_id)
    {
        $this->order_item_id = $order_item_id;
    }

    public function updated($fields)
    {   
        $this->validateOnly($fields, $this->rules, $this->messages, $this->validationAttributes);
    }

    public function addReview()
    {
        $this->validate($this->rules, $this->messages, $this->validationAttributes);

        $review = new Review();
        $review->rating = $this->rating;
        $review->comment = $this->comment;
        $review->order_item_id = $this->order_item_id;
        
        $review->save();

        $orderItem = OrderItem::find($this->order_item_id);
        $orderItem->rstatus = true;

        $orderItem->save();

        session()->flash('message','Your review has been added successfully!');
    }

    public function render()
    {
        $orderItem = OrderItem::find($this->order_item_id);
        return view('livewire.user.user-review-component',[
            'orderItem' => $orderItem
        ])->layout('layouts.base');
    }
}

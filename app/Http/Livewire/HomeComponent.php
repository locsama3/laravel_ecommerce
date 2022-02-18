<?php

namespace App\Http\Livewire;

use App\Models\HomeSlider;
use App\Models\Product;
use App\Models\Category;
use App\Models\HomeCategory;
use App\Models\Sale;
use Livewire\Component;
use Cart;
use Illuminate\Support\Facades\Auth;

class HomeComponent extends Component
{
    public function render()
    {
        $sliders = HomeSlider::where('status', 1)->get();

        $latest_products = Product::orderBy('created_at', 'DESC')->get()->take(8);

        $home_category = HomeCategory::find(1);
        $cates = explode(',', $home_category->sel_categories);
        $no_of_products = $home_category->no_of_products;

        $categories = Category::whereIn('id', $cates)->get();

        $sale_products = Product::where('sale_price', '>', 0)->inRandomOrder()->get()->take(8);

        $sale = Sale::find(1);

        if(Auth::check()){
            Cart::instance('cart')->restore(Auth::user()->email);

            Cart::instance('wishlist')->restore(Auth::user()->email); 
        }

        return view('livewire.home-component',[
            'sliders' => $sliders,
            'latest_products' => $latest_products,
            'categories' => $categories,
            'no_of_products' => $no_of_products,
            'sale_products' => $sale_products,
            'sale' => $sale
        ])->layout('layouts.base');
    }
}

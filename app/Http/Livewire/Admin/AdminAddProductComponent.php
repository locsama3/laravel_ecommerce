<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class AdminAddProductComponent extends Component
{
    use WithFileUploads;

    public $name;
    public $slug;
    public $short_description;
    public $description;
    public $regular_price;
    public $sale_price;
    public $SKU;
    public $stock_status;
    public $featured;
    public $quantity;
    public $image;
    public $category_id;
    public $gallery;

    public $subcategory_id;

    protected $rules = [
        'name' => 'required',
        'slug' => 'required|unique:products',
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required|numeric',
        'sale_price' => 'numeric',
        'SKU' => 'required',
        'stock_status' => 'required',
        'quantity' => 'required|numeric',
        'image' => 'required|mimes:jpeg,png,jpg',
        'category_id' => 'required',
    ];
 
    protected $messages = [
        'required' => ':attribute không được để trống.',
        'unique' => ':attribute này đã tồn tại. Vui lòng nhập lại.',
        'numeric' => ':attribute phải là số!',
        'mimes' => ':attribute chỉ nhận các hình ảnh có đuôi .jpeg, .jpg, .png.',
    ];
 
    protected $validationAttributes = [
        'name' => 'Tên danh mục',
        'slug' => 'Liên kết tĩnh',
        'short_description' => 'Mô tả ngắn',
        'description' => 'Mô tả',
        'regular_price' => 'Giá sản phẩm',
        'sale_price' => 'Giá khuyến mãi',
        'SKU' => 'Mã hàng hóa',
        'stock_status' => 'Trạng thái',
        'quantity' => 'Số lượng',
        'image' => 'Ảnh đại diện',
        'category_id' => 'Mã danh mục',
    ];

    public function mount()
    {
        $this->stock_status = 'instock';
        $this->featured = 0;
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name);
    }

    public function updated($fields)
    {   
        $this->validateOnly($fields, $this->rules, $this->messages, $this->validationAttributes);
    }

    public function addProduct()
    {
        $this->validate($this->rules, $this->messages, $this->validationAttributes);

        $product = new Product();

        $product->name = $this->name;
        $product->slug = $this->slug;
        $product->short_description = $this->short_description;
        $product->description = $this->description;
        $product->regular_price = $this->regular_price;
        $product->sale_price = $this->sale_price;
        $product->SKU = $this->SKU;
        $product->stock_status = $this->stock_status;
        $product->featured = $this->featured;
        $product->quantity = $this->quantity;

        $imageName = $this->slug.Carbon::now()->timestamp. '.' .$this->image->extension();
        $this->image->storeAs('products', $imageName);

        $product->image = $imageName;

        if($this->gallery){
            $galleryImgName = '';
            foreach($this->gallery as $key => $value){
                $imgName = $this->slug.Carbon::now()->timestamp. $key .'.' .$value->extension();
                $value->storeAs('products', $imgName);

                $galleryImgName = $galleryImgName. ',' .$imgName;
            }

            $product->images = $galleryImgName;
        }

        $product->category_id = $this->category_id;
        if($this->subcategory_id){
            $product->subcategory_id = $this->subcategory_id;
        }
        $product->save();   
        session()->flash('message', "Product has been created successfully!");
        
        $product = $this->reset();
    }

    public function changeSubcategory()
    {
        $this->subcategory_id = 0;
    }

    public function render()
    {   
        $categories = Category::all();

        $subcategories = Subcategory::where('category_id', $this->category_id)->get();
        return view('livewire.admin.admin-add-product-component', [
            'categories' => $categories,
            'subcategories' => $subcategories
        ])->layout('layouts.base');
    }
}

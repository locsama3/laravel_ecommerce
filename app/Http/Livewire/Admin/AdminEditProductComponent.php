<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class AdminEditProductComponent extends Component
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

    public $newImage;
    public $product_id;

    public $gallery;
    public $newGallery;

    public $subcategory_id;

    protected $rules = [
        'name' => 'required',
        'slug' => 'required',
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required|numeric',
        'sale_price' => 'numeric',
        'SKU' => 'required',
        'stock_status' => 'required',
        'quantity' => 'required|numeric',
        'category_id' => 'required',
    ];
 
    protected $messages = [
        'required' => ':attribute không được để trống.',
        'unique' => ':attribute này đã tồn tại. Vui lòng nhập lại.',
        'numeric' => ':attribute phải là số!',
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
        'category_id' => 'Mã danh mục',
    ];

    public function mount($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();

        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->short_description = $product->short_description;
        $this->description = $product->description;
        $this->regular_price = $product->regular_price;
        $this->sale_price = $product->sale_price;
        $this->SKU = $product->SKU;
        $this->stock_status = $product->stock_status;
        $this->featured = $product->featured;
        $this->quantity = $product->quantity;
        $this->image = $product->image;
        $this->gallery = explode(',', $product->images);
        $this->category_id = $product->category_id;
        $this->subcategory_id = $product->subcategory_id;
        $this->product_id = $product->id;
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name);
    }

    public function updated($fields)
    {   
        $this->validateOnly($fields, $this->rules, $this->messages, $this->validationAttributes);

        if($this->newImage){
            $this->validateOnly($fields, 
                ['newImage'  => 'required|mimes:jpeg,png,jpg'],
                [
                    'required' => 'Ảnh mô tả không được để trống.',
                    'mimes' => 'Ảnh mô tả chỉ nhận các hình ảnh có đuôi .jpeg, .jpg, .png.'
                ]
            );
        }
    }

    public function updateProduct()
    {
        $this->validate($this->rules, $this->messages, $this->validationAttributes);

        if($this->newImage){
            $this->validate( 
                ['newImage'  => 'required|mimes:jpeg,png,jpg'],
                [
                    'required' => 'Ảnh mô tả không được để trống.',
                    'mimes' => 'Ảnh mô tả chỉ nhận các hình ảnh có đuôi .jpeg, .jpg, .png.'
                ]
            );
        }
        
        $product = Product::find($this->product_id);

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

        if($this->newImage){
            unlink('assets/images/products/'.$product->image);

            $imageName = $this->slug.Carbon::now()->timestamp. '.' .$this->newImage->extension();
            $this->newImage->storeAs('products', $imageName);

            $product->image = $imageName;
        }

        if($this->newGallery){
            if($product->images){
                $images = explode(',', $product->images);
                foreach ($images as $image) {
                    if($image){
                        unlink('assets/images/products/'.$image);
                    }
                }
            }

            $imagesName = '';
            foreach ($this->newGallery as $key => $value) {
                $imgName = $this->slug.Carbon::now()->timestamp. $key .'.' .$value->extension();
                $value->storeAs('products', $imgName);
                $imagesName = $imagesName.','.$imgName;
            }

            $product->images = $imagesName;
        }

        $product->category_id = $this->category_id;

        if($this->subcategory_id){
            $product->subcategory_id = $this->subcategory_id;
        }

        $product->save();   
        session()->flash('message', "Product has been updated successfully!");

        return redirect()->route('admin.products');
    }

    public function changeSubcategory()
    {
        $this->subcategory_id = 0;
    }

    public function render()
    {   
        $categories = Category::all();
        $subcategories = Subcategory::where('category_id', $this->category_id)->get();
        return view('livewire.admin.admin-edit-product-component',[
            'categories' => $categories,
            'subcategories' => $subcategories
        ])->layout('layouts.base');
    }
}

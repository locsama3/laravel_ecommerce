<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Subcategory;
use Livewire\Component;
use Illuminate\Support\Str;

class AdminEditCategoryComponent extends Component
{   
    public $category_slug;
    public $category_id;
    public $name;
    public $slug;
    public $scategory_id;
    public $scategory_slug;

    protected $rules = [
        'name' => 'required',
        'slug' => 'required|unique:categories'
    ];
 
    protected $messages = [
        'required' => ':attribute không được để trống.',
        'unique' => ':attribute này đã tồn tại. Vui lòng nhập lại.'
    ];
 
    protected $validationAttributes = [
        'name' => 'Tên danh mục',
        'slug' => 'Liên kết tĩnh'
    ];

    public function mount($category_slug, $scategory_slug=null)
    {
        if($scategory_slug){
            $this->scategory_slug = $scategory_slug;
            $scategory = Subcategory::where('slug', $this->scategory_slug)->first();

            $this->scategory_id = $scategory->id;
            $this->category_id = $scategory->category_id;
            $this->name = $scategory->name;
            $this->slug = $scategory->slug;
        }else{
            $this->category_slug = $category_slug;
            $category = Category::where('slug', $this->category_slug)->first();
            $this->category_id = $category->id;
            $this->name = $category->name;
            $this->slug = $category->slug;
        }
    }

    public function updated($fields)
    {   
        $this->validateOnly($fields,
            [
                'name' => 'required',
                'slug' => 'required|unique:categories'
            ],
            [
                'required' => ':attribute không được để trống.',
                'unique' => ':attribute này đã tồn tại. Vui lòng nhập lại.'
            ],
            [
                'name' => 'Tên danh mục',
                'slug' => 'Liên kết tĩnh'
            ]
        );
    }

    public function generateslug()
    {
        $this->slug = Str::slug($this->name);
    }

    public function updateCategory()
    {
        $this->validate(
            [
                'name' => 'required',
                'slug' => 'required|unique:categories'
            ],
            [
                'required' => ':attribute không được để trống.',
                'unique' => ':attribute này đã tồn tại. Vui lòng nhập lại.'
            ],
            [
                'name' => 'Tên danh mục',
                'slug' => 'Liên kết tĩnh'
            ]
        );

        if($this->scategory_id){
            $sub_category = Subcategory::find($this->scategory_id);
            $sub_category->name = $this->name;
            $sub_category->slug = $this->slug;
            $sub_category->category_id = $this->category_id;
            $sub_category->save();
        }else{
            $category = Category::find($this->category_id);
            $category->name = $this->name;
            $category->slug = $this->slug;
            $category->save();
        }
        
        session()->flash('message', 'Category has been updated successfully!');

        return redirect()->route('admin.categories');
    }

    public function render()
    {
        $categories = Category::all();
        return view('livewire.admin.admin-edit-category-component',[
            'categories' => $categories
        ])->layout('layouts.base');
    }
}

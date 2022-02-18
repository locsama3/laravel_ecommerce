<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Subcategory;
use Livewire\Component;
use Illuminate\Support\Str;

class AdminAddCategoryComponent extends Component
{   
    public $name;
    public $slug;
    public $category_id;

    public function generateslug()
    {
        $this->slug = Str::slug($this->name);
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

    public function storeCategory()
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

        if($this->category_id){
            $sub_category = new Subcategory();
            $sub_category->name = $this->name;
            $sub_category->slug = $this->slug;
            $sub_category->category_id = $this->category_id;
            $sub_category->save();
        }else{
            $category = new Category();
            $category->name = $this->name;
            $category->slug = $this->slug;
            $category->save();
        }

        session()->flash('message', 'Category has been created successfully!');

        $category = $this->reset();
    }

    public function render()
    {   
        $categories = Category::all();
        return view('livewire.admin.admin-add-category-component',[
            'categories' => $categories
        ])->layout('layouts.base');
    }
}

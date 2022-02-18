<div>
    <style>
        nav svg{
            height: 20px;
        }

        nav .hidden{
            display: block!important;
        }

        .sclist{
            list-style: none;
            padding: 0;
        }

        .sclist li{
            line-height: 33px;
            border-bottom: 1px solid #ccc;
        }

        .slink{
            font-size: 16px;
            margin-left: 8px;
        }
    </style>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                                All Categories
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('admin.addcategory')}}" class="btn btn-success pull-right">Add New Category</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if(Session::has('message'))
                            <div class="alert alert-success" role = 'alert'>{{Session::get('message')}}</div>
                        @endif
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category Name</th>
                                    <th>Slug</th>
                                    <th>Sub category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $cate)
                                <tr>
                                    <td>{{$cate->id}}</td>
                                    <td>{{$cate->name}}</td>
                                    <td>{{$cate->slug}}</td>
                                    <td style="text-align: left;">
                                        <ul class="sclist">
                                            @foreach($cate->subCategories as $s_cate)
                                                <li>
                                                    <i class="fa fa-caret-light"></i>
                                                    {{$s_cate->name}}
                                                    <a href="{{route('admin.editcategory', [
                                                        'category_slug' => $cate->slug,
                                                        'scategory_slug' => $s_cate->slug
                                                    ])}}"
                                                        class="slink" 
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="#" 
                                                        onclick="confirm('Confirm delete?') || event.stopImmediatePropagation()" 
                                                        wire:click.prevent = "deleteSubcategory({{$s_cate->id}})"
                                                        style="margin-left: 8px;"
                                                        class="slink"  
                                                    >
                                                        <i class="fa fa-times text-danger"></i>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>   
                                    <td>
                                        <a href="{{route('admin.editcategory', ['category_slug' => $cate->slug])}}" class="">
                                            <i class="fa fa-edit fa-2x"></i>
                                        </a>
                                        <a href="#" 
                                            onclick="confirm('Confirm delete?') || event.stopImmediatePropagation()" 
                                            wire:click.prevent="deleteCategory({{$cate->id}})"
                                            style="margin-left: 8px;" 
                                        >
                                            <i class="fa fa-times fa-2x text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$categories->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

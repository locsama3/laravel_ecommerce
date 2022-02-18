<div>
    <style>
        nav svg{
            height: 20px;
        }

        nav .hidden{
            display: block!important;
        }

        table tbody td{
            vertical-align: middle!important;
        }
    </style>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                                All Products
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('admin.addproduct')}}" class="btn btn-success pull-right">Add New Product</a>
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
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Sale Price</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $prod)
                                <tr>
                                    <td>{{$prod->id}}</td>
                                    <td>
                                        <img src="{{asset('assets/images/products')}}/{{$prod->image}}" alt="{{$prod->name}}" width = "60"/>
                                    </td>
                                    <td>{{$prod->name}}</td>
                                    <td>{{$prod->stock_status}}</td>
                                    <td>{{$prod->regular_price}}</td>
                                    <td>{{$prod->sale_price > 0 ? $prod->sale_price : "No sale"}}</td>
                                    <td>{{$prod->category->name}}</td>
                                    <td>{{$prod->created_at}}</td>
                                    <td>
                                        <a href="{{route('admin.editproduct', ['product_slug' => $prod->slug])}}" class="">
                                            <i class="fa fa-edit fa-2x"></i>
                                        </a>
                                        <a href="#" 
                                            onclick="confirm('Confirm delete?') || event.stopImmediatePropagation()" 
                                            wire:click.prevent="deleteProduct({{$prod->id}})"
                                            style="margin-left: 8px;" 
                                        >
                                            <i class="fa fa-times fa-2x text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$products->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

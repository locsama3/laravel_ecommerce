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

        /* Dropdown Button */
        /* Dropdown button on hover & focus */
        .dropbtn:hover, .dropbtn:focus {
          background-color: #2980B9;
        }

        /* The container <div> - needed to position the dropdown content */
        .dropdown {
          position: relative;
          display: inline-block;
        }

        /* Dropdown Content (Hidden by Default) */
        .dropdown-content {
          display: none;
          position: absolute;
          background-color: #f1f1f1;
          min-width: 160px;
          box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
          z-index: 1;
        }

        /* Links inside the dropdown */
        .dropdown-content a {
          color: black;
          padding: 12px 16px;
          text-decoration: none;
          display: block;
        }

        /* Change color of dropdown links on hover */
        .dropdown-content a:hover {background-color: #ddd}

        /* Show the dropdown menu (use JS to add this class to the .dropdown-content container when the user clicks on the dropdown button) */
        .show {display:block;}
    </style>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        All orders
                    </div>
                    <div class="panel-body">
                        @if(Session::has('order_message'))
                            <div class="alert alert-success" role="alert">
                                {{Session::get('order_message')}}
                            </div>
                        @endif
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Subtotal</th>
                                    <th>Discount</th>
                                    <th>Tax</th>
                                    <th>Total</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Zipcode</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th colspan="2" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>{{$order->id}}</td>
                                    <td>${{$order->subtotal}}</td>
                                    <td>${{$order->discount}}</td>
                                    <td>${{$order->tax}}</td>
                                    <td>${{$order->total}}</td>
                                    <td>{{$order->first_name . ' ' . $order->last_name}}</td>
                                    <td>{{$order->mobile}}</td>
                                    <td>{{$order->email}}</td>
                                    <td>{{$order->zipcode}}</td>
                                    <td>{{$order->status}}</td>
                                    <td>{{$order->created_at}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button onclick="myFunction()" class="dropbtn btn btn-success btn-sm">
                                                Status
                                                <span class="caret"></span>
                                            </button>
                                          <div id="myDropdown" class="dropdown-content">
                                            <a href="#" class="dropdown-item"
                                                wire:click.prevent = "updateOrderStatus({{$order->id}}, 'delivered')"
                                            >
                                                Delivered
                                            </a>
                                            <a href="#" class="dropdown-item"
                                                wire:click.prevent = "updateOrderStatus({{$order->id}}, 'canceled')"
                                            >
                                                Canceled
                                            </a>
                                          </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{route('admin.orderdetails', ['order_id' => $order->id])}}" class="btn btn-info btn-sm">Details</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$orders->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        /* When the user clicks on the button,
        toggle between hiding and showing the dropdown content */
        function myFunction() {
          document.getElementById("myDropdown").classList.toggle("show");
        }

        // Close the dropdown menu if the user clicks outside of it
        window.onclick = function(event) {
          if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
              var openDropdown = dropdowns[i];
              if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
              }
            }
          }
        }
    </script>
@endpush
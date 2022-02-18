<div>
    <style>
        nav svg{
            height: 20px;
        }

        nav .hidden{
            display: block!important;
        }
    </style>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                                Contact Messages
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
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Content</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($contacts as $contact)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$contact->name}}</td>
                                    <td>{{$contact->email}}</td>
                                    <td>{{$contact->phone}}</td>
                                    <td>{{$contact->content}}</td>
                                    <td>{{$contact->created_at}}</td>
                                    <td>
                                        <a href="" class="">
                                            <i class="fa fa-edit fa-2x"></i>
                                        </a>
                                        <a href="#" 
                                            onclick="confirm('Confirm delete?') || event.stopImmediatePropagation()" 
                                            wire:click.prevent=""
                                            style="margin-left: 8px;" 
                                        >
                                            <i class="fa fa-times fa-2x text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$contacts->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

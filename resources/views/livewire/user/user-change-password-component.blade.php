<div>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Change Password
                    </div>
                    <div class="panel-body">
                        @if(Session::has('success_message'))
                            <div class="alert alert-success" role = 'alert'>
                                {{Session::get('success_message')}}
                            </div>
                        @endif
                        @if(Session::has('error_message'))
                            <div class="alert alert-danger" role = 'alert'>
                                {{Session::get('error_message')}}
                            </div>
                        @endif
                        <form class="form-horizontal" wire:submit.prevent = "changePassword">
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Current Password
                                </label>
                                <div class="col-md-4">
                                    <input type="password" placeholder="Current Password" class="form-control input-md" name="current_password"
                                        wire:model = 'current_password'
                                    >
                                    @error('current_password') 
                                        <p class="text-danger" style="margin-top: 6px">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    New Password
                                </label>
                                <div class="col-md-4">
                                    <input type="password" placeholder="New Password" class="form-control input-md" name="new_password"
                                        wire:model = 'new_password'
                                    >
                                    @error('new_password') 
                                        <p class="text-danger" style="margin-top: 6px">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Confirm Password
                                </label>
                                <div class="col-md-4">
                                    <input type="password" placeholder="Confirm Password" class="form-control input-md" name="password_confirmation"
                                        wire:model = 'password_confirmation'
                                    >
                                    @error('password_confirmation') 
                                        <p class="text-danger" style="margin-top: 6px">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label"></label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

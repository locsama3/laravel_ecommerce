<x-base-layout>
    <!--main area-->
    <main id="main" class="main-site left-sidebar">

        <div class="container">

            <div class="wrap-breadcrumb">
                <ul>
                    <li class="item-link"><a href="/" class="link">Trang chủ</a></li>
                    <li class="item-link"><span>Cấp lại mật khẩu</span></li>
                </ul>
            </div>
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12 col-md-offset-3">
                    <div class=" main-content-area">
                        <div class="wrap-login-item ">                      
                            <div class="login-form form-item form-stl">
                                <x-jet-validation-errors class="mb-4" />
                                <form name="frm-login" method="post" action="{{ route('password.update') }}">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                    <fieldset class="wrap-title">
                                        <h3 class="form-title">Tạo lại mật khẩu</h3>                                      
                                    </fieldset>
                                    <fieldset class="wrap-input">
                                        <label for="frm-login-uname">Email:</label>
                                        <input type="email" id="frm-login-uname" name="email" placeholder="Nhập địa chỉ email" :value="{{$request->email}}" required autofocus>
                                    </fieldset>
                                    <fieldset class="wrap-input item-width-in-half left-item ">
                                        <label for="password">Mật khẩu *</label>
                                        <input type="password" id="password" name="password" placeholder="**********" required autocomplete="new-password">
                                    </fieldset>
                                    <fieldset class="wrap-input item-width-in-half ">
                                        <label for="password_confirmation">Xác nhận mật khẩu *</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu" required autocomplete="new-password">
                                    </fieldset>

                                    <input type="submit" class="btn btn-submit" value="Lưu mật khẩu" name="submit">
                                </form>
                            </div>                                              
                        </div>
                    </div><!--end main products area-->     
                </div>
            </div><!--end row-->

        </div><!--end container-->

    </main>
    <!--main area-->
</x-base-layout>

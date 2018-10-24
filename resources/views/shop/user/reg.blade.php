@extends("shop.layouts.login")
@section("title","注册")
@section("form")
    <form action="" method="post">
        {{csrf_field()}}
        <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="用户名" name="name" value="{{old("name")}}">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Email" name="email" value="{{old("email")}}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="密码" name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="确认密码" name="password_confirmation">
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
        </div>
        <div class="row">

            <!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">注册</button>
            </div>
            <div class="col-xs-8"><a href="{{route("shop.user.login")}}" class="pull-right">已有账号</a></div>
            <!-- /.col -->
        </div>
    </form>


@endsection
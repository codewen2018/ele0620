@extends("admin.layouts.main")

@section("content")
    <a href="" class="btn btn-primary">添加</a>
    <table class="table table-bordered">
        <tr>
            <th>Id</th>
            <th>用户名</th>
            <th>Email</th>
            <th>店铺</th>

            <th>操作</th>
        </tr>
        @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>@if($user->shop) {{$user->shop->shop_name}} @endif</td>

                <td>

                    <a href="#" class="btn btn-info">编辑</a>
                    <a href="" class="btn btn-danger">删除</a>
                    @if(!$user->shop) <a href="{{route('admin.shop.add',[$user->id])}}" class="btn btn-success">添加店铺</a> @endif

                </td>
            </tr>
        @endforeach
    </table>

@endsection
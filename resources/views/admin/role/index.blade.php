@extends("admin.layouts.main")

@section("content")
    <a href="{{route('role.add')}}" class="btn btn-primary">添加</a>
    <table class="table table-bordered">
        <tr>
            <th>Id</th>
            <th>角色名</th>
            <th>拥有权限</th>

            <th>操作</th>
        </tr>
        @foreach($roles as $role)
            <tr>
                <td>{{$role->id}}</td>
                <td>{{$role->name}}</td>
                <td>{{str_replace(["[","]",'"'],"",json_encode($role->permissions()->pluck('intro'),JSON_UNESCAPED_UNICODE))}}</td>



                <td>
                    <a href="{{route('role.edit',$role->id)}}" class="btn btn-info">编辑</a>

                    <a href="{{route('role.del',$role->id)}}" class="btn btn-danger">删除</a>

                </td>
            </tr>
        @endforeach
    </table>

@endsection
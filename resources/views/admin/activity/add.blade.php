@extends("admin.layouts.main")
@section("css")
    @include('vendor.ueditor.assets')
@endsection
@section("content")
    <form class="form-horizontal" action="" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label class="col-sm-2 control-label">标题</label>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control" value="{{old('title')}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">内容</label>
            <div class="col-sm-10">

                <!-- 编辑器容器 -->
                <script id="container" name="content" type="text/plain">


                    {!!$act->content!!}
                </script>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">开始时间</label>
            <div class="col-sm-10">
                <input type="datetime-local" name="start_time" class="form-control" value="{{old('start_time')}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">结束时间</label>
            <div class="col-sm-10">
                <input type="datetime-local" name="end_time" class="form-control" value="{{old('end_time')}}">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">提交</button>
            </div>
        </div>
    </form>
@endsection

@section("js")
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container');

        ue.ready(function () {
            ue.setHeight(400);//设置高度
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });
    </script>
@stop
@extends("shop.layouts.main")
@section("content")
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">添加菜品</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="post" action="" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="box-body">
                <div class="form-group">
                    <select name="cate_id" class="form-control">
                        @foreach($cates as $cate)
                            <option value="{{$cate->id}}">{{$cate->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">名称</label>
                    <input type="text" class="form-control" id="name" placeholder="分类名称" name="goods_name"
                           value="{{old('goods_name')}}">
                </div>
                <div class="form-group">
                    <label for="goods_price">价格</label>
                    <input type="text" class="form-control" id="goods_price" placeholder="菜品编号"
                           name="goods_price" value="{{old('goods_price')}}">
                </div>

                <div class="form-group">
                    <label for="description">描述</label>
                    <textarea class="form-control" name="description">
{{old('description')}}
                    </textarea>

                </div>


                <div class="form-group">
                    <label for="description">LOGO</label>
                    <input type="file" name="goods_img" id="goods_img">

                </div>





            </div>

            <div class="form-group">
                <label for="tips">简介</label>
                <input type="text" class="form-control" id="tips" placeholder="简介"
                       name="tips" value="{{old('tips')}}">
            </div>

            <div class="form-group">
                <label for="rating">评分</label>
                <input type="text" class="form-control" id="rating" placeholder="评分"
                       name="rating" value="{{old('rating')}}">
            </div>

            <div class="form-group">
                <label for="month_sales">月销量</label>
                <input type="text" class="form-control" id="month_sales" placeholder="月销量"
                       name="month_sales" value="{{old('month_sales')}}">
            </div>

            <div class="form-group">
                <label for="satisfy_rate">满意度评分</label>
                <input type="text" class="form-control" id="satisfy_rate" placeholder="满意度评分"
                       name="satisfy_rate" value="{{old('satisfy_rate')}}">
            </div>
            <div class="form-group">
                <label for="satisfy_count">满意度数量</label>
                <input type="text" class="form-control" id="satisfy_count" placeholder="满意度数量"
                       name="satisfy_count" value="{{old('satisfy_count')}}">
            </div>
            <div class="form-group">
                <label for="satisfy_count">是否上架</label>

                <input type="radio" name="status" checked value="1">是
                <input type="radio" name="status" value="0">否

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
            </div>

        </form>
    </div>

@endsection


@extends("shop.layouts.main")
@section("title","申请店铺")

@section("content")
    <div class="box box-primary">
       {{-- <div class="box-header with-border">
            <h3 class="box-title">Quick Example</h3>
        </div>--}}
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="post" action="" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="box-body">

                <div class="form-group">
                    <label for="name">店铺名称：</label>
                    <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name') }}">
                </div>
                <div class="form-group">
                    <label>店铺分类：</label>
                    <select name="shop_cate_id" class="form-control">

                        @foreach($cates as $cate)
                            <option value="{{$cate->id}}">{{$cate->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="shop_img">店铺图片：</label>
                    <input type="file" name="shop_img">
                </div>
                <div class="form-group">
                    <label for="start_send">起送金额：</label>
                    <input type="number" name="start_send" class="form-control" value="{{ old('start_send') }}">
                </div>
                <div class="form-group">
                    <label for="send_cost">配送费：</label>
                    <input type="number" name="send_cost" class="form-control" value="{{ old('send_cost') }}">
                </div>

                <div class="form-group">
                    <label for="notice">店铺公告：</label>
                    <textarea name="notice" class="form-control">{{ old('notice') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="discount">优惠信息：</label>
                    <textarea name="discount" class="form-control">{{ old('discount') }}</textarea>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="brand" value="1" @if(old('brand')==1) checked @endif> 品牌连锁店
                        </label>


                        <label>
                            <input type="checkbox" name="on_time" value="1" @if(old('on_time')==1) checked @endif> 准时送达
                        </label>

                        <label>
                            <input type="checkbox" name="fengniao" value="1" @if(old('fengniao')==1) checked @endif> 蜂鸟配送
                        </label>

                        <label>
                            <input type="checkbox" name="bao" value="1" @if(old('bao')==1) checked @endif> 保
                        </label>

                        <label>
                            <input type="checkbox" name="piao" value="1" @if(old('piao')==1) checked @endif> 票
                        </label>

                        <label>
                            <input type="checkbox" name="zhun" value="1" @if(old('zhun')==1) checked @endif> 准
                        </label>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
            </div>
        </form>
    </div>

@endsection
# ELE点餐平台

## 项目介绍

整个系统分为三个不同的网站，分别是 

- 平台：网站管理者 
- 商户：入住平台的餐馆 
- 用户：订餐的用户

## Day01

### 开发任务

#### 平台端 

- 商家分类管理 
- 商家管理 
- 商家审核

#### 商户端 

- 商家注册

#### 要求 

- 商家注册时，同步填写商家信息，商家账号和密码 
- 商家注册后，需要平台审核通过，账号才能使用 
- 平台可以直接添加商家信息和账户，默认已审核通过

### 实现步骤

1. composer create-project --prefer-dist laravel/laravel ele0620 "5.5.*" -vvv

2. 配置虚拟主机 设置三个域名  设置hosts,并重启

   ```
   <VirtualHost *:80>
       DocumentRoot "D:\web\ele0620\public"
       ServerName www.ele.com
       ServerAlias shop.ele.com admin.ele.com
     <Directory "D:\web\ele0620\public">
         Options Indexes  FollowSymLinks ExecCGI
         # 开启分布式配置文件
         AllowOverride All
         Order allow,deny
         Allow from all
         Require all granted
     </Directory>
   </VirtualHost>
   ```

3. 建立数据库 ele0620

4. 修改配置文件.env

5. 语言包，设置中文语言

6. 布局模板，分admin和shop两个目录，分别在其目录下复制layouts

7. 数据迁移

   修改app/Providers/AppServiceProvider.php 文件

   ```php
   <?php
   
   namespace App\Providers;
   
   use Illuminate\Support\Facades\Schema;
   use Illuminate\Support\ServiceProvider;
   
   class AppServiceProvider extends ServiceProvider
   {
       /**
        * Bootstrap any application services.
        *
        * @return void
        */
       public function boot()
       {
           //
           Schema::defaultStringLength(191);
   
       }
   
       /**
        * Register any application services.
        *
        * @return void
        */
       public function register()
       {
           //
       }
   }
   
   ```

   执行数据迁移

   ```sh
   php artisan migrate
   ```

   >需要修改Users表，添加shop_id字段

8. 分析数据表

9. 建立模型

   ```sh
   php artisan make:model Models/ShopCategory -m
   ```

   ```php
   <?php
   
   use Illuminate\Support\Facades\Schema;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Database\Migrations\Migration;
   
   class CreateShopCategoriesTable extends Migration
   {
       /**
        * Run the migrations.
        *
        * @return void
        */
       public function up()
       {
           Schema::create('shop_categories', function (Blueprint $table) {
               $table->increments('id');
   
               $table->string("name")->unique()->comment("名称");
               $table->string("img")->comment("图片");
               $table->boolean("status")->default(1)->comment("状态：1 显示 0 隐藏");
               $table->integer("sort")->default(100)->comment("排序");
   
   
               $table->timestamps();
           });
       }
   
       /**
        * Reverse the migrations.
        *
        * @return void
        */
       public function down()
       {
           Schema::dropIfExists('shop_categories');
       }
   }
   
   ```

   ```sh
   php artisan migrate
   ```

10. 建立 控制器

    ```sh
    php artisan make:controller Admin/ShopCategoryController
    ```

11. 建立视图

    resources/views/admin/shop_category/index.blade.php

    ```php
    @extends("admin.layouts.main")
    @section("title","商家分类列表")
    @section("content")
    
    aaa
    
    @endsection
    
    ```

12. 路由

    ```php
    
    Route::domain("admin.ele.com")->namespace("Admin")->group(function (){
    
    
        //商户分类
        Route::get("shopCate/index","ShopCategoryController@index")->name("admin.shopCate.index");
    
    
    
    
    });
    ```

13. 再生成基础控制器

    ```sh
    php artisan make:controller Admin/BaseController
    ```

    > 所有控制器继承BaseController



### 数据表设计

#### 商家分类表shop_categories

| 字段名称 | 类型    | 备注               |
| -------- | ------- | ------------------ |
| id       | primary | 主键               |
| name     | string  | 分类名称           |
| img      | string  | 分类图片           |
| status   | int     | 状态：1显示，0隐藏 |

#### 商家信息表shops

| 字段名称         | 类型    | 备注                      |
| ---------------- | ------- | ------------------------- |
| id               | primary | 主键                      |
| shop_category_id | int     | 店铺分类ID                |
| shop_name        | string  | 名称                      |
| shop_img         | string  | 店铺图片                  |
| shop_rating      | float   | 评分                      |
| brand            | boolean | 是否是品牌                |
| on_time          | boolean | 是否准时送达              |
| fengniao         | boolean | 是否蜂鸟配送              |
| bao              | boolean | 是否保标记                |
| piao             | boolean | 是否票标记                |
| zhun             | boolean | 是否准标记                |
| start_send       | float   | 起送金额                  |
| send_cost        | float   | 配送费                    |
| notice           | string  | 店公告                    |
| discount         | string  | 优惠信息                  |
| status           | int     | 状态:1正常,0待审核,-1禁用 |
| user_id          | int     | 用户Id                    |

#### 商家账号表users

| 字段名称       | 类型    | 备注 |
| -------------- | ------- | ---- |
| id             | primary | 主键 |
| name           | string  | 名称 |
| email          | email   | 邮箱 |
| password       | string  | 密码 |
| remember_token | string  | toke |

#### 平台管理员表admins

| 字段名称       | 类型    | 备注  |
| -------------- | ------- | ----- |
| id             | primary | 主键  |
| name           | string  | 名称  |
| email          | string  | 邮箱  |
| password       | string  | 密码  |
| remember_token | string  | token |

### 要点难点及解决方案





## Day02

### 开发任务

- 完善day1的功能，要用事务保证同时删除用户和店铺，删除图片
- 平台：平台管理员账号管理
- 平台：管理员登录和注销功能，修改个人密码(参考微信修改密码功能)
- 平台：商户账号管理，重置商户密码
- 商户端：商户登录和注销功能，修改个人密码
- 修改个人密码需要用到验证密码功能,[参考文档](https://laravel-china.org/docs/laravel/5.5/hashing)
- 商户登录正常登录，登录之后判断店铺状态是否为1，不为1不能做任何操作

### 实现步骤

1. 在商户端口和平台端都要创建BaseController 以后都要继承自己的BaseController

2. 商户的登录和以前一样

3. 平台的登录，模型中必需继承 use Illuminate\Foundation\Auth\User as Authenticatable

4. 设置配置文件config/auth.php 

   ```php
    'guards' => [
           //添加一个guards
           'admin' => [
               'driver' => 'session',
               'provider' => 'admins',//数据提示者
           ],
   
          
       ],
    'providers' => [
        //提供商户登录
           'users' => [
               'driver' => 'eloquent',
               'model' => \App\Models\User::class,
           ],
        //提供平台登录
           'admins' => [
               'driver' => 'eloquent',
               'model' => \App\Models\Admin::class,
           ],
       ],
   ```

5. 平台登录的时候

   ```php
   Auth::guard('admin')->attempt(['name'=>$request->post('name'),'password'=>$request->password])
   ```

6. 平台AUTH权限判断

   ```php
   public function __construct()
       {
           $this->middleware('auth:admin')->except("login");
       }
   
   ```

7. 设置认证失败后回跳地址 在Exceptions/Handler.php后面添加

   ```php
   /**
        * 重写实现未认证用户跳转至相应登陆页
        * @param \Illuminate\Http\Request $request
        * @param AuthenticationException $exception
        * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
        */
       protected function unauthenticated($request, AuthenticationException $exception)
       {
   
           //return $request->expectsJson()
           //            ? response()->json(['message' => $exception->getMessage()], 401)
           //            : redirect()->guest(route('login'));
           if ($request->expectsJson()) {
               return response()->json(['message' => $exception->getMessage()], 401);
           } else {
               return in_array('admin', $exception->guards()) ? redirect()->guest('/admin/login') : redirect()->guest(route('user.login'));
           }
       }
   ```

## DAY03

### 开发任务

#### 商户端 

- 菜品分类管理 
- 菜品管理 

#### 要求

- 一个商户只能有且仅有一个默认菜品分类 
- 只能删除空菜品分类 
- 必须登录才能管理商户后台（使用中间件实现） 
- 可以按菜品分类显示该分类下的菜品列表 
- 可以根据条件（按菜品名称和价格区间）搜索菜品





### 数据表设计



#### 菜品分类表 menu_categories

| 字段名称          | 类型    | 备注                    |
| ----------------- | ------- | ----------------------- |
| id                | primary | 主键                    |
| name              | string  | 名称                    |
| type_accumulation | string  | 菜品编号（a-z前端使用） |
| shop_id           | int     | 所属商家ID              |
| description       | string  | 描述                    |
| is_selected       | string  | 是否是默认分类          |

#### 菜品表 menus

| 字段名称      | 类型    | 备注               |
| ------------- | ------- | ------------------ |
| id            | primary | 主键               |
| goods_name    | string  | 名称               |
| rating        | float   | 评分               |
| shop_id       | int     | 所属商家ID         |
| category_id   | int     | 所属分类ID         |
| goods_price   | float   | 价格               |
| description   | string  | 描述               |
| month_sales   | int     | 月销量             |
| rating_count  | int     | 评分数量           |
| tips          | string  | 提示信息           |
| satisfy_count | int     | 满意度数量         |
| satisfy_rate  | float   | 满意度评分         |
| goods_img     | string  | 商品图片           |
| status        | int     | 状态：1上架，0下架 |

### 实现步骤

### 要点难点

## Day04

### 开发任务

优化 
\- 将网站图片上传到阿里云OSS对象存储服务，以减轻服务器压力(<https://github.com/jacobcyl/Aliyun-oss-storage>) 
\- 使用webuploder图片上传插件，提升用户上传图片体验

平台 
\- 平台活动管理（活动列表可按条件筛选 未开始/进行中/已结束 的活动） 
\- 活动内容使用ueditor内容编辑器(<https://github.com/overtrue/laravel-ueditor>)

商户端 
\- 查看平台活动（活动列表和活动详情） 
\- 活动列表不显示已结束的活动

### 数据表设计

#### 活动表

| 字段名称   | 类型     | 备注         |
| ---------- | -------- | ------------ |
| id         | primary  | 主键         |
| title      | string   | 活动名称     |
| content    | text     | 活动详情     |
| start_time | datetime | 活动开始时间 |
| end_time   | datetime | 活动结束时间 |

## 实现步骤

### 阿里云OSS

1. 登录阿里云网站

2. 开通oss(实名认证之后申请半年免费)

3. 进入控制器 OSS操作面板

4. 新建 bucket   取名   域名   标准存储  公共读

5. 点击用户图像---》accesskeys--->继续使用accsskeys--->添加accesskeys--->拿到access_id和access_key

6. 执行 命令 安装 ali-oss插件

   ```sh
   composer require jacobcyl/ali-oss-storage -vvv
   ```

7. 修改 app/filesystems.php  添加如何代码

   ```php
   <?php
   
   return [
   
       ...此处省略N个代码
       'disks' => [
   
         
           'oss' => [
                   'driver'        => 'oss',
                   'access_id'     => 'LTAI8lXAo9nl2dn1',//账号
                   'access_key'    => 'hhSp1VESrBp7vruWjOKFIVSOe2Ugyb',//密钥
                   'bucket'        => 'ele0620',//空间名称
                   'endpoint'      => 'oss-cn-shenzhen.aliyuncs.com', // OSS 外网节点或自定义外部域名
   
       ],
      
       ],
   
   ];
   ```

8. 修改 .env配置文件  设置文件上传驱动为oss

   ```ini
   FILESYSTEM_DRIVER=oss
   ALIYUN_OSS_URL=http://ele0620.oss-cn-shenzhen.aliyuncs.com/    
   ALIYUNU_ACCESS_ID=LTAI8lXAo9nl2dn1
   ALIYUNU_ACCESS_KEY=hhSp1VESrBp7vruWjOKFIVSOe2Ugyb
   ALIYUNU_OSS_BUCKET=ele0620
   ALIYUNU_OSS_ENDPOINT=oss-cn-shenzhen.aliyuncs.com
   ```

9. 获取图片 及 缩略图

   ```php
    <td><img src="{{env("ALIYUN_OSS_URL").$menu->goods_img}}?x-oss-process=image/resize,m_fill,w_80,h_80"></td>
   ```



### webuploader

1. 下载 https://github.com/fex-team/webuploader/releases/download/0.1.5/webuploader-0.1.5.zip  解压

2. 得到目录到 public 目录

3. 分别引用CSS和JS 修改 layouts里main模板

   ```php
   <!--引入CSS-->
       <link rel="stylesheet" type="text/css" href="/webuploader/webuploader.css">
    <body>
           
       ....省略
       <!--引入JS-->
   <script type="text/javascript" src="/webuploader/webuploader.js"></script>
   @yield("js")
   </body>
   </html>
   
   ```

4. 视图中添加

   html部分

   ```php
     <div class="form-group">
                       <label>图像</label>
   
                       <input type="hidden" name="logo" value="" id="logo">
                       <!--dom结构部分-->
                       <div id="uploader-demo">
                           <!--用来存放item-->
                           <div id="fileList" class="uploader-list"></div>
                           <div id="filePicker">选择图片</div>
                       </div>
   
   
                   </div>
   ```

   js部分

   ```js
   @section("js")
       <script>
           // 图片上传demo
           jQuery(function () {
               var $ = jQuery,
                   $list = $('#fileList'),
                   // 优化retina, 在retina下这个值是2
                   ratio = window.devicePixelRatio || 1,
   
                   // 缩略图大小
                   thumbnailWidth = 100 * ratio,
                   thumbnailHeight = 100 * ratio,
   
                   // Web Uploader实例
                   uploader;
   
               // 初始化Web Uploader
               uploader = WebUploader.create({
   
                   // 自动上传。
                   auto: true,
   
                   formData: {
                       // 这里的token是外部生成的长期有效的，如果把token写死，是可以上传的。
                       _token:'{{csrf_token()}}'
                   },
   
   
                   // swf文件路径
                   swf: '/webuploader/Uploader.swf',
   
                   // 文件接收服务端。
                   server: '{{route("menu_cate.upload")}}',
   
                   // 选择文件的按钮。可选。
                   // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                   pick: '#filePicker',
   
                   // 只允许选择文件，可选。
                   accept: {
                       title: 'Images',
                       extensions: 'gif,jpg,jpeg,bmp,png',
                       mimeTypes: 'image/*'
                   }
               });
   
               // 当有文件添加进来的时候
               uploader.on('fileQueued', function (file) {
                   var $li = $(
                       '<div id="' + file.id + '" class="file-item thumbnail">' +
                       '<img>' +
                       '<div class="info">' + file.name + '</div>' +
                       '</div>'
                       ),
                       $img = $li.find('img');
   
                   $list.html($li);
   
                   // 创建缩略图
                   uploader.makeThumb(file, function (error, src) {
                       if (error) {
                           $img.replaceWith('<span>不能预览</span>');
                           return;
                       }
   
                       $img.attr('src', src);
                   }, thumbnailWidth, thumbnailHeight);
               });
   
               // 文件上传过程中创建进度条实时显示。
               uploader.on('uploadProgress', function (file, percentage) {
                   var $li = $('#' + file.id),
                       $percent = $li.find('.progress span');
   
                   // 避免重复创建
                   if (!$percent.length) {
                       $percent = $('<p class="progress"><span></span></p>')
                           .appendTo($li)
                           .find('span');
                   }
   
                   $percent.css('width', percentage * 100 + '%');
               });
   
               // 文件上传成功，给item添加成功class, 用样式标记上传成功。
               uploader.on('uploadSuccess', function (file,data) {
                   $('#' + file.id).addClass('upload-state-done');
   
                   $("#logo").val(data.url);
               });
   
               // 文件上传失败，现实上传出错。
               uploader.on('uploadError', function (file) {
                   var $li = $('#' + file.id),
                       $error = $li.find('div.error');
   
                   // 避免重复创建
                   if (!$error.length) {
                       $error = $('<div class="error"></div>').appendTo($li);
                   }
   
                   $error.text('上传失败');
               });
   
               // 完成上传完了，成功或者失败，先删除进度条。
               uploader.on('uploadComplete', function (file) {
                   $('#' + file.id).find('.progress').remove();
               });
           });
       </script>
   @stop
   ```

5. 创建 路由 和方法 用来上传图片

   ```php
    public function upload(Request $request)
       {
           //处理上传
   
           //dd($request->file("file"));
   
           $file=$request->file("file");
   
   
           if ($file){
               //上传
   
               $url=$file->store("menu_cate");
   
              /// var_dump($url);
               //得到真实地址  加 http的址
               $url=Storage::url($url);
   
               $data['url']=$url;
   
               return $data;
               ///var_dump($url);
           }
   
       }
   ```

6. 最后添加 CSS样式

   ```css
   #picker {
       display: inline-block;
       line-height: 1.428571429;
       vertical-align: middle;
       margin: 0 12px 0 0;
   }
   #picker .webuploader-pick {
       padding: 6px 12px;
       display: block;
   }
   
   
   #uploader-demo .thumbnail {
       width: 110px;
       height: 110px;
   }
   #uploader-demo .thumbnail img {
       width: 100%;
   }
   .uploader-list {
       width: 100%;
       overflow: hidden;
   }
   .file-item {
       float: left;
       position: relative;
       margin: 0 20px 20px 0;
       padding: 4px;
   }
   .file-item .error {
       position: absolute;
       top: 4px;
       left: 4px;
       right: 4px;
       background: red;
       color: white;
       text-align: center;
       height: 20px;
       font-size: 14px;
       line-height: 23px;
   }
   .file-item .info {
       position: absolute;
       left: 4px;
       bottom: 4px;
       right: 4px;
       height: 20px;
       line-height: 20px;
       text-indent: 5px;
       background: rgba(0, 0, 0, 0.6);
       color: white;
       overflow: hidden;
       white-space: nowrap;
       text-overflow : ellipsis;
       font-size: 12px;
       z-index: 10;
   }
   .upload-state-done:after {
       content:"\f00c";
       font-family: FontAwesome;
       font-style: normal;
       font-weight: normal;
       line-height: 1;
       -webkit-font-smoothing: antialiased;
       -moz-osx-font-smoothing: grayscale;
       font-size: 32px;
       position: absolute;
       bottom: 0;
       right: 4px;
       color: #4cae4c;
       z-index: 99;
   }
   .file-item .progress {
       position: absolute;
       right: 4px;
       bottom: 4px;
       height: 3px;
       left: 4px;
       height: 4px;
       overflow: hidden;
       z-index: 15;
       margin:0;
       padding: 0;
       border-radius: 0;
       background: transparent;
   }
   .file-item .progress span {
       display: block;
       overflow: hidden;
       width: 0;
       height: 100%;
       background: #d14 url(../images/progress.png) repeat-x;
       -webit-transition: width 200ms linear;
       -moz-transition: width 200ms linear;
       -o-transition: width 200ms linear;
       -ms-transition: width 200ms linear;
       transition: width 200ms linear;
       -webkit-animation: progressmove 2s linear infinite;
       -moz-animation: progressmove 2s linear infinite;
       -o-animation: progressmove 2s linear infinite;
       -ms-animation: progressmove 2s linear infinite;
       animation: progressmove 2s linear infinite;
       -webkit-transform: translateZ(0);
   }
   @-webkit-keyframes progressmove {
       0% {
           background-position: 0 0;
       }
       100% {
           background-position: 17px 0;
       }
   }
   @-moz-keyframes progressmove {
       0% {
           background-position: 0 0;
       }
       100% {
           background-position: 17px 0;
       }
   }
   @keyframes progressmove {
       0% {
           background-position: 0 0;
       }
       100% {
           background-position: 17px 0;
       }
   }
   
   a.travis {
     position: relative;
     top: -4px;
     right: 15px;
   }
   ```

### Ueditor

1. 下载
2. 配置
3. 发布配置文件
4. 引入
5. 使用

# Day05

## 开发任务

接口开发 

- 商家列表接口(支持商家搜索) 
- 获取指定商家接口

## 实现步骤





# Day06

## 开发任务

接口开发 

- 用户注册 
- 用户登录 
- 忘记密码
- 发送短信 
  要求 
- 创建会员表 
- 短信验证码发送成功后,保存到redis,并设置有效期5分钟 
- 用户注册时,从redis取出验证码进行验证

## 实现步骤

#### 1.短信发送

参考 https://packagist.org/packages/mrgoon/aliyun-sms 使用非Laravel框架方法

#### 2.redis使用

参考 https://laravel-china.org/docs/laravel/5.5/redis/1331

#### 3.会员注册实现



# Day07

## 开发任务

接口开发 

- 用户地址管理相关接口 
- 购物车相关接口

## 数据表设计



#### 用户地址表addresses

| 字段名称   | 类型    | 备注           |
| ---------- | ------- | -------------- |
| id         | primary | 主键           |
| user_id    | int     | 用户id         |
| province   | string  | 省             |
| city       | string  | 市             |
| county     | string  | 县             |
| address    | string  | 详细地址       |
| tel        | string  | 收货人电话     |
| name       | string  | 收货人姓名     |
| is_default | int     | 是否是默认地址 |



#### 购物车表carts

| 字段名称 | 类型    | 备注     |
| -------- | ------- | -------- |
| id       | primary | 主键     |
| user_id  | int     | 用户id   |
| goods_id | int     | 商品id   |
| amount   | int     | 商品数量 |

## 实现步骤



# Day08

## 开发任务

接口开发 

- 订单接口(使用事务保证订单和订单商品表同时写入成功) 
- 密码修改和重置密码接口



## 数据表设计



#### 订单表orders

| 字段名称   | 类型     | 备注                                              |
| ---------- | -------- | ------------------------------------------------- |
| id         | primary  | 主键                                              |
| user_id    | int      | 用户ID                                            |
| shop_id    | int      | 商家ID                                            |
| order_code | string   | 订单编号                                          |
| province   | string   | 省                                                |
| city       | string   | 市                                                |
| county     | string   | 县                                                |
| address    | string   | 详细地址                                          |
| tel        | string   | 收货人电话                                        |
| name       | string   | 收货人姓名                                        |
| total      | decimal  | 价格                                              |
| status     | int      | 状态(-1:已取消,0:待支付,1:待发货,2:待确认,3:完成) |
| created_at | datetime | 创建时间                                          |
|            |          |                                                   |

#### 订单商品表order_details

| 字段名称    | 类型    | 备注     |
| ----------- | ------- | -------- |
| id          | primary | 主键     |
| order_id    | int     | 订单id   |
| goods_id    | int     | 商品id   |
| amount      | int     | 商品数量 |
| goods_name  | string  | 商品名称 |
| goods_img   | string  | 商品图片 |
| goods_price | decimal | 商品价格 |

## 实现步骤



# Day09

### 开发任务

商户端 

- 订单管理[订单列表,查看订单,取消订单,发货] 

- 订单量统计[按日统计,按月统计,累计]（每日、每月、总计） 

- 菜品销量统计[按日统计,按月统计,累计]（每日、每月、总计） 

  平台 

- 订单量统计[按商家分别统计和整体统计]（每日、每月、总计） 

- 菜品销量统计[按商家分别统计和整体统计]（每日、每月、总计） 

- 会员管理[会员列表,查询会员,查看会员信息,禁用会员账号]

### 实现步骤

### 要点难点



# Day10

### 开发任务

平台 

- 权限管理 
- 角色管理[添加角色时,给角色关联权限] 
- 管理员管理[添加和修改管理员时,修改管理员的角色]





### 实现步骤

1. 安装 composer require spatie/laravel-permission -vvv

2. 生成数据迁移 php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"

   > 给权限表可以加个 intro 字段

3. 执行数据迁移  php artisan migrate

4. 生成配置文件 php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"

5. Admin模型中

   ```php
   class Admin extends Authenticatable
   {
       use HasRoles;
       protected $guard_name = 'admin'; // 使用任何你想要的守卫
       protected $fillable=['name','password','email'];
   }
   
   ```

6. 添加权限

   ```php
       public function add(Request $request)
       {
   
           if ($request->isMethod("post")){
   
   
               $data=$request->post();
               $data['guard_name']="admin";
               Permission::create($data);
   
   
           }
           return view("admin.per.add");
   
   
       }
   ```

7. 添加角色

   ```php
    //
       public function add(Request $request)
       {
   
           if ($request->isMethod("post")){
   
               //1.接收参数 并处理数据
              $pers=$request->post('pers');
               //2.添加角色
               $role=Role::create([
                   "name"=>$request->post("name"),
                   "guard_name"=>"admin"
               ]);
               //3. 给角色同步权限
               if ($pers){
                   $role->syncPermissions($pers);
               }
   
   
   
   
   
   
           }
   
   
           //得到所有权限
           $pers = Permission::all();
   
   
           return view("admin.role.add",compact("pers"));
   
       }
   ```

8. 给用户指定角色

   ```php
    /**
        * 添加用户
        */
       public function add(Request $request)
       {
           if ($request->isMethod('post')) {
   
   
               // dd($request->post('per'));
               //接收参数
               $data = $request->post();
               $data['password'] = bcrypt($data['password']);
   
   
               //创建用户
               $admin = Admin::create($data);
   
               //给用户添加角色 同步角色
               $admin->syncRoles($request->post('role'));
   
               //通过用户找出所有角色
               // $admin->roles();
   
               //跳转并提示
               return redirect()->route('admin.index')->with('success', '创建' . $admin->name . "成功");
   
   
           }
   
           //得到所有角色
           $roles=Role::all();
           return view('admin.admin.add',compact("roles"));
       }
   ```

9. 判断权限 在E:\web\ele\app\Http\Controllers\Admin\BaseController.php 添加如下代码

   ```php
   <?php
   
   namespace App\Http\Controllers\Admin;
   
   use App\Models\Admin;
   use Illuminate\Http\Request;
   use App\Http\Controllers\Controller;
   use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\Route;
   
   class BaseController extends Controller
   {
       public function __construct()
       {
           //1.添加中间件 auth:admin
           $this->middleware("auth:admin")->except(["login"]);
   
           //2. 有没有权限
   
           $this->middleware(function ($request, \Closure $next){
   
               //如果没有权限  停在这里
               //1. 得到当前访问地址的路由
               $route=Route::currentRouteName();
   
               //2.设置一个白名单
               $allow=[
                   "admin.login",
                   "admin.logout"
               ];
               //2.判断当前登录用户有没有权限
             /*  if (!in_array($route,$allow)){
   
                   if (!Auth::guard("admin")->user()->can($route)){
                       if (Auth::guard("admin")->id()!=1){
                           exit(view("admin.fuck"));
                       }
                   }
   
   
               }*/
   
   
   
             //要保证在白名单 并且 有权限 而且 Id==1
               if (!in_array($route,$allow) &&!Auth::guard("admin")->user()->can($route) && Auth::guard("admin")->id()!=1){
   
               /*  echo view("admin.fuck");
                 exit;*/
                   exit(view("admin.fuck"));
               }
   
             return $next($request);
   
           });
   
       }
   
   }
   ```

10. 创建admin.fuck视图

    ```php
     @extends("layouts.admin.default")
    
      @section("content")
         没有权限
      @endsection
    ```

11. 其它方法

    ```php
       //判断当前角色有没有当前权限
       $role->hasPermissionTo('edit articles');
       //判断当前用户有没有权限
       $admin->hasRole('角色名')
       //取出当前角色所拥有的所有权限
       $role->permissions();
       //取出当前用户所拥有的角色
       $roles = $admin->getRoleNames(); // 返回一个集合
    ```

# Day11

### 开发任务

#### 平台 

- 导航菜单管理 
- 根据权限显示菜单 
- 配置RBAC权限管理 

#### 商家 

- 发送邮件(商家审核通过,以及有订单产生时,给商家发送邮件提醒) 
  用户 
- 下单成功时,给用户发送手机短信提醒



### 实现步骤

##### 邮件发送

1. 配置.env

   ```ini
   MAIL_DRIVER=smtp
   MAIL_HOST=smtp.qq.com
   MAIL_PORT=465
   MAIL_USERNAME=你的邮箱
   MAIL_PASSWORD=你的授权码
   MAIL_ENCRYPTION=ssl
   MAIL_FROM_ADDRESS=你的邮箱
   MAIL_FROM_NAME=发件人名称
   ```

   >端口465是使用了ssl；
   >
   >MAIL_ENCRYPTION不填的话，端口是25；
   >
   >注意MAIL_PASSWORD是授权密码，不是登录密码！

2. 发送邮件

   ```php
   Route::get("test", function () {
   
   
       //$content = 'test';//邮件内容
       $shopName="互联网学院";
       $to = 'wjx@itsource.cn';//收件人
       $subject = $shopName.' 审核通知';//邮件标题
       \Illuminate\Support\Facades\Mail::send(
           'emails.shop',//视图
          compact("shopName"),//传递给视图的参数
           function ($message) use($to, $subject) {
               $message->to($to)->subject($subject);
           }
       );
   
   
   });
   ```

3. 创建对应的视图 resources/views/emails/shop.blade.php

   ```php
   <p>
       你的店铺：{{$shopName}} 已通过审核，请查看
   </p>
   ```


### 数据表设计

#### 导航菜单表 navs

| 字段名称 | 类型    | 备注       |
| -------- | ------- | ---------- |
| id       | primary | 主键       |
| name     | string  | 名称       |
| url      | string  | 地址       |
| sort     | int     | 排序       |
| pid      | int     | 上级菜单id |
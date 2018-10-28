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

# 实现步骤

## 阿里云OSS

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

   ```php
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

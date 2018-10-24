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

#### 商家账号表users

| 字段名称       | 类型    | 备注     |
| -------------- | ------- | -------- |
| id             | primary | 主键     |
| name           | string  | 名称     |
| email          | email   | 邮箱     |
| password       | string  | 密码     |
| remember_token | string  | toke     |
| shop_id        | int     | 所属商家 |

#### 平台管理员表admins

| 字段名称       | 类型    | 备注  |
| -------------- | ------- | ----- |
| id             | primary | 主键  |
| name           | string  | 名称  |
| email          | string  | 邮箱  |
| password       | string  | 密码  |
| remember_token | string  | token |

### 要点难点及解决方案
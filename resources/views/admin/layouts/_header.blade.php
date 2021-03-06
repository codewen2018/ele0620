<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">源码时代</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">首页 <span class="sr-only">(current)</span></a></li>
                <?php
               /* $navs = \App\Models\Nav::where("pid", 0)->get();

                dump($navs->toArray());
                foreach ($navs as $k1 => $v1) {


                    //找出第一个儿子
                    $child = \App\Models\Nav::where("pid", $v1->id)->first();

                    //如果没有儿子，把它父亲干掉
                    if ($child == null) {

                        unset($navs[$k1]);
                    }

                    //判断当前所有儿子都没有权限 也应该干掉
                    $childs=\App\Models\Nav::where("pid",$v1->id)->get();

                    //声明一个变量
                    $ok=0;
                    foreach ($childs as $k2=>$v2){



                        //判断当前儿子有没有权限
                        if (\Illuminate\Support\Facades\Auth::guard("admin")->user()->can($v2->url)){

                            $ok=1;

                        }

                        if ($ok==0){

                            unset($navs[$k1]);
                        }

                    }


                }*/


                ?>
                @foreach(\App\Models\Nav::navs1() as $k1=>$v1)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">{{$v1->name}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">

                            @foreach(\App\Models\Nav::where("pid",$v1->id)->get() as $k2=>$v2)


                                @if(\Illuminate\Support\Facades\Auth::guard("admin")->user()->can($v2->url) || \Illuminate\Support\Facades\Auth::guard("admin")->user()->id==1)
                                    <li><a href="{{route($v2->url)}}">{{$v2->name}}</a></li>
                                @endif


                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>

            <ul class="nav navbar-nav navbar-right">


                @auth("admin")
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">admin <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('admin.changePassword')}}">修改密码</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{route('admin.logout')}}">注销</a></li>
                        </ul>
                    </li>
                @endauth

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
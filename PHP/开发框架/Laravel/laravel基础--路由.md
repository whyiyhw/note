## 路由
- 基础路由
    -
    ```
    Route::get('/',functiom(){
       return 'hello laravel'; 
    })
    //自定义闭包
    Route::get('/','UserController@index')
    //自定义到控制器@方法
    ```
    - ==routes/web.php== 文件用于定义 web 界面的路由。
    - 这里面的路由都会被分配给==web 中间件组==，它提供了会话状态和 CSRF 保护等功能。
    - 定义在 ==routes/api.php== 中的路由都是无状态的，并且被分配了 ==api 中间件组==
    - routes/api.php 文件中定义的路由通过 RouteServiceProvider 被嵌套到一个路由组里面。
    - 在这个路由组中，会自动添加URL前缀 /api 到每个路由;
    - 可用的路由方法
    ```
    Route::get($uri, $callback);
    Route::post($uri, $callback);
    Route::put($uri, $callback);
    Route::patch($uri, $callback);
    Route::delete($uri, $callback);
    Route::options($uri, $callback);
    ```
    - 注册可响应多个`HTTP`请求的路由，这时你可以使用`match`方法，也可以使用`any`方法注册一个实现响应所有`HTTP`请求的路由：
    ```
    Route::match(['get', 'post'], '/', function () {
        //
    })
    Route::any('/', function () {
        
    }
    ```
    - CSRF 保护
        + 指向web路由文件中定义的==POST、PUT或DELETE==
        - 路由的任何HTML表单都应该包含一个 CSRF 令牌字段，否则，这个请求将会被拒绝.
        ```
        <form method="POST" action="/profile">
            @csrf
            ...
        </form>
        ```
        + 可以在`Middleware/VerifyCsrfToken.php`中添加不需要csrf保护的路由
    - 重定向路由
        + 可以使用==Route::redirect==方法。这个方法可以快速的实现重定向
        ```
        Route::redirect('/here', '/there', 301);
        ```
    - 视图路由
        - 如果你的路由只需要返回一个视图，可以使用 ==Route::view== 方法。它和 `redirect` 一样方便，不需要定义完整的路由或控制器。`view` 方法有三个参数，其中前两个是必填参数，分别是 URI 和视图名称。第三个参数选填，可以传入一个数组，数组中的数据会被传递给视图:
        ```
        Route::view('/welcome', 'welcome');
        Route::view('/welcome', 'welcome', ['name' => 'Taylor']);
        ```
- 路由参数
    - 
    - 必填参数 {$id}
    ```
    //必选单参数 {} 前后参数名可以不一致
    Route::get('user/{id}', function ($id) {
        return 'User '.$id;
    });
    
    //必选多参数 {} 参数名不能使用`-`可以使用`_`替代
    Route::get('posts/{post}/comments/{comment}', function ($postId, $commentId) {
        //
    });
    ```
    - 可选参数 {$id?}
        - 你可以在参数后面加上 ? 标记来实现，但前提是要确保路由的相应变量有默认值
    ```
    Route::get('user/{name?}', function ($name = null) {
        return $name;
    });

    Route::get('user/{name?}', function ($name = 'John') {
        return $name;
    });
    ```
    - 正则表达式约束
        - 可以使用`where`方法约束 路由参数格式where方法([参数名 => 正则约束条件])
    ```
    Route::get('user/{name}', function ($name) {
        //约束name只能为大小写英文1个或多个
    })->where('name', '[A-Za-z]+');
    
    Route::get('user/{id}', function ($id) {
        //
    })->where('id', '[0-9]+');
    
    Route::get('user/{id}/{name}', function ($id, $name) {
        //多个约束条件传数组
    })->where(['id' => '[0-9]+', 'name' => '[a-z]+']);
    ```
    - 全局约束
        - 如果你希望某个具体的路由参数都遵循同一个正则表达式的约束，就使用 pattern 方法在 RouteServiceProvider 的 boot 方法中定义这些模式：
        ```
        public function boot()
        {
            //定义全局id参数为数字约束
            Route::pattern('id', '[0-9]+');
        
            parent::boot();
        }
        ```
- 路由命名
    - 
        ```
        Route::get('user/profile', function () {
            //
        })->name('profile');
        ```
    - 生成指定路由的 URL
        ```
        //根据命名生成url
        $url = route('profile')
        //根据命名重定向
        return redirect()->route('profile')
        ```
    - 如果是有定义参数的命名路由，可以把参数作为 route 函数的第二个参数传入，指定的参数将会自动插入到 URL 中对应的位置
        ```
        Route::get('user/{id}/profile', function ($id) {
            //
        })->name('profile');
        
        $url = route('profile', ['id' => 1]);
        ```
    - 检查当前路由
        
        ```
        /**
         * 处理一次请求。
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next)
        {
            if ($request->route()->named('profile')) {
                //
            }
        
            return $next($request);
        }
        ```
- 路由组
    - 
    - 路由组允许你在大量路由之间共享路由属性，例如中间件或命名空间，而不需要为每个路由单独定义这些属性。共享属性应该以数组的形式传入 Route::group 方法的第一个参数中。
    - 中间件
        要给路由组中所有的路由分配中间件，可以在 group 之前调用 middleware 方法，中间件会依照它们在数组中列出的顺序来运行：
    ```
    Route::middleware(['first', 'second'])->group(function () {
        Route::get('/', function () {
            // 使用 first 和 second 中间件
        });
    
        Route::get('user/profile', function () {
            // 使用 first 和 second 中间件
        });
    });
    ```
    - 命名空间
    ```
    Route::namespace('Admin')->group(function () {
        // 在 "App\Http\Controllers\Admin" 命名空间下的控制器
    });
    ```
    默认情况下，RouteServiceProvider 会在命名空间组中引入你的路由文件，让你不用指定完整的 App\Http\Controllers 命名空间前缀就能注册控制器路由。因此，你只需要指定命名空间 App\Http\Controllers 之后的部分。
    - 子域名路由
        - 路由组也可以用来处理子域名。子域名可以像路由 URI 一样被分配路由参数，允许你获取一部分子域名作为参数给路由或控制器使用。可以在 group 之前调用 domain 方法来指定子域名：
        -
        ```
        Route::domain('{account}.myapp.com')->group(function () {
            Route::get('user/{id}', function ($account, $id) {
                //
            });
        });
        ```
    - 路由前缀
    可以用 prefix 方法为路由组中给定的 URL 增加前缀。例如，你可以为组中所有路由的 URI 加上 admin 前缀：
        ```
        Route::prefix('admin')->group(function () {
            Route::get('users', function () {
                // 匹配包含 "/admin/users" 的 URL
            });
        });
        ```
    - 路由名称前缀 针对于路由->name 后的名称(==5.6==)
    ```
    Route::name('admin.')->group(function () {
        Route::get('users', function () {
            // 路由分配名称“admin.users”...
        })->name('users');
    });
    ```
- 路由模型绑定
    - 
    -  当向路由或控制器行为注入模型 ID 时，就需要查询这个 ID 对应的模型。Laravel 为路由模型绑定提供了一个直接自动将模型实例注入到路由中的方法。例如，你可以注入与给定 ID 匹配的整个 User 模型实例，而不是注入用户的 ID。
    -  隐式绑定
        -   Laravel 会自动解析定义在路由或控制器行为中与类型提示的变量名匹配的路由段名称的 Eloquent 模型。例如：
        -
        ```
        Route::get('api/users/{user}', function (App\User $user) {
            return $user->email;
        });
        ```
        - 由于 $user 变量被类型提示为 Eloquent 模型 App\User，变量名称又与 URI 中的 {user} 匹配，因此，Laravel 会自动注入与请求 URI 中传入的 ID 匹配的用户模型实例。如果在数据库中找不到对应的模型实例，将会自动生成 404 异常。
    - 自定义键名(默认 ID)
        - 如果你想要模型绑定在检索给定的模型类时使用除 id 之外的数据库字段，你可以在 Eloquent 模型上重写 getRouteKeyName 方法
        ```
        /**
         * 为路由模型获取键名。
         *
         * @return string
         */
        public function getRouteKeyName()
        {
            return 'slug';
        }
        ```
    - 显示绑定
        - 要注册显式绑定，使用路由器的 model 方法来为给定参数指定类。在 RouteServiceProvider 类中的 boot 方法内定义这些显式模型绑定：
        ```
        //显示绑定user为 UserModel
        public function boot()
        {
            parent::boot();
        
            Route::model('user', App\User::class);
        }
        //再定义一个包含{user}参数的路由
        Route::get('profile/{user}', function (App\User $user) {
            //
        });
        ```
        - 因为我们已经将所有 {user} 参数绑定至 App\User 模型，所以 User 实例将被注入该路由。例如，profile/1 的请求会注入数据库中 ID 为 1 的 User 实例。

        如果在数据库不存在对应 ID 的数据，就会自动抛出一个 404 异常
    - 自定义逻辑解析
        使用自定义的解析逻辑，就需要使用 Route::bind 方法。传递到 bind 方法的 闭包 会接受 URI 中大括号对应的值，并且返回你想要在该路由中注入的类的实例
    ```
    public function boot()
    {
        parent::boot();
    
        Route::bind('user', function ($value) {
            return App\User::where('name', $value)->first() ?? abort(404);
        });
    }
    ```
- 访问控制(==5.6==)
    -
    - Laravel 包含了一个 中间件 用于控制应用程序对路由的访问。如果想要使用，请将 throttle 中间件分配给一个路由或一个路由组。throttle 中间件会接收两个参数，这两个参数决定了在给定的分钟数内可以进行的最大请求数。 例如，让我们指定一个经过身份验证并且用户每分钟访问频率不超过 60 次的路由：
    ```
    Route::middleware('auth:api', 'throttle:60,1')->group(function () {
        Route::get('/user', function () {
            //
        });
    });
    ```
- 动态访问控制
    - 您可以根据已验证的 User 模型的属性指定动态请求的最大值。 例如，如果您的 User 模型包含rate_limit属性，则可以将属性名称传递给 throttle 中间件，以便它用于计算最大请求计数：
    ```
    Route::middleware('auth:api', 'throttle:rate_limit,1')->group(function () {
        Route::get('/user', function () {
            //
        });
    });
    ```
- 表单方法伪造
    -
    - HTML 表单不支持 PUT、PATCH 或 DELETE 行为。所以当你要从 HTML 表单中调用定义了 PUT、PATCH 或 DELETE 路由时，你将需要在表单中增加隐藏的 _method 输入标签。使用 _method 字段的值作为 HTTP 的请求方法：
    ```
    <form action="/foo/bar" method="POST">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
    ```
    - 也可以使用 @ method Blade 指令生成 _method 输入：
    ```
    <form action="/foo/bar" method="POST">
        @method('PUT')
        @csrf
    </form>
    ```
- 访问当前路由
    -
    - 你可以使用 Route Facade 上的 current、currentRouteName 和 currentRouteAction 方法来访问处理传入请求的路由的信息：
    ```
    // 获取当前路由实例
    $route = Route::current(); 
    // 获取当前路由名称
    $name = Route::currentRouteName();
    // 获取当前路由action属性
    $action = Route::currentRouteAction();
    ```
- 路由缓存
    - 
    - 路由缓存不会作用于基于闭包的路由。要使用路由缓存，必须将闭包路由转化为控制器路由。
    - `php artisan route:cache` 路由缓存
    - `php artisan route:clear` 清除路由缓存
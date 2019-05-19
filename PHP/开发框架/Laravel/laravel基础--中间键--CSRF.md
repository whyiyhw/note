中间键 可以作为请求的前置或者后置方法 也就是==装饰者模式==而被使用
- 定义中间件
    - 
    - 使用 `php artisan make:middleware [name]` 来生成
    - 前置 & 后置中间件
        - 前置&后置只是相对于逻辑代码的位置来说的
        - 
        ```
        public function handle($request, Closure $next)
        {
            // 执行动作 请求前置操作
    
            $response = $next($request);
            
            //执行动作 请求后置操作
            
            return $response
        }
        ```
- 注册中间件
    - 
    - 所有的中间键都需要在app/Http/Kernel.php 中进行注册
    - 这个Kernel.php里面定义了三个数组 分别对应 全局/分组/单个中间键
        - 全局中间件
            - protected $middleware = []
            - These middleware are run during every request to your application.
        - 路由分组中间键
            - protected $middlewareGroups = []
            - The application's route middleware groups.
        - 注册路由中间键
        - protected $routeMiddleware = []
        - These middleware may be assigned to groups or used individually.
    - 为路由分配中间件
        - 一旦在 Kernel 中定义了中间件，就可使用 middleware 方法将中间件分配给路由： 
        ```
        Route::get('/', function () {
            //
        })->middleware('first', 'second');
        ```
    - 中间件组
        - middlewareGroups[
            'web'=>[
            
            ]
        ]
        - 默认有web和api组 可以自定义
        - 这样只要在这个组内的都会受到 里面Middleware的限制
        - 
        ```
        Route::get('/', function () {
            //
        })->middleware('web');
        
        Route::group(['middleware' => ['web']], function () {
            //
        });
        ```
- 中间件参数
    - 需要传递参数可以在 handle中传入第三个参数
    ```
        public function handle($request, Closure $next, $role,...)
        {
            if (! $request->user()->hasRole($role)) {
                // 重定向...
            }
    
            return $next($request);
        }
        
        Route::put('post/{id}', function ($id) {
            //
        })->middleware('role:editor,var...');
    ```
    - 约定使用:来隔开中间键名和参数 多个参数用,隔开
- Terminable 中间件
    -  要使用 terminate 只需要在 middleware 中定义一个 terminate 方法就可以了
    -  terminate的执行周期是在 Laravel 的相应结果返回之后，也就是整个请求周期快要结束的时候可以用来记录API的响应结果
    ```
        public function terminate($request, $response)
        {
            // Store the session data...
        }
    ```
- CSRF
    -  默认表单都应该包含`CSRF`字段
    ```
    {{ csrf_field() }}
    ```
    - CSRF 白名单
        - 你可以把这类路由放到 routes/web.php 外，因为 RouteServiceProvider 的 web 中间件适用于该文件中的所有路由。不过，你也可以通过将这类 URI 添加到 VerifyCsrfToken 中间件中的 $except 属性来排除对这类路由的 CSRF 保护：
        ```
            protected $except = [
                'stripe/*',
            ];
        ```
    - X-CSRF-TOKEN
        - 除了检查 POST 参数中的 CSRF 令牌外，VerifyCsrfToken 中间件还会检查 X-CSRF-TOKEN 请求头。你可以将令牌保存在 HTML meta 标签中：
        ```
        <meta name="csrf-token" content="{{ csrf_token() }}">
        ```
    - X-XSRF-TOKEN
        - Laravel 将当前的 CSRF 令牌存储在由框架生成的每个响应中包含的一个 XSRF-TOKEN cookie 中,可以使用 cookie 值来设置 X-XSRF-TOKEN 请求头
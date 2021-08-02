中间键使用全局使用
- 
- 需要在app.php中打开Middleware全局注释

配置文件的使用
- 需要在app.php打开门面注释
- $app->configure('options');
- 在根目录下建立config/options.php
- 使用config门面
- Config::get('options.access_id')


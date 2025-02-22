<?php declare(strict_types=1);
/**
 * Vitex 一个基于php7.0开发的 快速开发restful API的微型框架
 * @version  0.3.0
 *
 * @package vitex
 *
 * @author  skipify <skipify@qq.com>
 * @copyright skipify
 * @license MIT
 */

class Init
{
    /**
     * @var string
     */
    private $appname;
    /**
     * @var String
     */
    private $dirname;
    /**
     * @var String
     */
    private $inname;
    /**
     * @var array
     */
    private $dirs;

    public function __construct($dirname = '.', $appname = 'app', $inname = 'index.php')
    {
        $dirname       = rtrim($dirname, '/');
        $this->appname = $appname;
        $this->dirname = $dirname;
        $this->inname  = $inname;
        $this->dirname = $dirname;
    }

    public static function init()
    {
        $dirname = getcwd();
        echo '
            *****************************************

                          欢迎使用初始化系统
            使用本系统之前你应该先阅读vitex部署相关的内容

            *****************************************
        ';
        echo '请输入生成代码的路径，即webroot文件夹所在路径(' . $dirname . '):';
        $_dirname = fread(STDIN, 200);
        if (trim($_dirname)) {
            $dirname = trim($_dirname);
        }
        echo $dirname . PHP_EOL;

        echo '请输入应用名字，此名字应该是你的应用名且为命名空间的名字(app)：';
        $appname  = 'app';
        $_appname = fread(STDIN, 200);
        if (trim($_appname)) {
            $appname = trim($_appname);
        }
        echo $appname . PHP_EOL;

        echo '请输入应用的入口PHP文件名(index.php)：';
        $inname  = 'index.php';
        $_inname = fread(STDIN, 200);
        if (trim($_inname)) {
            $inname = trim($_inname);
        }
        echo $inname . PHP_EOL;

        echo '按回车确认';
        fread(STDIN, 1);

        $init = new Init($dirname, $appname, $inname);
        $init->create();
    }

    public function create()
    {
        $this->dirs = [
            $this->dirname . '/' . $this->appname . '/route',
            $this->dirname . '/' . $this->appname . '/model',
            $this->dirname . '/' . $this->appname . '/ext',
            $this->dirname . '/' . $this->appname . '/templates',
            $this->dirname . '/' . $this->appname . '/controller',
            $this->dirname . '/webroot/public',
        ];

        $index = $this->index();
        if (!$index) {
            echo '您的目录已经存在项目，请确认!!!';
            exit;
        }
        mkdir($this->dirname . '/' . $this->appname);

        foreach ($this->dirs as $dir) {
            @mkdir($dir);
        }

        $this->route();
        $this->model();
        $this->tpl();
        $this->controller();
    }

    /**
     * 生成首页入口文件
     * @return string
     */
    private function index()
    {
        $indexfile = $this->dirname . '/webroot/' . $this->inname;
        @mkdir($this->dirname . '/webroot/');

        if (file_exists($indexfile)) {
            return false;
        }
        $code = 'PD9waHAKcmVxdWlyZSAnLi4vdmVuZG9yL2F1dG9sb2FkLnBocCc7CiR2aXRleCA9IFx2aXRleFxWaXRleDo6Z2V0SW5zdGFuY2UoKTsKCmNvbnN0IFdFQlJPT1QgPSBfX0RJUl9fOwovL+mFjee9rui3r+eUseaWh+S7tuWcsOWdgAovL+mFjee9ruaooeadv+i3r+W+hAovL+mFjee9ruWIm+W7uueahOW6lOeUqGFwcO+8jOaKiuivpeebruW9leWinuWKoOWIsOiHquWKqOWKoOi9veeahOWQjeWNleS4rQokdml0ZXgtPmluaXQoJ3thcHB9JywgZGlybmFtZShfX0RJUl9fKSk7Cgokdml0ZXgtPnVzaW5nKG5ldyBcdml0ZXhcbWlkZGxld2FyZVxTZXNzaW9uKCkpOwoKJHZpdGV4LT5hbGwoJy8nLCBmdW5jdGlvbiAoKSB7CiAgICBlY2hvICc8aDE+V2VsY29tZSBWaXRleCEhPC9oMT4nOwp9KTsKCiR2aXRleC0+Z3JvdXAoJy93ZWxjb21lJywgJ0luZGV4Jyk7CiR2aXRleC0+Z2V0KCcvdXNlcicsJ1VzZXInKTsgLy/osIPnlKhDb250cm9sbGVy5LitVXNlcuexu+eahGdldOaWueazlQoKJHZpdGV4LT5ydW4oKTs=';
        $code = base64_decode($code);
        //创建新文件
        file_put_contents($indexfile, str_replace('{app}', $this->appname, $code));

        return true;
    }

    //模型示例
    public function model()
    {
        $file = $this->dirname . '/' . $this->appname . '/model/Model.php';
        $code = 'PD9waHAKLyoK6L+Z5piv5LiA5Liq5pmu6YCa5qih5Z6LCiAqLwpuYW1lc3BhY2Uge2FwcH1cbW9kZWw7CgpjbGFzcyBNb2RlbCBleHRlbmRzIFx2aXRleFxleHRcTW9kZWwKewogICAgcHVibGljIGZ1bmN0aW9uIF9fY29uc3RydWN0KCkKICAgIHsKICAgICAgICBwYXJlbnQ6Ol9fY29uc3RydWN0KCk7CiAgICAgICAgLy/pu5jorqTnmoTooajlkI3mmK/nsbvlkI0g5pys5L6L5Li6IGluZGV4KOWwj+WGmSkKICAgICAgICAvL+m7mOiupOeahOS4u+mUruS4uiBpZAogICAgICAgIC8v5Y+v5Lul5Zyo6L+Z6YeM6YeN5paw6K6+572u5Li76ZSu5ZKM6KGo5ZCNCiAgICAgICAgJHRoaXMtPnBrICAgID0gJ2lkJzsKICAgICAgICAkdGhpcy0+dGFibGUgPSAndXNlcic7CiAgICB9Cn0=';
        file_put_contents($file, str_replace('{app}', $this->appname, base64_decode($code)));
        return true;
    }

    //路由
    public function route()
    {
        $file = $this->dirname . '/' . $this->appname . '/route/index.php';
        $code = 'PD9waHAKCiR2aXRleC0+Z2V0KCcvanNvbicsIGZ1bmN0aW9uICgkcmVxLCAkcmVzKSB7CiAgICAgICAgICAkcmVzLT5qc29uKFsnbmFtZScgPT4gJ3ZpdGV4J10pOwogICAgICB9KQogICAgICAtPmdldCgnLycsIGZ1bmN0aW9uICgkcmVxLCAkcmVzKSB7CiAgICAgICAgICAkcmVzLT5yZW5kZXIoJ3dlbGNvbWUnKTsKICAgICAgfSk7Cg==';
        file_put_contents($file, base64_decode($code));
        return true;
    }

    public function tpl()
    {
        $file = $this->dirname . '/' . $this->appname . '/templates/welcome.html';
        $code = 'PGh0bWw+CjxoZWFkPgoJPHRpdGxlPldlbGNvbWU8L3RpdGxlPgo8L2hlYWQ+Cjxib2R5Pgo8aDE+V2VsY29tZTwvaDE+CjwvYm9keT4KPC9odG1sPg==';
        file_put_contents($file, base64_decode($code));
        return true;
    }

    public function controller()
    {
        $file = $this->dirname . '/' . $this->appname . '/controller/Controller.php';
        $code = 'PD9waHAKbmFtZXNwYWNlIHthcHB9XGNvbnRyb2xsZXI7Cgp1c2UgXHZpdGV4XENvbnRyb2xsZXIgYXMgVmNvbnRyb2xsZXI7CgpjbGFzcyBDb250cm9sbGVyIGV4dGVuZHMgVmNvbnRyb2xsZXIKewoKfQ==';
        file_put_contents($file, str_replace('{app}', $this->appname, base64_decode($code)));
        $file = $this->dirname . '/' . $this->appname . '/controller/User.php';
        $code = 'PD9waHAKbmFtZXNwYWNlIHthcHB9XGNvbnRyb2xsZXI7CgpjbGFzcyBVc2VyIGV4dGVuZHMgQ29udHJvbGxlcgp7CiAgICBwdWJsaWMgZnVuY3Rpb24gZ2V0KCkKICAgIHsKICAgICAgICBlY2hvICd1c2VyJzsKICAgIH0KfQ==';
        file_put_contents($file, str_replace('{app}', $this->appname, base64_decode($code)));
    }

}

Init::init();

<?php declare(strict_types=1);
/**
 * Vitex 一个基于php7.0开发的 快速开发restful API的微型框架
 * @version  0.2.0
 *
 * @package vitex
 *
 * @author  skipify <skipify@qq.com>
 * @copyright skipify
 * @license MIT
 */
namespace vitex\middleware;

use vitex\helper\Set;
use vitex\helper\Utils;
use vitex\Middleware;
use vitex\core\Exception;

/**
 * cookie中间件，用于把Cookie信息附加到req对象中
 */
class Cookie extends Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 设置cookie
     * @param [type] $name     [名字 ]
     * @param [type] $value    [值]
     * @param [type] $expires  [过期时间]
     * @param [type] $path     [路径]
     * @param [type] $domain   [域名]
     * @param [type] $secure   [https?]
     * @param [type] $httpOnly [httponly]
     */
    public function setCookie(
        $name,
        $value,
        $expires = null,
        $path = null,
        $domain = null,
        $secure = null,
        $httpOnly = null
    ) {
        $expires  = is_null($expires) ? $this->vitex->getConfig('cookies.lifetime') : $expires;
        $path     = is_null($path) ? $this->vitex->getConfig('cookies.path') : $path;
        $domain   = is_null($domain) ? $this->vitex->getConfig('cookies.domain') : $domain;
        $secure   = is_null($secure) ? $this->vitex->getConfig('cookies.secure') : $secure;
        $httpOnly = is_null($httpOnly) ? $this->vitex->getConfig('cookies.httponly') : $httpOnly;
        if (!is_numeric($expires)) {
            $expires = strtotime($expires);
        }
        $secret_key = $this->vitex->getConfig('cookies.secret_key');
        $encrypt    = $this->vitex->getConfig('cookies.encrypt');
        if ($encrypt) {
            $value = Utils::encrypt($value, $secret_key);
        }
        setcookie($name, $value, $expires, $path, $domain, $secure, $httpOnly);
    }

    /**
     * 根据指定的cookie名获取cookie
     * @param  string $name                                                       cookie的名字
     * @return string cookie的值，如果有加密返回的是解密后的值
     */
    public function getCookie($name = null)
    {
        $cookie  = $_COOKIE;
        $encrypt = $this->vitex->getConfig('cookies.encrypt');
        if ($encrypt) {
            $secret_key = $this->vitex->getConfig('cookies.secret_key');
            foreach ($cookie as &$c) {
                try {
                    $c = Utils::decrypt($c, $secret_key);
                } catch(Exception $e){}
            }
        }
        if ($name === null) {
            return $cookie;
        }
        return isset($cookie[$name]) ? $cookie[$name] : '';
    }

    /**
     * 清空所有或者指定的cookie
     * @param  string $key 键名
     * @return self
     */
    public function clearCookie($key = null)
    {
        if ($key) {
            setcookie($key, '', time() - 3600);
        } else {
            foreach ($_COOKIE as $key => $val) {
                $this->clearCookie($key);
            }
        }
        return $this;
    }

    /**
     * 调用中间件
     */
    public function call()
    {
        $cookie                    = $this->getCookie();
        $this->vitex->req->cookies = new Set($cookie);
        $this->runNext();
    }
}

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

use vitex\helper\SetMethod;
use vitex\Middleware;
use vitex\service\session\SessionHandler;
use vitex\Vitex;

/**
 * 把相关的Session信息附加到 req对象中
 */
class Session extends Middleware implements \ArrayAccess, \Iterator, \Countable
{
    use SetMethod;

    public function __construct($sid = '')
    {

        $this->setSessionHandler();
        if (!isset($_SESSION)) {
            if (session_id() == '') {
                if ($sid) {
                    session_id($sid);
                }
                session_start();
            }
        }
    }


    public function call()
    {
        $this->vitex->req->session = $this;
        $this->runNext();
    }

    /**
     * 设置保存session的handler
     */
    private function setSessionHandler()
    {
        $vitex = Vitex::getInstance();
        $instance = $vitex->container->get(SessionHandler::class);
        session_set_save_handler($instance, true);
    }

    /**
     * 设置session的值
     * @param  mixed $key session键名，如果为数组时则为包含键值的一个关联数组
     * @param  mixed $val session值，如果第一个参数是数组的时候此参数不需要指定
     * @return $this
     */
    public function set($key, $val = null)
    {
        if (is_array($key)) {
            $_SESSION = array_merge($_SESSION, $key);
        } else {
            $this->offsetSet($key, $val);
        }
        return $this;
    }

    /**
     * 获取指定键名的session值，如果不指定则返回整个session
     * @param  mixed $key 键名
     * @return mixed 返回的值
     */
    public function get($key = null)
    {
        if ($key) {
            return $this->offsetGet($key);
        } else {
            return $_SESSION;
        }
    }

    public function offsetExists($val)
    {
        return isset($_SESSION[$val]);
    }

    public function offsetSet($key, $val)
    {
        if (is_null($key)) {
            $_SESSION[] = $val;
        } else {
            $_SESSION[$key] = $val;
        }
    }

    public function offsetGet($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function offsetUnset($key)
    {
        unset($_SESSION[$key]);
    }

    //Iterator methods
    //
    public function rewind()
    {
        reset($_SESSION);
    }

    public function key()
    {
        return key($_SESSION);
    }

    public function next()
    {
        return next($_SESSION);
    }

    public function current()
    {
        return current($_SESSION);
    }

    public function count()
    {
        return count($_SESSION);
    }
}

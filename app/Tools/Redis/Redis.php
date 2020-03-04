<?php
/**
 * Author: 任小龙 Date:2019-09-03 Time:22:01
 */


namespace App\Tools\Redis;



class Redis
{
    private static $instance;

    private function __construct()
    {
        self::$instance = new \Redis();
        $config = RedisConfig::getConfig();
        self::$instance->connect($config['host'], $config['port']);
        self::$instance->auth($config['auth']);
    }

    private function __clone()
    {
        //禁止克隆
    }

    /*
     * 单例获取redis
     * @return \Redis|Redis
     */
    public static function getConn()
    {
        if (!self::$instance instanceof \Redis) {
            new self();
        }
        return self::$instance;
    }


    /*
     * 获取所有的key值
     * @return array
     */
    public static function getAllKeys($key = '*')
    {
        $con = self::getConn();
        return $con->keys($key);
    }

    /*
     * 删除key(删除某条缓存)
     * @param $tableName
     * @param $keywords
     * @param $id
     * @param $listNum
     * @param $page
     */
    public static function deleteKey($key)
    {
        $con = self::getConn();
        $res = $con->del($key);
        return $res;
    }

    /*
     * 批量删除缓存
     * @param string $prefix key值
     * return tool
     * author zuowenli
     * */
    public static function batchDelKeys($prefix)
    {
        $redis = self::getConn();
        $keys = $redis->keys($prefix . '*');
        return $redis->del($keys);
    }

    public static function setExpireTime($key, $expireTime)
    {
        $redis = self::getConn();
        $redis->expire($key, $expireTime);
        return true;
    }

    public static function getTtl($key)
    {
        $redis = self::getConn();
        return $redis->ttl($key);
    }

    //====string类型操作====

    /*
     * 获取string类型
     * @param $tableName
     * @param $keywords
     * @param $id
     * @param $listNum
     * @param $page
     * @return string
     */
    public static function getString($key)
    {
        $con = self::getConn();
        $data = $con->get($key);
        $data = unserialize($data);
        return $data;
    }

    /*
     * 设置string类型
     * @param $tableName
     * @param $keywords
     * @param $id
     * @param $listNum
     * @param $page
     * @param $data
     * @param $timeout
     * @return bool
     */
    public static function setString($key, $data, $timeout = 0)
    {
        $con = self::getConn();
        if ($timeout > 0) {
            $res = $con->setex($key, intval($timeout), serialize($data));
        } else {
            $res = $con->set($key, serialize($data));
        }
        return $res;
    }

    /*
     * 设置自增
     * @param $tableName
     * @param $keywords
     * @param $id
     * @param $listNum
     * @param $page
     * @param int $inc
     * @return int
     */
    public static function setInc($key, $inc = 1)
    {
        $con = self::getConn();
        return $con->incrBy($key, $inc);
    }

    //====操作HASH====

    public static function setHash($key, $data)
    {
        $con = self::getConn();
        $res = false;
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = serialize($v);
            }
            $res = $con->hMset($key, $data);
        }
        return $res;
    }

    /*
     * 获取Hash存储的内容
     * @param $tableName
     * @param $keywords
     * @param $id
     * @param string|array $field 这里有两种可能类型,string 与 array 两种
     * @return array|string
     */
    public static function getHash($key, $field = '*')
    {
        $con = self::getConn();
        $data = [];
        if (is_int($field)) $field = strval($field);
        if (is_string($field)) {
            if ($field == '*') {
                $data = $con->hGetAll($key);    //取出所有字段
            } else {
                $data = [$field => $con->hGet($key, $field)];   //取出指定单个字段
            }
        }
        if (is_array($field)) {
            $data = $con->hMGet($key, $field);  //取出指定多个字段
        }
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k] = unserialize($v);    //反序列化
            }
        }
        return $data;
    }

    /*
     * 清除hash存储中的单个field
     * 任小龙 2018/9/4 修改
     * @param $tableName
     * @param $keywords
     * @param $id
     * @param $listNum
     * @param $page
     * @param $field
     * @return int
     */
    public static function delHashField($key, $field)
    {
        $con = self::getConn();
        $res = $con->hDel($key, $field);
        return $res;
    }

    public static function flushAll()
    {
        $con = self::getConn();
        return $con->flushAll();
    }


    //====加锁===

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public static function setLock($key, $value)
    {
        $lockKey = self::getLockKey($key);
        $redis = self::getConn();
        $i = 20;
        while ($i) {
            $res = $redis->setnx($lockKey, $value);
            if ($res) {
                $redis->setTimeout($lockKey, 5);
                return true;
            } else {
                usleep(200000);
                $i--;
            }
        }
        return false;
    }

    public static function freeLock($key, $value)
    {
        $lockKey = self::getLockKey($key);
        $redis = self::getConn();
        $res = $redis->get($lockKey);
        if ($res == $value) {
            $redis->delete($lockKey);
            return true;
        } else {
            return false;
        }
    }

    public static function setSecondLock($key, $second)
    {
        $lockKey = self::getLockKey($key);
        $redis = self::getConn();
        $i = 20;
        while ($i) {
            $res = $redis->setnx($lockKey, 1);
            if ($res) {
                $redis->setTimeout($lockKey, $second);
                echo ' 抢锁成功 ';
                echo microtime(true);
                return true;
            } else {
                echo ' 抢锁失败 ';
                usleep(500000); //每隔0.5秒抢一次
                $i--;
            }
        }
        return false;
    }

    private static function getLockKey($key)
    {
        return "RXLWIN_LOCK_" . $key;
    }
}

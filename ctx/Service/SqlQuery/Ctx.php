<?php

namespace Ctx\Service\SqlQuery;

use Ctx\Basic\Ctx as BasicCtx;
use Ctx\Service\SqlQuery\Child\Connection;

/**
 * 模块接口声明文件
 * 备注：文件命名跟模块中的其他类不同，因为模块入口类只能被实例化一次
 * 也就是只能用ctx->模块 来实例化，不能用loadC来实例化更多
 */
class Ctx extends BasicCtx
{
    private function config()
    {
        return [
            [
                'type'      => '未分类',
                'name'      => 'cms开发库', //不可重复

                'driver'    => 'mysql',
                'host'      => '127.0.0.1',
                'port'      => 3306,
                'database'  => 'test',
                'username'  => 'user01',
                'password'  => '123',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'strict'    => true,
            ],
        ];
    }

    private function getDbConfig($name)
    {
        $databases = $this->config();
        return array_get(array_column($databases, null, 'name'), $name, []);
    }

    public function getDbDsn($name)
    {
        $config = $this->getDbConfig($name);
        return $config['host'] . ':' . $config['port'] . ':' . $config['database'];
    }

    /**
     * @var
     */
    private $connections;

    /**
     * @param $name
     * @return Connection
     *
     * @throws \PHPCtx\Ctx\Exceptions\Exception
     */
    private function getConnection($name)
    {
        if (! isset($this->conns[$name])) {
            $this->connections[$name] = $this->loadC('Connection', $this->getDbConfig($name));
        }

        return $this->connections[$name];
    }

    public function getVersion($name)
    {
        try {
            return $this->getConnection($name)->getVersion();
        } catch (\Exception $e) {
            return '未知版本';
        }
    }

    public function query($name, $sql)
    {
        return $this->getConnection($name)->select($sql);
    }

    public function getAllDatabases()
    {
        $allDatabases = $this->config();

        $ret = [];
        foreach ($allDatabases as $database) {
            $ret[] = [
                'type'  => $database['type'],
                'name'  => $database['name'],
            ];
        }

        return $ret;
    }
}

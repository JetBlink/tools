<?php

namespace Ctx\Service\SqlQuery\Child;

use Ctx\Basic\Ctx as BasicCtx;
use Illuminate\Database\Connectors\MySqlConnector;
use Illuminate\Database\MySqlConnection;

class Connection extends BasicCtx
{
    /**
     * @var MySqlConnection
     */
    private $connection;

    /**
     * Connection constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->connection = new MySqlConnection((new MySqlConnector())->connect($config), $config['database'], '', $config);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getVersion()
    {
        $ret = $this->connection->select("select version() as v");
        return $ret[0]->v;
    }

    /**
     * @param $query
     * @param array $bindings
     * @return bool|\PDOStatement
     */
    private function selectStatement($query, $bindings = [])
    {
        $statement = $this->connection->getPdo()->prepare($query);

        $this->connection->bindValues($statement, $this->connection->prepareBindings($bindings));

        $statement->execute();

        return $statement;
    }

    public function select($query, $bindings = [])
    {
        $statement = $this->selectStatement($query, $bindings);

        $affectedRows = $statement->rowCount();

        $columns = [];
        for ($i = 0; $i < $statement->columnCount(); $i++) {
            $meta = $statement->getColumnMeta($i);
            $columns[] = $meta['name'];
        }

        $result = $statement->fetchAll(\PDO::FETCH_NUM);

        return [$affectedRows, $columns, $result];
    }
}

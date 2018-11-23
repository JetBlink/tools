<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SqlQueryController extends Controller
{
    public function index(Request $request)
    {
        $debugTime = 0;
        $affectedRows = 0;
        $columns = [];
        $result = [];
        $sqlError = '';

        $allDatabases = $this->ctx->SqlQuery->getAllDatabases();
        $dbName = $request->input('db_name', array_get($allDatabases, '0.name', ''));
        $dbType = array_get(array_column($allDatabases, 'type', 'name'), $dbName, '');

        $sql = trim(trim($request->input('sql', '')), ';');
        if (! empty($sql)) {
            //重写sql
            if (strpos($sql, 'select') === 0 && strpos(substr($sql, -12), 'limit') === false) {
                $sql .= ' limit 10;';
            }

            try {
                \Log::channel('sql-query')->Info($sql);

                $debugTime = microtime(true);
                list($affectedRows, $columns, $result) = $this->ctx->SqlQuery->query($dbName, $sql);
                $debugTime = microtime(true) - $debugTime;
            } catch (\Exception $e) {
                $sqlError = $e->getMessage();
            }
        }

        $mysqlDsn = $this->ctx->SqlQuery->getDbDsn($dbName);
        $mysqlVersion = $this->ctx->SqlQuery->getVersion($dbName);
        $allDatabases = json_encode($allDatabases);

        return view('sql_query', compact(
            'allDatabases',
            'dbType',
            'dbName',
            'mysqlDsn',
            'mysqlVersion',

            'sql',
            'affectedRows',
            'columns',
            'result',
            'sqlError',
            'debugTime'
        ));
    }
}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta http-equiv="Content-Language" content="zh-cn" />
    <title>在线Mysql查询工具</title>
    <link type="text/css" href="/static/sql_query.css" rel="stylesheet" media="all" />
</head>
<body>
<form id="sql_form" name="sql_form" action="./" method="post">
    &nbsp;&nbsp;&nbsp;&nbsp;环境：<select id="db_type">
        <{html_options values=$arr_type selected=$arr_type_selected output=$arr_type}>
    </select>
    &nbsp;&nbsp;&nbsp;&nbsp;库：<select id="db_name" name="db_name">
    </select> {{ $mysqlDsn }}&nbsp;&nbsp;&nbsp;&nbsp;版本: {{ $mysqlVersion }}&nbsp;<br>
    &nbsp;&nbsp;&nbsp;&nbsp;<textarea id="sql" name="sql" style="width:800px;height:250px;">{{ $sql }}</textarea><br>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="查询" /><br>
</form>
@if (! empty($sqlError))
    <div>&nbsp;&nbsp;&nbsp;&nbsp;错误：&nbsp;&nbsp;&nbsp;{{ $sqlError }}</div>
@elseif (! empty($sql))
<br/>
&nbsp;&nbsp;&nbsp;&nbsp;影响行数：{{ $affectedRows }}行&nbsp;&nbsp;&nbsp;&nbsp;用时：{{ $debugTime }}秒<br>
<table border="1" cellspacing="1" cellSpacing="1" align="left" style="margin-left:10px;">
    <tr>
        @foreach ($columns as $column)
        <th style="font-weight:700;padding:5px;background-color:#669;color:#F9F6F6;text-align:center;">{{ $column }}</th>
        @endforeach
    </tr>

    @if (empty($result))
        <tr><td bgcolor="yellow" align="right" colspan="{{ count($columns) }}">共显示 0 行</td></tr>
    @endif

    @foreach ($result as $row)
    <tr>
        @foreach ($row as $item)
        <td>{{ $item }}</td>
        @endforeach
    </tr>

    @if ($loop->last)
        <tr><td bgcolor="yellow" align="right" colspan="{{ count($columns) }}">共显示 {{ $loop->index + 1 }} 行</td></tr>
    @endif
    @endforeach
</table>
@endif
<script type="application/javascript">
    let allDatabases = {!! $allDatabases !!};
    let dbType = "{{ $dbType }}";
    let dbName = "{{ $dbName }}";

    //分组数据
    let allDbTypes = [];
    let databasesType2Name = {};
    for (let i in allDatabases) {
        let dbType = allDatabases[i]['type'];
        let dbName = allDatabases[i]['name'];

        allDbTypes.push(dbType);
        databasesType2Name[dbType] = databasesType2Name[dbType] || [];
        databasesType2Name[dbType].push(dbName);
    }
    const typeset = new Set(allDbTypes);
    allDbTypes = [...typeset];

    loadDbTypes(dbType);
    function loadDbTypes(selectRow) {
        let html = '';
        allDbTypes.forEach(function (value) {
            if (value == selectRow) {
                html += `<option value="${value}" selected>${value}</option>`;
            } else {
                html += `<option value="${value}">${value}</option>`;
            }
        });

        document.querySelector("#db_type").innerHTML = html;
    }

    loadDbNames(dbType, dbName);
    function loadDbNames(dbType, selectRow) {
        let dbNames = databasesType2Name[dbType];
        dbName = dbName || dbNames[0];

        let html = '';
        dbNames.forEach(function (value) {
            if (value == selectRow) {
                html += `<option value="${value}" selected>${value}</option>`;
            } else {
                html += `<option value="${value}">${value}</option>`;
            }
        });

        document.querySelector("#db_name").innerHTML = html;
    }

    document.querySelector("#db_type").addEventListener('change', function () {
        loadDbNames(this.value, '');
    });
</script>
</body>
</html>

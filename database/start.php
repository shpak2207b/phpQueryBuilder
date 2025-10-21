<?php

$config = include "config.php";

include "database/QueryBuilder.php";
include "database/Connection.php";

return new \database\QueryBuilder(\database\Connection::make($config['database']));
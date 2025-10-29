<?php

echo '123';

use App\QueryBuilder;

$db = new QueryBuilder();
$db->delete('posts', 1);


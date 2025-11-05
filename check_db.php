<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__FILE__)
);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = DB::select('SELECT name FROM sqlite_master WHERE type=\'table\' AND name NOT LIKE \'sqlite_%\';');
foreach ($tables as $table) {
    echo \
\nTable:
`$table->name
\n\;
    $columns = DB::select('PRAGMA table_info(' . $table->name . ');');
    foreach ($columns as $column) {
        echo \
-
`$column->name
`$column->type
\n\;
    }
}


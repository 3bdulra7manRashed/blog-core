<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

ob_start();
require 'test-plans.php';
$output = ob_get_clean();
file_put_contents('test-output.log', $output);

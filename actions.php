<?php

require_once __DIR__ . "/inc/executor-factory.php";
require_once __DIR__ . "/inc/abstract-executor.php";

$hooks_option = get_option('woocd_hooks');
$hooks = explode(',', $hooks_option);
array_walk($hooks, 'trim');

foreach($hooks as $hook) {
    
    $executorFactory = new ExecutorFactory($hook); // Every tasker to build its own settings 
    $executor = $executorFactory->create();

    add_action($hook, array($executor, 'process'), 100);
}
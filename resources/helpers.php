<?php

function rr(...$args)
{
    if (php_sapi_name() === 'cli' || Request::wantsJson()) {
        extract(debug_backtrace(0, 1)[0]);
        echo "-- rr() $file:$line", PHP_EOL;
        foreach ($args as $x) var_dump($x);
        echo '----';
        die(1);
    } else {
        dd(...$args);
    }
}

<?php

function rr(...$args)
{
    extract(debug_backtrace(0, 1)[0]);
    if (php_sapi_name() === 'cli' || Request::wantsJson()) {
        echo "-- rr() $file:$line", PHP_EOL;
        foreach ($args as $x) var_dump($x);
        echo '----';
        die(1);
    } else {
        echo "<code class='sf-dump'>$file:$line</code>";
        dd(...$args);
    }
}

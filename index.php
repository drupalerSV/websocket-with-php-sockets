<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(file_exists(__DIR__.'/socket.sh')) {
    print_r('Attempting to connect to socket<br>'.PHP_EOL);

    $result = shell_exec('sh '.__DIR__.'/socket.sh');

    echo "<br><pre>$result</pre><br>";

    print_r('Socket connected<br>'.PHP_EOL);
}
else {
    print_r('Attempting to disconnect from socket'.PHP_EOL);
}

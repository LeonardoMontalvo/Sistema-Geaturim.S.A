<?php
function sis_error_log_file($errno, $errstr, $errfile, $errline, $filelog)
{
    $ddf = fopen($filelog, 'a');
    $errfile = explode('/', $errfile);
    $errfile = $errfile[count($errfile) - 1];
    fwrite($ddf, "[" . date("r") . "] Error $errno-$errfile-$errline: $errstr\r\n");
    fclose($ddf);
}

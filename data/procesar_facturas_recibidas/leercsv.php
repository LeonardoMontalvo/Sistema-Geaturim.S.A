<?php
$file = $_FILES["file"];
$fp = fopen($file["tmp_name"], "r");
$rows = [];
while (!feof($fp)) {
    $line = fgets($fp);
    $data = parse_csv($line, "\t");
    array_push($rows, $data[0]);
}
fclose($fp);

$cabeceras = array_shift($rows);

$newrows = array_map(
    function ($line) use ($cabeceras) {
        $nline = [];
        foreach ($line as $key => $value) {
            $nline[$cabeceras[$key]] = $value;
        }
        return $nline;
    },
    $rows
);

echo json_encode($newrows);

function parse_csv($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
{
    $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string);
    $enc = preg_replace_callback(
        '/"(.*?)"/s',
        function ($field) {
            return urlencode(utf8_encode($field[1]));
        },
        $enc
    );
    $lines = preg_split($skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s', $enc);
    return array_map(
        function ($line) use ($delimiter, $trim_fields) {
            $fields = $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line);
            return array_map(
                function ($field) {
                    return str_replace('!!Q!!', '"', utf8_decode(urldecode($field)));
                },
                $fields
            );
        },
        $lines
    );
}

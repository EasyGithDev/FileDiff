<?php

setLocale(LC_ALL, 'fr_FR');

$in = __DIR__ . '/in';
$out = __DIR__ . '/out';

// $f1 = fopen("$in/first.csv", "r");
// $f2 = fopen("$in/second.csv", "r");
// $f3 = fopen("$out/added.csv", "w");
// $f4 = fopen("$out/deleted.csv", "w");

$f1 = fopen("$in/first.txt", "r");
$f2 = fopen("$in/second.txt", "r");
$f3 = fopen("$out/added.txt", "w");
$f4 = fopen("$out/deleted.txt", "w");

$sep = ',';

function compare(string $a, string $b): string
{
    $a = preg_replace('#[^\w\s]+#', '', iconv('utf-8', 'ascii//TRANSLIT', $a));
    $b = preg_replace('#[^\w\s]+#', '', iconv('utf-8', 'ascii//TRANSLIT', $b));

    return strcmp($a, $b);
}

function read($handle, string $sep, array &$data = null): string
{
    if (feof($handle))
        return "";

    if (($data = fgetcsv($handle, 0, $sep)) !== false)
        return $data[0];

    return "";
}

function write($handle, array $data, string $sep): void
{
    fputcsv($handle, $data, $sep);
}

do {

    $pos1 = ftell($f1);
    $pos2 = ftell($f2);

    // echo 'p1:', $pos1, " p2:", $pos2, PHP_EOL;
    $data1 = $data2 = [];
    $val1 = read($f1,  $sep, $data1);
    $val2 = read($f2,  $sep, $data2);

    fseek($f1, $pos1);
    fseek($f2, $pos2);

    if ($val1 !== "" && $val2 !== "") {
        $res = compare($val1, $val2);
    } else if ($val1 === "" && $val2 !== "") {
        $res = 1;
    } else if ($val1 !== "" && $val2 === "") {
        $res = -1;
    } else {
        break;
    }

    // echo 'v1 ',  $val1, ' v2 ', $val2, ' res ', $res, PHP_EOL;
    // echo 'p1:', $pos1, " p2:", $pos2, PHP_EOL;

    // data are equals in f1 and f2 
    if ($res == 0) {
        read($f1,  $sep);
        read($f2,  $sep);
    }
    // data missing in  f2, the data as been deleted from f1
    // read a new line in f1
    else if ($res < 0) {
        echo "Data is missing in file 2. Data:", $val1, PHP_EOL;
        write($f4, $data1, $sep);
        read($f1,  $sep);
    }
    // new data in f2,  the data as been added from f1
    // read a new line in f2
    else if ($res > 0) {
        echo "New data detected in file 2. Data: ", $val2, PHP_EOL;
        write($f3, $data2, $sep);
        read($f2, $sep);
    }
} while (!(feof($f1) && feof($f2)));

fclose($f1);
fclose($f2);
fclose($f3);
fclose($f4);

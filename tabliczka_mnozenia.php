<?php
    $start_date = date("H:i:s");
    $start_time = time();
    echo "start " . $start_date . "<br>";
    echo "Size pobrany z URL : " . $_GET['size'] . "<br><br>";
?>

<?php

function multiplication_table($size) {
    if (!is_numeric($size) || $size < 1 || $size > 10) {
        return json_encode(array("error" => "Wymiar tablicy musi być liczbą od 1 do 10."), JSON_UNESCAPED_UNICODE);
    }

    $result = array();
    for ($i = 1; $i <= $size; $i++) {
        $row = array();
        for ($j = 1; $j <= $size; $j++) {
            $row[$j] = $i * $j;
        }
        $result[$i] = $row;
    }

    return json_encode($result);
    
}

$size = isset($_GET['size']) ? intval($_GET['size']) : 0;

$result = multiplication_table($size);
echo $result;
?>

<?php
    $end_date = date("H:i:s");
    $end_time = time();
    echo "<h1>Różnica: " . (($end_time - $start_time) * 1000) . " sek.</h1>";
    echo "end " . $end_date;
?>
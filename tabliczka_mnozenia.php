<?php
    
    $startTime = time();
    $programStart = microtime(true);
    $inFileNameSize = $_GET['size'];

    // tworzenie pliku cache i ustawienie jego lifetime
    $cacheFile = "multiply_table_cache_".$inFileNameSize.".php";
    $cacheTime = 10; // lifetime pliku cache

    if (file_exists($cacheFile) && (filemtime($cacheFile) + $cacheTime > time()))
    {
        echo "Cache loaded!<br><br>";
        readfile($cacheFile);
        $programStop = microtime(true);
        $diffTime = (($programStop - $programStart) * 1000);
        echo "<h2>Różnica: " . $diffTime . " ms.</h2>";
        exit();
    }

    ob_start();

    //start programu

    $startDate = date("H:i:s");
    echo "START " . $startDate . "<br>";

    // funkcja budująca tabliczkę mnożenia JSON w oparciu o "size" podanym w URL strony
    function multiply_table($size) {
        if (!is_numeric($size) || $size < 1 || $size > 1000) {
            return json_encode(array("error" => "Wymiar tablicy musi być liczbą od 1 do 10."), JSON_UNESCAPED_UNICODE); // JSON_UNESCAPED_UNICODE umożliwia wyświetlenie polskich znaków w przypadku błędu podania wartości "size" poza wymaganą
        }
        // pętla tworząca tabliczkę mnożenia
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
    // super globalna $_GET pobiera "size" z URL strony
    // ? parametr trójargumentowy oddziela isset (gdy 'size' jest ustawiony) od intval (gdy parametr "size" nie istnieje) ustawiając go automatycznie na 0
    $size = isset($_GET['size']) ? intval($_GET['size']) : 0;

    // potwierdzenie czy parametr "size" jest ustawiony czy nie
    if ($size === 0) {
        echo "<b>Parametr 'size' nie został ustawiony w adresie URL.</b><br><br>";
    }
    else {
        echo "<b>'Size' pobrany z URL :</b> " . $_GET['size'] . "<br><br>";
    }
    
    // wywoływanie rezultatu funkcji
    $result = multiply_table($size);
    echo $result;

    $file = fopen($cacheFile, "w");
    fwrite($file,ob_get_contents());
    fclose($file);


    //program stop
    $endDate = date("H:i:s");
    $programStop = microtime(true);
    $diffTime = (($programStop - $programStart) * 1000);
    echo "<h2>Różnica: " . $diffTime . " ms.</h2>";
    echo "Saving new cache"
?>
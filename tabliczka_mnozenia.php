<?php
    
    $startTime = time();
    $programStart = microtime(true);

    // super globalna $_GET pobiera "size" z adresu URL strony
    $size = isset($_GET['size']) ? $_GET['size'] : '';

    // Sprawdzenie czy parametr "size" jest ustawiony i jest liczbą całkowitą
    if (ctype_digit($size) && $size >= 1 && $size <= 100) {
        echo "<b>'Size' pobrany z URL :</b> " . $size . "<br><br>";
        
        // Tworzenie pliku cache i ustawienie jego lifetime
        $cacheFile = "multiply_table_cache_size_" . $size . ".txt";
        $cacheTime = 10; // lifetime pliku cache (w sekundach)

        // Sprawdzenie czy plik cache istnieje i czy nie jest przestarzały
        if (file_exists($cacheFile) && (filemtime($cacheFile) + $cacheTime > time()))
        {
            readfile($cacheFile);
            $programStop = microtime(true);
            $diffTime = (($programStop - $programStart) * 1000);
            echo "<h2>Różnica: " . round($diffTime, 4) . " ms.</h2>";
            echo "Cache loaded!";
            exit();
        }
            
        ob_start();

        // Start programu
        $startDate = date("H:i:s");
        echo "START " . $startDate . "<br><br>";

        function multiplyTable($size) {
            // Tworzenie tabliczki mnożenia
            $result = array();
            for ($i = 1; $i <= $size; $i++) {
                $row = array();
                for ($j = 1; $j <= $size; $j++) {
                    $row[$j] = $i * $j;
                }
                $result[$i] = $row;
            }

            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        // Wywołanie funkcji i wyświetlenie wyniku
        $result = multiplyTable($size);
        echo $result;

        // Zapis pliku cache
        $file = fopen($cacheFile, "w");
        if (fwrite($file, ob_get_contents())) {
            fclose($file);
            echo "<br>Cache file saved successfully.";
        } else {
            echo "<br>Error writing to cache file.";
        }

        // Stop programu
        $programStop = microtime(true);
        $diffTime = (($programStop - $programStart) * 1000);
        echo "<h2>Różnica czasu: " . round($diffTime, 4) . " ms.</h2>";
        echo "New cache file saved!";
    } else {
        echo "<b>Parametr 'size' nie został ustawiony w adresie URL lub jest niepoprawny.</b><br><br> Należy dopisać w adresie URL - ?size=(1-100)";
    }

?>

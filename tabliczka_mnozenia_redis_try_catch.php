<?php
require 'vendor/autoload.php';

// ustawienie połączenia z redis-server
$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
]);

// pobranie parametru size
$size = isset($_GET['size']) ? $_GET['size'] : '';

// zapisanie czasu startu programu
$programStart = microtime(true);

// sprawdzenie czy program ma połączenie z redis
try {
    $connected = $redis->ping();
    if ($connected) {
        echo "<b>Połączenie z Redis zostało poprawnie nawiązane.</b><br>";
        if (ctype_digit($size) && $size >= 1 && $size <= 100) {
            try {
                $cachedResult = $redis->get(('key'.$size));
                if ($cachedResult) {
                // wczytanie wyników z cache
                echo "<br>Wyniki z pamięci podręcznej Redis!<br><br>";
                $programStop = microtime(true);
                // czas programu w przypadku wczytania danych z pamięci ram
                $diffTime = (($programStop - $programStart) * 1000);
                echo "<h2>Różnica: " . round($diffTime, 4) . " ms.</h2>";
                echo $cachedResult;
                exit();
            }
        } catch (Exception $e) {
            echo "<b>Błąd w czasie pobierania danych z Redis: </b>" . $e->getMessage();
        }

            // funkcja budująca tabliczkę mnożenia
            function multiplyTable($size) {
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

            $result = multiplyTable($size);
            // zapis do redis-server oraz ustawienie TTL na 10sekund
            $redis->setex('key'.$size, 10 ,$result);
            $wartosc = $redis->get('key'.$size);

            echo "<br>Zapisano w Redis:<br>TTL ustawiono na 10sekund<br>";
            $programStop = microtime(true);
            $diffTime = (($programStop - $programStart) * 1000);

            // różnica czasu w przypadku generowania oraz zapisywania pliku
            echo "<h2>Różnica czasu: " . round($diffTime, 4) . " ms.</h2>";
            echo $wartosc;
            
        } else {
            echo "<b>Parametr 'size' nie został ustawiony w adresie URL lub jest niepoprawny.</b><br><br> Należy dopisać w adresie URL - ?size=(1-100)";
        }
        
    } else {
        echo "<b><br>Nie udało się nawiązać połączenia z Redis.</b>";
    }

} catch (Exception $e) {
    echo "<b>Błąd połączenia z Redis: </b>" . $e->getMessage();
}
?>

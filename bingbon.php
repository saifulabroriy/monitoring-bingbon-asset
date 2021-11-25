<?php
    // Include file module telegram 
    include_once "telegram.php";

    // Mendapatkan state bot telegram
    $state = $_POST["state"];

    // Fungsi mendapatkan data di return menjadi JSON dari URL
    function getData($url){
        // persiapkan curl
        $curl = curl_init(); 

        // Set Opsi pada curl
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 

        // eksekusi dan dimasukkan ke markets
        $res = curl_exec($curl); 

        // Error Handler
        if (curl_errno($curl)) {
            print "Error: " . curl_error($curl);
        } else {
            // Tutup curl
            curl_close($curl);

            return json_decode($res, true);
        }
    }

    // URL untuk mendapatkan data JSON
    $url = "https://api.bingbon.com/api/v1/market/symbols";

    // Mendapatkan data dari bingbon
    $data = getData($url);

    // Mendapatkan record setiap aset atau koin
    $markets = $data['data']['result'];

    $low = array();
    $high = array();

    // Perulangan untuk menampilkan isi tabel
    $index = 1;
    foreach ($markets as $m) {
        // Variabel untuk selisih low dengan last dan high dengan last
        $lowest = $m['last_price'] - $m['low'];
        $highest = $m['high'] - $m['last_price'];

        // Jika hasil selisih di bawah 0 maka push aset itu ke array
        // Low
        if($lowest <= 0){
            array_push($low, $m['base_currency']);
        }

        // High
        if($highest <= 0){
            array_push($high, $m['base_currency']);
        }

        // HTML tabel body berisi data aset
        echo '<tr class="table__row">
            <td class="table__item table__item--number">' . $index . '</td>
            <td class="table__item table__item--coin">' . $m['base_currency'] . '</td>
            <td class="table__item table__item--pair">' . $m['quote_currency'] . '</td>
            <td class="table__item table__item--last">' . number_format($m['last_price'], 3, ",", ".") . '</td>
            <td class="table__item table__item--low">' . number_format($m['low'], 3, ",", ".") . '</td>
            <td class="table__item table__item--high">' . number_format($m['high'], 3, ",", ".") . '</td>
            <td class="table__item table__item--lowest">' . number_format($lowest, 3, ",", ".") . '</td>
            <td class="table__item table__item--highest">' . number_format($highest, 3, ",", ".") . '</td>
        </tr>';

        $index++;
    }

    // Jika state yang dikirim true
    if ($state == "true"){
        // Mendapatkan timestamp dari mikrotime bingbon
        $waktu = round($data['timestamp'] / 1000 );

        $msg = "Waktu Server: " . date('j M Y, H:i:s', $waktu) . " %0a%0a";
        // Jika array low ada isinya
        if (count($low) > 0){
            $msg .= "LOW %0a%0a";
            for ($i=0; $i < count($low); $i++) { 
                $msg .= $i + 1 . ". " . $low[$i] . " %0a";
            }
            $msg .= "%0a";
        }
        
        // Jika array high ada isinya
        if (count($high) > 0){
            $msg .= "HIGH %0a%0a";
            for ($i=0; $i < count($high); $i++) { 
                $msg .= $i + 1 . ". " . $high[$i] . " %0a";
            }
        }

        // Jika salah satu dari array low dan high ada isinya maka kirim pesan
        if (count($high) > 0 || count($low) > 0){
            sendMessage($msg);
        }
    }
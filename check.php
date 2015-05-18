<?php
/**
 * HTTPステータスコードを取得する
 * 
 * 正常な場合は200が返却され、異常な場合はそのステータスが、
 * そして存在しないURLの場合は返ってくる値はnullとなる。
 *
 * @param string $url
 * @return mixed $header status code or null
 */
function getStatusCode($url) {
        $header = null;
        $options = array(
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HEADER         => true,
                        CURLOPT_FOLLOWLOCATION => false,
                        CURLOPT_ENCODING       => "",
                        CURLOPT_USERAGENT      => "spider",
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_AUTOREFERER    => true,
                        CURLOPT_CONNECTTIMEOUT => 120,
                        CURLOPT_TIMEOUT        => 120,
                        CURLOPT_MAXREDIRS      => 10,
        );
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);

        if(!curl_errno($ch)) {
                $header = curl_getinfo($ch);
        }// end if
        curl_close($ch);
        return $header['http_code'];
}// end function

//アカウント.txt
$fp = fopen("accounts.txt", "r");
//凍結されたアカウント
$fps = fopen("suspend.txt", "a");
//生き残ったアカウント
$fpa = fopen("alive.txt", "a");

$i = 1;
 while ($sn = fgets($fp)){

$sn = rtrim($sn);

 if(getStatusCode('https://twitter.com/'.$sn) !== 200){
 	
        echo $i,' : Suspended - @',$sn,PHP_EOL;
        
        fwrite($fps, $sn.PHP_EOL);

    }else{

        echo $i,' : Alive - @',$sn,PHP_EOL;
        
        fwrite($fpa, $sn.PHP_EOL);

    }

    ++$i;

}

fclose($fp);
fclose($fps);
fclose($fpa);

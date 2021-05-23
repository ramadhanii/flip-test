<?php
require_once "./CONFIG.PHP";

class CurlHelper{
    protected $withHeader = true;
    public function __construct($withHeader=true){
        $this->withHeader = $withHeader;
    }

    public function do($url, $data, $method="POST"){
        try{

            if($this->withHeader){
                $header = [
                    "Content-Type: application/x-www-form-urlencoded",
                    "Authorization: basic ".base64_encode(SERVER_KEY.":")
                ];
            }
            
            if(is_array($data)) $data = http_build_query($data);
            
            $ch  = curl_init();
            curl_setopt($ch, CURLOPT_URL, 				$url . (($method == 'GET') ? "?".$data : "" ));
            curl_setopt($ch, CURLOPT_HEADER, 			TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, 		$header ?? []);
            curl_setopt($ch, CURLINFO_HEADER_OUT,		true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 	TRUE);
            if($method == "POST"){
                curl_setopt($ch, CURLOPT_POST, 				TRUE);
            }
            if(!in_array($method, ['GET', 'POST'])){
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            }
            if($method != 'GET'){
                curl_setopt($ch, CURLOPT_POSTFIELDS, 		(is_array($data) ? http_build_query($data) : $data));
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 	2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 	false);
            curl_setopt($ch, CURLOPT_USERAGENT, 		"FlipServices/1.0");
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 	25);
            curl_setopt($ch, CURLOPT_TIMEOUT, 			25);
            curl_setopt($ch, CURLOPT_VERBOSE, 			true);

            $rawResponse 	= curl_exec($ch);
            $header_size 	= curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $httpcode 		= curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header 		= substr($rawResponse, 0, $header_size);
            $heads 			= explode("\n",$header);
            $head  			= FALSE;
            foreach ($heads as $v) {
                $v = trim($v);
                if (strlen($v) < 1) continue;
                if (preg_match('/^(HTTP\/\d\.\d) (\d{3}) (.*)/i',$v,$m)) {
                    $head['http'] = $m[1];
                    $head['status_code'] = $m[2];
                    $head['status_string'] = $m[2] . " - " . $m[3];
                } elseif (preg_match('/^([\w\-\.]+):\s*?(\S.*)$/',$v,$m)) {
                    $head[$m[1]] = $m[2];
                } else continue;
            }
            $response['cURLerror'] = curl_error($ch);
            curl_close($ch);
            $response['header'] = $head;

            $body = substr($rawResponse, $header_size);
            $body = trim($body);	
            if(!!($jsonRes = json_decode($body, true)))
                $response['body'] = json_decode($body, true);
            else
                $response['body'] = $body;
        }catch(Exception $e){
            $response['error'] = $e;
        }

        return $response['body'];
    }
}
?>
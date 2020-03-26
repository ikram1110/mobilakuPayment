<?php
  // Call Encryption File
  include_once __DIR__ . "/BniEnc.php";

  // Initiate URL
  $url = [
    "dev" => "https://apibeta.bni-ecollection.com/",
    "production" => "https://api.bni-ecollection.com/"
  ];

  // Initiate Client ID & Secret Key
  $client_id = "11011";
  $secret_key = "c9ce5dc8768fe8adcfc65d6d53dc0acc";

  //  Handle Payload Request Data
  $data_raw = file_get_contents("php://input");
  //var_dump($data_raw); die();
  $data_json = (array)json_decode($data_raw);
  $data_json['client_id'] = $client_id;

  // Encrypt Data before Send to BNI Api
  $data_encrypt = bni_hash('encrypt',$data_json,$client_id,$secret_key);
  $data_send = json_encode($data_encrypt);

  // Send Data to BNI Api & Handling Response
  $response_raw = send_data($data_send,$url['dev']);
  var_dump($response_raw); die();
 

  var_dump($response);

  function bni_hash($type,$data,$client,$key){
    if($type == "encrypt"){
      return [
        'client_id' => $client,
        'data' => BniEnc::encrypt($data,$client,$key),
      ];
    }

    if($type == "decrypt"){
      return BniEnc::decrypt($data,$client,$key);
    }
  }

  function send_data($data,$url){
    $user_agent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36";
    $header[] = "Content-Type: application/json";
    $header[] = "Accept-Encoding: gzip, deflate";
    $header[] = "Cache-Control: max-age=0";
    $header[] = "Connection: keep-alive";
    $header[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_ENCODING, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  
	  $rs = curl_exec($ch);
    
    if(empty($rs)){
      var_dump($rs, curl_error($ch));
      curl_close($ch);
      return false;
    }
    curl_close($ch);
    return $rs;
  }
  
?>

<?php
  //echo "test"; die();
  $data = file_get_contents('php://input');

  $data_json = json_decode($data);

  echo $data_json; die();

  $url = 'https://api.mobilaku.co.id/admin/auth/validate';
  
	$header[] = 'Content-Type: application/json';
  $header[] = 'Authorization: '.$data_json->token;
  
	$curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.mobilaku.co.id/admin/auth/validate",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
      "Accept: */*",
      "Accept-Encoding: gzip, deflate",
      "Authorization: ".$data_json->token,
      "Cache-Control: no-cache",
      "Connection: keep-alive",
      "Content-Length: 0",
      "Content-Type: application/json",
      "Host: api.mobilaku.co.id",
      "cache-control: no-cache"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }
?>

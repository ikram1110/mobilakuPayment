<?php

include_once __DIR__ . "/BniEnc.php";


// FROM BNI
$client_id = '11011';
$secret_key = 'c9ce5dc8768fe8adcfc65d6d53dc0acc';
$url = 'localhost:9090/dealer/callback';


// URL utk simulasi pembayaran: http://dev.bni-ecollection.com/


$data = file_get_contents('php://input');

$data_json = json_decode($data, true);

if (!$data_json) {
	// handling orang iseng
	echo '{"status":"999","message":"jangan iseng :D"}';
}
else {
	if ($data_json['client_id'] === $client_id) {
		$data_asli = BniEnc::decrypt(
			$data_json['data'],
			$client_id,
			$secret_key
		);

		if (!$data_asli) {
			// handling jika waktu server salah/tdk sesuai atau secret key salah
			echo '{"status":"999","message":"waktu server tidak sesuai NTP atau secret key salah."}';
		}
		else {
			// insert data asli ke db
			/* $data_asli = array(
				'trx_id' => '', // silakan gunakan parameter berikut sebagai acuan nomor tagihan
				'virtual_account' => '',
				'customer_name' => '',
				'trx_amount' => '',
				'payment_amount' => '',
				'cumulative_payment_amount' => '',
				'payment_ntb' => '',
				'datetime_payment' => '',
				'datetime_payment_iso8601' => '',
			); */
			send_data(json_encode($data_asli),$url);
			echo '{"status":"000"}';
			exit;
		}
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


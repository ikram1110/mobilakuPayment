<?php
echo "test";

  $data_raw = file_get_contents("php://input");
  var_dump($data_raw); die();
  $data_json = (array)json_decode($data_raw);
  // var_dump($data_json);
?>

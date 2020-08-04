<?php
  function getToken($data, $email, $password, $url = 'https://www.twygoead.com') {
    $response = wp_remote_post("$url/oauth/token", array(
      'method'      => 'POST',
      'timeout'     => 45,
      'redirection' => 5,
      'httpversion' => '1.0',
      'blocking'    => true,
      'headers'     => array(),
      'body'        => array(
        'grant_type' => 'password',
        'username' => $email,
        'password' => $password
      ),
      )
    );
    $response_decoded = json_decode($response['body']);
    return $response_decoded->access_token;
  }

  function send_student($token, $data, $org_id, $url = 'https://www.twygoead.com') {
    $response = wp_remote_post("$url/api/v1/o/$org_id/students", array(
      'method'      => 'POST',
      'timeout'     => 45,
      'redirection' => 5,
      'httpversion' => '1.0',
      'blocking'    => true,
      'headers'     => array(
         'Authorization' => 'Bearer ' . $token,
       ),
      'body'        => $data,
      )
    );
  }

  function send_course($token, $data, $org_id, $url = 'https://www.twygoead.com') {
    $response = wp_remote_post("$url/api/v1/o/$org_id/courses", array(
      'method'      => 'POST',
      'timeout'     => 45,
      'redirection' => 5,
      'httpversion' => '1.0',
      'blocking'    => true,
      'headers'     => array(
         'Authorization' => 'Bearer ' . $token,
       ),
      'body'        => $data,
      )
    );
  }
?>

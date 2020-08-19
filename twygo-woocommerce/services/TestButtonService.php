<?php

function test_button() {
  echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script>
      function test_infos() {
        const email = document.getElementById('email_twygo').value;
        const password = document.getElementById('password_twygo').value;

        $.ajax({
            url: 'https://alpha.twygo.com/oauth/token',
            dataType: 'json',
            type: 'POST',
            data: {
              grant_type: 'password',
              username: email,
              password: password
            },
            success: function(data, status){
              $('#msg').css({ background: '#c3f7c8', border: '2px solid #00c617', color: '#00c617' })
              $('#msg').html('<p>Sucesso ao validar informações.</p>')
            },
            error: function(err){
              $('#msg').css({ background: '#f7a3a3', border: '2px solid red', color: 'red' })
              $('#msg').html('<p>Informações inválidas, tente novamente.</p>')
            }
        });
      }
    </script>
    <button
      style='background-color: #00c617;
      height: 30px;
      border-radius: 4px;
      border: 1px solid #00c617;
      cursor: pointer;
      color: #fff;
      font-size: 12px;'
      type='button'
      onClick='test_infos();'
    >
      Validar informações
    </button>";
}

?>

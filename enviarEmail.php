<?php 
  //pegando dados do formulário
    $nome = $_POST['nome'];
    $mensagem_form = $_POST['mensagem'];


       
  
  /* Propriedades e cabeçalho da mensagem */
    $titulo = "Mensagem com Anexo"; //titulo que vai ser enviado no email
    $limitador = "_=======". date('YmdHms'). time() . "=======_";
    $headers  = "MIME-Version: 1.0\n";
    $headers .= "Content-type: multipart/mixed; boundary=\"$limitador\"\n"; 
    $headers .= "From: macassistenciaprime\n";
    $cid = date('YmdHms').'.'.time();



  //Pegando data para anexar no HTML
    $data = date('d/m/Y');

  //escrevendo corpo do HTML
  $mensagem = "
  <html>
    
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet'>
    <body style=' padding: 0px; margin:0px; width: 550px; margin: auto calc( ( 100% - 550px )  / 2 ); font-family: 'Source Sans Pro', sans-serif;'>
      
      <header style='width: 100%; background-color: #02A4EF; padding:  40px ;'>
        <p style='text-align:center'><img src='http://www.bojanglesmuseum.com/wp-content/uploads/2017/07/Cool-Free-Logo-Design-Templates-Online-57-About-Remodel-Logo-Creater-with-Free-Logo-Design-Templates-Online.jpg' style=' width: 280px; height: 117px; padding: 20px auto;'></p>
        <h2 style=' text-align: center; color: #fff;'>Formulário Com Anexo</h2>
      </header>


      <section class='container' style='width: 100%; padding: 20px;'>
        <h3 class='title' style=' text-align: center; margin: 10px auto;'>Titulo</h3>
        <p style='padding: 10px;border-bottom: 2px solid #cdcdcd;'>Nome: $nome</p>
        <p style='padding: 10px;border-bottom: 2px solid #cdcdcd;'>Mensagem: $mensagem_form</p>
      </section>


      <footer style=' padding: 40px;width: 100%;background-color: #f5f5f5;'>
        <p style=' text-align: center;font-weight: bold;font-size: 12px;color: gray;'>
          seusite.com.br<br>
          Data: $data<br>
        </p>
      </footer>
    </body>


  </html>
  ";



  /* Atribuindo ao corpo do e-mail o HTML criado acima */
    $messenger .= "--$limitador\n";
    $messenger .= "Content-Transfer-Encoding: 8bits\n";
    $messenger .= "Content-type: text/html; charset=iso-8859-1\n";
    $messenger .= "$mensagem\n";
    $messenger .= "--$limitador\n";


   /* Anexando os arquivos ao e-mail .*/
            $quantFiles = count($_FILES['file']['name']); //conta quantos arquivos foram passados pelo arquivo do tipo file
           
            /* Laço que inclui todos os arquivos passados pelo input do tipo file em anexo */
            for($i=0; $i < $quantFiles; $i++){
              $file_name = $_FILES['file']['name'][$i]; //pega o nome que está na primeira posição do input com nome FILE
              $file_size = $_FILES["file"]["size"][$i];
              $file_type = $_FILES["file"]["type"][$i];
              $file_tmp =  $_FILES["file"]["tmp_name"][$i];

              $arquivo=fopen($_FILES['file']['tmp_name'][$i],'r');
              $contents = fread($arquivo, filesize($_FILES['file']['tmp_name'][$i]));
              $encoded_attach = base64_encode($contents);
              fclose($arquivo);
              $encoded_attach = chunk_split($encoded_attach);
               
              /* Atribuindo os anexos ao e-mail */
                 $messenger .= "Content-type: $file_type;\n\tname=\"$file_name\"\n";
                 $messenger .= "Content-Disposition: attachment;\n\tfilename=\"$file_name\"\n";
                 $messenger .= "Content-Transfer-Encoding:\tbase64\n";
                 $messenger .= "Content-ID: <$cid>\n";
                 $messenger .= "$encoded_attach\n";
                 $messenger .= "--$limitador\n";
            }       
  $messenger .= "--$limitador--\n";



  /* Enviando o email */
  mail("seuemail@provedor.com", $titulo, $messenger, $headers);
  echo "<script>window.open('index.html', '_self');</script>";
?>
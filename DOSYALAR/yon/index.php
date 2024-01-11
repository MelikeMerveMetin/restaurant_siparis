 <?php 
    include_once("fonk/yonfonk.php");
    $clas = new yonetim;
    $clas->cookcon($vt, true);
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="../dosya/jqu.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../dosya/boost.css">
    <title>Yönetici Giriş</title>
    <style>
        #log {
            margin-top:20%; 
            min-height:250px;
            background-color:#FEFEFE;
            border-radius:10px;
            border:1px solid #B7B7B7;
        }
    </style>
</head>

    <body style = "background-color:#EEE;">

        <div class = "container">
        
            <div class="col-md-4"><a href="../index.php" class="btn btn-success">ANASAYFAYA DÖN</a></div>
           
        
            <div class = "row mx-auto">

                    <div class="col-md-4 mx-auto text-center" id = "log">

                        <?php

                            @$buton = $_POST["buton"];

                            if(!$buton):
                            
                        ?>
                            
                        <form action = "<?php $_SERVER['PHP_SELF'] ?>" method = "post">
                            <div class="col-md-12 border-bottom p-2"><h3>Yönetici Giriş</h3></div>
                            <div class="col-md-12"><input type="text" name="kulad" class="form-control mt-3" required="required" placeholder="Yönetici Adı" autofocus="autofocus"/> </div>
                            <div class="col-md-12"><input type="password" name="sifre" class="form-control mt-2" required="required" placeholder="Şifrenizi Girin"/> </div>
                            <div class="col-md-12"><input type="submit" name="buton" class="btn btn-success mt-2" value="GİRİŞ"/> </div>
                            
                        </form>

                        
                        <?php

                           // echo md5(sha1(md5("1234")));

                            else:

                                @$sifre = htmlspecialchars(strip_tags($_POST["sifre"]));
                                @$kulad = htmlspecialchars(strip_tags($_POST["kulad"]));

                                    if ($sifre == "" || $kulad == "") :
                                        echo "Kullanıcı adı ve şifre boş geçilemez";
                                        header("refresh:2, url=index.php");

                                    else:
                                        // sorgulama
                                        $clas->giriskont($vt, $kulad, $sifre);
                                    endif;

                            endif;

                        ?>
                    </div>



        </div>
 
    </body>

</html>
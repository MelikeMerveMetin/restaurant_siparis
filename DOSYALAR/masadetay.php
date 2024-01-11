<?php session_start(); 

include("fonksiyon/fonksiyonlar.php"); 
$siparis = new Siparis;
$veri=$siparis->sorgular($db,"SELECT * FROM garson where durum=1",1)->num_rows; 
if ($veri==0) : header("Location:masa.php"); 
endif;
 @$masaid=$_GET["masaid"]; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Bootstrap CSS -->

        <script src="dosya/jqu.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous" />

        <link rel="stylesheet" href="dosya/melike.css" />

        <script>
            $(document).ready(function () {
                var id = "<?php echo $masaid; ?>";
                $("#veri").load("islemler.php?islem=goster&id=" + id);
                $("#btn").click(function () {
                    $.ajax({
                        type: "POST",
                        url: "islemler.php?islem=ekle",
                        data: $("#formum").serialize(),
                        success: function (donen_veri) {
                            $("#veri").load("islemler.php?islem=goster&id=" + id);
                            $("#formum").trigger("reset");
                            $("#cevap").html(donen_veri).fadeOut(1400);
                        },
                    });
                });

                $("#urunler a").click(function () {
                    var sectionId = $(this).attr("sectionId");
                    $("#sonuc").load("islemler.php?islem=urun&katid=" + sectionId);
                });
            });
        </script>

        <style>
            html,
            body {
                height: 100%;
            }
        </style>

        <title>Restaurant Sipariş Sistemi</title>
    </head>
    <body>
        <div class="container-fluid h-100">
            <?php 

if ($masaid!="") :

$son=$siparis->masagetir($db,$masaid); 
$dizi=$son->fetch_assoc(); ?>

            <div class="row justify-content-center h-100">
                <div class="col-md-4 sagiskelet">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-8 text-center mx-auto p-3" id="a3"><?php echo $dizi["ad"]; ?></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 mx-auto" id="veri"></div>
                                <div class="col-md-12" id="cevap"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- orta bölüm -->

                <div class="col-md-8">
                    <div class="row pt-2">
                        <form id="formum">
                            <div class="col-md-12" style="min-height: 200px;" id="urunler"><?php $siparis->vipTemaUrunGrup($db); ?></div>
                            <div class="col-md-12 text-center" style="min-height:410px; background-color: #dde1e1;" id="sonuc">
                                <i class="fas fa-arrow-up" style="font-size: 90px; color: #f8f8f8; margin-top: 10%;"></i>
                            </div>

                            <div class="col-md-12" style="min-height: 170px;">
                                <div class="row">
                                    <div class="col-md-2 text-center border-right">
                                        <input type="hidden" name="masaid" value="<?php echo $dizi["id"]; ?>" />
                                        <input type="button" id="btn" value="<" class="btn mt-5 mb-1 eklebuton" />
                                    </div>

                                    <div class="col-md-10 border-right text-center">
                                        <h2>ÜRÜN ADET</h2>
                                        <hr />
                                        <?php 
										  									 
   for ($i=1; $i<=10; $i++):   
  ?>
                                        <label class="btn m-2 adetbuton">
                                            <input name="adet" type="radio" value="<?php echo $i ?>" />
                                            <?php echo $i ?>
                                        </label>
                                        <?php  endfor;   ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            
  
  <textarea class="form-control mt-2 mb-5" name="a" aria-label="With textarea" placeholder="Özel Olarak İstediklerinizi Yazınız"></textarea>
</div>
                  
                        </form>
                    </div>
                  
                </div>

                <?php 

else:

	echo "hata var";
	header("refresh:1,url=masa.php");
 endif; ?>
            </div>

            <!-- Optional JavaScript; choose one of the two! -->

            <!-- Option 1: Bootstrap Bundle with Popper -->

            <!-- Option 2: Separate Popper and Bootstrap JS -->
            <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
        </div>
    </body>
</html>

<?php session_start(); 
include_once("../fonksiyon/fonksiyonlar.php"); 
$siparis = new Siparis;
@$masaid=$_GET["masaid"];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />

        <script src="../dosya/jqu.js"></script>
        <link rel="stylesheet" href="../dosya/boost.css" />

        <script>
            $(document).ready(function () {
                $("#btnn").click(function () {
                    $.ajax({
                        type: "POST",
                        url: "islemler.php?islem=hesap",
                        data: $("#hesapform").serialize(),
                        success: function (donen_veri) {
                            $("#hesapform").trigger("reset");
                            window.opener.location.reload(true);
                            window.close();
                        },
                    });
                });
            });
        </script>
        <title>FİŞ BASTIR</title>
    </head>
    <body onload="window.print()">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 mx-auto">
                    <?php 
                        if ($masaid!="") :
                           $son=$siparis->masagetir($db,$masaid); 
                           $dizi=$son->fetch_assoc(); $dizi["ad"];
                           $id=htmlspecialchars($_GET["masaid"]);
                           $a="SELECT * FROM anliksiparis where masaid=$id";
                           $d=$siparis->sorgular($db,$a,1); 
                                 if ($d->num_rows==0) :
	                                 echo "Henüz sipariş yok";
								 else: ?>
                    <table class="table">
                        <tr>
                            <td colspan="3" class="border-top-0 text-center">
                                <strong>MASA :</strong>
                                <?php echo $dizi["ad"]?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3" class="border-top-0 text-left">
                                <strong>Tarih :</strong>
                                <?php echo date("d.m.Y")?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="border-top-0 text-left">
                                <strong>Saat :</strong>
                                <?php echo date("h:i:s")?>
                            </td>
                        </tr>

                        <?php
					
					$sontutar=0;
						while ($gelenson=$d->fetch_assoc()) : $tutar = $gelenson["adet"] * $gelenson["urunfiyat"]; $sontutar +=$tutar; $masaid=$gelenson["masaid"]; ?>
                        <tr>
                            <td colspan="1" class="border-top-0 text-center"><?php echo $gelenson["urunad"]?></td>
                            <td colspan="1" class="border-top-0 text-center"><?php echo $gelenson["adet"]?></td>
                            <td colspan="1" class="border-top-0 text-center"><?php echo number_format($tutar,2,'.',',')?></td>
                        </tr>
                        <?php					
						endwhile;	
											
						?>

                        <tr>
                            <td colspan="2" class="border-top-0 font-weight-bold"><strong>GENEL TOPLAM :</strong></td>
                            <td colspan="2" class="border-top-0 text-center">
                                <?php echo number_format($sontutar,2,'.',',')?>
                                TL
                            </td>
                        </tr>

                        <form id="hesapform">
                            <input type="hidden" name="masaid" value="<?php echo $id?>" />
                            <input type="button" id="btnn" value="HESABI KAPAT" class="btn btn-danger btn-block mt-4" />
                        </form>

                        <?php			
					
					endif;	 


?>

                        <?php 


else:

	echo "hata var";
	header("refresh:1,url=index.php");
 endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <!-- Optional JavaScript; choose one of the two! -->

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <!-- Option 2: Separate Popper and Bootstrap JS -->
        <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    --></body>
</html>

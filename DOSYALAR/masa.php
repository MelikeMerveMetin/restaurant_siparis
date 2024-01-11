<?php 

include_once("fonksiyon/fonksiyonlar.php");
$siparis = new Siparis; 

$veri=$siparis->sorgular($db,"SELECT * FROM garson where durum=1",1)->num_rows; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <script src="dosya/jqu.js"></script>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="dosya/melike.css" />

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous" />

        <title>Restaurant Sipariş Sistemi</title>

        <script>
            $(document).ready(function () {
                $("#bekleyenler").load("islemler.php?islem=garsonbilgigetir");
                $("#rezervelistesi").load("islemler.php?islem=rezervelistesi");
                $("#rezerveformalan").hide();

                var deger = "<?php echo $veri; ?>";

                if (deger == 0) {
                    $("#girismodal").modal({
                        backdrop: "static",
                        keyboard: false,
                    });
                    $("body").on("hidden.bs.modal", ".modal", function () {
                        $(this).removeData("bs.modal");
                    });
                } else {
                    $("#girismodal").modal("hide");
                }

                $("#girisbak").click(function () {
                    $.ajax({
                        type: "POST",
                        url: "islemler.php?islem=kontrol",
                        data: $("#garsonform").serialize(),
                        success: function (donen_veri) {
                            $("#garsonform").trigger("reset");

                            $(".modalcevap").html(donen_veri);
                        },
                    });
                });

                setInterval(function () {
                    window.location.reload();
                }, 60000);

                $("#rezerveformac").click(function () {
                    $("#rezerveformalan").show();
                    // burada bişe yapacağız
                    $("#rezerveformalan").animate(
                        {
                            opacity: "show",
                            width: "show",
                        },
                        "fast",
                        "linear",
                        function () {}
                    );
                });

                $("#rezerveformkapa").click(function () {
                    // burada bişe yapacağız
                    $("#rezerveformalan").animate(
                        {
                            opacity: "hide",
                            width: "hide",
                        },
                        "fast",
                        "linear",
                        function () {}
                    );
                });

                $("#rezervebtn").click(function () {
                    $.ajax({
                        type: "POST",
                        url: "islemler.php?islem=rezerveet",
                        data: $("#rezerveform").serialize(),
                        success: function (donen_veri) {
                            $("#rezerveform").trigger("reset");

                            window.location.reload();
                        },
                    });
                });
            });
        </script>
    </head>
    <body>
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100">
                <!--MASALAR -->

                <div class="col-lg-9">
                    <div class="row"><?php  $siparis->vipTemaMasalar($db); ?></div>
                    <div class="col-md-4"><a href="index.php" class="btn btn-success">ANASAYFAYA DÖN</a></div>
                </div>

                <!--MASALAR -->

                <!-- The Modal -->
                <div class="modal fade" id="girismodal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header text-center">
                                <h4 class="modal-title">Garson Girişi</h4>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                <form id="garsonform">
                                    <div class="row mx-auto text-center">
                                        <div class="col-md-12">Garson Ad</div>
                                        <div class="col-md-12">
                                            <select name="ad" class="form-control mt-2">
                                                <option value="0">Seç</option>
                                                <?php
  
  $b=$siparis->sorgular($db,"SELECT * FROM garson",1);
   while ($garsonlar=$b->fetch_assoc()) : ?>
                                                <option value='<?php echo $garsonlar["ad"]?>'><?php echo $garsonlar["ad"]?></option>
                                                <?php 
  endwhile;
  
  ?>
                                            </select>
                                        </div>

                                        <div class="col-md-12">Şifre</div>
                                        <div class="col-md-12">
                                            <input name="sifre" type="password" class="form-control mt-2" />
                                        </div>

                                        <div class="col-md-12">
                                            <input type="button" id="girisbak" value="GİR" class="btn btn-info mt-4" />
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="modalcevap"></div>
                        </div>
                    </div>
                </div>

                <div class="row" id="rezerveformalan">
                    <form id="rezerveform">
                        <div class="row mx-auto text-center">
                            <div class="col-md-12 font-weight-bold p-1"><font id="rezerveformkapa" class="float-left text-danger pl-2">X</font>Masa Ad</div>
                            <div class="col-md-12">
                                <select name="masaid" class="form-control mt-2">
                                    <option value="0">Seç</option>
                                    <?php
  
  $b=$siparis->sorgular($db,"SELECT * FROM masalar where durum=0 and rezervedurum=0",1); 
  while ($masalar=$b->fetch_assoc()) : ?>
                                    <option value='<?php echo $masalar["id"]?>'><?php echo $masalar["ad"]?></option>
                                    <?php
  endwhile;
  
  ?>
                                </select>
                            </div>

                            <div class="col-md-12 font-weight-bold p-1">Kişi (Opsiyonel)</div>
                            <div class="col-md-12">
                                <input name="kisi" type="text" class="form-control mt-2" />
                            </div>

                            <div class="col-md-12">
                                <input type="button" id="rezervebtn" value="REZERVE ET" class="btn btn-info mt-4 mb-2" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="col-lg-4">
                                Garson Adı:
                                <?php $siparis->garsonbak($db); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Optional JavaScript; choose one of the two! -->

                <!-- Option 1: Bootstrap Bundle with Popper -->

                <!-- Option 2: Separate Popper and Bootstrap JS -->
                <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
            </div>
        </div>
    </body>
</html>

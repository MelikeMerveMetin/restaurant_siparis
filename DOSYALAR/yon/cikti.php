<?php
include_once("fonk/yonfonk.php");
$yonclas = new yonetim;
$yonclas->cookcon($vt, false); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
        <script src="../dosya/jqu.js"></script>
        <link rel="stylesheet" href="../dosya/boost.css" />
        <script>
            function yazdir() {
                window.print();
                window.close();
            }
        </script>
        <title>Çıktı Sayfası</title>
    </head>
    <body>
        <div class="container-fluid bg-light">
            <div class="row row-fluid">
                <?php
            @$islem = $_GET["islem"];

            switch ($islem) :

                case "ciktial":

                    @$tarih1 = $_GET["tar1"];
                    @$tarih2 = $_GET["tar2"];
                    
                    $veri = $yonclas->ciktiSorgusu($vt, "SELECT * FROM rapor WHERE DATE(tarih) BETWEEN '$tarih1' AND '$tarih2'"); $veri2 = $yonclas->ciktiSorgusu($vt, "SELECT * FROM rapor WHERE DATE(tarih) BETWEEN '$tarih1' AND '$tarih2'");
                ?>
                <table class="table text-center table-light table-bordered mx-auto mt-4 table-striped col-md-12">
                    <thead>
                        <tr>
                            <th colspan="5">
                                <div class="alert alert-info text-center mx-auto mt-4">
                                    Tarih Seçimi:
                                    <?php echo $tarih1?>
                                    -
                                    <?php echo $tarih2?>
                                </div>
                            </th>

                            <th colspan="2">
                                <button onclick="yazdir()" class="btn btn-warning mx-auto mt-4">YAZDIR</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="4">
                                <table class="table text-center table-bordered col-md-12">
                                    <thead>
                                        <tr>
                                            <th colspan="4" class="table-dark">Masa Sipariş ve Hasılat</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr class="table-danger">
                                            <th colspan="2">Ad</th>
                                            <th colspan="1">Adet</th>
                                            <th colspan="1">Hasılat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                   $kilit = $yonclas->ciktiSorgusu($vt, "SELECT * FROM gecicimasa");
                   if ($kilit->num_rows == 0):
                       while ($gel = $veri->fetch_assoc()):
                           // Raporlama için masa adını çekiyorum
                           $id = $gel["masaid"];
                           $masaveri = $yonclas->ciktiSorgusu($vt, "SELECT * FROM masalar WHERE id=$id")->fetch_assoc();
                           $masaad = $masaveri["ad"]; // Raporlama için masa adını çekiyorum
                           $raporbak = $yonclas->ciktiSorgusu($vt, "SELECT * FROM gecicimasa WHERE masaid=$id");
                           if ($raporbak->num_rows == 0):
                               // gecici masaya, sipariş verilmediyse ekleme yap
                               $has = $gel["adet"] * $gel["urunfiyat"];
                               $adet = $gel["adet"];
                               $yonclas->ciktiSorgusu($vt, "insert into gecicimasa (masaid, masaad, hasilat, adet) values ($id, '$masaad', $has, $adet)"); // yada güncelleme yap
                           else:
                               $raporson = $raporbak->fetch_assoc();
                               $gelenadet = $raporson["adet"];
                               $gelenhas = $raporson["hasilat"];
                               $sonhasilat = $gelenhas + $gel["adet"] * $gel["urunfiyat"];
                               $sonadet = $gelenadet + $gel["adet"];
                               $yonclas->ciktiSorgusu($vt, "UPDATE gecicimasa set hasilat=$sonhasilat, adet=$sonadet WHERE masaid=$id");
                           endif;
                       endwhile;
                   endif;
                   $son = $yonclas->ciktiSorgusu($vt, "SELECT * FROM gecicimasa order by hasilat desc;");
                                          $toplamadet= 0;
                                           $toplamhasilat = 0; 
                                           while ($listele = $son->fetch_assoc()): ?>
                                        <tr>
                                            <td colspan="2"><?php echo $listele["masaad"];?></td>
                                            <td colspan="1"><?php echo $listele["adet"];?></td>
                                            <td colspan="1"><?php echo substr($listele["hasilat"], 0, 5);?></td>
                                        </tr>
                                        <?php
                        $toplamadet += $listele["adet"];
                        $toplamhasilat += $listele["hasilat"];
                    endwhile;

                    ?>
                                        <tr class="table-danger">
                                            <td colspan="2">TOPLAM</td>
                                            <td colspan="1"><?php echo $toplamadet?></td>
                                            <td colspan="1"><?php echo substr($toplamhasilat, 0, 6)?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </th>
                            <th colspan="4">
                                <table class="table text-center table-bordered col-md-12">
                                    <thead>
                                        <tr>
                                            <th colspan="4" class="table-dark">Ürün Sipariş ve Hasılat</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr class="table-danger">
                                            <th colspan="2">Ad</th>
                                            <th colspan="1">Adet</th>
                                            <th colspan="1">Hasılat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $kilit2 = $yonclas->ciktiSorgusu($vt, "SELECT * FROM geciciurun");
                                        if ($kilit2->num_rows == 0):
                                            while ($gel2 = $veri2->fetch_assoc()):
                                                // Raporlama için ürün id ve ad çekiyorum
                                                $id = $gel2["urunid"];
                                                $urunad = $gel2["urunad"];
                                                $raporbak = $yonclas->ciktiSorgusu($vt, "SELECT * FROM geciciurun WHERE urunid=$id");
                                                if ($raporbak->num_rows == 0):
                                                    // gecici masaya, sipariş verilmediyse ekleme yap
                                                    $has = $gel2["adet"] * $gel2["urunfiyat"];
                                                    $adet = $gel2["adet"];
                                                    $yonclas->ciktiSorgusu($vt, "insert into geciciurun (urunid, urunad, hasilat, adet) values ($id, '$urunad', $has, $adet)"); // yada güncelleme yap
                                                else:
                                                    $raporson = $raporbak->fetch_assoc();
                                                    $gelenadet = $raporson["adet"];
                                                    $gelenhas = $raporson["hasilat"];
                                                    $sonhasilat = $gelenhas + $gel2["adet"] * $gel2["urunfiyat"];
                                                    $sonadet = $gelenadet + $gel2["adet"];
                                                    $yonclas->ciktiSorgusu($vt, "UPDATE geciciurun set hasilat=$sonhasilat, adet=$sonadet WHERE urunid=$id");
                                                endif;
                                            endwhile;
                                        endif;
                                        $son2 = $yonclas->ciktiSorgusu($vt, "SELECT * FROM geciciurun order by hasilat desc;");
                                        $toplamadet2 = 0;
                                        $toplamhasilat2 = 0;
                                        while ($listele2 = $son2->fetch_assoc()): ?>
                                        <tr>
                                            <td colspan="2"><?php echo $listele2["urunad"]?></td>
                                            <td colspan="1"><?php echo $listele2["adet"]?></td>
                                            <td colspan="1"><?php echo substr($listele2["hasilat"], 0, 5)?></td>
                                        </tr>
                                        <?php
                        $toplamadet2 += $listele2["adet"];
                        $toplamhasilat2 += $listele2["hasilat"];
                    endwhile;

                    ?>
                                        <tr class="table-danger">
                                            <td colspan="2">TOPLAM</td>
                                            <td colspan="1"><?php echo $toplamadet2?></td>
                                            <td colspan="1"><?php echo substr($toplamhasilat2, 0, 6)?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </th>
                        </tr>
                    </tbody>
                </table>
                <?php

                    break;
                    
                case "garsoncikti":
                    
                    @$tarih1 = $_GET["tar1"];
                    @$tarih2 = $_GET["tar2"];
                    
                    $veri = $yonclas->ciktiSorgusu($vt, "SELECT * FROM rapor WHERE DATE(tarih) BETWEEN '$tarih1' AND '$tarih2'");
                    $veri2 = $yonclas->ciktiSorgusu($vt, "SELECT * FROM rapor WHERE DATE(tarih) BETWEEN '$tarih1' AND '$tarih2'");
                ?>
                <table class="table text-center table-light table-bordered mx-auto mt-4 table-striped col-md-12">
                    <thead>
                        <tr>
                            <th colspan="5">
                                <div class="alert alert-info text-center mx-auto mt-4">
                                    Tarih Seçimi:
                                    <?php echo $tarih1?>
                                    -
                                    <?php echo $tarih2?>
                                </div>
                            </th>
                            <th colspan="2">
                                <button onclick="yazdir()" class="btn btn-warning mx-auto mt-4">YAZDIR</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="4">
                                <table class="table text-center table-bordered col-md-12">
                                    <thead>
                                        <tr>
                                            <th colspan="4" class="table-dark">Garson Performans</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr class="table-danger">
                                            <th colspan="2">Garson Ad</th>
                                            <th colspan="2">Adet</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                         $kilit = $yonclas->ciktiSorgusu($vt, "SELECT * FROM gecicigarson");
                                         if ($kilit->num_rows == 0):
                                             while ($gel = $veri->fetch_assoc()):
                                                 // Raporlama için masa adını çekiyorum
                                                 $garsonid = $gel["garsonid"];
                                                 $masaveri = $yonclas->ciktiSorgusu($vt, "SELECT * FROM garson WHERE id=$garsonid")->fetch_assoc();
                                                 $garsonad = $masaveri["ad"]; // Raporlama için masa adını çekiyorum
                                                 $raporbak = $yonclas->ciktiSorgusu($vt, "SELECT * FROM gecicigarson WHERE garsonid=$garsonid");
                                                 if ($raporbak->num_rows == 0):
                                                     // gecici masaya, sipariş verilmediyse ekleme yap //
                                                     $has = $gel["adet"] * $gel["urunfiyat"];
                                                     $adet = $gel["adet"];
                                                     $yonclas->ciktiSorgusu($vt, "insert into gecicigarson (garsonid, garsonad, adet) values ($garsonid, '$garsonad', $adet)"); // yada güncelleme yap
                                                 else:
                                                     $raporson = $raporbak->fetch_assoc();
                                                     $gelenadet = $raporson["adet"];
                                                     $sonadet = $gelenadet + $gel["adet"];
                                                     $yonclas->ciktiSorgusu($vt,"UPDATE gecicigarson set adet=$sonadet WHERE garsonid=$garsonid");
                                                 endif;
                                             endwhile;
                                         endif;
                                         $son = $yonclas->ciktiSorgusu($vt, "SELECT * FROM gecicigarson order by adet desc;"); 
                                         $toplamadet = 0; 
                                         while ($listele = $son->fetch_assoc()): ?>
                                        <tr>
                                            <td colspan="2"><?php echo $listele["garsonad"]?></td>
                                            <td colspan="2"><?php echo $listele["adet"]?></td>
                                        </tr>
                                        <?php
                                        $toplamadet += $listele["adet"];
                                endwhile;

                        ?>
                                        <tr class="table-danger">
                                            <td colspan="2">TOPLAM</td>
                                            <td colspan="2"><?php echo $toplamadet?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </th>
                        </tr>
                    </tbody>
                </table>
                <?php
                    
                    
                    break;
                    

            endswitch;
            ?>
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

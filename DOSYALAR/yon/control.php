<?php
include_once("fonk/yonfonk.php");
$yonclas = new yonetim;
$yonclas->cookcon($vt, false);
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <script src="../dosya/jqu.js"></script>
    <link rel="stylesheet" href="../dosya/boost.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
            body {
                height:100%;
                width:100%;
                position:absolute;
            }
            .container-fluid,
            .row-fluid {
                height:inherit;
            }
            #lk:link, #lk:visited {
                color:#888;
                text-decoration:none;
            }
            #lk:hover {
                color:#000;
            }
            #div2 {
                min-height:100%; 
                background-color:#EEE;
            }
            #div1 {
                background-color:#fff;
                border:1px solid #F1F1F1;
                border-radius:5px;
            }
        </style>
        <script language="javascript">
            var popupWindow = null;

            function ortasayfa(url, winName, w, h, scroll) {
                LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
                TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
                settings = 'height=' + h + ', width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ', resizable'
                popupWindow = window.open(url, winName, settings)
            }

            $(document).ready(function () {
                $('a[data-confirm]').click(function (ev) {
                    var href = $(this).attr('href');

                    if (!$('#dataConfirmModal').length) {
                        $('body').append('<div class="modal fade" id="dataConfirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">\n\
                                                <div class="modal-dialog modal-dialog-centered" role="document">\n\
                    <div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="exampleModalLongTitle">ONAY</h5></div>\n\
                        <div class="modal-body"></div>   \n\
                            <div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">VAZGEÇ</button><a class="btn btn-primary" id="dataConfirmOK">EVET</a></div></div></div></div></div>');
            
                            $('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
                            $('#dataConfirmOK').attr('href',href);
                            $('#dataConfirmModal').modal({show:true});                            
                            return false;
                            //window.location.reload();

                    }
                    
                });
                    
            });
            
        </script>
        <title>Restaurant Sipariş - Control Paneli</title>
  </head>
  <body>
  <div class = "container-fluid bg-light">
        <div class = "row row-fluid">
            <div class = "col-md-2 border-right " style = "min-height:750px;">
                <div class = "row">
                    <div class = "col-md-12 p-4 mx-auto text-center text-white font-weight-bold" style="background-color:purple">
                        <h4><?php echo $yonclas->kulad($vt); ?></h4>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-12 bg-light p-2 pl-3 border-bottom border-top text-white">
                        <a href="control.php?islem=masayon" id="lk">Masa Yönetimi</a>
                    </div>
                    <div class = "col-md-12 bg-light p-2 pl-3 border-bottom text-white">
                        <a href="control.php?islem=urunyon" id="lk">Ürün Yönetimi</a>
                    </div>
                    <div class = "col-md-12 bg-light p-2 pl-3 border-bottom text-white">
                        <a href="control.php?islem=kategoriyon" id="lk">Kategori Yönetimi</a>
                    </div>
                    <div class = "col-md-12 bg-light p-2 pl-3 border-bottom text-white">
                        <a href="control.php?islem=garsonyon" id="lk">Garson Yönetimi</a>
                    </div>
                    <div class = "col-md-12 bg-light p-2 pl-3 border-bottom text-white">
                        <a href="control.php?islem=garsonper" id="lk">Garson Performans</a>
                    </div>
                    <div class = "col-md-12 bg-light p-2 pl-3 border-bottom text-white">
                        <a href="control.php?islem=rapor" id="lk">Rapor Yönetimi</a>
                    </div>
                    
                    
                    <?php $yonclas->linkkontrol($vt); ?>
                    
                    <div class = "col-md-12 bg-light p-2 pl-3 border-bottom text-white">
                        <a href="control.php?islem=sifredegistir" id="lk">Şifre Değiştir</a>
                    </div>
                    <div class = "col-md-12 bg-light p-2 pl-3 border-bottom text-white">
                        <a href="control.php?islem=cikis" id="lk" data-confirm="Çıkış yapmak istediğinize emin misiniz?">Çıkış</a>
                    </div>

                    <table class = "table text-center table-light table-bordered mt-2 table-striped">
                        <thead>
                            <tr class = "table-danger">
                                <th scope = "col" colspan = "4">ANLIK DURUM</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope = "col" colspan = "3">Toplam Sipariş</th>
                                <th scope = "col" colspan = "1" class = "text-danger"><?php $yonclas->anliktoplamsiparis($vt); ?></th>
                            </tr>
                            <tr>
                                <th scope = "col" colspan = "3">Doluluk Oranı</th>
                                <th scope = "col" colspan = "1" class = "text-danger"><?php $yonclas->anlikdolulukorani($vt); ?></th>
                            </tr>
                            <tr>
                                <th scope = "col" colspan = "3">Toplam Masa</th>
                                <th scope = "col" colspan = "1" class = "text-danger"><?php $yonclas->anliktoplamMasa($vt); ?></th>
                            </tr>
                            <tr>
                                <th scope = "col" colspan = "3">Toplam Kategori</th>
                                <th scope = "col" colspan = "1" class = "text-danger"><?php $yonclas->anliktoplamKategori($vt); ?></th>
                            </tr>
                            <tr>
                                <th scope = "col" colspan = "3">Toplam Ürün</th>
                                <th scope = "col" colspan = "1" class = "text-danger"><?php $yonclas->anliktoplamUrun($vt); ?></th>
                            </tr>
                            <tr>
                                <th scope = "col" colspan = "3">Toplam Garson</th>
                                <th scope = "col" colspan = "1" class = "text-danger"><?php $yonclas->toplamGarson($vt); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class = "col-md-10">
                <div class = "row" id="div2">
                    <div class = "col-md-12 mt-4" id="div1">

                        <?php
                        @$islem = $_GET["islem"];

                        switch ($islem) :

                            //------------MASA YÖNETİM İŞLEMLERİ---------------
                            case "masayon":
                                $yonclas->masayon($vt);
                                break;

                            case "masasil":
                                $yonclas->masasil($vt);
                                break;

                            case "masaguncelle":
                                $yonclas->masaguncelle($vt);
                                break;

                            case "masaekle":
                                $yonclas->masaekle($vt);
                                break;

                            //------------ÜRÜN YÖNETİM İŞLEMLERİ---------------
                            case "urunyon":
                                $yonclas->urunyon($vt, 0);
                                break;

                            case "urunsil":
                                $yonclas->urunsil($vt);
                                break;

                            case "urunguncelle":
                                $yonclas->urunguncelle($vt);
                                break;

                            case "urunekle":
                                $yonclas->urunekle($vt);
                                break;

                            case "katgore":
                                $yonclas->urunyon($vt, 2);
                                break;

                            case "aramasonuc":
                                $yonclas->urunyon($vt, 1);
                                break;

                            //------------KATEGORİ YÖNETİM İŞLEMLERİ---------------

                            case "kategoriyon":
                                $yonclas->kategoriyon($vt);
                                break;

                            case "kategoriekle":
                                $yonclas->kategoriekle($vt);
                                break;

                            case "kategorisil":
                                $yonclas->kategorisil($vt);
                                break;

                            case "kategoriguncelle":
                                $yonclas->kategoriguncelle($vt);
                                break;

                            //-----------GARSON YÖNETİM

                            case "garsonyon";
                                $yonclas->garsonyon($vt);
                                break;

                            case "garsonekle";
                                $yonclas->garsonekle($vt);
                                break;

                            case "garsonsil";
                                $yonclas->garsonsil($vt);
                                break;

                            case "garsonguncelle";
                                $yonclas->garsonguncelle($vt);
                                break;
                                case "garsonper";
                                $yonclas->garsonper($vt);
                                break;
                        
                            //----------YÖNETİCİ AYARLARI
                            
                            case "yonayar":
                                $yonclas->yoneticiayar($vt);
                                break;

                            case "yoneticiekle":
                                $yonclas->yoneticiekle($vt);
                                break;

                            case "yoneticisil":
                                $yonclas->yoneticisil($vt);
                                break;

                            case "yoneticiguncelle":
                                $yonclas->yoneticiguncelle($vt);
                                break;

                            //-----------RAPORLAMA - ŞİFRE DEĞİŞİM - ÇIKIŞ

                            case "rapor":
                                $yonclas->rapor($vt);
                                break;

                            case "sifredegistir":
                                $yonclas->sifredegistir($vt);
                                break;

                            case "cikis":
                                $yonclas->cikis($vt, $yonclas->kulad($vt));
                                break;

                        endswitch;
                        ?>                           
                    </div>
                </div>
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
    -->
  </body>
</html>
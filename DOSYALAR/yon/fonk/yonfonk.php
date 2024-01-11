<?php ob_start();

$vt = new mysqli("localhost","root","","restoransiparis") or die ("Baglanamadi");
$vt->set_charset("utf8");

class yonetim {

    private function uyari($tip, $metin, $sayfa) {   
        echo '<div class="alert alert-'.$tip.'">'.$metin.'</div>';
        header('refresh:2, url = '.$sayfa.'');
    }

    private function genelsorgu($dv, $sorgu) {
        $sorgum = $dv->prepare($sorgu);
        $sorgum->execute();
        return $sorguson = $sorgum->get_result();
    }
    
    public function ciktiSorgusu($dv, $sorgu) {
        $sorgum = $dv->prepare($sorgu);
        $sorgum->execute();
        return $sorguson = $sorgum->get_result();
    }
    
    public function anliktoplamsiparis($vt) {
        $anliktoplamsiparis = $this->genelsorgu($vt, "select SUM(adet) from anliksiparis")->fetch_assoc();
        echo $anliktoplamsiparis['SUM(adet)'];
    }

    public function anliktoplamMasa($vt) {
        echo $this->genelsorgu($vt, "select * from masalar")->num_rows;
    }

    public function anliktoplamKategori($vt) {
        echo $this->genelsorgu($vt, "select * from kategoriler")->num_rows;
    }

    public function anliktoplamUrun($vt) {
        echo $this->genelsorgu($vt, "select * from urunler")->num_rows;
    }
    
    public function toplamGarson($vt) {
        echo $this->genelsorgu($vt, "select * from garson")->num_rows;
    }
    

    // Anlık Doluluk oranı belirleme fonksiyonu
    public function anlikdolulukorani($dv) {
        $dolulukdurum=$this->genelsorgu($dv,"select * from doluluk")->fetch_assoc();
        $toplam = $dolulukdurum["bos"] + $dolulukdurum["dolu"];
        $oran = ($dolulukdurum["dolu"] / $toplam) * 100;

        // Doluluk oranı virgülden sonrakileri azaltmak icin 5 i düsür.
        echo $oran = substr($oran, 0, 5). " %";
    }

    // Masa yönetimi ve listeleme fonksiyonu
    public function masayon ($vt) {
        $so=$this->genelsorgu($vt, "select * from masalar");

        echo '<table class="table text-center table-striped table-bordered mx-auto col-md-6 mt-4">
        <thead>
            <tr>
                <th scope="col"> <a href = "control.php?islem=masaekle" class="btn btn-success">+</a> MASA ADI</th>
                <th scope="col">GÜNCELLE</th>
                <th scope="col">SİL</th>
            </tr>
        </thead>
        <tbody>';

        while ($sonuc=$so->fetch_assoc()):
            echo    '<tr>
                        <td>'.$sonuc["ad"].'</td>
                        <td><a href = "control.php?islem=masaguncelle&masaid='.$sonuc["id"].'" class="btn btn-warning"</a>Güncelle</td>
                        <td><a href = "control.php?islem=masasil&masaid='.$sonuc["id"].'" class="btn btn-danger" data-confirm="Masayı silmek istediğinize emin misiniz?"</a>Sil</td>
                    </tr>';
            
        endwhile;

        echo '</tbody>
            </table>';

    }
    
    // Yönetici masa sil fonksiyonu
    public function masasil ($vt) {
        $masaid = $_GET["masaid"];

        if ($masaid != "" && is_numeric($masaid)):
            $this->genelsorgu($vt, "delete from masalar where id=$masaid");
            $this->uyari("success", "Masa Silindi", "control.php?islem=masayon");
        else:
            $this->uyari("danger", "Hata Oluştu", "control.php?islem=masayon");

        endif;
    }

    // Yönetici masa güncelle fonksiyonu
    public function masaguncelle($vt) {

        @$buton = $_POST["buton"];

        echo '<div class="col-md-3 text-center mx-auto mt-5 table-bordered">';

        if ($buton) :
                // db işlemleri
                @$masaad = htmlspecialchars($_POST["masaad"]);
                @$masaid = htmlspecialchars($_POST["masaid"]);

                if ($masaad == "" || $masaid == "") :
                    $this->uyari("danger", "Bilgiler boş olamaz", "control.php?islem=masayon");

                else:
                    $this->genelsorgu($vt, "update masalar set ad = '$masaad' where id = $masaid");
                    $this->uyari("success", "Masa Güncellendi", "control.php?islem=masayon");

                endif;
        else:
            $masaid = $_GET["masaid"];
            $aktar = $this->genelsorgu($vt, "select * from masalar where id = $masaid")->fetch_assoc();

            echo '
                    <form action = "" method = "post">

                        <div class="col-md-12 table-light border-bottom"><h4>MASA GÜNCELLE</h4></div>
                        <div class="col-md-12 table-light"><input type = "text" name = "masaad" class = "form-control mt-3" value = "'.$aktar["ad"].'"/></div>
                        <div class="col-md-12 table-light"><input name = "buton" value = "Güncelle" type = "submit" class = "btn btn-success mt-3 mb-3"/></div>

                        <input type = "hidden" name = "masaid" value = "'.$aktar["id"].'"/>
                    </form>
                ';

        endif;

        echo '</div>';
    
    }

    // Yönetici masa ekle fonksiyonu
    public function masaekle ($vt) {

        @$buton = $_POST["buton"];

        if ($buton) :
                // db işlemleri
                @$masaad = htmlspecialchars($_POST["masaad"]);

                if ($masaad == "") :
                    $this->uyari("danger", "Masa adı boş olamaz", "control.php?islem=masayon");

                else:
                    $this->genelsorgu($vt, "insert into masalar (ad) values ('$masaad')");
                    $this->uyari("success", "Masa Eklendi", "control.php?islem=masayon");

                endif;

        else:

            echo '<div class="col-md-3 text-center mx-auto mt-5 table-bordered">
                    <form action = "" method = "post">

                        <div class="col-md-12 table-light border-bottom"><h4>MASA EKLE</h4></div>
                        <div class="col-md-12 table-light"><input type = "text" name = "masaad" class = "form-control mt-3" require /></div>
                        <div class="col-md-12 table-light"><input name = "buton" value = "Ekle" type = "submit" class = "btn btn-success mt-3 mb-3"/></div>

                    </form>
                </div>';

        endif;
    }

    // Yönetici Ürün Ekleme - Güncelleme - Silme Fonksiyonları 
    public function urunyon ($vt, $tercih) {

        if ($tercih == 1):
            // Kategori içerisinde arama
            $aramabuton = $_POST["aramabuton"];
            $urun = $_POST["urun"];

            if ($aramabuton):
                $so=$this->genelsorgu($vt, "select * from urunler where ad LIKE '%$urun%'");
            endif;

            elseif ($tercih == 2):
            // Kategorileri listeleme
                $arama = $_POST["arama"];
                $katid = $_POST["katid"];

                if ($arama):
                    $so=$this->genelsorgu($vt, "select * from urunler where katid = $katid");
                endif;
    
            elseif ($tercih == 0):
                $so=$this->genelsorgu($vt, "select * from urunler");
        endif;

        // Yönetim Paneli Ürün Arama Bölümü
        echo '<table class="table text-center table-striped table-bordered mx-auto col-md-6 mt-4 table-dark">
                <thead>
                    <tr>
                        <th><form action="control.php?islem=aramasonuc" method="post"><input type="search" name="urun" class="form-control" placeholder="Ürün adı..."/></th>

                        <th><input type="submit" name="aramabuton" value="ARA" class="btn btn-danger"/></form></th>

                        <th><form action="control.php?islem=katgore" method="post"><select name="katid" class="form-control">';

                                $kategorilistesi = $this->genelsorgu($vt, "select * from kategoriler");

                                while ($katson = $kategorilistesi->fetch_assoc()) :
                                    echo '<option value="'.$katson["id"].'">'.$katson["ad"].'</option>';
                                endwhile;

        echo '</select></th>
                    <th><input type="submit" name="arama" value="GETİR" class="btn btn-danger"/></form></th>
                    </tr>
                </thead>
             </table>';
        
        echo '<table class="table text-center table-bordered table-hover mx-auto col-md-6 mt-4">
        <thead>
            <tr>
                <th scope="col"> <a href = "control.php?islem=urunekle" class="btn btn-success">+</a> ÜRÜN ADI</th>
                <th scope="col">FİYAT</th>
                <th scope="col">GÜNCELLE</th>
                <th scope="col">SİL</th>
            </tr>
        </thead>
        <tbody>';

        while ($sonuc=$so->fetch_assoc()):
            echo    '<tr>
                        <td>'.$sonuc["ad"].'</td>
                        <td>'.$sonuc["fiyat"].'</td>
                        <td><a href = "control.php?islem=urunguncelle&urunid='.$sonuc["id"].'" class="btn btn-warning"</a>Güncelle</td>
                        <td><a href = "control.php?islem=urunsil&urunid='.$sonuc["id"].'" class="btn btn-danger" data-confirm="Ürünü silmek istediğinize emin misiniz?"</a>Sil</td>
                    </tr>';
            
        endwhile;

        echo '</tbody>
            </table>';

    }

    // Yönetici ürün silme fonksiyonu  
    public function urunsil ($vt) {
        @$urunid = $_GET["urunid"];

        if ($urunid != "" && is_numeric($urunid)):
            
            $satir = $this->genelsorgu($vt, "select * from anliksiparis where urunid = $urunid");

            if ($satir->num_rows != 0):

                echo '<div class = "alert alert-info mt-5">
                Bu ürün aşağıdaki masalarda sipariş olarak mevcut;<br>';
                while ($masabilgi = $satir->fetch_assoc()):
                    $masaid = $masabilgi["masaid"];
                    $masasonuc = $this->genelsorgu($vt, "select * from masalar where id = $masaid")->fetch_assoc();
   
                    echo "- ".$masasonuc["ad"]."<br>";
                endwhile;
                echo '</div>';
                
            else:
                $this->genelsorgu($vt, "delete from urunler where id=$urunid");
                $this->uyari("success", "Ürün Silindi", "control.php?islem=urunyon");
            endif;

            
        else:
            $this->uyari("danger", "Hata Oluştu", "control.php?islem=urunyon");

        endif;
    }

    // Yönetici ürün güncelleme fonksiyonu
    public function urunguncelle($vt) {

        @$buton = $_POST["buton"];

        echo '<div class="col-md-3 text-center mx-auto mt-5 table-bordered">';

        if ($buton) :
                // db işlemleri
                @$urunad = htmlspecialchars($_POST["urunad"]);
                @$urunid = htmlspecialchars($_POST["urunid"]);
                @$fiyat = htmlspecialchars($_POST["fiyat"]);
                @$katid = htmlspecialchars($_POST["katid"]);

                if ($urunad == "" || $urunid == "" || $fiyat == "" || $katid == "") :
                    $this->uyari("danger", "Bilgiler boş olamaz", "control.php?islem=urunyon");

                else:
                    $this->genelsorgu($vt, "update urunler set ad = '$urunad', fiyat = $fiyat, katid = $katid where id = $urunid");
                    $this->uyari("success", "Ürün Güncellendi", "control.php?islem=urunyon");

                endif;
                else:
                    $urunid = $_GET["urunid"];
                    $aktar = $this->genelsorgu($vt, "select * from urunler where id = $urunid")->fetch_assoc();
            ?>
                    <form action = "<?php $_SERVER['PHP_SELF']; ?>" method = "post">
            
            <?php

                    echo '<div class="col-md-12 table-light border-bottom"><h4>ÜRÜN GÜNCELLE</h4></div>
                        <div class="col-md-12 table-light">Ürün Adı<input type = "text" name = "urunad" class = "form-control mt-3" value = "'.$aktar["ad"].'"/></div>
                        <div class="col-md-12 table-light">Ürün Fiyat<input type = "text" name = "fiyat" class = "form-control mt-3" value = "'.$aktar["fiyat"].'"/></div>
                        <div class="col-md-12 table-light">';

                            $katid = $aktar["katid"];
                            $katcek = $this->genelsorgu($vt, "select * from kategoriler");

                            echo 'Kategori  <select name = "katid" class = "mt-3">';

                            while ($katson = $katcek->fetch_assoc()):

                                if ($katson["id"] == $katid):

                                    echo '<option value = "'.$katson["id"].'" selected = "selected">'.$katson["ad"].'</option>';

                                else:

                                    echo '<option value = "'.$katson["id"].'">'.$katson["ad"].'</option>';

                                endif;
   
                            endwhile;

                           echo '</select>';

                        echo  '</div>
                            <div class="col-md-12 table-light"><input name = "buton" value = "Güncelle" type = "submit" class = "btn btn-success mt-3 mb-3"/></div>
                            <input type = "hidden" name = "urunid" value = "'.$urunid.'"/>

                    </form>';

            endif;

        echo '</div>';
    
    }

        // Yönetici ürün ekleme fonksiyonu
        public function urunekle($vt) {

            @$buton = $_POST["buton"];
    
            echo '<div class="col-md-3 text-center mx-auto mt-5 table-bordered">';
    
            if ($buton) :
                    // db işlemleri
                    @$urunad = htmlspecialchars($_POST["urunad"]);
                    @$fiyat = htmlspecialchars($_POST["fiyat"]);
                    @$katid = htmlspecialchars($_POST["katid"]);
    
                    if ($urunad == "" || $fiyat == "" || $katid == "") :
                        $this->uyari("danger", "Bilgiler boş olamaz", "control.php?islem=urunyon");
    
                    else:
                        $this->genelsorgu($vt, "insert into urunler (katid, ad, fiyat) values ($katid, '$urunad', $fiyat)");
                        $this->uyari("success", "Ürün Eklendi", "control.php?islem=urunyon");
    
                    endif;
                    else:
                       
                ?>
                        <form action = "<?php $_SERVER['PHP_SELF']; ?>" method = "post">
                
                <?php
    
                        echo '<div class="col-md-12 table-light border-bottom"><h4>ÜRÜN EKLE</h4></div>
                            <div class="col-md-12 table-light">Ürün Adı<input type = "text" name = "urunad" class = "form-control mt-3"/></div>
                            <div class="col-md-12 table-light">Ürün Fiyat<input type = "text" name = "fiyat" class = "form-control mt-3"/></div>
                            <div class="col-md-12 table-light">';

                                $katcek = $this->genelsorgu($vt, "select * from kategoriler");
    
                                echo 'Kategori  <select name = "katid" class = "mt-3">';
    
                                while ($katson = $katcek->fetch_assoc()):

                                    echo '<option value = "'.$katson["id"].'">'.$katson["ad"].'</option>';
       
                                endwhile;
    
                               echo '</select>';
    
                            echo  '</div>
                                <div class="col-md-12 table-light"><input name = "buton" value = "EKLE" type = "submit" class = "btn btn-success mt-3 mb-3"/></div>
    
                        </form>';
    
                endif;
    
            echo '</div>';
        
        }

        // KATEGORİ KODLARI BAŞLANGIÇ
        // Kategori yönetimi ve listeleme fonksiyonu
    public function kategoriyon ($vt) {
        $so=$this->genelsorgu($vt, "select * from kategoriler");

        echo '<table class="table text-center table-striped table-bordered mx-auto col-md-6 mt-4">
        <thead>
            <tr>
                <th scope="col"> <a href = "control.php?islem=kategoriekle" class="btn btn-success">+</a> KATEGORİ ADI</th>
                <th scope="col">GÜNCELLE</th>
                <th scope="col">SİL</th>
            </tr>
        </thead>
        <tbody>';

        while ($sonuc=$so->fetch_assoc()):
            echo    '<tr>
                        <td>'.$sonuc["ad"].'</td>
                        <td><a href = "control.php?islem=kategoriguncelle&katid='.$sonuc["id"].'" class="btn btn-warning"</a>Güncelle</td>
                        <td><a href = "control.php?islem=kategorisil&katid='.$sonuc["id"].'" class="btn btn-danger" data-confirm="Kategoriyi silmek istediğinize emin misiniz?"</a>Sil</td>
                    </tr>';
            
        endwhile;

        echo '</tbody>
            </table>';

    }
    
    // Yönetici kategori silme fonksiyonu
    public function kategorisil ($vt) {
        $katid = $_GET["katid"];

        if ($katid != "" && is_numeric($katid)):
            $this->genelsorgu($vt, "delete from kategoriler where id=$katid");
            $this->uyari("success", "Kategori Silindi", "control.php?islem=kategoriyon");
        else:
            $this->uyari("danger", "Hata Oluştu", "control.php?islem=kategoriyon");

        endif;
    }

    // Yönetici kategori ekleme fonksiyonu
    public function kategoriekle ($vt) {

        @$buton = $_POST["buton"];
        
        //echo '<div class="col-md-3 table-light text-center mx-auto mt-5 table-bordered" style="border-radius:10px;">';

        if ($buton) :
                // db işlemleri
                @$katad = htmlspecialchars($_POST["katad"]);
                @$mutfakdurum = htmlspecialchars($_POST["mutfakdurum"]);

                if ($katad == "") :
                    $this->uyari("danger", "Kategori adı boş olamaz", "control.php?islem=kategoriyon");

                else:
                    $this->genelsorgu($vt, "insert into kategoriler (ad, mutfakdurum) values ('$katad', $mutfakdurum)");
                    $this->uyari("success", "Kategori Eklendi", "control.php?islem=kategoriyon");

                endif;

        else:
            ?>

            <div class="col-md-3 text-center mx-auto mt-5 table-bordered">
                    <form action = "<?php $_SERVER['PHP_SELF'];?>" method = "post">
            <?php
                    echo '<div class="col-md-12 table-light border-bottom"><h4 class = "mt-2">KATEGORİ EKLE</h4></div>
                        <div class="col-md-12 table-light"><input type = "text" name = "katad" class = "form-control mt-3" require placeholder="Kategori Adı"/></div>
                            <div class="col-md-12">
                                <select name="mutfakdurum" class="form-control mt-3">
                                    <option value="0">Mutfak Uygun</option>
                                    <option value="1">Mutfak Dışı</option>
                                </select>
                            </div>
                        <div class="col-md-12 table-light"><input name = "buton" value = "Ekle" type = "submit" class = "btn btn-success mt-3 mb-3"/></div>

                    </form>';

        endif;
        
        echo '</div>';
    }

    // Yönetici kategori güncelle fonksiyonu
    public function kategoriguncelle($vt) {

        @$buton = $_POST["buton"];

        echo '<div class="col-md-3 text-center mx-auto mt-5 table-bordered">';

        if ($buton) :
                // db işlemleri
                @$katad = htmlspecialchars($_POST["katad"]);
                @$katid = htmlspecialchars($_POST["katid"]);
                @$mutfakdurum = htmlspecialchars($_POST["mutfakdurum"]);

                if ($katad == "" || $katid == "") :
                    $this->uyari("danger", "Kategori adı boş olamaz", "control.php?islem=kategoriyon");

                else:
                    $this->genelsorgu($vt, "update kategoriler set ad='$katad', mutfakdurum=$mutfakdurum where id = $katid");
                    $this->uyari("success", "Kategori Güncellendi", "control.php?islem=kategoriyon");

                endif;
                else:
                    $katid = $_GET["katid"];
                    $aktar = $this->genelsorgu($vt, "select * from kategoriler where id = $katid")->fetch_assoc();
            ?>
                    <form action = "<?php $_SERVER['PHP_SELF']; ?>" method = "post">
            
            <?php

                    echo '<div class="col-md-12 table-light border-bottom"><h4>KATEGORİ GÜNCELLE</h4></div>
                            <div class="col-md-12 table-light">Kategori Adı<input type = "text" name = "katad" class = "form-control mt-3" value = "'.$aktar["ad"].'"/></div>
                                
                                <div class="col-md-12 text-danger mt-2">
                                    <select name="mutfakdurum" class="form-control mt-3">';
                    
                                        if($aktar["mutfakdurum"] == 0):
                                            
                                            echo '<option value="0" selected="selected">Mutfak Uygun</option>   
                                                    <option value="1" selected="selected">Mutfak Dışı</option>';
                                        
                                            else:
                                                
                                                echo '<option value="1" selected="selected">Mutfak Dışı</option>
                                                        <option value="0">Mutfak Uygun</option>';

                                        endif;
   
                                   echo ' </select>
                                </div>                        
                                    <div class="col-md-12 table-light"><input name = "buton" value = "Güncelle" type = "submit" class = "btn btn-success mt-3 mb-3"/></div>
                                    <input type = "hidden" name = "katid" value = "'.$katid.'"/>
                        </form>';

                endif;

        echo '</div>';
    
    }

        // GARSON YÖNETİM KODLARI BAŞLANGIÇ
        // Garson yönetimi ve listeleme fonksiyonu
    public function garsonyon ($vt) {
        $so=$this->genelsorgu($vt, "select * from garson");

        echo '<table class="table text-center table-striped table-bordered mx-auto col-md-6 mt-4">
        <thead>
            <tr>
                <th scope="col"> <a href = "control.php?islem=garsonekle" class="btn btn-success">+</a> GARSON ADI</th>
                <th scope="col">ŞİFRE</th>
                <th scope="col">GÜNCELLE</th>
                <th scope="col">SİL</th>
            </tr>
        </thead>
        <tbody>';

        while ($sonuc=$so->fetch_assoc()):
            echo    '<tr>
                        <td>'.$sonuc["ad"].'</td>
                        <td>'.$sonuc["sifre"].'</td>
                        <td><a href = "control.php?islem=garsonguncelle&garsonid='.$sonuc["id"].'" class="btn btn-warning"</a>Güncelle</td>
                        <td><a href = "control.php?islem=garsonsil&garsonid='.$sonuc["id"].'" class="btn btn-danger" data-confirm="Garsonu silmek istediğinize emin misiniz?">Sil</a></td>
                    </tr>';
            
        endwhile;

        echo '</tbody>
            </table>';

    }
    
    // Garson silme fonksiyonu
    public function garsonsil ($vt) {
        $garsonid = $_GET["garsonid"];

        if ($garsonid != "" && is_numeric($garsonid)):
            $this->genelsorgu($vt, "delete from garson where id=$garsonid");
            $this->uyari("success", "Garson Silindi", "control.php?islem=garsonyon");
        else:
            $this->uyari("danger", "Hata Oluştu", "control.php?islem=garsonyon");

        endif;
    }

    // Garson ekleme fonksiyonu
    public function garsonekle ($vt) {

        @$buton = $_POST["buton"];
        
        echo '<div class="col-md-3 text-center mx-auto mt-5">';

        if ($buton) :
                
                @$garsonad = htmlspecialchars($_POST["garsonad"]);
                @$garsonsifre = htmlspecialchars($_POST["garsonsifre"]);
                  $garsonsifre=md5(sha1($garsonsifre));
                if ($garsonad == "" || $garsonsifre == "") :
                    $this->uyari("danger", "Garson adı veya şifre boş olamaz", "control.php?islem=garsonyon");
                    // db işlemleri
                else:
                    $this->genelsorgu($vt, "insert into garson (ad, sifre) values ('$garsonad', '$garsonsifre')");
                    $this->uyari("success", "Garson Eklendi", "control.php?islem=garsonyon");

                endif;

        else:
            ?>
                    <form action = "<?php $_SERVER['PHP_SELF']; ?>" method = "post">
            <?php
                    echo '<div class="col-md-12 table-light border-bottom"><h4>GARSON EKLE</h4></div>
                            <div class="col-md-12 table-light"><input type = "text" name = "garsonad" placeholder="Ad" class = "form-control mt-3" require /></div>
                            <div class="col-md-12 table-light"><input type = "password" name = "garsonsifre" placeholder="Şifre" class = "form-control mt-3" require /></div>
                            <div class="col-md-12 table-light"><input name = "buton" value = "Ekle" type = "submit" class = "btn btn-success mt-3 mb-3"/></div>
                    </form>';

        endif;
    }

    // Garson güncelle fonksiyonu
    public function garsonguncelle($vt) {

        @$buton = $_POST["buton"];

        echo '<div class="col-md-3 text-center mx-auto mt-5 table-bordered">';

        if ($buton) :
                // db işlemleri
                @$garsonad = htmlspecialchars($_POST["garsonad"]);
                @$garsonsifre = htmlspecialchars($_POST["garsonsifre"]);               
                @$garsonid = htmlspecialchars($_POST["garsonid"]);

                if ($garsonad == "" || $garsonsifre == "") :
                    $this->uyari("danger", "Bilgiler boş olamaz", "control.php?islem=garsonyon");

                else:
                    $this->genelsorgu($vt, "update garson set ad = '$garsonad', sifre = '$garsonsifre' where id = $garsonid");
                    $this->uyari("success", "Garson Güncellendi", "control.php?islem=garsonyon");

                endif;
                else:
                    $garsonid = $_GET["garsonid"];
                    $aktar = $this->genelsorgu($vt, "select * from garson where id = $garsonid")->fetch_assoc();
            ?>
                    <form action = "<?php $_SERVER['PHP_SELF'] ?>" method = "post">
            
            <?php

                    echo '<div class="col-md-12 table-light border-bottom"><h4>GARSON GÜNCELLE</h4></div>
                            <div class="col-md-12 table-light">Garson Adı<input type = "text" name = "garsonad" class = "form-control mt-3" value = "'.$aktar["ad"].'"/></div>
                            <div class="col-md-12 table-light">Garson Şifre<input type = "password" name = "garsonsifre" class = "form-control mt-3" value = "'.$aktar["sifre"].'"/></div>
                            <div class="col-md-12 table-light"><input name = "buton" value = "Güncelle" type = "submit" class = "btn btn-success mt-3 mb-3"/></div>
                            <input type = "hidden" name = "garsonid" value = "'.$garsonid.'"/>
                        </form>';

                endif;

        echo '</div>';
        
        
        
    }
    
    // Garson performans işlemleri ve ekranı
    public function garsonper($vt) {

            @$tarih = $_GET["tar"];
            switch ($tarih):
                    
                case "ay":
                    $this->genelsorgu($vt, "Truncate gecicigarson");
                    $veri = $this->genelsorgu($vt, "select * from rapor where tarih >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
                    $veri2 = $this->genelsorgu($vt, "select * from rapor where tarih >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
                break;
               
                case "tarih":
                    $this->genelsorgu($vt, "Truncate gecicigarson");
                    $tarih1 = $_POST["tarih1"];
                    $tarih2 = $_POST["tarih2"];
                    echo '<div class = "alert alert-info text-center mx-auto mt-4">

                       Tarih Seçimi: '.$tarih1.' - '.$tarih2.'

                    </div>';
                    $veri = $this->genelsorgu($vt, "select * from rapor where DATE(tarih) BETWEEN '$tarih1' AND '$tarih2'");
                    $veri2 = $this->genelsorgu($vt, "select * from rapor where DATE(tarih) BETWEEN '$tarih1' AND '$tarih2'");
                break;
                   
                default;
                    $this->genelsorgu($vt, "Truncate gecicigarson");
                    $veri = $this->genelsorgu($vt, "select * from rapor where tarih >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
                    $veri2 = $this->genelsorgu($vt, "select * from rapor where tarih >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
                break;

            endswitch;
     
            echo '<table class = "table text-center table-light table-bordered mx-auto mt-4 table-striped col-md-10">
                
                    <thead>
                        <tr>
                            <th colspan="4">';
            
                                if(@$tarih1!="" || @$tarih2!=""):
                                    echo '<a href="cikti.php?islem=garsoncikti&tar1='.$tarih1.'&tar2='.$tarih2.'" onclick="ortasayfa(this.href,\'mywindow\',\'900\',\'800\',\'yes\');return false" class = "btn btn-warning">YAZDIR</a>';
                                
                                    echo '<a href="garsonexcel.php?tar1='.$tarih1.'&tar2='.$tarih2.'" class = "btn btn-info">EXCEL AKTAR</a>';
                                endif;
                            

                            echo '<thead>
                            <tr>
                                <th><a href="control.php?islem=garsonper&tar=ay">Bu Ay</a></th>
                                <form action="control.php?islem=garsonper&tar=tarih" method="post">
                                <th><input type="date" name = "tarih1" class="form-control col-md-10"></th>
                                <th><input type="date" name = "tarih2" class="form-control col-md-10"></th>
              
                            <th><input name="buton" type="submit" class="btn btn-danger" value="RAPOR"></form></th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th colspan = "4">
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
                                </thead><tbody>';

                                $kilit = $this->genelsorgu($vt, "select * from gecicigarson");
                                if($kilit->num_rows==0) :

                                    while ($gel = $veri->fetch_assoc()):
                                        // Raporlama için masa adını çekiyorum
                                        $garsonid = $gel["garsonid"];
                                        $masaveri = $this->genelsorgu($vt, "select * from garson where id=$garsonid")->fetch_assoc();
                                        $garsonad = $masaveri["ad"];
                                        // Raporlama için masa adını çekiyorum

                                        $raporbak = $this->genelsorgu($vt, "select * from gecicigarson where garsonid=$garsonid");

                                        if ($raporbak->num_rows==0) :
                                            // gecici masaya, sipariş verilmediyse ekleme yap
                                            // $has = $gel["adet"] * $gel["urunfiyat"];
                                            $adet = $gel["adet"];
                                            $this->genelsorgu($vt, "insert into gecicigarson (garsonid, garsonad, adet) values ($garsonid, '$garsonad', $adet)");

                                        else:
                                            // yada güncelleme yap
                                            $raporson = $raporbak->fetch_assoc();
                                            $gelenadet = $raporson["adet"];

                                            $sonadet = $gelenadet + $gel["adet"];

                                            $this->genelsorgu($vt, "update gecicigarson set adet=$sonadet where garsonid=$garsonid");

                                        endif;

                                    endwhile;

                                endif;

                                $son = $this->genelsorgu($vt, "select * from gecicigarson order by adet desc;");

                                $toplamadet = 0;

                                while ($listele = $son->fetch_assoc()):
                                    echo '<tr>
                                            <td colspan="2">'.$listele["garsonad"].'</td>
                                            <td colspan="2">'.$listele["adet"].'</td>
                                        </tr>';
                                        $toplamadet += $listele["adet"];
                                endwhile;

                        echo '<tr class="table-danger">
                                <td colspan="2">TOPLAM</td>
                                <td colspan="2">'.$toplamadet.'</td>
                            </tr>
                        
                                </tbody> 
                                    </table>                   
                                        </th>

                            </tr>
                                </tbody>
                                    </table>';

        }
        
    // Yönetici Ekleme ve Yönetici Ayarları Bölümü

    public function yoneticiayar ($vt) {
        
       // $this->yoneticiYetkiKontrol($vt);
        
        $so=$this->genelsorgu($vt, "select * from yonetim");

        echo '<table class="table text-center table-striped table-bordered mx-auto col-md-6 mt-4">
        <thead>
            <tr>
                <th scope="col"> <a href = "control.php?islem=yoneticiekle" class="btn btn-success">+</a> YÖNETİCİ ADI</th>
                <th scope="col">GÜNCELLE</th>
                <th scope="col">SİL</th>
            </tr>
        </thead>
        <tbody>';

        while ($sonuc=$so->fetch_assoc()):
            echo    '<tr>
                        <td>'.$sonuc["kulad"].'</td>
                        <td><a href = "control.php?islem=yoneticiguncelle&yonid='.$sonuc["id"].'" class="btn btn-warning"</a>Güncelle</td>';
                            
                        $sonuc["yetki"]==1 ? $durum = "disabled" : $durum = "";

            echo    '<td><a href = "control.php?islem=yoneticisil&yonid='.$sonuc["id"].'" class="btn btn-danger '.$durum.'" data-confirm="Yöneticiyi silmek istediğinize emin misiniz?"</a>Sil</td>
                    </tr>';
            
        endwhile;

        echo '</tbody>
            </table>';

    }
    // Yönetici silme fonksiyonu
    public function yoneticisil ($vt) {
        
        $this->yoneticiYetkiKontrol($vt);
        
        $yonid = $_GET["yonid"];

        if ($yonid != "" && is_numeric($yonid)):
            $this->genelsorgu($vt, "delete from yonetim where id=$yonid");
            $this->uyari("success", "Yönetici Silindi", "control.php?islem=yonayar");
        else:
            $this->uyari("danger", "Hata Oluştu", "control.php?islem=yonayar");

        endif;
    }

    // Yönetici ekleme fonksiyonu
    public function yoneticiekle ($vt) {
        
        $this->yoneticiYetkiKontrol($vt);

        @$buton = $_POST["buton"];

        if ($buton) :
                // db işlemleri
                @$yonad = htmlspecialchars($_POST["yonad"]);
                @$yonsifre = htmlspecialchars($_POST["yonsifre"]);
                
                $yonsifre=md5(sha1(md5($yonsifre)));

                if ($yonad == "" || $yonsifre == "") :
                    $this->uyari("danger", "Yönetici adı boş olamaz", "control.php?islem=yonayar");

                else:
                    
                    $this->genelsorgu($vt, "insert into yonetim (kulad, sifre) values ('$yonad', '$yonsifre')");
                    $this->uyari("success", "Yönetici Eklendi", "control.php?islem=yonayar");

                endif;

        else:
            ?>

            <div class="col-md-3 text-center mx-auto mt-5 table-bordered">
                    <form action = "<?php $_SERVER['PHP_SELF'] ?>" method = "post">
            <?php
                    echo '<div class="col-md-12 table-light border-bottom"><h4>YÖNETİCİ EKLE</h4></div>
                        <div class="col-md-12 table-light"><input type = "text" name = "yonad" class = "form-control mt-3" require placeholder = "Yönetici Adı"/></div>
                        <div class="col-md-12 table-light"><input type = "password" name = "yonsifre" class = "form-control mt-3" require placeholder = "Yönetici Şifre"/></div>
                        <div class="col-md-12 table-light"><input name = "buton" value = "Ekle" type = "submit" class = "btn btn-success mt-3 mb-3"/></div>

                    </form>
                </div>';

        endif;
    }

    // Yönetici güncelle fonksiyonu
    public function yoneticiguncelle($vt) {
        
        $this->yoneticiYetkiKontrol($vt);

        @$buton = $_POST["buton"];

        echo '<div class="col-md-3 text-center mx-auto mt-5 table-bordered">';

        if ($buton) :
                // db işlemleri
                @$yonad = htmlspecialchars($_POST["yonad"]);
                @$yonid = htmlspecialchars($_POST["yonid"]);
                @$yetki = htmlspecialchars($_POST["yetki"]);
                
               // $yonsifre=md5(sha1(md5($yonsifre)));

                if ($yonad == "" || $yonid == "") :
                    $this->uyari("danger", "Bilgiler boş olamaz", "control.php?islem=yonayar");

                else:
                    $this->genelsorgu($vt, "update yonetim set kulad = '$yonad', yetki='$yetki' where id = $yonid");
                    $this->uyari("success", "Yönetici Güncellendi", "control.php?islem=yonayar");

                endif;
                else:
                    $yonid = $_GET["yonid"];
                    $aktar = $this->genelsorgu($vt, "select * from yonetim where id = $yonid")->fetch_assoc();
            ?>
                    <form action = "<?php $_SERVER['PHP_SELF'] ?>" method = "post">
            
            <?php

                    echo '<div class="col-md-12 table-light border-bottom"><h4>YÖNETİCİ GÜNCELLE</h4></div>
                            <div class="col-md-12 table-light">Yönetici Adı<input type = "text" name = "yonad" class = "form-control mt-3" value = "'.$aktar["kulad"].'"/></div>
                            <div class="col-md-12 table-light">Yetki
                            <select name="yetki" class="form-select" aria-label="Default select example">
 
  <option value="0" selected>Yetki Verme </option>
  <option value="1">Yetki Ver</option>
 
</select>
                            </div>
                            <div class="col-md-12 table-light"><input name = "buton" value = "Güncelle" type = "submit" class = "btn btn-success mt-3 mb-3"/></div>
                            <input type = "hidden" name = "yonid" value = "'.$yonid.'"/>
                        </form>';

                endif;

        echo '</div>';
    
    } 
        
        // Yönetici şifre Değiştirme fonksiyonu
        public function sifredegistir ($vt) {

            @$buton = $_POST["buton"];
    
            if ($buton) :
                    // db işlemleri
                    @$eskisifre = htmlspecialchars($_POST["eskisifre"]);
                    @$yeni1 = htmlspecialchars($_POST["yeni1"]);
                    @$yeni2 = htmlspecialchars($_POST["yeni2"]);
    
                    if ($eskisifre == "" || $yeni1 == "" || $yeni2 == "") :
                        $this->uyari("danger", "Bilgiler boş olamaz", "control.php?islem=sifredegistir");
    
                    else:
                        $eskisifreson=md5(sha1(md5($eskisifre)));
                        $id=$this->coz($_COOKIE["id"]); 
                        if($this->genelsorgu($vt, "select * from yonetim where sifre = '$eskisifreson' and id='$id' ")->num_rows == 0) :
                            //Kayıt yoksa eski şifre hatalı
                            $this->uyari("danger", "Eski şifre hatalı", "control.php?islem=sifredegistir");

                        elseif($yeni1 != $yeni2):
                            $this->uyari("danger", "Yeni şifreler aynı değil", "control.php?islem=sifredegistir");

                        else:
                            $yenisifreson=md5(sha1(md5($yeni1)));
                        
                            $id=$this->coz($_COOKIE["id"]); 
                            
                            $this->genelsorgu($vt, "update yonetim set sifre = '$yenisifreson' where id=$id");
                            $this->uyari("success", "Şifre Değiştirildi", "control.php");

                        endif;

                    endif;  

            else:
                ?>
    
                <div class="col-md-3 text-center mx-auto mt-5">
                        <form action = "<?php $_SERVER['PHP_SELF'] ?>" method = "post">
                <?php
                        echo '<div class="col-md-12 table-light border-bottom"><h4>ŞİFRE DEĞİŞTİR</h4></div>
                            <div class="col-md-12 table-light"><input type = "password" name = "eskisifre" class = "form-control mt-3" require placeholder="Eski Şifreniz"/></div>
                            <div class="col-md-12 table-light"><input type = "password" name = "yeni1" class = "form-control mt-3" require placeholder="Yeni Şifreniz"/></div>
                            <div class="col-md-12 table-light"><input type = "password" name = "yeni2" class = "form-control mt-3" require placeholder="Yeni Şifreniz Tekrar"/></div>
                            <div class="col-md-12 table-light"><input name = "buton" value = "Değiştir" type = "submit" class = "btn btn-success mt-3 mb-3"/></div>
    
                        </form>
                    </div>';
    
            endif;
        }

        // Yönetici Raporlama Fonksiyonu
        public function rapor($vt) {

            @$tarih = $_GET["tar"];
            switch ($tarih):

                case "bugun":
                    $this->genelsorgu($vt, "Truncate gecicimasa");
                    $this->genelsorgu($vt, "Truncate geciciurun");
                    $veri = $this->genelsorgu($vt, "select * from rapor where tarih = CURDATE()");
                    $veri2 = $this->genelsorgu($vt, "select * from rapor where tarih = CURDATE()");
                break;

                case "dun":
                    $this->genelsorgu($vt, "Truncate gecicimasa");
                    $this->genelsorgu($vt, "Truncate geciciurun");
                    $veri = $this->genelsorgu($vt, "select * from rapor where tarih = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
                    $veri2 = $this->genelsorgu($vt, "select * from rapor where tarih = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
                break;

                case "hafta":
                    $this->genelsorgu($vt, "Truncate gecicimasa");
                    $this->genelsorgu($vt, "Truncate geciciurun");
                    $veri = $this->genelsorgu($vt, "select * from rapor where YEARWEEK(tarih) = YEARWEEK(CURRENT_DATE)");
                    $veri2 = $this->genelsorgu($vt, "select * from rapor where YEARWEEK(tarih) = YEARWEEK(CURRENT_DATE)");
                break;
                    
                case "ay":
                    $this->genelsorgu($vt, "Truncate gecicimasa");
                    $this->genelsorgu($vt, "Truncate geciciurun");
                    $veri = $this->genelsorgu($vt, "select * from rapor where tarih >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
                    $veri2 = $this->genelsorgu($vt, "select * from rapor where tarih >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
                break;
                    
                case "tum":
                    $this->genelsorgu($vt, "Truncate gecicimasa");
                    $this->genelsorgu($vt, "Truncate geciciurun");
                    $veri = $this->genelsorgu($vt, "select * from rapor");
                    $veri2 = $this->genelsorgu($vt, "select * from rapor");
                break;

                case "tarih":
                    $this->genelsorgu($vt, "Truncate gecicimasa");
                    $this->genelsorgu($vt, "Truncate geciciurun");
                    $tarih1 = $_POST["tarih1"];
                    $tarih2 = $_POST["tarih2"];
                    echo '<div class = "alert alert-info text-center mx-auto mt-4">

                       Tarih Seçimi: '.$tarih1.' - '.$tarih2.'

                    </div>';
                    $veri = $this->genelsorgu($vt, "select * from rapor where DATE(tarih) BETWEEN '$tarih1' AND '$tarih2'");
                    $veri2 = $this->genelsorgu($vt, "select * from rapor where DATE(tarih) BETWEEN '$tarih1' AND '$tarih2'");
                break;
                   
                default;
                    $this->genelsorgu($vt, "Truncate gecicimasa");
                    $this->genelsorgu($vt, "Truncate geciciurun");
                    $veri = $this->genelsorgu($vt, "select * from rapor where YEARWEEK(tarih) = YEARWEEK(CURRENT_DATE)");
                    $veri2 = $this->genelsorgu($vt, "select * from rapor where YEARWEEK(tarih) = YEARWEEK(CURRENT_DATE)");
                break;

            endswitch;
            /*
            bugun
            dun
            hafta
            ay
            tum
            */

            echo '<table class = "table text-center table-light table-bordered mx-auto mt-4 table-striped col-md-10">
                
                    <thead>
                        <tr>
                            <th colspan="8">';
            
                                if(@$tarih1!="" || @$tarih2!=""):
                                    echo '<a href="cikti.php?islem=ciktial&tar1='.$tarih1.'&tar2='.$tarih2.'" onclick="ortasayfa(this.href,\'mywindow\',\'900\',\'800\',\'yes\');return false" class = "btn btn-warning">YAZDIR</a>';
                                
                                    echo '<a href="excel.php?tar1='.$tarih1.'&tar2='.$tarih2.'" class = "btn btn-info">EXCEL AKTAR</a>';
                                endif;
                            

                            echo '</th>
                        </tr>
                    </thead>

                    <thead>
                        <tr>
                            <th><a href="control.php?islem=rapor&tar=bugun">Bugün</a></th>
                            <th><a href="control.php?islem=rapor&tar=dun">Dün</a></th>
                            <th><a href="control.php?islem=rapor&tar=hafta">Bu Hafta</a></th>
                            <th><a href="control.php?islem=rapor&tar=ay">Bu Ay</a></th>
                            <th><a href="control.php?islem=rapor&tar=tum">Tüm Zamanlar</a></th>
                            <th colspan="2"><form action="control.php?islem=rapor&tar=tarih" method="post">
                                <input type="date" name = "tarih1" class="form-control col-md-10">
                                <input type="date" name = "tarih2" class="form-control col-md-10">
                            </th>
                            <th><input name="buton" type="submit" class="btn btn-danger" value="RAPOR"></form></th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th colspan = "4">
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
                                </thead><tbody>';

                                $kilit = $this->genelsorgu($vt, "select * from gecicimasa");
                                if($kilit->num_rows==0) :

                                    while ($gel = $veri->fetch_assoc()):
                                        // Raporlama için masa adını çekiyorum
                                        $id = $gel["masaid"];
                                        $masaveri = $this->genelsorgu($vt, "select * from masalar where id=$id")->fetch_assoc();
                                        $masaad = $masaveri["ad"];
                                        // Raporlama için masa adını çekiyorum

                                        $raporbak = $this->genelsorgu($vt, "select * from gecicimasa where masaid=$id");

                                        if ($raporbak->num_rows==0) :
                                            // gecici masaya, sipariş verilmediyse ekleme yap
                                            $has = $gel["adet"] * $gel["urunfiyat"];
                                            $adet = $gel["adet"];
                                            $this->genelsorgu($vt, "insert into gecicimasa (masaid, masaad, hasilat, adet) values ($id, '$masaad', $has, $adet)");

                                        else:
                                            // yada güncelleme yap
                                            $raporson = $raporbak->fetch_assoc();
                                            $gelenadet = $raporson["adet"];
                                            $gelenhas = $raporson["hasilat"];

                                            $sonhasilat = $gelenhas + ($gel["adet"] * $gel["urunfiyat"]);
                                            $sonadet = $gelenadet + $gel["adet"];

                                            $this->genelsorgu($vt, "update gecicimasa set hasilat=$sonhasilat, adet=$sonadet where masaid=$id");

                                        endif;

                                    endwhile;

                                endif;

                                $son = $this->genelsorgu($vt, "select * from gecicimasa order by hasilat desc;");

                                $toplamadet = 0;
                                $toplamhasilat = 0;

                                while ($listele = $son->fetch_assoc()):
                                    echo '<tr>
                                            <td colspan="2">'.$listele["masaad"].'</td>
                                            <td colspan="1">'.$listele["adet"].'</td>
                                            <td colspan="1">'.number_format($listele["hasilat"],2,'.','.').'</td>
                                        </tr>';
                                        $toplamadet += $listele["adet"];
                                        $toplamhasilat += $listele["hasilat"];
                                endwhile;

                        echo '<tr class="table-danger">
                                <td colspan="2">TOPLAM</td>
                                <td colspan="1">'.$toplamadet.'</td>
                                <td colspan="1">'. number_format($toplamhasilat,2,'.','.').'</td>
                            </tr>
                        
                                </tbody> 
                                    </table>                   
                                        </th>
                                            <th colspan = "4">
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
                                </thead><tbody>';
                                
                                $kilit2 = $this->genelsorgu($vt, "select * from geciciurun");
                                if($kilit2->num_rows==0) :

                                    while ($gel2 = $veri2->fetch_assoc()):
                                        // Raporlama için ürün id ve ad çekiyorum
                                        $id = $gel2["urunid"];
                                        $urunad = $gel2["urunad"];
                                        
                                        $raporbak = $this->genelsorgu($vt, "select * from geciciurun where urunid=$id");

                                        if ($raporbak->num_rows==0) :
                                            // gecici masaya, sipariş verilmediyse ekleme yap
                                            $has = $gel2["adet"] * $gel2["urunfiyat"];
                                            $adet = $gel2["adet"];
                                            $this->genelsorgu($vt, "insert into geciciurun (urunid, urunad, hasilat, adet) values ($id, '$urunad', $has, $adet)");

                                        else:
                                            // yada güncelleme yap
                                            $raporson = $raporbak->fetch_assoc();
                                            $gelenadet = $raporson["adet"];
                                            $gelenhas = $raporson["hasilat"];

                                            $sonhasilat = $gelenhas + ($gel2["adet"] * $gel2["urunfiyat"]);
                                            $sonadet = $gelenadet + $gel2["adet"];

                                            $this->genelsorgu($vt, "update geciciurun set hasilat=$sonhasilat, adet=$sonadet where urunid=$id");

                                        endif;

                                    endwhile;

                                endif;

                                $son2 = $this->genelsorgu($vt, "select * from geciciurun order by hasilat desc;");

                                $toplamadet2 = 0;
                                $toplamhasilat2 = 0;

                                while ($listele2 = $son2->fetch_assoc()):
                                    echo '<tr>
                                            <td colspan="2">'.$listele2["urunad"].'</td>
                                            <td colspan="1">'.$listele2["adet"].'</td>
                                            <td colspan="1">'.number_format($listele2["hasilat"],2,'.','.').'</td>
                                        </tr>';
                                        $toplamadet2 += $listele2["adet"];
                                        $toplamhasilat2 += $listele2["hasilat"];
                                endwhile;

                        echo '<tr class="table-danger">
                                <td colspan="2">TOPLAM</td>
                                <td colspan="1">'.$toplamadet2.'</td>
                                <td colspan="1">'.number_format($toplamhasilat2,2,'.','.').'</td>
                            </tr>
                        
                            </tbody> 
                        </table>    
                    </th>
                </tr>
            </tbody>
        </table>';

        }
        
    // Yönetici yetki kontrol fonksiyonları yetkisi olan yönetici; yönetim ayarlarını görecek
    public function linkkontrol($vt) {
        
        $id=$this->coz($_COOKIE["id"]);      
        $sorgu = "select * from yonetim where id=$id";
        $gelensonuc = $this->genelsorgu($vt, $sorgu);
        $b = $gelensonuc->fetch_assoc();
        
        if($b["yetki"]==1):
            
            echo '<div class = "col-md-12 bg-light p-2 pl-3 border-bottom text-white">
                        <a href="control.php?islem=yonayar" id="lk">Yönetici Ayarları</a>
                    </div>';
         
        endif;
    
    }
    
    // tarayıcıda adres satirindan switch caselerden ulaşamaması için yönetici yetkisini kontrol ediyorum.
    public function yoneticiYetkiKontrol($vt) {
        
        $id=$this->coz($_COOKIE["id"]);      
        $sorgu = "select * from yonetim where id=$id";
        $gelensonuc = $this->genelsorgu($vt, $sorgu);
        $b = $gelensonuc->fetch_assoc();
        
        if($b["yetki"]==0):
            
            header("Location:control.php"); // YÖnetici yetkisi yoksa control.php ye yönelendir.
         
        endif;
        
    }
  
    // Yönetici şifreleri için base64 şifreleme ve şifre çözme fonksiyonları
    // Şifreleme Fonksiyonu
    public function sifrele($veri){
        
       return base64_encode(gzdeflate(gzcompress(serialize($veri)))); 
       
    }
    
    // Şifre Çözme Fonksiyonu
    public function coz($veri){
        
       return unserialize(gzuncompress(gzinflate(base64_decode($veri))));
        
    }
        
    // Yönetici Kullanıcı adı için
    public function kulad($db) {
        
        $id=$this->coz($_COOKIE["id"]);      
        $sorgu = "select * from yonetim where id=$id";
        $gelensonuc = $this->genelsorgu($db, $sorgu);
        $b = $gelensonuc->fetch_assoc();
        return $b["kulad"];
    }
    
    // Yönetici çıkış fonksiyonu
    public function cikis ($r, $deger) {
       
        $id=$this->coz($_COOKIE["id"]);
        $sorgu = "update yonetim set aktif=0 where id=$id";
        $sor=$r->prepare($sorgu);
        $sor->execute();
        $deger=md5(sha1(md5($deger)));       
        setcookie("kul", $deger, time() - 10);
        setcookie("id", $_COOKIE["id"], time() - 10);
        $this->uyari("success", "Çıkış Yapılıyor", "index.php");
    }
  
    public function giriskont($r, $k, $s){
        
        $sonhal=md5(sha1(md5($s)));
        $sorgu = "select * from yonetim where kulad = '$k' and sifre = '$sonhal'";
        $sor=$r->prepare($sorgu);
        $sor->execute();
        $sonbilgi=$sor->get_result();
        $veri=$sonbilgi->fetch_assoc();
        if ($sonbilgi->num_rows == 0) :

            $this->uyari("danger", "Bilgiler Hatalı", "index.php");

        else:
            
            $sorgu = "update yonetim set aktif=1 where kulad = '$k' and sifre = '$sonhal'";
            $sor=$r->prepare($sorgu);
            $sor->execute();
            
            $this->uyari("success", "Hoşgeldiniz", "control.php");

        // cookie oluşturacağım 
            $kulson=md5(sha1(md5($k)));       
            setcookie("kul", $kulson, time() + 60*60*24); //şifre için cookie yapmaya gerek yok.60*60*24
            $id=$this->sifrele($veri["id"]);
            setcookie("id", $id, time() + 60*60*24);

        endif;

    }
    
   //cookie olup olmadığını kontrol ediyorum, cookie değeri ile db deki değeri uyuşmuyorsa kullanıcı control.php de kalıyor.  
   public function cookcon($d, $durum=false) {

        if (isset($_COOKIE["kul"])) :

             $deger = $_COOKIE["kul"];
             
             $id=$this->coz($_COOKIE["id"]);
             
             $sorgu = "select * from yonetim where id=$id";
             $sor=$d->prepare($sorgu);
             $sor->execute();            
             $sonbilgi=$sor->get_result();
             $veri = $sonbilgi->fetch_assoc();
             $sonhal=md5(sha1(md5($veri["kulad"])));

            if ($sonhal != $_COOKIE["kul"]) :
                setcookie("kul", $deger, time() - 10);
                header("Location:index.php");

            else:

                if ($durum==true) : header("Location:control.php"); 
                endif;

            endif;

            else:
                
               if ($durum==false) : header("Location:index.php"); 
               endif;

            endif;
            

   }
    
}

?>
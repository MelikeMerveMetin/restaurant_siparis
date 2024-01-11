<?php

$db = new mysqli("localhost","root","","restoransiparis")or die ("Bağlanamadı");
$db->set_charset("utf8");


class Siparis  {
	
	
			private function benimsorum($vt,$sorgu,$tercih) {//silme  güncelleme işlemlerinde get_resulta gerek olmadığı için tercih değişkeni kondu
				
					$a=$sorgu;
					$b=$vt->prepare($a);
					$b->execute();
					if ($tercih==1):
					return $c=$b->get_result();	//sorgu sonucu dönen sayı			
					endif;				
				
			} // baba fonksiyon
		
	    function sorgular($vt,$sorgu,$tercih) {
				
					$a=$sorgu;
					$b=$vt->prepare($a);
					$b->execute();
					if ($tercih==1):
					return $c=$b->get_result();				
					endif;								
				
			}
			
		function bekleyensatir($db) {
		
		return $this->benimsorum($db,"SELECT * FROM mutfaksiparis",1)->num_rows;
		
	}
	
		function doluluk($dv) {
			
			$son=$this->benimsorum($dv,"SELECT * FROM doluluk",1);
			$veriler=$son->fetch_assoc();			
			$toplam = $veriler["bos"] + $veriler["dolu"];			
		 	$oran =  ($veriler["dolu"] / $toplam) * 100 ;		
			echo $oran=substr($oran,0,4). " %";			
			
		}		
		
			function masatoplam($dv) {
				echo $this->benimsorum($dv,"SELECT * FROM masalar",1)->num_rows;						
		} // masa toplam sayı
		
		function siparistoplam($dv) {
				echo $this->benimsorum($dv,"SELECT * FROM anliksiparis",1)->num_rows;						
		} // masa toplam sayı
		
		// MASA DETAY FONKSİYON
		
  				function masagetir ($vt,$id) {			
				$get="SELECT * FROM masalar WHERE id=$id";			
				return $this->benimsorum($vt,$get,1);	
			
		}		

	// MASA DETAY FONKSİYON

	  function urungrup($db) {	
	$se="SELECT * FROM kategoriler";
	$gelen=$this->benimsorum($db,$se,1);	
	while ($son=$gelen->fetch_assoc()) :	
	echo '<a class="btn btn-dark mt-2 text-white" sectionId="'.$son["id"].'">'.$son["ad"].'</a><br>';	
	endwhile;	
		
	}
	
	
	function garsonbak($db) {
		
		$gelen=$this->benimsorum($db,"SELECT * FROM garson WHERE durum=1",1)->fetch_assoc();
	
		if ($gelen["ad"]!="") :
		
		
		echo $gelen["ad"];
		
		echo '<a href="islemler.php?islem=cikis" class="m-3"><kbd style="background-color:purple;">ÇIK</kbd></a>';
		else:
		
		echo "Garson Yok";
			
			
		endif;
	
}
	function vipTemaMasalar($dv) {	
			
					$sonuc=$this->benimsorum($dv,"SELECT * FROM masalar",1);
					$bos=0;
					$dolu=0;				
					while ($masason=$sonuc->fetch_assoc()) :
					
					$siparisler='SELECT * FROM anliksiparis WHERE masaid='.$masason["id"].'';
					$satir=$this->benimsorum($dv,$siparisler,1)->num_rows;
					
					if ($satir==0):
					
					$icon='ovalb';		
					
					else:
					$icon='ovald';
					
					endif;
																
			
		$this->benimsorum($dv,$siparisler,1)->num_rows==0 ? $bos++ : $dolu++ ;	
		
		
		if ($masason["rezervedurum"]==0) :
		
		echo '<div class="col-lg-2 col-md-3 col-sm-12">  
		<a href="masadetay.php?masaid='.$masason["id"].'" id="lin"> 
		
		
					<div class="row  p-2">
					
							<div class="col-lg-12 p-2  genelCervece" id="anadiv">
							
								<div class="row">
							
<div class="col-lg-3 col-md-3  col-sm-4 pr-2 pt-1 '.$icon.'">
<span style="font-size: 25px;" class="fas fa-mug-hot"></span>

</div>
<div class="col-lg-7 col-md-6 pl-2 col-sm-4 masaad">'.$masason["ad"].'</div> ';
			
			if ($satir!=0): echo '<div class="col-lg-1 col-md-6 pl-2 col-sm-4">
			<kbd class="sipsayi float-left">'.$satir.'</kbd></div>';
			
			else:
			
			echo '<div class="col-lg-1 col-md-3 pl-2 col-sm-4"></div>';
			 endif; 
			echo'</div>
			
			<div class="row">
			<div class="col-lg-12 pt-3">
			';
			
			
					$this->dakikakontrolet($masason["saat"],$masason["dakika"]);
			     
    				echo '
					</div></div>
					
					</div></div>
					</a>
					</div>';	
		
					
					
					
					else:
					
					
					echo '
					
					<div class="col-lg-2 col-md-3 col-sm-12">  
				
		
					<div class="row  p-2">
					
							<div class="col-lg-12 p-2   genelCervece" id="anadiv">
							
								<div class="row">
							
<div class="col-lg-3 col-md-3 col-sm-4 pl-2 pr-2 pt-1 ovalr">
<span style="font-size: 25px;" class="fas fa-mug-hot"></span>

</div>
<div class="col-lg-7 col-md-6  col-sm-4 masaad">'.$masason["ad"].'</div> 
			
			<div class="col-lg-1 col-md-3 pl-2 col-sm-4"></div></div>
			
			
			
			
			<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 pt-3">
			
			<kbd class="mb-0 float-right bg-dark text-warning border border-warning" style="position:absolute;">Kişi: '.$masason["kisi"].' </kbd>
			
					</div></div>
					
					</div></div>
					</a>
					</div>
					
			
	';
		
		
		endif;		
		
					endwhile;
					
					$dol="UPDATE doluluk SET bos=$bos, dolu=$dolu WHERE id=1";
					$dolson=$dv->prepare($dol);
					$dolson->execute();
		
		
	}	 // masalar
	
	
	 function vipTemaUrunGrup($db) {	
	
	$gelen=$this->benimsorum($db,"SELECT * FROM kategoriler where mutfakdurum=0",1);	
	while ($son=$gelen->fetch_assoc()) :
		
	echo '<a class="btn m-2 text-center kategoributon" style="color:#68d3c8;" sectionId="'.$son["id"].'">'.$son["ad"].'</a>';	
	endwhile;	
		
	} // tema2 grup
	
	
	function mutfakdakika($saat,$dakika) {
		
		
		if ($saat!=0 && $dakika!=0) :
		
		
					if ($saat<date("H")) :
					
					$deger= (60 + date("i")) - $dakika;
					
					echo $deger;
					 
			
					else:
					
							$deger =  date("i") - $dakika;				
							
							echo $deger;						
				
			
					
					endif;
		
		
		
		endif;
		
		
		
	}		
	
	
	function dakikakontrolet($saat,$dakika) {
		
		
		if ($saat!=0 && $dakika!=0) :
			date_default_timezone_set('Europe/Istanbul');
		
					if ($saat<date("H")) :
					
					$deger= (60 + date("i")) - $dakika;
					
					echo '<kbd class="ml-2 mb-0 mt-2  bg-light text-danger" >'.$deger.'  dakika önce </kbd>';
					 
			
					else:
					
					$deger =  date("i") - $dakika;
					
								if ($deger==0):
								
								echo '<kbd class="ml-2 mb-0 mt-2  bg-light text-danger" >Yeni eklendi</kbd>';
								
								else:
								
									echo '<kbd class="ml-2 mb-0 mt-2  bg-light text-danger" >'.$deger.'  dakika önce </kbd>';
									
								endif;
		
					endif;
		
		endif;
	
	}			
}

?>
<?php ob_start(); session_start(); 

include("fonksiyon/fonksiyonlar.php"); 

@$masaid=$_GET["masaid"];
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script src="dosya/jqu.js"></script>
<link rel="stylesheet" href="dosya/boost.css" >
<link rel="stylesheet" href="dosya/melike.css" >

<script>
$(document).ready(function() {	

		$('#degistirform').hide();
		$('#birlestirform').hide();
		$('#parcaform').hide();
		
				$('#btnn').click(function() {		
				$.ajax({			
					type : "POST",
					url :'islemler.php?islem=hesap',
					data :$('#hesapform').serialize(),			
					success: function(donen_veri){
					$('#hesapform').trigger("reset");
						window.location.reload();
					},			
				})		
			})
		
		
		$('#degistir a').click(function() { 
	$('#birlestirform').slideUp();
	$('#degistirform').slideDown();
	
	});	
	
		$('#birlestir a').click(function() { 
	$('#degistirform').slideUp();
	$('#birlestirform').slideDown();
	
	
	});		
	
		$('#degistirbtn').click(function() {		
		$.ajax({			
			type : "POST",
			url :'islemler.php?islem=masaislem',
			data :$('#degistirformveri').serialize(),			
			success: function(donen_veri){
			$('#degistirformveri').trigger("reset");
				window.location.reload();
			},			
		})		
	});
	
		$('#birlestirbtn').click(function() {		
		$.ajax({			
			type : "POST",
			url :'islemler.php?islem=masaislem',
			data :$('#birlestirformveri').serialize(),			
			success: function(donen_veri){
			$('#birlestirformveri').trigger("reset");
				window.location.reload();
			},			
		})		
	});
	
		$('#kapa1').click(function() {		
		$('#degistirform').slideUp();	
	});
		
				
			$('#kapa2').click(function() {		
		$('#birlestirform').slideUp();	
	});
	
	
		
			$('#bildirimlink a').click(function() {	
					
			var sectionId =$(this).attr('sectionId');		
			
				
		$.post("islemler.php?islem=hazirurunsil",{"id":sectionId},function(){	
			window.location.reload();	
			$('#uy'+sectionId).hide();
			
			$("#bekleyenler").load("islemler.php?islem=garsonbilgigetir");
			
		 });			
		});		
		
		
		$('#rezervelistem a').click(function() {	
					
			var sectionId =$(this).attr('sectionId');		
			
				
		$.post("islemler.php?islem=rezervekaldir",{"id":sectionId},function(){	
			window.location.reload();	
			$('#mas'+sectionId).hide();
			
			$("#rezervelistesi").load("islemler.php?islem=rezervelistesi");
			
		 });			
		});	
		

	$('#parcaHesapAc a').click(function() { 
	
	
	$('#parcaform').slideToggle();
	
	
	});	
		
		
		
		
			$('#parcabtn').click(function() {
				
		$.ajax({			
			type : "POST",
			url :'islemler.php?islem=parcaHesapOde',
			data :$('#parcaForm').serialize(),			
			success: function(donen_veri){
			$('#parcaForm').trigger("reset");
				window.location.reload();
			},			
		})		
	});
	
	
		
		
				
});


var popupWindow=null;

function ortasayfa(url,winName,w,h,scroll) {

LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;	
TopPosition = (screen.height) ? (screen.height-h)/2 : 0;	
settings='height='+h+',	width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'

popupWindow=window.open(url,winName,settings)
	
}
</script>
<title>Restaurant Sipariş Sistemi</title>
  </head>
  <body>
   
<?php


function sorgular($vt,$sorgu,$tercih) {				
					$a=$sorgu;
					$b=$vt->prepare($a);
					$b->execute();
					if ($tercih==1):
					return $c=$b->get_result();				
					endif;	
					
}

function uyari($mesaj,$renk) {	
echo '<div class="alert alert-'.$renk.' mt-4 text-center">'.$mesaj.'</div>';	
}

function formgetir($masaid,$db,$baslik,$durum,$btnvalue,$btnid,$formvalue) {
	
	
	echo '<div class="card border-success m-3 mx-auto" style="max-width:18rem;">
	<div class="card-header">'.$baslik.'</div><div class="card-body text-success">
	
	<form id="'.$formvalue.'"> 
						 
						 <input type="hidden" name="mevcutmasaid" value="'.$masaid.'" />
						 
						 <select name="hedefmasa" class="form-control">'; 
						 
						
						$masadeg=sorgular($db,"SELECT * FROM masalar WHERE durum=$durum",1); 
						
						while ($son = $masadeg->fetch_assoc()):
						
						if ($masaid!=$son["id"]) :
						echo '<option value="'.$son["id"].'">'.$son["ad"].'</option>';
						endif;
			
						endwhile;
			 
						    
                       echo'</select> <input type="button" id="'.$btnid.'" value="'.$btnvalue.'"  class="btn btn-success btn-block mt-2" /> </form></div></div>';	
	
}

function garsonbilgi($db) {
		
		$siparisler=sorgular($db,"SELECT * FROM mutfaksiparis order by masaid desc",1);
		
			
		echo '<table class="table table-bordered table-striped bg-white text-center mt-1 anasayfaTablo p-0" id="bildirimlink">
		
		<tbody>
		<tr class="font-weight-bold">
		
		<td>MASA</td>
		<td>ÜRÜN</td>
		<td>ADET</td>
		<td>İŞLEM</td>
			
		
		</tr>';
		
		while ($geldiler=$siparisler->fetch_assoc()) :
		$masaid=$geldiler["masaid"];
		
						$masaad=sorgular($db,"SELECT * FROM masalar WHERE id=$masaid",1);
						$masabilgi=$masaad->fetch_assoc();
						
						
				echo '	<tr>
                    
						<td class="text-center border-0 mx-auto  p-0 m-0">
						'.$masabilgi["ad"].'
						</td>
						
						<td class="text-center border-0 mx-auto  p-0 m-0">
						'.$geldiler["urunad"].'
						</td>
						
						<td class="text-center border-0 mx-auto  p-0 m-0">
						'.$geldiler["adet"].'
						</td>
						
						<td class="text-center border-0 mx-auto  p-0 m-0">
						<a class="fas fa-check  m-1" sectionId="'.$geldiler["id"].'" style="font-size:20px; color:purple" id="uy'.$geldiler["id"].'"></a>
						</td>
						
													
						</tr>';		  
  
			endwhile;
  
  
  
 echo '</tbody></table>';	
		
	}







@$islem=$_GET["islem"];

switch ($islem) :


case "masaislem":

$mevcutmasaid=$_POST["mevcutmasaid"];
$hedefmasa=$_POST["hedefmasa"];


sorgular($db,"UPDATE anliksiparis set masaid=$hedefmasa WHERE masaid=$mevcutmasaid",1); 



				 /* MASANIN DURUMUNU GÜNCELLEYECEĞİM*/				 
				 $ekleson2=$db->prepare("UPDATE masalar set durum=0 WHERE id=$mevcutmasaid");
				 $ekleson2->execute();				 
				  /* MASANIN DURUMUNU GÜNCELLEYECEĞİM*/
				  
				  	 /* MASANIN DURUMUNU GÜNCELLEYECEĞİM*/				 
				 $ekleson2=$db->prepare("UPDATE masalar set durum=1 WHERE id=$hedefmasa");
				 $ekleson2->execute();				 
				  /* MASANIN DURUMUNU GÜNCELLEYECEĞİM*/



break; // MASA TAŞIMA

case "hesap":

		if (!$_POST):
		
		echo "Posttan gelmiyosun";
		
		else:
		
			$masaid=htmlspecialchars($_POST["masaid"]);
			
			$verilericek=sorgular($db,"SELECT * FROM anliksiparis WHERE masaid=$masaid",1);
			
			while($don=$verilericek->fetch_assoc()):
			$a=$don["masaid"];
			$b=$don["urunid"];
			$c=$don["urunad"];
			$d=$don["urunfiyat"];
			$e=$don["adet"];
			$garsonid=$don["garsonid"];			
			$bugun = date("Y-m-d");
			
			$raporekle="insert into rapor (masaid,garsonid,urunid,urunad,urunfiyat,adet,tarih) VALUES($a,$garsonid,$b,'$c',$d,$e,'$bugun')";
			
			$raporekles=$db->prepare($raporekle);		
			$raporekles->execute();
			endwhile;	
	
			
			$silme=$db->prepare("DELETE FROM anliksiparis WHERE masaid=$masaid");		
			$silme->execute();
			
			
			$silme2=$db->prepare("DELETE FROM masabakiye WHERE masaid=$masaid");		
			$silme2->execute();
			
				 /* MASANIN DURUMUNU GÜNCELLEYECEĞİM*/				 
				 $ekleson2=$db->prepare("UPDATE masalar set durum=0 WHERE id=$masaid");
				 $ekleson2->execute();				 
				  /* MASANIN DURUMUNU GÜNCELLEYECEĞİM*/
				  
				  
				   /* MASANIN LOG KAYDI*/		
				 	 
				 $ekleson23=$db->prepare("UPDATE masalar set saat=0, dakika=0 WHERE id=$masaid");
				 $ekleson23->execute();				 
				  /* MASANIN LOG KAYDI*/
				
				
		
		endif;

break;

case "sil":

		if (!$_POST):		
		echo "Posttan gelmiyosun";		
		else:		
		$urunid=htmlspecialchars($_POST["urunid"]);
		$masaid=htmlspecialchars($_POST["masaid"]);
			
		$sorgu="DELETE FROM anliksiparis WHERE urunid=$urunid and masaid=$masaid";
		$silme=$db->prepare($sorgu);		
		$silme->execute();	
		
		$sorgu2="DELETE FROM mutfaksiparis WHERE urunid=$urunid and masaid=$masaid";
		$silme2=$db->prepare($sorgu2);		
		$silme2->execute();			
		endif;

break; // SİLME

 case "goster":					
					
 
 					$id=htmlspecialchars($_GET["id"]);
					
 
 					
				$d=sorgular($db,"SELECT * FROM anliksiparis WHERE masaid=$id",1);
				
	$verilericek=sorgular($db,"SELECT * FROM masabakiye WHERE masaid=$id",1);
		
					
					
					if ($d->num_rows==0) :					
					uyari("Henüz sipariş yok","danger");
					 /* MASANIN DURUMUNU GÜNCELLEYECEĞİM*/				 
				 $ekleson2=$db->prepare("UPDATE masalar set durum=0 WHERE id=$id");
				 $ekleson2->execute();				 
				  /* MASANIN DURUMUNU GÜNCELLEYECEĞİM*/
					
					 /* MASANIN LOG KAYDI*/		
				 	 
				 $ekleson2=$db->prepare("UPDATE masalar set saat=0, dakika=0 WHERE id=$id");
				 $ekleson2->execute();				 
				  /* MASANIN LOG KAYDI*/
					
					
																	
					else:
					
					echo '<table class=" table table-bordered table-striped bg-white text-center ">
					<tbody>
					<tr class="font-weight-bold">
					<td  class="p-2" >Ürün Adı</td>
					<td  class="p-2">Adet</td>
					<td  class="p-2">Tutar</td>
					
					</tr>';
					$adet=0;
					$sontutar=0;
						while ($gelenson=$d->fetch_assoc()) :
						
						$tutar = $gelenson["adet"] * $gelenson["urunfiyat"];
						
						$adet +=$gelenson["adet"];
						$sontutar +=$tutar;
						$masaid=$gelenson["masaid"];
						
						
						
						echo '<tr>
						<td class="p-2">'.$gelenson["urunad"].'</td>
						<td class="p-2">'.$gelenson["adet"].'</td>
						<td class="p-2">'.number_format($tutar,2,'.',',').'</td>	
	</tr>';						
						endwhile;						
						echo '
						<tr class="bg-light text-dark text-center">
						<td class="font-weight-bold">TOPLAM</td>					
						<td class="font-weight-bold" style="color:purple">'.$adet.'</td>
						<td class="font-weight-bold" style="color:purple">';
						
							if ($verilericek->num_rows!=0) :
		
							$masaninBakiyesi=$verilericek->fetch_assoc();
							
							$odenenTutar=$masaninBakiyesi["tutar"];
							$kalanTutar=$sontutar-$odenenTutar;
							
		echo '<p class="text-danger m-0 p-0"><del>'.number_format($sontutar,2,'.',','). " </del> | 
							
	<font class='text-success'>" . number_format($odenenTutar,2,'.',',')."</font>
	<font class='text-dark'><br>Ödenecek : ". number_format($kalanTutar,2,'.',',')."</font></p>" ;
							
							
							else:
							
							echo number_format($sontutar,2,'.',','). " TL";
		
							endif;	
		
						 echo'</td>
											
						</tr>					
						</tbody></table>
				
						 	 <div class="col-md-12">
										 <div class="row">
										 		<div class="col-md-6" id="degistir"><a  class="btn islembutonlar btn-block mt-1" ><i class="fas fa-exchange-alt mt-1"> MASA Değiştir</i></a> </div>
												 <div class="col-md-6" id="birlestir"><a  class="btn islembutonlar btn-block mt-1" ><i class="fas fa-stream mt-1"> MASA Birleştir</i></a>  </div>
										 </div>
										 
										 
										 
										  <div class="row text-center">
										  		 <div class="col-md-12 mx-auto" id="degistirform">'; formgetir($masaid,$db,"Masa Değiştir<span id='kapa1' class='text-danger float-right'>X</span>",0,"DEĞİŞTİR","degistirbtn","degistirformveri"); echo'</div>
												 <div class="col-md-12" id="birlestirform">'; formgetir($masaid,$db,"Masa Birleştir<span id='kapa2' class='text-danger float-right'>X</span>",1,"BİRLEŞTİR","birlestirbtn","birlestirformveri"); echo'</div>
										 		
										  </div>
										 
								 </div>
								
								 	 
						 	 
						 					
						</div>';				
					
					endif;	 
 break; 
 
 
 case "mutfaksip":

		if (!$_POST):		
		echo "Posttan gelmiyosun";		
		else:		
		$urunid=htmlspecialchars($_POST["urunid"]);
		$masaid=htmlspecialchars($_POST["masaid"]);			
		
		$sorgu2="UPDATE mutfaksiparis WHERE urunid=$urunid and masaid=$masaid";
		$silme2=$db->prepare($sorgu2);		
		$silme2->execute();			
		endif;

break; // MUTFAK SİPARİŞ
 
 
 case "ekle":	

 
 if ($_POST) :
 
 @$masaid=htmlspecialchars($_POST["masaid"]);
 @$urunid=htmlspecialchars($_POST["urunid"]);
 @$adet=htmlspecialchars($_POST["adet"]);
 @$a=htmlspecialchars($_POST["a"]);

 
 				if ($masaid=="" || $urunid=="" || $adet=="" ) :			
				uyari("Boş alan bırakma","danger");								
				else:
					$d=sorgular($db,"SELECT * FROM urunler WHERE id=$urunid",1);
					$son=$d->fetch_assoc();				
					$urunad=$son["ad"];	
					$katid=$son["katid"];
					$urunfiyat=$son["fiyat"];
					date_default_timezone_set('Europe/Istanbul');
				  $saat=date("H");	
				  $dakika=date("i");
						
						
				
						 /* MUTFAĞA BİLGİ GÖNDERİLİYOR*/	
						$mutfak="SELECT * FROM mutfaksiparis WHERE urunid=$urunid and masaid=$masaid";
						$var2=sorgular($db,$mutfak,1);				
						
						if ($var2->num_rows!=0) :
						
						$urundizi=$var2->fetch_assoc();
						$sonadet=$adet + $urundizi["adet"];
						$islemid=$urundizi["id"];
						
						$guncel2="UPDATE mutfaksiparis set adet=$sonadet WHERE id=$islemid";
						$guncelson2=$db->prepare($guncel2);
						$guncelson2->execute();	
							
							else:
										   
					
					$durumba=sorgular($db,"SELECT * FROM kategoriler WHERE id=$katid",1);
				 	$durumbak=$durumba->fetch_assoc();
					
									
									if ($durumbak["mutfakdurum"]==0) :
									
								sorgular($db,"insert into mutfaksiparis (masaid,urunid,urunad,adet,saat,dakika,metin) VALUES ($masaid,$urunid,'$urunad',$adet,$saat,$dakika,'$a')",0);
								
								
									
									endif;
							
							endif;
				
				 /* MUTFAĞA BİLGİ GÖNDERİLİYOR*/		
						
$var=sorgular($db,"SELECT * FROM anliksiparis WHERE urunid=$urunid and masaid=$masaid",1);				
						
						if ($var->num_rows!=0) :
						
						$urundizi=$var->fetch_assoc();
						$sonadet=$adet + $urundizi["adet"];					
						$islemid=$urundizi["id"];
						
						
						
						
						
				 $guncelson=$db->prepare("UPDATE anliksiparis set adet=$sonadet WHERE id=$islemid");
						$guncelson->execute();				
						uyari("ADET GÜNCELLENDİ","success");
						
					 /* MASANIN LOG KAYDI*/		
				 	 
			 $ekleson2=$db->prepare("UPDATE masalar set saat=$saat, dakika=$dakika WHERE id=$masaid");
				 $ekleson2->execute();				 
				  /* MASANIN LOG KAYDI*/	
				  					
						else:
					
						// garsonun idsini alıyorum
					
					$gelen=sorgular($db,"SELECT * FROM garson WHERE durum=1",1)->fetch_assoc();
	
					$garsonidyaz=$gelen["id"];
					// garsonun idsini alıyorum		
				
				
				 
				 /* MASANIN DURUMUNU GÜNCELLEYECEĞİM*/				 
				 $ekleson2=$db->prepare("UPDATE masalar set durum=1 WHERE id=$masaid");
				 $ekleson2->execute();				 
				  /* MASANIN DURUMUNU GÜNCELLEYECEĞİM*/
				  
				  
				  /* MASANIN LOG KAYDI*/	
				  date_default_timezone_set('Europe/Istanbul');	
				  $saat=date("H");	
				  $dakika=date("i");	 
				 $ekleson2=$db->prepare("UPDATE masalar set saat=$saat, dakika=$dakika WHERE id=$masaid");
				 $ekleson2->execute();				 
				  /* MASANIN LOG KAYDI*/
						
						
					 $ekle="insert into anliksiparis (masaid,garsonid,urunid,urunad,urunfiyat,adet) VALUES ($masaid,$garsonidyaz,$urunid,'$urunad',$urunfiyat,$adet)"; 
				 $ekleson=$db->prepare($ekle);
				 $ekleson->execute(); 		  
							 
					uyari("EKLENDİ","success");					
						endif;
		endif;	


		else:
		uyari("HATA VAR","danger");			
 		endif;


break;

	case "urun":

					$katid=htmlspecialchars($_GET["katid"]);
					
					$a="SELECT * FROM urunler WHERE katid=$katid";
					$d=sorgular($db,$a,1);		
					
						while ($sonuc=$d->fetch_assoc()):
							
								echo '<label class="btn  m-2 pt-4  text-center" style="margin:2px; background-color:#fff; height:80px; min-width:100px; color:#193d49;">
					<input name="urunid" type="radio" value="'.$sonuc["id"].'" />
					'.$sonuc["ad"].' - '.$sonuc["fiyat"].' TL
					</label>';
							
							endwhile;
					
		break; // URUN GETİR
		
		
		
		case "kontrol":
		
		$ad=htmlspecialchars($_POST["ad"]);
		$sifre=htmlspecialchars($_POST["sifre"]);
		
		if (@$ad!="" && @$sifre!="") :
		
		
				$var=sorgular($db,"SELECT * FROM garson WHERE ad='$ad'  and sifre='$sifre'",1);
				
				
					if ($var->num_rows==0) :
					
						echo '<div class="alert alert-danger text-center">Bilgiler uyuşmuyor</div>';
					
					else:
					
					$garson=$var->fetch_assoc();
					$garsonid=$garson["id"];
					sorgular($db,"UPDATE garson set durum=1 WHERE id=$garsonid",1);
					?>
                    <script>
					window.location.reload();
					
					</script>
                    
                    <?php
					
					
					endif;

		else:
		
		echo '<div class="alert alert-danger text-center">Boş bölüm bırakma</div>';
		
		endif;
		
		
		break; // KONTROL
		
		
		case "cikis":
		sorgular($db,"UPDATE garson set durum=0",1);
		header("Location:masa.php");				
		break;
		
		case "garsonbilgigetir":
		
		garsonbilgi($db);
		
		break; // ÇIKIŞ
		
		
	case "hazirurunsil":
		
		
		
		if (!$_POST):		
		echo "Posttan gelmiyosun";		
		else:		
		$id=htmlspecialchars($_POST["id"]);			
		$sorgu2="DELETE FROM mutfaksiparis WHERE id=$id";
		$silme2=$db->prepare($sorgu2);		
		$silme2->execute();			
		endif;
		
		break; // MUTFAK ÜRÜN SİL
		
		
	case "rezerveet":
		
		if ($_POST):		
				
		$masaid=htmlspecialchars($_POST["masaid"]);	
		$kisi=htmlspecialchars($_POST["kisi"]);	
		if ($kisi=="") :
		
		$kisi="Yok";
		endif;			
			
		
		$rezerveet=$db->prepare("UPDATE masalar set durum=1,rezervedurum=1,kisi='$kisi' WHERE id=$masaid");		
		$rezerveet->execute();			
		endif;
		
		break; // REZERVE ET
	
		case "rezervelistesi":
		
		$siparisler=sorgular($db,"SELECT * FROM masalar WHERE rezervedurum=1",1);
				
		echo '<table class="table table-bordered table-striped bg-white table-responsive-lg border-0 text-center mt-1 anasayfaTablo p-0" id="rezervelistem">
		<tbody>
		<tr class="font-weight-bold">
		
		<td>MASA</td>
		<td>KİŞİ</td>
		<td>İŞLEM</td>		
		
		</tr>
		
		';
		
		while ($geldiler=$siparisler->fetch_assoc()) :
						
				echo '	<tr>
                    
						<td class="text-center  mx-auto  p-0 m-0">
						 '.$geldiler["ad"].'
						</td>
						
						<td class="text-center  mx-auto  p-0 m-0">
						'.$geldiler["kisi"].'
						</td>
						
						<td class="text-center  mx-auto  p-0 m-0">
						<a class="fas fa-check  m-1" sectionId="'.$geldiler["id"].'" style="font-size:20px; color:purple" id="mas'.$geldiler["id"].'"></a>
						</td>	
															
					</tr>';		
 
			endwhile;
  
 echo '</tbody></table>
 
';	
		
		break;
		
		case "rezervekaldir":
		
		if ($_POST):		
			
		$id=htmlspecialchars($_POST["id"]);		
		
		$rezerveet=$db->prepare("UPDATE masalar set durum=0,rezervedurum=0,kisi='Yok' WHERE id=$id");		
		$rezerveet->execute();				
				
		endif;
		
		break; // REZERVE LİSTESİ	
		
endswitch;
?>

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
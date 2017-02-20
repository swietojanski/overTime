<?PHP

function kalendarz($idZolnierza)
{
  $url = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
   if(isset($_GET[zobacz])){
        $url = explode("&zobacz=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $adres=$url;
}  else {
        $adres=$url;
} 


//USUWAMY ZAZNACZONE WOLNE ZA NADGODZINY
        if(isset($_POST['usun_za_nad'])){
            if(!empty($_POST['wybrane'])){
                foreach($_POST['wybrane'] as $idUsun){
                    $sprawdzenie = mysql_query("SELECT * FROM `wykorzystane_nadgodziny` WHERE `idWykorzystane`='$idUsun'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
                    if((int)mysql_num_rows($sprawdzenie) > 0) { 
                        $usun = mysql_query("DELETE FROM `wykorzystane_nadgodziny` WHERE `idWykorzystane`='$idUsun'");
                        $komunikat = "Anulowano dzień wolny";
                    }else{
                        $komunikat = "Nie ma co anulować, dzień wolny nie istnieje";
                    }
                }
            }
        /*
            header("Location: $adres");
            exit;  
        */
    }
    
    //USUWAMY ZAZNACZONE WOLNE ZA SLUZBY
        if(isset($_POST['usun_za_slu'])){
            if(!empty($_POST['wybrane'])){
                foreach($_POST['wybrane'] as $idUsun){
                    $sprawdzenie = mysql_query("SELECT * FROM `wykorzystane_sluzby` WHERE `idWykorzystane`='$idUsun'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
                    if((int)mysql_num_rows($sprawdzenie) > 0) { 
                        $usun = mysql_query("DELETE FROM `wykorzystane_sluzby` WHERE `idWykorzystane`='$idUsun'");
                        $komunikat = "Anulowano dzień wolny";
                    }else{
                        $komunikat = "Nie ma co anulować, dzień wolny nie istnieje";
                    }
                }
            }
        /*
            header("Location: $adres");
            exit;  
        */
    }
    
  $year = date("Y");
  $monthNum = date("n");
  $_m = $_GET['m'] - 1;
  $m_ = $_GET['m'] + 1;
  if(isset($_GET['m'])){
    $monthNum = date("n")+$_GET['m'];  
  }
 
  $data_kontrolna = date ("M Y", mktime (0,0,0,$monthNum,1,$year));
  $numer_miesiaca = date("n" , strtotime($data_kontrolna));
  $wybrany_rok = date("Y" , strtotime($data_kontrolna));
  $dni_w_miesiacu = date("t",mktime(0,0,0,$monthNum,1,$year));//zwroci ilosc dni w danym miesiacu
  $dzien_tygodnia = date("w"); //zwroci dzien tygodnia od 0 do 6 Liczbowa forma dnia tygodnia
  $dzien_w_miesiacu = date("j"); //zwroci dzien z miesiaca od 1 do 31
  $pierwszy_dzien_miesiaca = date("w", mktime(0,0,0,$monthNum, 1, $year)); //pierwszy dzien miesiaca

  if($dayofweek == 0) $dayofweek = 7;
  if($pierwszy_dzien_miesiaca == 0) $pierwszy_dzien_miesiaca = 7;

  switch($numer_miesiaca){
    case 1 : $nazwa_miesiaca = "Styczeń";break;
    case 2 : $nazwa_miesiaca = "Luty";break;
    case 3 : $nazwa_miesiaca = "Marzec";break;
    case 4 : $nazwa_miesiaca = "Kwiecień";break;
    case 5 : $nazwa_miesiaca = "Maj";break;
    case 6 : $nazwa_miesiaca = "Czerwiec";break;
    case 7 : $nazwa_miesiaca = "Lipiec";break;
    case 8 : $nazwa_miesiaca = "Sierpień";break;
    case 9 : $nazwa_miesiaca = "Wrzesień";break;
    case 10 : $nazwa_miesiaca = "Październik";break;
    case 11 : $nazwa_miesiaca = "Listopad";break;
    case 12 : $nazwa_miesiaca = "Grudzień";break;
  }

  echo("<table class=\"dniwolne\">");
  echo("<caption>");
  echo "<a href='index.php?id=panele/moje/wolne&m=".$_m."' class=\"nawikal wlinii\"><</a>";
  echo"<a href='index.php?id=panele/moje/wolne'><span class=\"datakal\">".$nazwa_miesiaca." ".$wybrany_rok."</span></a>";
  echo "<a href='index.php?id=panele/moje/wolne&m=".$m_."' class=\"nawikal wlinii\">></a>";
   if(isset($komunikat)){echo "<br>".$komunikat;}
  echo("</caption><tr class=\"blekitne empty-cells\">");
  ?>
  <tr class="triada-1">
  <th>Pn</th>
  <th>Wt</th>
  <th>Śr</th>
  <th>Cz</th>
  <th>Pi</th>
  <th>So</th>
  <th>Nd</th>
  </tr>
  <?php      

  $j = $dni_w_miesiacu + $pierwszy_dzien_miesiaca - 1;

  for($i = 0; $i < $j; $i++){
        if($i < $pierwszy_dzien_miesiaca - 1){
          echo"<td class=\"blekitne empty-cells\"></td>";
          continue;
        }
        if(($i % 7) == 0){
          echo("</tr><tr>");
        }
        if(($i - $pierwszy_dzien_miesiaca + 2) == $dzien_w_miesiacu){
           //tutaj jest wyswietlany aktualny dzien
        }
        else{
          $color = "gray";
        }
        
        $dzien = ($i - $pierwszy_dzien_miesiaca + 2);
        $miesiac = $numer_miesiaca;
        $rok = $wybrany_rok;
        $staradata="$dzien-$miesiac-$rok";
        $like_data=date("Y-m-d", strtotime($staradata));//generujemy date do porownania w mysql

        $sprawdzenie = mysql_query("SELECT * FROM v_dni_wolne where idZolnierza='$idZolnierza' and kiedy = '$like_data'") 
        or die('Błąd zapytania');
        $zapytanie = mysql_query("SELECT *, sum(ile) as wolnego FROM v_dni_wolne where idZolnierza='$idZolnierza' and v_dni_wolne.kiedy = '$like_data'") 
        or die('Błąd zapytania');

            if(mysql_num_rows($sprawdzenie) > 0) {
                $r = mysql_fetch_object($zapytanie);
                echo("<td class=\"zlozony-4\">");
                echo "<a href=\"".$adres."&zobacz=".$like_data."\" title=\"".(($r->wolnego)/60)." godz.\"";if(isset($_GET['zobacz']) &&  $_GET['zobacz']==$like_data){echo'class="dopelniajacy-1"';}
                echo" godz.\">".($i - $pierwszy_dzien_miesiaca + 2)."</a>";
                echo("</td>");
            }else{
                echo("<td class=\"triada-2\">");
                echo($i - $pierwszy_dzien_miesiaca + 2);
                echo("</td>");    
            }
        

  }
  echo("</tr></table>");
  
  function szczegoly($idZolnierza, $like_data){
      
        $sprawdzenie = mysql_query("SELECT idNadgodziny, idSluzby FROM v_dni_wolne where idZolnierza='$idZolnierza' and v_dni_wolne.kiedy = '$like_data'") 
        or die('Błąd zapytania');
        $r = mysql_fetch_object($sprawdzenie);
        $nadgodzina=$r->idNadgodziny;
        $sluzba=$r->idSluzby;
        
        
        if(!empty($nadgodzina)){
            $nadgodziny = mysql_query("SELECT *, DATE_FORMAT(nadgodziny.kiedy, '%d-%m-%Y') as wolne_z_dnia, v_dni_wolne.ile AS minut FROM v_dni_wolne left join nadgodziny using(idNadgodziny) where idZolnierza='$idZolnierza' and v_dni_wolne.kiedy = '$like_data' order by nadgodziny.kiedy") 
            or die('Błąd zapytania');
                        echo "<div class=\"zawartosc blekitne\" >";
                        echo "Wolne za nadgodziny:<br>";
                        echo "</div>";
                        echo "<form name=\"usun\" method=\"post\">";      
                while($r = mysql_fetch_object($nadgodziny)) {
                        echo "<div class=\"zawartosc blekitne\" >";
                        echo "Za dzień $r->wolne_z_dnia godzin ".(($r->minut)/60)."<br>";
                        echo "</div>"; 
                        echo "<input type=\"hidden\" name=\"wybrane[]\" value=\"$r->idWykorzystane\">"; 
                        $razem+=(($r->minut)/60);
                } 
                        echo "<div class=\"zawartosc blekitne\" >";
                        echo "Razem $razem godz.<br>";
                        echo "</div>";
                        echo "<div class=\"zawartosc blekitne\" >";
                        echo "Zaakceptował: <br>";
                        echo "</div>";
                        echo "<div class=\"zawartosc wysrodkuj\" >";
                        echo "<input type=\"submit\" name=\"usun_za_nad\" class=\"zapisz animacja\" value=\"usuń\" title=\"Usuń wolne\"/>";  
                        echo "</form>";
                        echo "</div>";
          }
          if(!empty($sluzba)){
            $sluzby = mysql_query("SELECT *, DATE_FORMAT(sluzby.kiedy, '%d-%m-%Y') as wolne_z_dnia, v_dni_wolne.ile AS minut FROM v_dni_wolne left join sluzby using(idSluzby) where idZolnierza='$idZolnierza' and v_dni_wolne.kiedy = '$like_data' order by sluzby.kiedy") 
            or die('Błąd zapytania');
                        echo "<div class=\"zawartosc blekitne\" >";
                        echo "Wolne za służby:<br>";
                        echo "</div>"; 
                        echo "<form name=\"usun\" method=\"post\">"; 
                while($r = mysql_fetch_object($sluzby)) {  
                        echo "<div class=\"zawartosc blekitne\" >";
                        echo "Za dzień $r->wolne_z_dnia godzin ".(($r->minut)/60)."<br>";
                        echo "</div>";
                        echo "<input type=\"hidden\" name=\"wybrane[]\" value=\"$r->idWykorzystane\">"; 
                        $razem+=(($r->minut)/60);
                } 
                        echo "<div class=\"zawartosc blekitne\" >";
                        echo "Razem $razem godz.<br>";
                        echo "</div>"; 
                        echo "<div class=\"zawartosc wysrodkuj\" >";
                        echo "<input type=\"submit\" name=\"usun_za_slu\" class=\"zapisz animacja\" value=\"usuń\" title=\"Usuń wolne\"/>";  
                        echo "</form>";
                        echo "</div>";
          }
  }


}

?>

<h1>Kalendarz dni wolnych</h1>
<h2 class="podpowiedzi zaokraglij">Sprawdzisz tutaj kiedy miałeś lub będziesz miał wolne</h2>
<div class="flex-container">
    <div class="panel tysiac">
       <div class="tytul">
          <p>kalendarz</p>
<!--          <p class="right"><a href="#index.php?id=panele/admin/uzytkownicy" class="pl-10 pr-10 edytuj valing40" title="wyświetl wszystkich użytkowników">opcja</a></p>-->
       </div>
       <div class="zawartosc" >
            <?php kalendarz(id_zolnierza()); ?>
       </div>    
    </div>
</div>
<?php if(isset($_GET['zobacz']) and !isset($_POST['wybrane'])){ ?>
<div class="flex-container">
    <div class="panel tysiac">
       <div class="tytul">
          <p>szczegóły</p>
<!--          <p class="right"><a href="#index.php?id=panele/admin/uzytkownicy" class="pl-10 pr-10 edytuj valing40" title="wyświetl wszystkich użytkowników">opcja</a></p>-->
       </div>
       <div class="zawartosc" >
            <?php szczegoly(id_zolnierza(),mysql_real_escape_string($_GET['zobacz'])); ?>
       </div>    
    </div>
</div>
<?php } ?>
<div class="flex-container">
    <div class="panel bez-tla tysiac mt-10">
        <div class="white">
           <span class="zlozony-4 pall-10">dni wolne</span><span class="triada-2 pall-10 ml-10">dni w pracy</span>
       </div>    
    </div>
</div>



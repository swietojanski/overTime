<?PHP
if($_SESSION['permissions']<5){
function rozkaz()
{
    
   $url = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
   if(isset($_GET[zobacz])){
        $url = explode("&zobacz=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $adres=$url;
}  else {
        $adres=$url;
} 
   if(isset($_GET['m'])){
        $url = explode("&m=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $adres_m=$url;
}  else {
        $adres_m=$url;
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
  echo "<a href='$adres_m&m=".$_m."' class=\"nawikal wlinii\"><</a>";
  echo"<a href='$adres_m'><span class=\"datakal\">".$nazwa_miesiaca." ".$wybrany_rok."</span></a>";
  echo "<a href='$adres_m&m=".$m_."' class=\"nawikal wlinii\">></a>";
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

        
            switch ($_SESSION['permissions']){
        case 1:
            //admin
            $sprawdzenie = mysql_query("SELECT kiedy FROM v_dni_wolne where kiedy = '$like_data' group by kiedy") 
            or die('Błąd zapytania');
            break;
        case 2:
            //dowodca grupy
            $idGrupy = czyDowodcaGrupy();
                if (empty($idGrupy)){
                    $idGrupy = id_grupy();
                }
            $sprawdzenie = mysql_query("SELECT kiedy FROM v_dni_wolne left join zolnierze using (idZolnierza) left join eskadry on zolnierze.idEskadry=eskadry.idEskadry left join grupy using(idGrupy) where idGrupy='$idGrupy' and kiedy = '$like_data' group by kiedy") or die('Błąd zapytania');
            break; 
        case 3:
            //dowodca eskadry
            $idDowodcy = id_zolnierza(); //tego nie uzywam
            $idEskadry = id_eskadry();
            $sprawdzenie = mysql_query("SELECT kiedy FROM v_dni_wolne left join zolnierze using (idZolnierza) left join eskadry on zolnierze.idEskadry=eskadry.idEskadry left join grupy using(idGrupy) where zolnierze.idEskadry = '$idEskadry' and kiedy = '$like_data' group by kiedy") or die('Błąd zapytania');
            
            break;
        case 4:
            //szef eskadry
            $idEskadry = id_eskadry();
            $sprawdzenie = mysql_query("SELECT kiedy FROM v_dni_wolne left join zolnierze using (idZolnierza) left join eskadry on zolnierze.idEskadry=eskadry.idEskadry left join grupy using(idGrupy) where zolnierze.idEskadry = '$idEskadry' and kiedy = '$like_data' group by kiedy") or die('Błąd zapytania');
            break;
        case 5:
            //dowodca klucza
            header('Location: index.php');
            break;
        case 6:
            //zolnierz
            header('Location: index.php');
            break;
    }

        /*$zapytanie = mysql_query("SELECT *, sum(ile) as wolnego FROM v_dni_wolne where idZolnierza='$idZolnierza' and v_dni_wolne.kiedy = '$like_data'") 
        or die('Błąd zapytania');*/

            if(mysql_num_rows($sprawdzenie) > 0) {
                $r = mysql_fetch_object($sprawdzenie);
                echo("<td class=\"zlozony-4\">");
                echo "<a href=\"".$adres."&zobacz=".$like_data."#word\" title=\"Kliknij i zobacz\"";if(isset($_GET['zobacz']) &&  $_GET['zobacz']==$like_data){echo'class="dopelniajacy-1"';}
                echo" godz.\">".($i - $pierwszy_dzien_miesiaca + 2)."</a>";
                echo("</td>");
            }else{
                echo("<td class=\"triada-2\">");
                echo ($i - $pierwszy_dzien_miesiaca + 2);
                echo("</td>");    
            }
        

  }
  echo("</tr></table>");
  
  
  
    
  /*  
    
  $url = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
   if(isset($_GET['d'])){
        $url = explode("&d=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $adres=$url;
}  else {
        $adres=$url;
} 

  $date = date("Y-m-d");
  $dzien = date("j");  
  $year = date("Y");
  $monthNum = date("n");
  $_d = $_GET['d'] - 1;
  $d_ = $_GET['d'] + 1;
  if(isset($_GET['d'])){
    $dzien = date("j")+$_GET['d'];  
  }
    $data_kontrolna = date ("Y-m-d", mktime (0,0,0,$monthNum,$dzien,$year));
  $numer_miesiaca = date("m" , strtotime($data_kontrolna));
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
 
  $dzien_tyg_pl = array('Monday' => 'poniedziałek', 'Tuesday' => 'wtorek', 'Wednesday' => 'środa', 'Thursday' => 'czwartek', 'Friday' => 'piątek', 'Saturday' => 'sobota', 'Sunday' => 'niedziela');
  $dzien_w = date("l" , strtotime($data_kontrolna));
  echo $dzien_tyg_pl[$dzien_w].", ";
  echo $dzien_w = date("j" , strtotime($data_kontrolna))." ";
  echo $nazwa_miesiaca." ";
  echo $rok_w = date("Y" , strtotime($data_kontrolna));
  $dzien_w_miesiacu = date("d"); //zwroci dzien z miesiaca od 1 do 31



  echo "<a href='$adres&d=".$_d."' class=\"nawikal wlinii\"><</a>";
  echo"<a href='$adres'><span class=\"datakal\">".$data_kontrolna."</span></a>";
  echo "<a href='$adres&d=".$d_."' class=\"nawikal wlinii\">></a>";
 
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
               
            }else{
                   
            }*/
  
  
  
  
  
  function punkty($like_data){
      
    switch ($_SESSION['permissions']){
        case 1:
            //admin
            $lista = mysql_query("SELECT idZolnierza FROM v_dni_wolne group by idZolnierza order by idZolnierza") or die('Błąd zapytania');
            break;
        case 2:
            //dowodca grupy
            $idGrupy = czyDowodcaGrupy();
                if (empty($idGrupy)){
                    $idGrupy = id_grupy();
                }
            $lista = mysql_query("SELECT * FROM v_dni_wolne left join zolnierze using (idZolnierza) left join eskadry on zolnierze.idEskadry=eskadry.idEskadry left join grupy using(idGrupy) where idGrupy='$idGrupy' group by idZolnierza order by idGrupy, idZolnierza, zolnierze.idEskadry, zolnierze.idKlucza, idStopien desc") or die('Błąd zapytania');
            break; 
        case 3:
            //dowodca eskadry
            $idDowodcy = id_zolnierza(); //tego nie uzywam
            $idEskadry = id_eskadry();
            $lista = mysql_query("SELECT * FROM v_dni_wolne left join zolnierze using (idZolnierza) left join eskadry on zolnierze.idEskadry=eskadry.idEskadry left join grupy using(idGrupy) where zolnierze.idEskadry = '$idEskadry' group by idZolnierza order by idGrupy, idZolnierza, zolnierze.idEskadry, zolnierze.idKlucza, idStopien desc") or die('Błąd zapytania');
            break;
        case 4:
            //szef eskadry
            $idEskadry = id_eskadry();
            $lista = mysql_query("SELECT * FROM v_dni_wolne left join zolnierze using (idZolnierza) left join eskadry on zolnierze.idEskadry=eskadry.idEskadry left join grupy using(idGrupy) where zolnierze.idEskadry = '$idEskadry' group by idZolnierza order by idGrupy, idZolnierza, zolnierze.idEskadry, zolnierze.idKlucza, idStopien desc") or die('Błąd zapytania');
            break;
        case 5:
            //dowodca klucza
            header('Location: index.php');
            break;
        case 6:
            //zolnierz
            header('Location: index.php');
            break;
    }
      
        //przefiltrowanie zolnierzy, ktorych wolne zostana wyswietlone w kalendarzu
        while($zolnierze = mysql_fetch_object($lista)) {      
      
      
            $sprawdzenie = mysql_query("SELECT idNadgodziny, idSluzby FROM v_dni_wolne where v_dni_wolne.kiedy = '$like_data' and idZolnierza='$zolnierze->idZolnierza'") or die('Błąd zapytania');
            $r = mysql_fetch_object($sprawdzenie);
            $nadgodzina=$r->idNadgodziny;
            $sluzba=$r->idSluzby;
        
        if(!empty($nadgodzina)){
            $nadgodziny = mysql_query("SELECT powod.Opis as powod, powod.naPolecenie as naPolecenie, v_dni_wolne.kto_dodal as zaakceptowal, DATE_FORMAT(nadgodziny.kiedy, '%d.%m.%Y') as wolne_z_dnia, DATE_FORMAT(v_dni_wolne.kiedy, '%d.%m.%Y') as wolne_na_dzien, v_dni_wolne.ile AS minut FROM v_dni_wolne left join nadgodziny using(idNadgodziny) left join powod using (idPowod) where idZolnierza='$zolnierze->idZolnierza' and v_dni_wolne.kiedy = '$like_data' order by nadgodziny.kiedy") 
            or die('Błąd zapytania');
                        
                    echo "<p class=MsoNormal style='text-align:justify;text-indent:17.85pt'><span style='font-size:12.0pt;line-height:107%;font-family:\"Times New Roman\",serif'>
                    W związku z&nbsp;wykonywaniem przez ";st_nazwisko_imie($zolnierze->idZolnierza);echo" w&nbsp;dniach ";
                $razem=0;
                while($r = mysql_fetch_object($nadgodziny)) {
                    echo "$r->wolne_z_dnia"."&nbsp;r. – ".(($r->minut)/60)."&nbsp;godz., "; 
                    $razem+=(($r->minut)/60);
                    $powod=$r->powod;
                    $wolne_na_dzien=$r->wolne_na_dzien;
                    $na_polecenie=$r->naPolecenie;
                } 
                    echo "na polecenie $na_polecenie
                        zadań służbowych związanych z&nbsp;$powod i&nbsp;związanym z&nbsp;tym przekroczeniem tygodniowej normy czasu służby
                        udzielam w&nbsp;dniu $wolne_na_dzien&nbsp;r. czasu wolnego od służby w&nbsp;wymiarze $razem&nbsp;godz.</span></p>"; 
                    
          }
          if(!empty($sluzba)){
            $sluzby = mysql_query("SELECT dyzurny.Opis as powod, dyzurny.naPolecenie as naPolecenie, v_dni_wolne.kto_dodal as zaakceptowal, DATE_FORMAT(sluzby.kiedy, '%d.%m.%Y') as wolne_z_dnia, DATE_FORMAT(v_dni_wolne.kiedy, '%d.%m.%Y') as wolne_na_dzien, v_dni_wolne.ile AS minut FROM v_dni_wolne left join sluzby using(idSluzby) left join dyzurny on idDyzurny=idTyp where idZolnierza='$zolnierze->idZolnierza' and v_dni_wolne.kiedy = '$like_data' order by sluzby.kiedy") 
            or die('Błąd zapytania służb');
                    echo "<p class=MsoNormal style='text-align:justify;text-indent:17.85pt'><span style='font-size:12.0pt;line-height:107%;font-family:\"Times New Roman\",serif'>
                    W związku z&nbsp;pełnieniem przez ";st_nazwisko_imie($zolnierze->idZolnierza);echo" w&nbsp;dniu ";
                $razem=0;
                    while($r = mysql_fetch_object($sluzby)) {
                    echo "$r->wolne_z_dnia"."&nbsp;r. – ".(($r->minut)/60)."&nbsp;godz., "; 
                    $razem+=(($r->minut)/60);
                    $powod=$r->powod;
                    $wolne_na_dzien=$r->wolne_na_dzien;
                    $na_polecenie=$r->naPolecenie;
                } 
                    echo "na polecenie $na_polecenie, nieetatowej, całodobowej służby $powod i&nbsp;związanym z&nbsp;tym przekroczeniem tygodniowej normy czasu służby
                        udzielam w&nbsp;dniu $wolne_na_dzien&nbsp;r. czasu wolnego od służby w&nbsp;wymiarze $razem&nbsp;godz.</span></p>"; 
          }
    }
  }
  
  
  
  }
 

?>

<h1>Rozkaz dzienny</h1>
<h2 class="podpowiedzi zaokraglij">Punkty do rozkazu dziennego</h2>
<div class="flex-container" id="kalendarz">
    <div class="panel tysiac">
       <div class="tytul">
          <p>punkty do rozkazu</p>
<!--          <p class="right"><a href="#index.php?id=panele/admin/uzytkownicy" class="pl-10 pr-10 edytuj valing40" title="wyświetl wszystkich użytkowników">opcja</a></p>-->
       </div>
       <div class="zawartosc" >
            <?php rozkaz(); ?>
       </div>    
    </div>
</div>
<?php if(isset($_GET['zobacz'])){ ?>
<div class="flex-container" id="word">
    <div class="panel tysiac bialy ramka">
       <div class="word-margines" >
            <div class=WordSection1>

                <p class=MsoNormal><b><span style='font-size:12.0pt;line-height:107%;
                font-family:"Times New Roman",serif'>5.   ZWOLNIENIA OD ZAJĘĆ</span></b></p>

                <?php punkty(mysql_real_escape_string($_GET['zobacz'])); ?>

            </div>
       </div>    
    </div>
</div>
<?php }
}?>
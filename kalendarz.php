<?PHP

function kalendarz()
{
  $year = date("Y");
  $monthNum = date("n");
  $daysofmonth = date("t",mktime(0,0,0,$monthNum,1,$year));//zwroci ilosc dnia w danym miesiacu
  $dayofweek = date("w");
  $dayofmonth = date("j");
  $firstdayofmonth = date("w", mktime(0,0,0,$monthNum, 1, $year));

  if($dayofweek == 0) $dayofweek = 7;
  if($firstdayofmonth == 0) $firstdayofmonth = 7;

  switch($monthNum){
    case 1 : $monthName = "Styczeń";break;
    case 2 : $monthName = "Luty";break;
    case 3 : $monthName = "Marzec";break;
    case 4 : $monthName = "Kwiecień";break;
    case 5 : $monthName = "Maj";break;
    case 6 : $monthName = "Czerwiec";break;
    case 7 : $monthName = "Lipiec";break;
    case 8 : $monthName = "Sierpień";break;
    case 9 : $monthName = "Wrzesień";break;
    case 10 : $monthName = "Październik";break;
    case 11 : $monthName = "Listopad";break;
    case 12 : $monthName = "Grudzień";break;
  }

  echo("<table class=\"dniwolne\">");
  echo("<caption>");
  echo($monthName." ".$year);
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
            
        $idZolnierza = id_zolnierza();

  $j = $daysofmonth + $firstdayofmonth - 1;

  for($i = 0; $i < $j; $i++){
        if($i < $firstdayofmonth - 1){
          echo"<td class=\"blekitne empty-cells\"></td>";
          continue;
        }
        if(($i % 7) == 0){
          echo("</tr><tr>");
        }
        if(($i - $firstdayofmonth + 2) == $dayofmonth){
          $color = "black"; //tutaj jest wyswietlany aktualny dzien
        }
        else{
          $color = "gray";
        }
        
        $dzien = ($i - $firstdayofmonth + 2);
        $miesiac = $monthNum;
        $rok = $year;
        $staradata="$dzien-$miesiac-$rok";
        $like_data=date("Y-m-d", strtotime($staradata));//generujemy date do porownania w mysql

        $sprawdzenie = mysql_query("SELECT * FROM v_dni_wolne where idZolnierza='$idZolnierza' and kiedy = '$like_data' limit 1") 
        or die('Błąd zapytania');
        $zapytanie = mysql_query("SELECT *, sum(ile) as wolnego FROM v_dni_wolne where idZolnierza='$idZolnierza' and kiedy = '$like_data'") 
        or die('Błąd zapytania');

            if(mysql_num_rows($sprawdzenie) == 1) {
                $r = mysql_fetch_object($zapytanie);
                echo("<td class=\"zlozony-4\">");
                echo "<span title=\"".(($r->wolnego)/60)." godz.\">".($i - $firstdayofmonth + 2)."</span>";
                echo("</td>");
            }else{
                echo("<td class=\"triada-2\">");
                echo($i - $firstdayofmonth + 2);
                echo("</td>");    
            }
        

  }
  echo("</tr></TABLE>");
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
            <?php kalendarz(); ?>
       </div>    
    </div>
</div>
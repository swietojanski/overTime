<?php
//wyswietlenie nadgodzin zalogowanego uzytkownika
function mojeNadgodziny($kogo) {

    if( isset($kogo) ) {
        $czyje_id = $kogo;
        $czyje_url = '&czyje='.$kogo;
    }else{
        $czyje_id = id_zolnierza(); //pobranie id aktualnie zalogowanego zolnierza
    }
    
//Definiujemy zmienne pomocnicze do stronicowania
$ile=2; //ilosc wyswietlanych wpisow na strone
$strona=0; //poczatkowy numer strony, jezeli nie zostal podany
$idUsun = $_GET['usun'];
$idEdytuj = $_GET['edytuj'];
$idZapisz = $_POST['zapisz'];


//ZMIENNE DO ZAPISANIA EDYTOWANYCH NADGODZIN
$data = $_POST['data'];
$godzina = $_POST['godzina'];
$godzina = str_replace(",",".",$godzina);
$liczenie = count($data); //zliczenie ilosci wystapien pola data input
$zaznaczone = $_POST['edytuj'];
$liczEdytuj = 0; //zliczenie ilosci wystapien checbox

    if(isset($_GET['ile'])){
        $ile = (int)$_GET['ile'];
    }
    
    if(isset($_GET['strona'])){
        $strona = (int)$ile*$_GET['strona']; //pobieramy numer strony
    }
    
//USUWAMY DODANE NADGODZINY    
    if(isset($idUsun)){//najpierw sprawdzamy czy zmienna istnieje
        $sprawdzenie = mysql_query("SELECT * FROM `nadgodziny` WHERE `idNadgodziny`='$idUsun'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
        if((int)mysql_num_rows($sprawdzenie) > 0) {
            $usun = mysql_query("DELETE FROM `nadgodziny` WHERE `idNadgodziny`='$idUsun'");          
        }else{
            $blad_usuniecia = "Nie ma co usunąć, zrobiłeś to wcześniej"; //niewypisany, wiec go nie zobaczymy
        }
    }
    
//ZAPISUJEMY ZMIENIONE NADGODZINY
    
    if(isset($idZapisz)){//najpierw sprawdzamy czy zmienna istnieje
            for($a=0;$a<$liczenie;$a++)
        {
            
                $idZapisz=$zaznaczone[$a];
                echo $idZapisz."<br>";
            
            //$data = '12-22-2009';
            $dataq = explode("-", $data[$a]);
            $dataq = $dataq[2]."-".$dataq[1]."-".$dataq[0];
            $godzinaq = $godzina[$a]*60; //mnozymy podana ilosc godzin przez 60 minut i zapisujemy jako inty do bazy
              
    
            $sprawdzenie = mysql_query("SELECT * FROM `nadgodziny` WHERE `idNadgodziny`='$idZapisz'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
            if((int)mysql_num_rows($sprawdzenie) > 0) { 
                $edytuj = mysql_query("UPDATE `nadgodziny` SET `ile`='$godzinaq', `kiedy`='$dataq' WHERE `idNadgodziny`='$idZapisz'");
            }else{
                $blad_usuniecia = "Nie ma co edytować, nadgodzina nie istnieje";
            }
        }
      
        $extra = 'index.php?id=panele/moje/nadgodziny&ile=';
        $extra2 = $ile;
        $extra3 = '&strona=';
        $extra4 = $_POST[strona];
        header("Location: $extra$extra2$extra3$extra4$czyje_url");
        exit;  
    
    }
   
 //POBIERAMY DODANE NADGODZINY
$ilewpisow = mysql_query("SELECT *, DATE_FORMAT(kiedy, '%d-%m-%Y') AS ostatnie FROM nadgodziny WHERE czyje_id='$czyje_id'") or die('Błąd zapytania');
$wpisow = (int)mysql_num_rows($ilewpisow);
$stron = ceil($wpisow/$ile); //ilosc stron
$zapytanie = mysql_query("SELECT *, DATE_FORMAT(kiedy, '%d-%m-%Y') AS ostatnie, DATE_FORMAT(DATE_ADD(DATE_ADD(kiedy, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS termin FROM nadgodziny WHERE czyje_id='$czyje_id' ORDER BY idNadgodziny DESC LIMIT $ile OFFSET $strona") 
or die('Błąd zapytania'); 



//Pętla po stronach
if ($stron>1) {
    echo "<div class=\"flex-container\">";
        echo "<div class=\"zawartosc order-2\">";
        echo "<select id=\"selectbox\">";
         for($i=0;$i<$stron;$i++){
         //jeśli wyswietlona obecna strona, nie twórz linku do strony
            if($i*$ile==$strona){
            echo ' <option value="'.($i+1).'" SELECTED>'.($i+1).'</option>';
            $dousuniecia=$i;
            $poprzednia=$i-1;
            $nastepna=$i+1;
            }elseif($i<5){
                echo '<option value="index.php?id=panele/moje/nadgodziny'.$czyje_url.'&ile='.$ile.'&strona='.$i.'">'.($i+1).'</option>';
            }
         }
         //echo '[...] <a href="index.php?id=panele/moje/nadgodziny&ile='.$ile.'&do='.($i-1).'">'.$i.' </a>';
         echo "</select>";
         echo "</div>";
         echo "<div class=\"order-1\">";
         //wygenerowanie linku do poprzedniej strony
            if ($poprzednia > -1) {
            echo '<a href="index.php?id=panele/moje/nadgodziny'.$czyje_url.'&ile='.$ile.'&strona='.$poprzednia.'" class="button blekitne">Poprzednia</a>';
            }else{
                echo "<button class=\"button\" disabled>Poprzednia</button>";
            }
        echo "</div>";
        echo "<div class=\"order-3\">";
        //wygenerowanie linku do nastepnej strony
        if ($nastepna < $i) {
            echo '<a href="index.php?id=panele/moje/nadgodziny'.$czyje_url.'&ile='.$ile.'&strona='.$nastepna.'" class="button blekitne">Nastepna</a>';
        } else {
            echo "<button class=\"button\" disabled>Nastepna</button>";
        }
        echo "</div>";
    echo "</div>";
}
/*Wypisanie danych nadgodzin z bazy mysql*/
    if(mysql_num_rows($zapytanie) > 0) { //jezeli zapytanie zwrocilo wiecej zapytan od 0 to wykonaj sie
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */    
        echo "<table>";
            echo "<caption>Ilosc wpisow w bazie: ".$wpisow."</caption>";
            echo "<form class=\"nadgodzinki\" name=\"nadgodzinki\" method=\"post\" action=\"\">";
            echo "<thead>";
                echo "<tr class=\"blekitne empty-cells\">";
                    echo "<th></th>";
                    echo "<th><input type=\"checkbox\" id=\"select-all\" disabled=\"disabled\"></th>";
                    echo "<th>za dzień</th>";
                    echo "<th>godzin</th>";
                    echo "<th>ważne do</th>";
                    echo "<th colspan=\"2\">";
                        if ($zaznaczone[$liczEdytuj]==$r->idNadgodziny){
                            echo "<input type=\"submit\" id=\"edytujgodzinki\" class=\"edytuj\" value=\"edytuj zaz.\" title=\"edytuj zaznaczone\"/></th>";
                        }else{
                            echo "<input type=\"submit\" name=\"aktualizuj\" class=\"aktualizuj\" value=\"zapisz zaz.\" title=\"zapisz zaznaczone\"/></th>";
                        }
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
                while($r = mysql_fetch_object($zapytanie)) {
                    echo "<tr class=\"blekitne\">";
                        echo "<td>";
                            echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=".id_zolnierza($r->kto_dodal)."\">";
                            echo "<img src=\"img/avatars/";avatar($r->kto_dodal);
                            echo "\" class=\"zaokraglij\" height=\"26px\" title=\"Dodane: ".$r->kiedy_dodal."\" align=\"absmiddle\">";
                            echo "</a>";
                        echo "</td>";
                       
                            if (isset($idEdytuj) && $idEdytuj==$r->idNadgodziny OR $zaznaczone[$liczEdytuj]==$r->idNadgodziny){/*jezeli istnieje zmienna edytuj i jest rowna id nadgodziny to wyswietl edycje*/
                                echo "<td>";
                                    echo "<input type=\"checkbox\" name=\"edytuj[]\" value=\"$r->idNadgodziny\" checked>";
                                echo "</td>";
                                echo "<td><input type=\"text\" class=\"wysrodkuj\" name=\"data[]\" placeholder=\"$r->ostatnie\" value=\"$r->ostatnie\" pattern=\"(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-[0-9]{4}\" required=\"true\" size=\"10\"></td>"; /*wyswietlamy edycje daty*/ 
                                echo "<td><input type=\"text\" class=\"wysrodkuj\" name=\"godzina[]\" placeholder=\"".(($r->ile)/60)."\" pattern='((\d{1,2}\.[5])|(\d{1,2}))' required=\"true\" size=\"4\"></td>"; /*wyswietlamy godziny*/
                            }else{
                                echo "<td>";
                                    echo "<input type=\"checkbox\" name=\"edytuj[]\" value=\"$r->idNadgodziny\">";
                                echo "</td>";
                                echo "<td>$r->ostatnie</td>"; /*wyswietlamy daty*/
                                echo "<td>".(($r->ile)/60)."</td>"; /*wyswietlamy godziny*/
                            }
                        echo "<td>$r->termin</td>"; /*wyswietlamy do kiedy mamy czas wykorzystać nadgodziny*/
                            if (isset($idEdytuj) && $idEdytuj==$r->idNadgodziny OR $zaznaczone[$liczEdytuj]==$r->idNadgodziny){
                                echo "<input type=\"hidden\" name=\"zapisz\" value=\"$r->idNadgodziny\">";
                                echo "<input type=\"hidden\" name=\"strona\" value=\"$dousuniecia\">";
                                echo "<td><input type=\"submit\" class=\"aktualizuj\" name=\"dodajnadgodziny\" value=\"Zapisz\" title=\"Zapisz do bazy\"/></td>";
                                echo "<td><a class=\"anuluj\" href=\"index.php?id=panele/moje/nadgodziny$czyje_url&ile=$ile&strona=$dousuniecia\">Anuluj</a></td>";
                                $liczEdytuj++;
                            }else{
                                echo "<td><a class=\"edytuj\" href=\"index.php?id=panele/moje/nadgodziny$czyje_url&ile=".$ile."&strona=".$dousuniecia."&edytuj=".$r->idNadgodziny."\">Edytuj</a></td>";
                                echo "<td><a class=\"usun\" href=\"index.php?id=panele/moje/nadgodziny$czyje_url&ile=".$ile."&strona=".$dousuniecia."&usun=".$r->idNadgodziny."\">Usuń</a></td>";
                            }
                    echo "</tr>";
                }
            echo "</tbody>";
            echo "</form>"; 
        echo "</table>";   
    }
}
?>

<?php if($_SESSION['permissions']==1){ //wpusc jezeli ma prawa admina
    $url = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
   if(isset($_GET['dodaj'])){
        $url = explode("&dodaj", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $adres_d=$url;
    }  else {
        $adres_d=$url;
    } 
function stopnie_wojskowe(){
    $url = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
   if(isset($_GET['edytuj'])){
        $url = explode("&edytuj=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $adres=$url;
    }  else {
        $adres=$url;
    } 
    if(isset($_GET['usun'])){
        $url = explode("&usun=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $adres_u=$url;
        $adres=$url;
    }  else {
        $adres_u=$url;
    } 
//jeżeli istnieje $kogo profil sprawdzamy czy mamy dostep do danego profilu     

    $idUsun = $_GET['usun'];
    $idEdytuj = $_GET['edytuj'];
    $idZapisz = $_POST['zapisz'];


    //ZMIENNE DO ZAPISANIA EDYTOWANYCH NADGODZIN
    $skrot = $_POST['skrot'];
    $pelna = $_POST['pelna_nazwa'];
    $kolejnosc = $_POST['waga'];
    $skrot = str_replace(",",".",$skrot);
    
    $zaznaczone = $_POST['edytuj'];
    $liczenie = count($skrot); //zliczenie ilosci wystapien pola skrot input
    $liczEdytuj = 0; //zliczenie ilosci wystapien checbox

    //USUWAMY DODANE STOPNIE    
        if(isset($idUsun)){//najpierw sprawdzamy czy zmienna istnieje
            $sprawdzenie = mysql_query("SELECT * FROM `stopnie` WHERE `idStopien`='$idUsun'");// zapytanie sprawdzajace czy stopien o danym id jest w bazie 
            if((int)mysql_num_rows($sprawdzenie) > 0) {
                        $usun = mysql_query("DELETE FROM `stopnie` WHERE `idStopien`='$idUsun'");
                        $komunikat = "Stopień został usunięty";
            }else{
                $komunikat = "Nie ma co usunąć, zrobiłeś to wcześniej"; //niewypisany, wiec go nie zobaczymy
            }
        }

    //ZAPISUJEMY ZMIENIONE NADGODZINY
        if(isset($_POST['zapisz'])){//najpierw sprawdzamy czy zmienna istnieje
            for($a=0;$a<$liczenie;$a++)
            {

                echo $idZapisz=$zaznaczone[$a]; //wybranie id z checboxow
                echo $skrotq = $skrot[$a]; //mnozymy podana ilosc godzin przez 60 minut i zapisujemy jako inty do bazy
                echo $pelnaq = $pelna[$a]; 
                echo $waga = $kolejnosc[$a];
                $sprawdzenie = mysql_query("SELECT * FROM `stopnie` WHERE `idStopien`='$idZapisz'");// zapytanie sprawdzajace czy nadpelna o danym id jest w bazie 
                if((int)mysql_num_rows($sprawdzenie) > 0) { 
                    if(empty($waga)){
                        $edytuj = mysql_query("UPDATE `stopnie` SET `Skrot`='$skrotq', `Pelna`='$pelnaq' WHERE `idStopien`='$idZapisz';");
                    }else{
                        $edytuj = mysql_query("UPDATE `stopnie` SET `Skrot`='$skrotq', `Pelna`='$pelnaq', `Waga`='$waga' WHERE `idStopien`='$idZapisz';");
                    }
                }else{
                    echo $komunikat = "Nie ma co edytować, stopień nie istnieje";
                }
            }

            header("Location: $adres");
            exit;  

        }

     //POBIERAMY DODANE NADGODZINY
    
    $zapytanie = mysql_query("SELECT * FROM stopnie order by Waga, idStopien") 
    or die('Błąd zapytania'); 
    $wpisow = (int)mysql_num_rows($zapytanie);


    /*Wypisanie sluzb z bazy mysql*/
        if(mysql_num_rows($zapytanie) > 0) { //jezeli zapytanie zwrocilo wiecej zapytan od 0 to wykonaj sie
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */    
            echo "<table>";
                echo "<caption>Ilosc wpisow w bazie: ".$wpisow."<br>".$komunikat."</caption>";
                echo "<form name=\"stopnie_wosjkowe\" method=\"post\" action=\"\">";
                echo "<thead>";
                    echo "<tr class=\"blekitne empty-cells\">";
                        echo "<th><input type=\"checkbox\" id=\"select-all\" disabled=\"disabled\"></th>";
                        echo "<th class=\"left\">skrót</th>";
                        echo "<th class=\"left\">nazwa</th>";
                        if(isset($_GET['edytuj']) or isset($_POST['edytuj'])){
                        echo "<th class=\"left\">waga</th>";
                        }else{
                            echo "<th class=\"left\"></th>";
                        }
                        echo "<th colspan=\"2\">";
                        if(!isset($_GET['edytuj'])){
                            if ($zaznaczone[$liczEdytuj]==$r->idStopien){
                                echo "<input type=\"submit\" id=\"edytujgodzinki\" class=\"edytuj\" value=\"edytuj zaz.\" title=\"edytuj zaznaczone\"/></th>";
                            }else{
                                echo "<input type=\"submit\" name=\"aktualizuj\" class=\"aktualizuj\" value=\"zapisz zaz.\" title=\"zapisz zaznaczone\"/></th>";
                            }
                        }else{
                            echo "<a class=\"anuluj\" href=\"$adres\">Anuluj</a></th>";
                        }
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                    while($r = mysql_fetch_object($zapytanie)) {
                        echo "<tr class=\"blekitne\">";

                                if (isset($idEdytuj) && $idEdytuj==$r->idStopien OR $zaznaczone[$liczEdytuj]==$r->idStopien){/*jezeli istnieje zmienna edytuj i jest rowna id nadgodziny to wyswietl edycje*/
                                    echo "<td>";
                                        echo "<input type=\"checkbox\" name=\"edytuj[]\" value=\"$r->idStopien\" checked>";
                                    echo "</td>";
                                    echo "<td class=\"left\"><input type=\"text\" class=\"pl-5\" name=\"skrot[]\" placeholder=\"$r->Skrot\" value=\"$r->Skrot\" required size=\"10\"></td>"; /*wyswietlamy edycje daty*/ 
                                    echo "<td class=\"left\"><input type=\"text\" class=\"pl-5\" name=\"pelna_nazwa[]\" placeholder=\"$r->Pelna\" value=\"$r->Pelna\" required size=\"20\"></td>";
                                    echo "<td class=\"left\"><input type=\"text\" class=\"wysrodkuj\" name=\"waga[]\" placeholder=\"$r->Waga\" value=\"$r->Waga\" size=\"2\"></td>"; /*wyswietlamy godziny*/
                                }else{
                                    echo "<td>";
                                        echo "<input type=\"checkbox\" name=\"edytuj[]\" value=\"$r->idStopien\">";
                                    echo "</td>";
                                    echo "<td class=\"left\">$r->Skrot</td>"; /*wyswietlamy skrot*/
                                    echo "<td class=\"left\">$r->Pelna</td>"; /*wyswietlamy pelna nazwe*/
                                    echo "<td class=\"left\"></td>"; /*wyswietlamy pelna nazwe*/
                                }
                                if (isset($idEdytuj) && $idEdytuj==$r->idStopien OR $zaznaczone[$liczEdytuj]==$r->idStopien){
                                    echo "<input type=\"hidden\" name=\"zapisz\" value=\"$r->idStopien\">";
                                    echo "<td class=\"w60\"><input type=\"submit\" class=\"aktualizuj\" name=\"aktualizuj\" value=\"Zapisz\" title=\"Zapisz do bazy\"/></td>";
                                    echo "<td class=\"w60\"><a class=\"anuluj\" href=\"$adres\">Anuluj</a></td>";
                                    $liczEdytuj++;
                                }else{
                                    echo "<td class=\"w60\"><a class=\"edytuj\" href=\"".$adres."&edytuj=".$r->idStopien."\">Edytuj</a></td>";
                                    echo "<td class=\"w60\"><a class=\"usun\" href=\"".$adres_u."&usun=".$r->idStopien."\">Usuń</a></td>";
                                }
                        echo "</tr>";
                    }
                echo "</tbody>";
                echo "</form>"; 
            echo "</table>";  
        }else{
            echo "Brak stopni do wyświetlenia. Dodaj nowe.";
        }
    
}

function dodaj_stopnie_wojskowe($skrot,$nazwa){
            if(!empty($skrot)&&!empty($nazwa)) {
                $zapytanie = "INSERT INTO `stopnie` (`Skrot`, `Pelna`) VALUES ('$skrot', '$nazwa');";
                $wykonaj = mysql_query($zapytanie) or die("Błąd zapytania dodania");
                echo "Dodałeś stopień do bazy danych";
            }else{
                echo "Nie wprowadziłeś danych do dodania";
            }
}

    
?>
<h1> Stopnie </h1>
<h2 class="podpowiedzi zaokraglij">Lista stopni wojskowych, które możesz dodać, usunąc lub edytować</h2>

<?php if(isset($_GET['dodaj'])){ ?>
<div class="flex-container">
    <div class="panel szescset">
        <div class="tytul">
            <p>dodaj stopien</p><p class="right"><a href="<?php echo $adres_d;?>" class="pl-10 pr-10 anuluj valing40" title="wróć do spisu">wróć</a></p>
        </div>
        <div class="zawartosc wysrodkuj">
            <?php 
                if(isset($_POST['skrot'])){
                $skrot = mysql_real_escape_string($_POST['skrot']);
                $nazwa = mysql_real_escape_string($_POST['pelna_nazwa']);
                dodaj_stopnie_wojskowe($skrot,$nazwa);}
            ?>
            
                <form name="stopnie_wosjkowe" method="post" action="">
            <table>
                <thead>
                    <tr class="empty-cells">
                        <th>skrót</th>
                        <th>nazwa</th>
                    </tr>
                </thead>
                <tbody>
                        <td><input type="text" class="pl-5" name="skrot" placeholder="np. szer."  required size="20"></td>
                        <td><input type="text" class="pl-5" name="pelna_nazwa" placeholder="np. szeregowy"  required size="20"></td>
                    </tbody> 
            </table> 
        <input type="submit" name="dodaj" class="zapisz animacja" value="zapisz" title="Dodaj stopień wojskowy"/> 
    </form> 
            

            
            
        </div>  
    </div>
</div>
<?php }else{ ?>
<div class="flex-container">
    <div class="panel szescset">
        <div class="tytul">
            <p>stopnie</p><p class="right"><a href="<?php echo $adres_d;?>&dodaj" class="pl-10 pr-10 edytuj valing40" title="dodaj nowy stopień">dodaj</a></p>
        </div>
        <div class="zawartosc wysrodkuj">
            <?php stopnie_wojskowe();  ?>
        </div>  
    </div>
</div>
<?php
      }
}  else { //jezeli to nie admin powiadom go o tym
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
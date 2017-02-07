<?php
if($_SESSION['permissions']==1){ //wpusc jezeli ma prawa admina
/* 
 * Panel do zarządzania użytkownikami dla administratora
 * v.0.1
 * Wyswietlanie, modyfikacje, usuwanie.
 */
  
function szukaj($user, $sortowanie) {
$wyrazenia = mysql_real_escape_string($user);

//wybieramy wyszukiwarke wg sortowania
    switch ($sortowanie){
        case 1:
            //domyslne alfabetyczne
            $szukaj = mysql_query("SELECT * FROM uzytkownicy LEFT JOIN zolnierze using (idZolnierza) order by Login ASC;") 
            or die('Błąd zapytania'); 
            break;
        case 2:
            //domyslne alfabetyczne
            $szukaj = mysql_query("SELECT * FROM uzytkownicy LEFT JOIN zolnierze using (idZolnierza) WHERE Login LIKE '%".$wyrazenia."%'  order by Login ASC;") 
            or die('Błąd zapytania'); 
            break;
    }

$znalezionych = mysql_num_rows($szukaj);
$idEdytuj=$_GET['edytuj'];
  
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 
*/ 
    /*Wypisanie danych sluzb z bazy mysql*/
        if(mysql_num_rows($szukaj) > 0) { //jezeli zapytanie zwrocilo wiecej zapytan od 0 to wykonaj sie
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */    
            echo "<table>";
                echo "<caption>Ilosc wpisow w bazie: "."</caption>";
                echo "<form class=\"nadgodzinki\" name=\"nadgodzinki\" method=\"post\" action=\"\">";
                echo "<thead>";
                    echo "<tr class=\"blekitne empty-cells\">";
                        echo "<th></th>";
                        echo "<th><input type=\"checkbox\" id=\"select-all\" disabled=\"disabled\"></th>";
                        echo "<th>login</th>";
                        echo "<th>uprawnienie</th>";
                        echo "<th>data utworzenia</th>";
                        echo "<th colspan=\"2\"></th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                    while($r = mysql_fetch_object($szukaj)) {
                        echo "<tr class=\"blekitne\">";
                            echo "<td>";
                                echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=".id_zolnierza($r->idZolnierza)."\">";
                                echo "<img src=\"img/avatars/".$r->Avatar;
                                echo "\" class=\"zaokraglij\" height=\"60px\" title=\"Dodany: ".$r->DataUtworzenia."\" align=\"absmiddle\">";
                                echo "</a>";
                            echo "</td>";

                                if (isset($idEdytuj) && $idEdytuj==$r->Login){/*jezeli istnieje zmienna edytuj i jest rowna id sluzby to wyswietl edycje*/
                                    echo "<td>";
                                        echo "<input type=\"checkbox\" name=\"edytuj[]\" value=\"$r->Login\" checked>";
                                    echo "</td>";
                                    echo "<td><input type=\"text\" class=\"wysrodkuj\" name=\"login[]\" placeholder=\"$r->Login\" value=\"$r->Login\" required=\"true\" size=\"10\"></td>"; /*wyswietlamy edycje daty*/ 
                                    echo "<td><input type=\"text\" class=\"wysrodkuj\" name=\"haslo[] $r->Login\" placeholder=\"hasło\" value=\"\" required=\"true\" size=\"10\"></td>"; /*wyswietlamy edycje daty*/ 
                                }else{
                                    echo "<td>";
                                        echo "<input type=\"checkbox\" name=\"edytuj[]\" value=\"$r->Login\">";
                                    echo "</td>";
                                    echo "<td>$r->Login</td>"; /*wyswietlamy daty*/
                                    echo "<td>".$r->idUprawnienia."</td>"; /*wyswietlamy godziny*/
                                }
                            echo "<td>$r->DataUtworzenia</td>"; /*wyswietlamy do kiedy mamy czas wykorzystać nadgodziny*/
                                if (isset($idEdytuj) && $idEdytuj==$r->Login){
                                    echo "<input type=\"hidden\" name=\"zapisz\" value=\"$r->Login\">";
                                    echo "<input type=\"hidden\" name=\"strona\" value=\"\">";
                                    echo "<td><input type=\"submit\" class=\"aktualizuj\" name=\"dodajnadgodziny\" value=\"Zapisz\" title=\"Zapisz do bazy\"/></td>";
                                    echo "<td><a class=\"anuluj\" href=\"index.php?id=panele/moje/sluzby&ile=$ile&strona=\">Anuluj</a></td>";
                                }else{
                                    echo "<td><a class=\"edytuj\" href=\"index.php?id=panele/admin/sluzby&edytuj=".$r->Login."\">Edytuj</a></td>";
                                    echo "<td><a class=\"usun\" href=\"index.php?id=panele/admin/sluzby&usun=$r->Login\">Usuń</a></td>";
                                }
                        echo "</tr>";
                    }
                echo "</tbody>";
                echo "</form>"; 
            echo "</table>";   
    } else{
        echo "Brak wyników wyszukiwania";
    }
    
 }   
    
    
    
    
function uzytkownicy() {
   
//jeżeli istnieje $kogo profil sprawdzamy czy mamy dostep do danego profilu     

    //Definiujemy zmienne pomocnicze do stronicowania
    $nazwa=$_SESSION['user']."-uzytkownikow";
    if(isset($_COOKIE[$nazwa]) && !empty($_COOKIE[$nazwa])){
        $ile=$_COOKIE[$nazwa];
    }else{    
        $ile=10; //ilosc wyswietlanych wpisow na strone
    }
    $strona=0; //poczatkowy numer strony, jezeli nie zostal podany
    $idUsun = $_GET['usun'];
    $idEdytuj = $_GET['edytuj'];
    $idZapisz = $_POST['zapisz'];


    //ZMIENNE DO ZAPISANIA EDYTOWANYCH UZYTKOWNIKOW
    $login = $_POST['login'];
    $uprawnienie = $_POST['uprawnienie'];
    $liczenie = count($uprawnienie); //zliczenie ilosci wystapien pola data input
    $zaznaczone = $_POST['edytuj'];
    $liczEdytuj = 0; //zliczenie ilosci wystapien checbox


        if(isset($_GET['strona'])){
            $strona = (int)$ile*$_GET['strona']; //pobieramy numer strony
        }

    //USUWAMY DODANYCH UZYTKOWNIKOW 
        if(isset($idUsun)){//najpierw sprawdzamy czy zmienna istnieje
            $sprawdzenie = mysql_query("SELECT * FROM `uzytkownicy` WHERE `Login`='$idUsun'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
            if((int)mysql_num_rows($sprawdzenie) > 0) {
                
                while($check = mysql_fetch_object($sprawdzenie)) {  
                    $zgoda = $check->Login;
                }
                        $usun = mysql_query("DELETE FROM `uzytkownicy` WHERE `Login`='$idUsun'");
                        $komunikat = "Usunięto użytkownika: $idUsun";
                     
            }else{
                $komunikat = "Nie ma co usunąć, użytkownik nie istnieje"; //niewypisany, wiec go nie zobaczymy
            }
        }

    //ZAPISUJEMY ZMIENIONE NADGODZINY

        if(isset($idZapisz)){//najpierw sprawdzamy czy zmienna istnieje
                for($a=0;$a<$liczenie;$a++)
            {

                    $idZapisz=$zaznaczone[$a];
                    echo $idZapisz."<br>";


                $sprawdzenie = mysql_query("SELECT * FROM `uzytkownicy` WHERE `Login`='$idZapisz'");// zapytanie sprawdzajace czy uzytkownik o danym loginie jest w bazie 
                if((int)mysql_num_rows($sprawdzenie) > 0) { 
                    $edytuj = mysql_query("UPDATE `uzytkownicy` SET `Login`='$login' `idUprawnienia`='$uprawnienie' WHERE `Login`='$idZapisz'");
                }else{
                    $komunikat = "Nie ma co edytować, użytkownik nie istnieje";
                }
            }

            $extra = 'index.php?id=panele/admin/uzytkownicy';
            $extra3 = '&strona=';
            $extra4 = $_POST[strona];
            header("Location: $extra$extra3$extra4");
            exit;  

        }

     //POBIERAMY DODANE NADGODZINY
    $ilewpisow = mysql_query("SELECT * FROM uzytkownicy") or die('Błąd zapytania');
    $wpisow = (int)mysql_num_rows($ilewpisow);
    $stron = ceil($wpisow/$ile); //ilosc stron
    $zapytanie = mysql_query("SELECT * FROM uzytkownicy LEFT JOIN zolnierze using (idZolnierza) LEFT JOIN uprawnienia using (idUprawnienia) order by Login ASC LIMIT $ile OFFSET $strona") 
    or die('Błąd zapytania'); 



    //Pętla po stronach
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
                    echo '<option value="index.php?id=panele/admin/uzytkownicy&ile='.$ile.'&strona='.$i.'">'.($i+1).'</option>';
                }
             }
             //echo '[...] <a href="index.php?id=panele/moje/nadgodziny&ile='.$ile.'&do='.($i-1).'">'.$i.' </a>';
             echo "</select>";
             echo "</div>";
             echo "<div class=\"order-1\">";
             //wygenerowanie linku do poprzedniej strony
                if ($poprzednia > -1) {
                echo '<a href="index.php?id=panele/admin/uzytkownicy&ile='.$ile.'&strona='.$poprzednia.'" class="button blekitne">Poprzednia</a>';
                }else{
                    echo "<button class=\"button\" disabled>Poprzednia</button>";
                }
            echo "</div>";
            echo "<div class=\"order-3\">";
            //wygenerowanie linku do nastepnej strony
            if ($nastepna < $i) {
                echo '<a href="index.php?id=panele/admin/uzytkownicy&ile='.$ile.'&strona='.$nastepna.'" class="button blekitne">Nastepna</a>';
            } else {
                echo "<button class=\"button\" disabled>Nastepna</button>";
            }
            echo "</div>";
        echo "</div>";

    /*Wypisanie danych nadgodzin z bazy mysql*/
        if(mysql_num_rows($zapytanie) > 0) { //jezeli zapytanie zwrocilo wiecej zapytan od 0 to wykonaj sie
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */    
            echo "<table>";
                echo "<caption>Ilosc użytkowników w bazie: ".$wpisow."<br>$komunikat</caption>";
                echo "<form class=\"nadgodzinki\" name=\"nadgodzinki\" method=\"post\" action=\"\">";
                echo "<thead>";
                    echo "<tr class=\"blekitne empty-cells\">";
                        echo "<th></th>";
                        echo "<th><input type=\"checkbox\" id=\"select-all\" disabled=\"disabled\"></th>";
                        echo "<th>za dzień</th>";
                        echo "<th>godzin</th>";
                        echo "<th>ważne do</th>";
                        echo "<th colspan=\"2\">";
                            if ($zaznaczone[$liczEdytuj]==$r->Login){
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
                                echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=".id_zolnierza($r->Login)."\">";
                                echo "<img src=\"img/avatars/$r->Avatar";
                                echo "\" class=\"zaokraglij\" height=\"60px\" title=\"Dodany: ".$r->DataUtworzenia." align=\"absmiddle\">";
                                echo "</a>";
                            echo "</td>";

                                if (isset($idEdytuj) && $idEdytuj==$r->Login OR $zaznaczone[$liczEdytuj]==$r->Login){/*jezeli istnieje zmienna edytuj i jest rowna id nadgodziny to wyswietl edycje*/
                                    echo "<td>";
                                        echo "<input type=\"checkbox\" name=\"edytuj[]\" value=\"$r->Login\" checked>";
                                    echo "</td>";
                                    echo "<td><input type=\"text\" class=\"wysrodkuj\" name=\"login[]\" placeholder=\"$r->Login\" value=\"$r->Login\" required=\"true\" size=\"10\"></td>"; /*wyswietlamy edycje daty*/ 
                                    echo "<td><input type=\"text\" class=\"wysrodkuj ggodzin\" name=\"uprawnienie[]\" placeholder=\"$r->idUprawnienia\" required=\"true\" size=\"4\" id=\"$r->idUprawnienia\"></td>"; /*wyswietlamy godziny*/
                                }else{
                                    echo "<td>";
                                        echo "<input type=\"checkbox\" name=\"edytuj[]\" value=\"$r->Login\">";
                                    echo "</td>";
                                    echo "<td>$r->Login</td>"; /*wyswietlamy loginy*/
                                    echo "<td>$r->Typ</td>"; /*wyswietlamy uprawnienia*/
                                }
                            echo "<td><a class=\"usun\" href=\"index.php?id=panele/admin/uzytkownicy&strona=$dousuniecia\">zeruj hasło</a></td>"; /*wyswietlamy do kiedy mamy czas wykorzystać nadgodziny*/
                                if (isset($idEdytuj) && $idEdytuj==$r->Login OR $zaznaczone[$liczEdytuj]==$r->Login){
                                    echo "<input type=\"hidden\" name=\"zapisz\" value=\"$r->Login\">";
                                    echo "<input type=\"hidden\" name=\"strona\" value=\"$dousuniecia\">";
                                    echo "<td><input type=\"submit\" class=\"aktualizuj\" name=\"dodajnadgodziny\" value=\"Zapisz\" title=\"Zapisz do bazy\"/></td>";
                                    echo "<td><a class=\"anuluj\" href=\"index.php?id=panele/admin/uzytkownicy&strona=$dousuniecia\">Anuluj</a></td>";
                                    $liczEdytuj++;
                                }else{
                                    echo "<td><a class=\"edytuj\" href=\"index.php?id=panele/admin/uzytkownicy&strona=".$dousuniecia."&edytuj=".$r->Login."\">Edytuj</a></td>";
                                    echo "<td><a class=\"usun\" href=\"index.php?id=panele/admin/uzytkownicy&strona=".$dousuniecia."&usun=".$r->Login."\">Usuń</a></td>";
                                }
                        echo "</tr>";
                    }
                echo "</tbody>";
                echo "</form>"; 
            echo "</table>";   
        }else{
            echo "Brak nadgodzin do wyświetlenia";
        }
            //Ukryte okienko dialog z przyciskami do umieszczania nadgodzin w inpucie
            echo "<div id=\"dialog\" title=\"Ile nadgodzin?\">";
                //wypisanie przyciskow w okienku typu dialog jquery
                for ($i=1;$i<25;$i++){
                    echo "<button>".$i."</button>"; 
                }
            echo "</div>";
            //koniec ukrytego okienka
}//koniec funkcji uzytkownicy    
    
    
   
    
    






?>
<h1>Użytkownicy</h1>
<h2 class="podpowiedzi zaokraglij">

    <form action="" method="get" name="uzytkownicy">
        <input type="hidden" name="id" value="panele/admin/uzytkownicy"/><label for="userek">Skorzystaj z wyszukiwarki: <input type="search" required="true" results="5" minlength="2" maxlength="50" autosave="some_unique_value" placeholder="<?php if (isset($_GET['login'])){echo$_GET['login'];}else{echo "login użytkownika...";}?>" name="login" title="Wpisz nazwę użytkownika" class="szukajusera pl-5" id="userek"/></label><input type="submit" value="Szukaj" class="szukaj" />
    </form>
</h2>

<div class="panel">
   <div class="tytul">
      <p>znalezieni</p>
   </div>
   <div class="zawartosc" >
        <?php uzytkownicy(); ?>
       <?php //if (isset($_GET['login'])){szukaj($_GET['login'], 2);}else{szukaj("", 1);}?>
   </div>    
</div>
<?php
}//koniec filtracji dostepu
?>

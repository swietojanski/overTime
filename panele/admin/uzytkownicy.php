<?php

if($_SESSION['permissions']==1){ //wpusc jezeli ma prawa admina
/* 
 * Panel do zarządzania użytkownikami dla administratora
 * v.0.1
 * Wyswietlanie, modyfikacje, usuwanie.
 */
  
function uzytkownicy() {
    
$url = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; //generujemy aktualny adres wyswietlanej strony
if(isset($_GET[desc])){
        $url = explode("&desc", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $adres=$url;
}  else {
        $adres=$url."&desc";
}
   
//jeżeli istnieje $kogo profil sprawdzamy czy mamy dostep do danego profilu     

    //Definiujemy zmienne pomocnicze do stronicowania
    $nazwa=$_SESSION['user']."-wpisuzy";
    if(isset($_COOKIE[$nazwa]) && !empty($_COOKIE[$nazwa])){
        $ile=$_COOKIE[$nazwa];
    }else{    
        $ile=10; //ilosc wyswietlanych wpisow na strone
    }
    $strona=0; //poczatkowy numer strony, jezeli nie zostal podany
    $idUsun = $_GET['usun'];
    $idEdytuj = $_GET['edytuj'];
    $idZapisz = $_POST['zapisz'];
    $idZeruj = $_GET['zeruj'];
    
    if(isset($_GET['szukaj'])){
        $wyrazenie = mysql_real_escape_string($_GET['szukaj']);
        $u = '&szukaj=';
        $rl = $_GET['szukaj'];
        $szukany = "$u$rl";
    }else {
        $wyrazenie="";
        $szukany="";
    }
    
        if(isset($_GET['desc'])){
            $sortowanie = 2;
            $desc = '&desc';
        }else {
            $sortowanie = 1;
        }


    //ZMIENNE DO ZAPISANIA EDYTOWANYCH UZYTKOWNIKOW
    $login = $_POST['login'];
    $uprawnienie = $_POST['uprawnienie'];
    $zolnierz = $_POST['zolnierze'];
    $liczenie = count($uprawnienie); //zliczenie ilosci wystapien pola data input
    $zaznaczone = $_POST['edytuj'];
    $liczEdytuj = 0; //zliczenie ilosci wystapien checkbox


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

    //ZAPISUJEMY ZMIENIONE DANE

        if(isset($idZapisz)){//najpierw sprawdzamy czy zmienna istnieje
            for($a=0;$a<$liczenie;$a++){
                    
                    $idZapisz=$zaznaczone[$a];
                    $idUprawnienia=$uprawnienie[$a];
                    $idZolnierza=$zolnierz[$a];
                $sprawdzenie = mysql_query("SELECT * FROM `uzytkownicy` WHERE `Login`='$idZapisz'");// zapytanie sprawdzajace czy uzytkownik o danym loginie jest w bazie 
                if((int)mysql_num_rows($sprawdzenie) > 0) { 
                    $edytuj = mysql_query("UPDATE `uzytkownicy` SET `idUprawnienia`='$idUprawnienia', `idZolnierza`='$idZolnierza' WHERE `Login`='$idZapisz'");
                }else{
                    $komunikat = "Nie ma co edytować, użytkownik nie istnieje";
                }
            }

            $extra = 'index.php?id=panele/admin/uzytkownicy';
            $extra3 = '&strona=';
            $extra4 = $_POST[strona];
            header("Location: $extra$extra3$extra4$szukany$desc");
            exit;  

        }
        
        //ZERUJEMY HASLO 
        if(isset($idZeruj)){//najpierw sprawdzamy czy zmienna istnieje
            $sprawdzenie = mysql_query("SELECT * FROM `uzytkownicy` WHERE `Login`='$idZeruj'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
                if((int)mysql_num_rows($sprawdzenie) > 0) {
                        $zakodowane = md5($idZeruj);
                        $usun = mysql_query("UPDATE `uzytkownicy` SET `Haslo`='$zakodowane' WHERE `Login`='$idZeruj'");
                        $komunikat = "Zresetowano hasło użytkownika: $idZeruj. Nowe hasło: $idZeruj";
                     
                }else{
                $komunikat = "Nie można zmienić hasła, użytkownik nie istnieje"; //niewypisany, wiec go nie zobaczymy
            }
        }        

     //POBIERAMY DODANE NADGODZINY
    
        
    $ilewpisow = mysql_query("SELECT * FROM uzytkownicy WHERE Login LIKE '%".$wyrazenie."%'") or die('Błąd zapytania');
    $wpisow = (int)mysql_num_rows($ilewpisow);
    $stron = ceil($wpisow/$ile); //ilosc stron
    //wybieramy wyszukiwarke wg sortowania
    switch ($sortowanie){
        case 1:
            //domyslne od A do Z
            $zapytanie = mysql_query("SELECT *, CONCAT_WS(' ',stopnie.Skrot, UPPER(zolnierze.Nazwisko), zolnierze.Imie) AS Zolnierz FROM uzytkownicy LEFT JOIN zolnierze using (idZolnierza) LEFT JOIN uprawnienia using (idUprawnienia) LEFT JOIN stopnie using (idStopien) WHERE Login LIKE '%".$wyrazenie."%' order by Login ASC LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania');
            break;
        case 2:
            //posortuje od z do a
            $zapytanie = mysql_query("SELECT *, CONCAT_WS(' ',stopnie.Skrot, UPPER(zolnierze.Nazwisko), zolnierze.Imie) AS Zolnierz FROM uzytkownicy LEFT JOIN zolnierze using (idZolnierza) LEFT JOIN uprawnienia using (idUprawnienia) LEFT JOIN stopnie using (idStopien) WHERE Login LIKE '%".$wyrazenie."%' order by Login DESC LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania'); 
            break;
    }



if($stron > 1) {
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
                    echo '<option value="index.php?id=panele/admin/uzytkownicy&strona='.$i.''.$szukany.''.$desc.'">'.($i+1).'</option>';
                }
             }
             //echo '[...] <a href="index.php?id=panele/moje/nadgodziny&ile='.$ile.'&do='.($i-1).'">'.$i.' </a>';
             echo "</select>";
             echo "</div>";
             echo "<div class=\"order-1\">";
             //wygenerowanie linku do poprzedniej strony
                if ($poprzednia > -1) {
                echo '<a href="index.php?id=panele/admin/uzytkownicy&strona='.$poprzednia.''.$szukany.''.$desc.'" class="button blekitne">Poprzednia</a>';
                }else{
                    echo "<button class=\"button\" disabled>Poprzednia</button>";
                }
            echo "</div>";
            echo "<div class=\"order-3\">";
            //wygenerowanie linku do nastepnej strony
            if ($nastepna < $i) {
                echo '<a href="index.php?id=panele/admin/uzytkownicy&strona='.$nastepna.''.$szukany.''.$desc.'" class="button blekitne">Nastepna</a>';
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
                echo "<caption>Ilosc użytkowników w bazie: ".$wpisow."<br>$komunikat</caption>";
                echo "<form class=\"nadgodzinki\" name=\"nadgodzinki\" method=\"post\" action=\"\">";
                echo "<thead>";
                    echo "<tr class=\"blekitne empty-cells\">";
                        echo "<th></th>";
                        echo "<th><input type=\"checkbox\" id=\"select-all\" disabled=\"disabled\"></th>";
                        echo "<th><a href=\"$adres\" title=\"sortuj\">login</a></th>"; 
                        echo "<th>hasło</th>";
                        echo "<th>uprawnienie</th>";
                        echo "<th class=\"left\">przypisany</th>";
                        echo "<th colspan=\"2\">";
                            if ($zaznaczone[$liczEdytuj]==$r->Login){
                                echo "<input type=\"submit\" id=\"edytujgodzinki\" class=\"edytuj\" value=\"edytuj zaz.\" title=\"edytuj zaznaczone\"/></th>";
                            }else{
                                echo "<input type=\"submit\" name=\"zapisz\" class=\"aktualizuj\" value=\"zapisz zaz.\" title=\"zapisz zaznaczone\"/></th>";
                            }
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                    while($r = mysql_fetch_object($zapytanie)) {
                        echo "<tr class=\"blekitne\" >";
                            echo "<td style=\"width:70px;\">";
                                echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=".id_zolnierza($r->Login)."\">";
                                echo "<img src=\"img/avatars/$r->Avatar";
                                echo "\" class=\"zaokraglij \" height=\"50px\" title=\"Dodany: ".$r->DataUtworzenia."\" align=\"absmiddle\">";
                                echo "</a>";
                            echo "</td>";

                                if (isset($idEdytuj) && $idEdytuj==$r->Login OR $zaznaczone[$liczEdytuj]==$r->Login){/*jezeli istnieje zmienna edytuj i jest rowna id nadgodziny to wyswietl edycje*/
                                    echo "<td>";
                                        echo "<input type=\"checkbox\" name=\"edytuj[]\" value=\"$r->Login\" checked>";
                                    echo "</td>";
                                    echo "<td><input type=\"text\" class=\"wysrodkuj\" name=\"login[]\" placeholder=\"$r->Login\" value=\"$r->Login\" required=\"true\" size=\"10\" disabled></td>"; /*wyswietlamy edycje uzytkownika*/
                                    echo "<td><a class=\"usun\" href=\"index.php?id=panele/admin/uzytkownicy&strona=$dousuniecia&zeruj=".$r->Login."$szukany$desc\">resetuj hasło</a></td>";
                                    echo "<td>"; uprawnienia("multi"); echo "</td>"; /*wyswietlamy uprawnienia*/
                                    echo "<td class=\"left\">";
                                    lista_zolnierzy(id_zolnierza($r->Login), "multi");
                                    echo"</td>"; /*wyswietlamy zolnierzy*/
                                }else{
                                    echo "<td>";
                                        echo "<input type=\"checkbox\" name=\"edytuj[]\" value=\"$r->Login\">";
                                    echo "</td>";
                                    echo "<td>$r->Login</td>"; /*wyswietlamy loginy*/
                                    echo "<td><a class=\"usun\" href=\"index.php?id=panele/admin/uzytkownicy&strona=$dousuniecia&zeruj=".$r->Login."$szukany$desc\">resetuj hasło</a></td>"; /*wyswietlamy do kiedy mamy czas wykorzystać nadgodziny*/                                    
                                    echo "<td>$r->Typ</td>"; /*wyswietlamy uprawnienia*/
                                    echo "<td class=\"left\">$r->Zolnierz</td>"; /*wyswietlamy zolnierza*/
                                }

                                if (isset($idEdytuj) && $idEdytuj==$r->Login OR $zaznaczone[$liczEdytuj]==$r->Login){
                                    echo "<input type=\"hidden\" name=\"zapisz\" value=\"$r->Login\">";
                                    echo "<input type=\"hidden\" name=\"strona\" value=\"$dousuniecia\">";
                                    echo "<td><input type=\"submit\" class=\"aktualizuj\" name=\"zapisz\" value=\"Zapisz\" title=\"Zapisz do bazy\"/></td>";
                                    echo "<td><a class=\"anuluj\" href=\"index.php?id=panele/admin/uzytkownicy&strona=$dousuniecia$szukany$desc\">Anuluj</a></td>";
                                    $liczEdytuj++;
                                }else{
                                    echo "<td><a class=\"edytuj\" href=\"index.php?id=panele/admin/uzytkownicy&strona=".$dousuniecia."&edytuj=".$r->Login."$szukany$desc\">Edytuj</a></td>";
                                    echo "<td><a class=\"usun\" href=\"index.php?id=panele/admin/uzytkownicy&strona=".$dousuniecia."&usun=".$r->Login."$szukany$desc\">Usuń</a></td>";
                                }
                        echo "</tr>";
                    }
                echo "</tbody>";
                echo "</form>"; 
            echo "</table>";   
        }else{
            echo "Brak użytkowników do wyświetlenia";
        }

}//koniec funkcji uzytkownicy    

?>
<h1>Użytkownicy</h1>
<h2 class="podpowiedzi zaokraglij">

    <form action="" method="get" name="uzytkownicy">
        <input type="hidden" name="id" value="panele/admin/uzytkownicy"/><label for="userek">Skorzystaj z wyszukiwarki: <input type="search" required="true" results="5" minlength="1" maxlength="50" autosave="some_unique_value" placeholder="<?php if (isset($_GET['szukaj'])){echo$_GET['szukaj'];}else{echo "login użytkownika...";}?>" name="szukaj" title="Wpisz nazwę użytkownika" class="szukajusera pl-5" id="userek"/></label><input type="submit" value="Szukaj" class="szukaj" />
    </form>
    
</h2>
<div class="flex-container">
    <div class="panel tysiac">
       <div class="tytul">
          <p>znalezieni</p>
          <p class="right"><a href="index.php?id=panele/admin/uzytkownicy" class="pl-10 pr-10 edytuj valing40" title="wyświetl wszystkich użytkowników">wszyscy</a></p>
       </div>
       <div class="zawartosc" >
            <?php uzytkownicy(); ?>
           <?php //if (isset($_GET['login'])){szukaj($_GET['login'], 2);}else{szukaj("", 1);}?>
       </div>    
    </div>
    </div>
<?php
}//koniec filtracji dostepu
?>

<?php

/* 
 * Wyswietlamy alerty i powiadomienia zolnierzy, dowodcow
 */

function pusc_na_wolne($kogo,$kiedy,$ile,$idWniosku){
            (int)$ile;
            (int)$wpisano=0;
            (int)$dowpisania=0;
            mysql_query("SET AUTOCOMMIT=0");
            mysql_query("START TRANSACTION");
            while($ile != $wpisano){
                $sprawdzenie = mysql_query("SELECT * FROM v_zestawienie_nadgodzin WHERE idZolnierza='$kogo' and pozostalo!='0' order by termin LIMIT 1");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie
                if((int)mysql_num_rows($sprawdzenie) == 1) {
                        $check = mysql_fetch_object($sprawdzenie);
                        $idNadgodziny = intval($check->idNadgodziny);
                        $pozostalo = intval($check->pozostalo);
                        $zgoda = intval($check->idZolnierza);
                }
                
                $dowpisania=$ile-$wpisano;//obliczamy ile pozostalo jeszcze minut do wpisania
                //
                //jezeli ilosc minut do wpisania jest wieksza niz minuty pobrane z bazy to wpisujemy cale pobrane
                if($dowpisania>=$pozostalo){
                    $wpisz = mysql_query("INSERT INTO `wykorzystane_nadgodziny` (`idNadgodziny`, `ile`, `kiedy`, `kto_dodal`, `kiedy_dodal`) VALUES ('$idNadgodziny', '$pozostalo', '$kiedy', '$kogo', NOW());") or die(mysql_error());
                $wpisano=$wpisano+$pozostalo;
                }else{
                    //jezeli ilosc minut potrzebnych do wpisania jest mniejsza niz te pobrane z bazy danych to bierzemy ilosc 
                    $wpisz = mysql_query("INSERT INTO `wykorzystane_nadgodziny` (`idNadgodziny`, `ile`, `kiedy`, `kto_dodal`, `kiedy_dodal`) VALUES ('$idNadgodziny', '$dowpisania', '$kiedy', '$kogo', NOW());") or die(mysql_error());
                    $wpisano=$wpisano+$dowpisania;
                }
            
            }
            
                    if($zgoda == mamDostepDo($zgoda)){
                        $usun = mysql_query("DELETE FROM `wnioski_nadgodziny` WHERE `idWniosku`='$idWniosku'");
                    }
            
                if ($wpisz && $usun) {
                mysql_query("COMMIT");
                return 1; //poszlo do bazy
                } else {
                mysql_query("ROLLBACK");  
                return 2; //nie udalo sie
                }
                mysql_query('SET AUTOCOMMIT = 1');
            

    //insert dodajacy wykorzystane nadgodziny

    
    
    
    /*
    mysql_query("SET AUTOCOMMIT=0");
    mysql_query("START TRANSACTION");

    $r1 = mysql_query("INSERT INTO `uzytkownicy` (`Login`, `Haslo`, `idZolnierza`, `idUprawnienia`) VALUES ('udana', '21232f297a57a5a743894a0e4a801fc3', '22', '1');")
        or die(mysql_error());

    $r2 = mysql_query("INSERT INTO `uzytkownicy` (`Login`, `Haslo`, `idZolnierza`, `idUprawnienia`) VALUES ('nieudana', '21232f297a57a5a743894a0e4a801fc3', '99', '1');")
        or die("cos poszlo nie tak");


    if ($r1 and $r2) {
        mysql_query("COMMIT");
        echo "udalo sie";
    } else {
        echo "nie udalo sie";
        mysql_query("ROLLBACK");  
    }
    mysql_query('SET AUTOCOMMIT = 1');
    */
}


function wnioski($kogo) {

    if(isset($kogo)) { //&& $kogo != id_zolnierza() drugi warunek ale nie pamietam po co go dalem
        $czyje_url = '&profil='.$kogo; //dopisujemy url do zalogowanego
            //zmienna pomocnicza do wyswietlania nadgodzin uzytkownika
        $czyje_id = $kogo;
    }elseif(empty ($kogo)){
        $kogo = id_zolnierza();
            //zmienna pomocnicza do wyswietlania nadgodzin uzytkownika
        $czyje_id = $kogo;
    }
//jeżeli istnieje $kogo profil sprawdzamy czy mamy dostep do danego profilu     
    if( isset($kogo) && $kogo == mamDostepDo($kogo)) {
    


    //Definiujemy zmienne pomocnicze do stronicowania
    $nazwa=$_SESSION['user']."-nadgodziny";
    if(isset($_COOKIE[$nazwa]) && !empty($_COOKIE[$nazwa])){
        $ile=$_COOKIE[$nazwa];
    }else{    
        $ile=10; //ilosc wyswietlanych wpisow na strone
    }
    $strona=0; //poczatkowy numer strony, jezeli nie zostal podany
    $idOdrzuc = $_GET['odrzuc'];
    $idAkcpetuj = $_GET['akceptuj'];
    $idAkceptujZaz = $_POST['akceptujzaz'];
    $idOdrzucZaz = $_POST['odrzuc'];
    

    //ZMIENNE DO ZAPISANIA EDYTOWANYCH NADGODZIN
    $zaznaczone = $_POST['akceptuj'];

        if(isset($_GET['strona'])){
            $strona = (int)$ile*$_GET['strona']; //pobieramy numer strony
        }

    //USUWAMY DODANE NADGODZINY    
        if(isset($idOdrzuc) && !empty($idOdrzuc)){//najpierw sprawdzamy czy zmienna istnieje
            $sprawdzenie = mysql_query("SELECT * FROM `wnioski_nadgodziny` WHERE `idWniosku`='$idOdrzuc'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
            if((int)mysql_num_rows($sprawdzenie) > 0) {
                
                while($check = mysql_fetch_object($sprawdzenie)) {  
                    $zgoda = intval($check->kogo);
                }
                    if($zgoda == mamDostepDo($zgoda)){
                        $usun = mysql_query("DELETE FROM `wnioski_nadgodziny` WHERE `idWniosku`='$idOdrzuc'");
                        $komunikat = "Odrzuciłeś wniosek"; //niewypisany, wiec go nie zobaczymy
                    }else{
                        echo "<p class='wysrodkuj'>Nie masz uprawnień.<br>Nie kombinuj.</p>"; 
                    }              
            }else{
                $komunikat = "Nie ma co usunąć, zrobiłeś to wcześniej"; //niewypisany, wiec go nie zobaczymy
            }
        }
        
        

        //USUWAMY ZAZNACZONE WNIOSKI
    if(isset($_POST['odrzuc'])){//to run PHP script on submit
        if(!empty($_POST['wniosek'])){
            foreach($_POST['wniosek'] as $idOdrzucZaz){
                $sprawdzenie = mysql_query("SELECT * FROM `wnioski_nadgodziny` WHERE `idWniosku`='$idOdrzucZaz'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
                if((int)mysql_num_rows($sprawdzenie) > 0) { 
                    $usun = mysql_query("DELETE FROM `wnioski_nadgodziny` WHERE `idWniosku`='$idOdrzucZaz'");
                    $komunikat = "Odrzuciłeś wnioski";
                }else{
                    $blad_edycji = "Nie ma co usunąć, wniosek nie istnieje";
                }
            }
        }
        /*
            $extra = 'index.php?id=alerty';
            $extra3 = '&strona=';
            $extra4 = $_POST[strona];
            header("Location: $extra$extra3$extra4$czyje_url");
            exit;  
*/
    }


    //AKCEPTUJEMY DODANE WNIOSKI POJEDYNCZO    
        if(isset($idAkcpetuj) && !empty($idAkcpetuj)){//najpierw sprawdzamy czy zmienna istnieje
            $sprawdzenie = mysql_query("SELECT * FROM `wnioski_nadgodziny` WHERE `idWniosku`='$idAkcpetuj'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
            if((int)mysql_num_rows($sprawdzenie) > 0) {
                
                while($check = mysql_fetch_object($sprawdzenie)) {  
                    $zgoda = intval($check->kogo);
                     $typek = $check->kogo;
                     $dzionek = $check->wolne;
                     $nieobecnosc = $check->ile;
                     $usun_wniosek = $check->idWniosku;
                }
                    if($zgoda == mamDostepDo($zgoda)){
                        pusc_na_wolne($typek, $dzionek, $nieobecnosc, $usun_wniosek);
                       $komunikat = "Zaakceptowałeś wniosek"; //niewypisany, wiec go nie zobaczymy
                    }else{
                        echo "<p class='wysrodkuj'>Nie masz uprawnień.<br>Nie kombinuj.</p>"; 
                    }              
            }else{
                $komunikat = "Nie ma co akceptować, zrobiłeś to wcześniej"; //niewypisany, wiec go nie zobaczymy
            }
        }
        
    //AKCEPTUJEMY DODANE WNIOSKI ZBIOROWO

        if(isset($idAkceptujZaz)){//najpierw sprawdzamy czy zmienna istnieje
            if(!empty($_POST['wniosek'])){
                foreach($_POST['wniosek'] as $idAkceptujZaz){
                $sprawdzenie = mysql_query("SELECT * FROM `wnioski_nadgodziny` WHERE `idWniosku`='$idAkceptujZaz'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
                
                if((int)mysql_num_rows($sprawdzenie) > 0) {
                    
                    while($check = mysql_fetch_object($sprawdzenie)) {  
                        $zgoda = intval($check->kogo);
                        $typek = $check->kogo;
                        $dzionek = $check->wolne;
                        $nieobecnosc = $check->ile;
                        $usun_wniosek = $check->idWniosku;
                    }
                    if($zgoda == mamDostepDo($zgoda)){
                        pusc_na_wolne($typek, $dzionek, $nieobecnosc, $usun_wniosek);
                       $komunikat = "Zaakceptowałeś wnioski"; //niewypisany, wiec go nie zobaczymy
                    }else{
                        echo "<p class='wysrodkuj'>Nie masz uprawnień.<br>Nie kombinuj.</p>"; 
                    }              
                }else{
                    $blad_edycji = "Nie ma co usunąć, wniosek nie istnieje";
                }
            }
/*
            $extra = 'index.php?id=alerty';
            $extra3 = '&strona=';
            $extra4 = $_POST[strona];
            header("Location: $extra$extra3$extra4$czyje_url");
            exit;  
*/
        }
        }

     //POBIERAMY DODANE wnioski
     /*ALERTY DLA DOWODCOW*/
//wybieramy dowodce wg uprawnien
    switch ($_SESSION['permissions']){
        case 1:
            //admin
            $wnioski = mysql_query("SELECT *, CONCAT_WS(' ',stopnie.Skrot, UPPER(zolnierze.Nazwisko), zolnierze.Imie) as wnioskujacy FROM wnioski_nadgodziny left join zolnierze on kogo=idZolnierza left join stopnie using (idStopien) ORDER BY wolne LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania'); 
            break;
        case 2:
            //dowodca grupy
            $idGrupy = id_grupy();
            $wnioski = mysql_query("SELECT *, CONCAT_WS(' ',stopnie.Skrot, UPPER(zolnierze.Nazwisko), zolnierze.Imie) as wnioskujacy FROM wnioski_nadgodziny left join zolnierze on kogo=idZolnierza left join eskadry using(idEskadry) left join stopnie using (idStopien) where idGrupy='$idGrupy' ORDER BY wolne LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania');
            break; 
        case 3:
            //dowodca eskadry
            $idDowodcy = id_zolnierza();
            $idEskadry = id_eskadry();
            $wnioski = mysql_query("SELECT *, CONCAT_WS(' ',stopnie.Skrot, UPPER(zolnierze.Nazwisko), zolnierze.Imie) as wnioskujacy FROM wnioski_nadgodziny left join zolnierze on kogo=idZolnierza left join stopnie using (idStopien) where idEskadry='$idEskadry' ORDER BY wolne LIMIT $ile OFFSET $strona") 
            or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do eskadry'); 
            break;
        case 4:
            //szef eskadry
            $idEskadry = id_eskadry();
            $wnioski = mysql_query("SELECT *, CONCAT_WS(' ',stopnie.Skrot, UPPER(zolnierze.Nazwisko), zolnierze.Imie) as wnioskujacy FROM wnioski_nadgodziny left join zolnierze on kogo=idZolnierza left join stopnie using (idStopien) where idEskadry='$idEskadry' ORDER BY wolne LIMIT $ile OFFSET $strona") 
            or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do eskadry'); 
            break;
        case 5:
            //dowodca klucza
            $idKlucza = id_klucza();
            $wnioski = mysql_query("SELECT *, CONCAT_WS(' ',stopnie.Skrot, UPPER(zolnierze.Nazwisko), zolnierze.Imie) as wnioskujacy FROM wnioski_nadgodziny left join zolnierze on kogo=idZolnierza left join stopnie using (idStopien) where idKlucza='$idKlucza' ORDER BY wolne LIMIT $ile OFFSET $strona") 
            or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do eskadry'); 
            break;
        case 6:
            //zolnierz
            header('Location: index.php');
            break;
    }
    $wpisow = (int)mysql_num_rows($wnioski);
    $stron = ceil($wpisow/$ile); //ilosc stron
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
                    echo '<option value="index.php?id=alerty'.$czyje_url.'&strona='.$i.'">'.($i+1).'</option>';
                }
             }
             //echo '[...] <a href="index.php?id=panele/moje/nadgodziny&ile='.$ile.'&do='.($i-1).'">'.$i.' </a>';
             echo "</select>";
             echo "</div>";
             echo "<div class=\"order-1\">";
             //wygenerowanie linku do poprzedniej strony
                if ($poprzednia > -1) {
                echo '<a href="index.php?id=alerty'.$czyje_url.'&strona='.$poprzednia.'" class="button blekitne">Poprzednia</a>';
                }else{
                    echo "<button class=\"button\" disabled>Poprzednia</button>";
                }
            echo "</div>";
            echo "<div class=\"order-3\">";
            //wygenerowanie linku do nastepnej strony
            if ($nastepna < $i) {
                echo '<a href="index.php?id=alerty'.$czyje_url.'&strona='.$nastepna.'" class="button blekitne">Nastepna</a>';
            } else {
                echo "<button class=\"button\" disabled>Nastepna</button>";
            }
            echo "</div>";
        echo "</div>";
    }
    /*Wypisanie danych nadgodzin z bazy mysql*/
        if(mysql_num_rows($wnioski) > 0) { //jezeli zapytanie zwrocilo wiecej zapytan od 0 to wykonaj sie
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */    
            echo "<table>";
                echo "<caption>Ilosc oczekujących wniosków: $wpisow<br>";if(isset($komunikat)){echo $komunikat;}echo"</caption>";
                echo "<form class=\"nadgodzinki\" name=\"nadgodzinki\" method=\"post\" action=\"\">";
                echo "<thead>";
                    echo "<tr class=\"blekitne empty-cells\">";
                        echo "<th></th>";
                        echo "<th><input type=\"checkbox\" id=\"select-all\" disabled=\"disabled\"></th>";
                        echo "<th>w dniu</th>";
                        echo "<th>godzin</th>";
                        echo "<th class=\"left\">wnioskujący</th>";
                        echo "<th colspan=\"2\">";
                        echo "<input type=\"submit\" name=\"akceptujzaz\" id=\"edytujgodzinki\" class=\"edytuj\" value=\"akceptuj zaznaczone\" title=\"akceptuj zaznaczone\"/></th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                    while($r = mysql_fetch_object($wnioski)) {
                        echo "<tr class=\"blekitne\">";
                            echo "<td class=\"left\">";
                                echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=".$r->kogo."\">";
                                echo "<img src=\"img/avatars/";avatar($r->kogo);
                                echo "\" class=\"zaokraglij\" height=\"26px\" title=\"".$r->wnioskujacy."<br>Dodane: ".$r->kiedy_zlozyl."\" align=\"absmiddle\">";
                                echo "</a>";
                            echo "</td>";


                                    echo "<td>";
                                        echo "<input type=\"checkbox\" name=\"wniosek[]\" value=\"$r->idWniosku\">";
                                    echo "</td>";
                                    echo "<td>$r->wolne</td>"; /*wyswietlamy daty*/
                                    echo "<td>".(($r->ile)/60)."</td>"; /*wyswietlamy godziny*/
                                
                            echo "<td class=\"left\">$r->wnioskujacy</td>"; /*wyswietlamy do kiedy mamy czas wykorzystać nadgodziny*/                              
                                    echo "<td><a class=\"edytuj\" href=\"index.php?id=alerty$czyje_url&strona=".$dousuniecia."&akceptuj=".$r->idWniosku."\">Akceptuj</a></td>";
                                    echo "<td><a class=\"usun\" href=\"index.php?id=alerty$czyje_url&strona=".$dousuniecia."&odrzuc=".$r->idWniosku."\">Odrzuć</a></td>";   
                        echo "</tr>";
                    }
                echo "</tbody>";
                echo "<tr class=\"blekitne empty-cells\">";
                        echo "<th></th>";
                        echo "<th></th>";
                        echo "<th></th>";
                        echo "<th></th>";
                        echo "<th></th>";
                        echo "<th colspan=\"2\">";
                        echo "<input type=\"hidden\" name=\"strona\" value=\"$dousuniecia\">";
                                echo "<input type=\"submit\" name=\"odrzuc\" id=\"odrzuczaz\" class=\"usunwszystkie\" value=\"odrzuć zaznaczone\" title=\"odrzuć zaznaczone\"/></th>";
                    echo "</tr>";
                echo "</form>"; 
            echo "</table>";   
        }else{
            echo "Brak nadgodzin do wyświetlenia";
        }
           
    }else{
        echo mamDostepDo($kogo); //funkcja zwraca blad
    }
}//koniec moich nadgodzin

?>


<h1>Centrum powiadomień</h1>
<h2 class="podpowiedzi zaokraglij">Powiadomienia, wnioski</h2>
<div class="flex-container">
    <div class="panel tysiac">
       <div class="tytul">
          <p>wnioski</p>
<!--          <p class="right"><a href="#index.php?id=panele/admin/uzytkownicy" class="pl-10 pr-10 edytuj valing40" title="wyświetl wszystkich użytkowników">opcja</a></p>-->
       </div>
       <div class="zawartosc" >
            <?php wnioski(); ?>
       </div>    
    </div>
    </div>
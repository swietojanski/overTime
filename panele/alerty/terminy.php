<?php

/* 
 * Wyswietlamy powiadomienia zwiazane z przekroczeniem terminu
 */


function terminy($kogo) {

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
    $nazwa=$_SESSION['user']."-a-terminy";
    if(isset($_COOKIE[$nazwa]) && !empty($_COOKIE[$nazwa])){
        $ile=$_COOKIE[$nazwa];
    }else{    
        $ile=10; //ilosc wyswietlanych wpisow na strone
    }
    $strona=0; //poczatkowy numer strony, jezeli nie zostal podany
    
        if(isset($_GET['strona_t'])){
            $strona = (int)$ile*$_GET['strona_t']; //pobieramy numer strony
        }

     //POBIERAMY DODANE wnioski
     /*ALERTY DLA DOWODCOW*/
//wybieramy dowodce wg uprawnien
    $dzisiaj = date("Y-m-d");
    switch ($_SESSION['permissions']){
        case 1:
            //admin
            $terminy_n = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' GROUP BY idZolnierza ORDER BY ma_wykorzystac DESC LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania'); 
            $terminy_s = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' GROUP BY idZolnierza ORDER BY ma_wykorzystac DESC LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania'); 
            $licz_terminy = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' GROUP BY idZolnierza
                                         union all
                                         SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' GROUP BY idZolnierza") 
            or die('Błąd zapytania liczenia'); 
            break;
        case 2:
            //dowodca grupy
            $idGrupy = czyDowodcaGrupy();
            if (empty($idGrupy)){
                $idGrupy = id_grupy();
            }
            $terminy_n = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin left join zolnierze using (idZolnierza) left join eskadry using(idEskadry) left join grupy using (idGrupy) WHERE idGrupy='$idGrupy' and DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' GROUP BY idZolnierza ORDER BY ma_wykorzystac DESC LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania'); 
            $terminy_s = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb left join zolnierze using (idZolnierza) left join eskadry using(idEskadry) left join grupy using (idGrupy) WHERE idGrupy='$idGrupy' and DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' GROUP BY idZolnierza ORDER BY ma_wykorzystac DESC LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania'); 
            $licz_terminy = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin left join zolnierze using (idZolnierza) left join eskadry using(idEskadry) left join grupy using (idGrupy) WHERE idGrupy='$idGrupy' and DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' GROUP BY idZolnierza
                                         union all
                                         SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb left join zolnierze using (idZolnierza) left join eskadry using(idEskadry) left join grupy using (idGrupy) WHERE idGrupy='$idGrupy' and DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' GROUP BY idZolnierza") 
            or die('Błąd zapytania liczenia'); 
            break; 
        case 3:
            //dowodca eskadry
            $idDowodcy = id_zolnierza();
            $idEskadry = id_eskadry();
            $terminy_n = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idEskadry='$idEskadry' GROUP BY idZolnierza ORDER BY ma_wykorzystac DESC LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania'); 
            $terminy_s = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idEskadry='$idEskadry' GROUP BY idZolnierza ORDER BY ma_wykorzystac DESC LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania'); 
            $licz_terminy = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idEskadry='$idEskadry' GROUP BY idZolnierza
                                         union all
                                         SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idEskadry='$idEskadry' GROUP BY idZolnierza") 
            or die('Błąd zapytania liczenia');  
            break;
        case 4:
            //szef eskadry
            $idEskadry = id_eskadry();
            $terminy_n = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idEskadry='$idEskadry' GROUP BY idZolnierza ORDER BY ma_wykorzystac DESC LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania'); 
            $terminy_s = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idEskadry='$idEskadry' GROUP BY idZolnierza ORDER BY ma_wykorzystac DESC LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania'); 
            $licz_terminy = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idEskadry='$idEskadry' GROUP BY idZolnierza
                                         union all
                                         SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idEskadry='$idEskadry' GROUP BY idZolnierza") 
            or die('Błąd zapytania liczenia');  
            break;
        case 5:
            //dowodca klucza
            $idKlucza = id_klucza();
            $terminy_n = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idKlucza='$idKlucza' GROUP BY idZolnierza ORDER BY ma_wykorzystac DESC LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania'); 
            $terminy_s = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idKlucza='$idKlucza' GROUP BY idZolnierza ORDER BY ma_wykorzystac DESC LIMIT $ile OFFSET $strona") 
            or die('Błąd zapytania'); 
            $licz_terminy = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idKlucza='$idKlucza' GROUP BY idZolnierza
                                         union all
                                         SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idKlucza='$idKlucza' GROUP BY idZolnierza") 
            or die('Błąd zapytania liczenia');  
            break;
        case 6:
            //zolnierz
            header('Location: index.php');
            break;
    }
    $wpisow = (int)mysql_num_rows($licz_terminy);
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
                    echo '<option value="index.php?id=alerty'.$czyje_url.'&strona_t='.$i.'">'.($i+1).'</option>';
                }
             }
             //echo '[...] <a href="index.php?id=panele/moje/nadgodziny&ile='.$ile.'&do='.($i-1).'">'.$i.' </a>';
             echo "</select>";
             echo "</div>";
             echo "<div class=\"order-1\">";
             //wygenerowanie linku do poprzedniej strony
                if ($poprzednia > -1) {
                echo '<a href="index.php?id=alerty'.$czyje_url.'&strona_t='.$poprzednia.'" class="button blekitne">Poprzednia</a>';
                }else{
                    echo "<button class=\"button\" disabled>Poprzednia</button>";
                }
            echo "</div>";
            echo "<div class=\"order-3\">";
            //wygenerowanie linku do nastepnej strony
            if ($nastepna < $i) {
                echo '<a href="index.php?id=alerty'.$czyje_url.'&strona_t='.$nastepna.'" class="button blekitne">Nastepna</a>';
            } else {
                echo "<button class=\"button\" disabled>Nastepna</button>";
            }
            echo "</div>";
        echo "</div>";
    }
    /*Wypisanie danych nadgodzin z bazy mysql*/
    echo "<div class=\"flex-container\">";
        if(mysql_num_rows($terminy_n) > 0) { //jezeli zapytanie zwrocilo wiecej zapytan od 0 to wykonaj sie
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
            
                    while($r = mysql_fetch_object($terminy_n)) {

            echo "<div class=\"flex-container\">";
                echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->idZolnierza\" id=\"profil\">"
                . "<div class=\"panel\">";
                                    echo "<div class=\"podpis\">";
                        echo "<p class=\"dane\">";st_nazwisko_imie($r->idZolnierza);echo"</p>";
                    echo "</div>";
                    echo "<div class=\"zawartosc blekitne wybrane\" >";
                        echo "<img src=\"img/profiles/thumbnail/$r->Zdjecie\" width=\"200px\" align=\"absmiddle\" alt=\"Zdjęcie profiliwe\" title=\"Wyślij na wolne\" class=\"zaokraglij\">";
                    echo "</div>";
                                        echo "<div class=\"podpis\">";
                        echo "<p class=\"dane\" title=\"Nadgodziny<br>Termin minął: ".($r->waznosc)."r.\">".(($r->ma_wykorzystac)/60)." godz.</p>";
                    echo "</div>";
                echo "</div></a>";
            echo "</div>";       
                    }
        }
        if(mysql_num_rows($terminy_s) > 0) { //jezeli zapytanie zwrocilo wiecej zapytan od 0 to wykonaj sie
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
           
                    while($r = mysql_fetch_object($terminy_s)) {

            echo "<div class=\"flex-container\">";
                echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->idZolnierza\" id=\"profil\">"
                . "<div class=\"panel\">";
                                    echo "<div class=\"podpis\">";
                        echo "<p class=\"dane\">";st_nazwisko_imie($r->idZolnierza);echo"</p>";
                    echo "</div>";
                    echo "<div class=\"zawartosc blekitne wybrane\" >";
                        echo "<img src=\"img/profiles/thumbnail/$r->Zdjecie\" width=\"200px\" align=\"absmiddle\" alt=\"Zdjęcie profiliwe\" title=\"Wyślij na wolne\" class=\"zaokraglij\">";
                    echo "</div>";
                                        echo "<div class=\"podpis\">";
                        echo "<p class=\"dane\" title=\"Służby<br>Termin minął: ".($r->waznosc)."r.\">".(($r->ma_wykorzystac)/480)." dn.</p>";
                    echo "</div>";
                echo "</div></a>";
            echo "</div>";       
                    }
            
                
        }
        if(mysql_num_rows($terminy_s) == 0 && mysql_num_rows($terminy_n) == 0){
            echo "Czas ponadnormatywny nie przekroczył terminu ważności";
        }
           echo "</div>"; 
    }else{
        echo mamDostepDo($kogo); //funkcja zwraca blad
    }
}//koniec moich nadgodzin

?>
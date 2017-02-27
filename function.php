<?php
//warunek ktory nie pozwoli skorzystac z zadnej funkcji jezeli nie jestesmy zalogowani
if ($_SESSION['auth'] == TRUE) {
    

//Wyswietli id profilu aktualnie zalogowanego uzytkownika lub po podaniu loginu jakiegos uzytkownika
function id_zolnierza($kogo) {
    
    if( isset($kogo) ) {
            $uzytkownik = $kogo;
    }else{
        $uzytkownik = $_SESSION['user']; // jezeli nie podane $kogo to wyswietl id zalogowanego uzytkownika
    }

$zapytanie = mysql_query("SELECT zolnierze.idZolnierza FROM zolnierze left join uzytkownicy using (idZolnierza) WHERE zolnierze.idZolnierza='$uzytkownik' or uzytkownicy.Login='$uzytkownik' limit 1") 
or die('Błąd zapytania'); 
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 w sumie to rowna 1
*/ 
    if(mysql_num_rows($zapytanie) === 1) { 
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
            while($r = mysql_fetch_object($zapytanie)) {  
                return intval($r->idZolnierza);
            } 
        }
}
//sprawdzamy ID ESKADRY po podaniu loginu lub id do jakiej eskadry nalezy zolnierz
function id_eskadry($kogo) {
    
    if(isset($kogo)) {
            $uzytkownik = $kogo;
    }else{
        $uzytkownik = $_SESSION['user']; // jezeli nie podane $kogo to wyswietl id zalogowanego uzytkownika
    }

$zapytanie = mysql_query("SELECT zolnierze.idEskadry FROM zolnierze left join uzytkownicy using (idZolnierza) WHERE zolnierze.idZolnierza='$uzytkownik' or uzytkownicy.Login='$uzytkownik' limit 1") 
or die('Błąd zapytania'); 
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 w sumie to rowna 1
*/ 
    if(mysql_num_rows($zapytanie) == 1) { 
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
            while($r = mysql_fetch_object($zapytanie)) {  
                return intval($r->idEskadry);
            } 
        }
}

//sprawdzamy ID GRUPY po podaniu loginu lub id do jakiej eskadry nalezy zolnierz
function id_grupy($kogo) {
    
    if( isset($kogo) ) {
            $uzytkownik = $kogo;
    }else{
        $uzytkownik = $_SESSION['user']; // jezeli nie podane $kogo to wyswietl id zalogowanego uzytkownika
    }

$zapytanie = mysql_query("SELECT grupy.idGrupy FROM grupy left join eskadry using(idGrupy) left join zolnierze using(idEskadry) left join uzytkownicy using (idZolnierza) WHERE zolnierze.idZolnierza='$uzytkownik' or uzytkownicy.Login='$uzytkownik' limit 1") 
or die('Błąd zapytania'); 
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 w sumie to rowna 1
*/ 
    if(mysql_num_rows($zapytanie) == 1) { 
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
            while($r = mysql_fetch_object($zapytanie)) {  
                return intval($r->idGrupy);
            } 
        }
}

//SPRAWDZAMY CZY PODANY ID JEST Dowodca Grupy 
//po podaniu id lub zaciagnieciu go z sesji zalogowanego uzytkownika sprawdzamy
//czy jest przypisany jako dowodca grupy
//jezeli tak zwracamy jego ID a w warunku mozemy wypisac to co chcemy
function czyDowodcaGrupy($kto) {
    if( isset($kto) ) {
            $uzytkownik = $kto;
    }else{
        $uzytkownik = id_zolnierza($_SESSION['user']); // jezeli nie podane $kogo to wyswietl id zalogowanego uzytkownika
    }

$dca = mysql_query("SELECT idGrupy FROM grupy WHERE grupy.DcaGrupy='$uzytkownik'") 
or die('Błąd zapytania'); 
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 w sumie to ma byc rowna 1
 * ale moze byc tak, ze jeden zolnierz jest dowodca dwoch grup ale malo prawdopodobne
*/ 
    if(mysql_num_rows($dca) > 0) { 
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
            while($dowodca = mysql_fetch_object($dca)) {  
                return intval($dowodca->idGrupy);
            } 
        }
}
//SPRAWDZAMY CZY PODANY ID JEST Dowodca Eskadry 
//po podaniu id lub zaciagnieciu go z sesji zalogowanego uzytkownika sprawdzamy
//czy jest przypisany jako dowodca eskadry
//jezeli tak zwracamy jego ID a w warunku mozemy wypisac to co chcemy
function czyDowodca($kto) {
    if( isset($kto) ) {
            $uzytkownik = $kto;
    }else{
        $uzytkownik = id_zolnierza($_SESSION['user']); // jezeli nie podane $kogo to wyswietl id zalogowanego uzytkownika
    }

$dca = mysql_query("SELECT eskadry.idEskadry FROM eskadry WHERE eskadry.DcaEskadry='$uzytkownik'") 
or die('Błąd zapytania'); 
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 w sumie to rowna 1
*/ 
    if(mysql_num_rows($dca) > 0) { 
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
            while($dowodca = mysql_fetch_object($dca)) {  
                return intval($dowodca->idEskadry);
            } 
        }
}
//SPRAWDZAMY CZY PODANY ID JEST Szefem Eskadry 
//po podaniu id lub zaciagnieciu go z sesji zalogowanego uzytkownika sprawdzamy
//czy jest przypisany jako dowodca eskadry
//jezeli tak zwracamy jego ID a w warunku mozemy wypisac to co chcemy
function czySzef($kto) {
    if( isset($kto) ) {
            $uzytkownik = $kto;
    }else{
        $uzytkownik = id_zolnierza($_SESSION['user']); // jezeli nie podane $kogo to wyswietl id zalogowanego uzytkownika
    }

$szef = mysql_query("SELECT eskadry.idEskadry FROM eskadry WHERE eskadry.SzefEskadry='$uzytkownik'") 
or die('Błąd zapytania'); 
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 w sumie to rowna 1
*/ 
            if(mysql_num_rows($szef) > 0) { 
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
                while($szefo = mysql_fetch_object($szef)) {  
                    return intval($szefo->idEskadry);
                } 
            }     
}

function skrotGrupy($id){
    if( isset($id) ) {
        $ideskadry = $id;
         $zapytanie = mysql_query("SELECT grupy.Skrot, grupy.Nazwa FROM grupy WHERE grupy.idGrupy = '$ideskadry'") or die('Błąd zapytania');
    }else{
        $ideskadry = id_eskadry(); // jezeli nie podane $kogo to wyswietl id zalogowanego uzytkownika
        $zapytanie = mysql_query("SELECT grupy.Skrot, grupy.Nazwa FROM grupy WHERE grupy.idGrupy = (SELECT eskadry.idGrupy FROM eskadry where eskadry.idEskadry='$ideskadry') or die('Błąd zapytania'");
    }



 
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 w sumie to rowna 1
*/ 
    if(mysql_num_rows($zapytanie) == 1) { 
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
            while($r = mysql_fetch_object($zapytanie)) {  
                return $a= "<abbr title=\"$r->Nazwa\">$r->Skrot</abbr>";
            } 
        }  
}

function skrotEskadry($id){
    if( isset($id) ) {
            $uzytkownik = $id;
    }else{
        $uzytkownik = id_eskadry(); // jezeli nie podane $kogo to wyswietl id zalogowanego uzytkownika
    }

$zapytanie = mysql_query("SELECT eskadry.Skrot, eskadry.Nazwa FROM eskadry WHERE eskadry.idEskadry='$uzytkownik'") 
or die('Błąd zapytania'); 
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 w sumie to rowna 1
*/ 
    if(mysql_num_rows($zapytanie) === 1) { 
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
            while($r = mysql_fetch_object($zapytanie)) {  
                return $a= "<abbr title=\"$r->Nazwa\">$r->Skrot</abbr>";
            } 
        }  
}

function id_klucza($kogo) {
    
    if( isset($kogo) ) {
            $uzytkownik = $kogo;
    }else{
        $uzytkownik = $_SESSION['user']; // jezeli nie podane $kogo to wyswietl id zalogowanego uzytkownika
    }

$zapytanie = mysql_query("SELECT zolnierze.idKlucza FROM zolnierze left join uzytkownicy using(idZolnierza) WHERE zolnierze.idZolnierza='$uzytkownik' or uzytkownicy.Login = '$uzytkownik'") 
or die('Błąd zapytania'); 
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 w sumie to rowna 1
*/ 
    if(mysql_num_rows($zapytanie) === 1) { 
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
            while($r = mysql_fetch_object($zapytanie)) {  
                return intval($r->idKlucza);
            } 
        }
}

//funkcja sprawdza czy mamy dostep do zolnierza
//jezeli mamy dostep zwroci jego id
//jezeli dostepu brak wyswietli komunikat brak dostepu
function mamDostepDo($kogo){
    //sprawdzamy czy mamy dostep do wybranych nadgodzin
    switch ($_SESSION['permissions']){
    case 1:
        //admin
        $wystapil = mysql_query("SELECT idZolnierza FROM zolnierze WHERE idZolnierza='$kogo'") 
        or die('Błąd zapytania'); 
        break;
    case 2:
        //dowodca grupy
        $wystapil = mysql_query("SELECT idZolnierza FROM zolnierze WHERE idZolnierza='$kogo'") 
        or die('Błąd zapytania');
        break; 
    case 3:
        //dowodca eskadry
        //$idDowodcy = id_zolnierza();
        //$wystapil = mysql_query("SELECT idZolnierza FROM zolnierze, eskadry WHERE zolnierze.idEskadry = eskadry.idEskadry AND eskadry.DcaEskadry = '$idDowodcy' AND idZolnierza='$kogo'") 
        $idEskadry = id_eskadry();
        $wystapil = mysql_query("SELECT idZolnierza FROM zolnierze WHERE zolnierze.idEskadry = '$idEskadry' AND idZolnierza='$kogo'") 
        or die('Masz uprawnienia dowódcy eskadry, ale nie jesteś dowódcą do eskadry'); 
        break;
    case 4:
        //szef eskadry
        $idSzefa = id_zolnierza();
        $wystapil = mysql_query("SELECT idZolnierza  FROM zolnierze, eskadry WHERE zolnierze.idEskadry = eskadry.idEskadry AND eskadry.SzefEskadry = '$idSzefa' AND idZolnierza='$kogo'") 
        or die('Masz uprawnienia szefa, ale nie jesteś przypisany jako szef eskadry'); 
        break;
    case 5:
        //dowodca klucza
        $idKlucza = id_klucza();
        $wystapil = mysql_query("SELECT idZolnierza FROM zolnierze WHERE idKlucza='$idKlucza' AND idZolnierza='$kogo'") 
        or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do klucza'); 
        break;
    case 6:
        //zolnierz
        $idZolnierza = id_zolnierza();
        $wystapil = mysql_query("SELECT idZolnierza FROM zolnierze WHERE idZolnierza='$idZolnierza' AND idZolnierza='$kogo'") 
        or die('Błąd zapytania'); 
        break;
} 

    if(mysql_num_rows($wystapil) === 1) { 
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
            while($r = mysql_fetch_object($wystapil)) {  
                $moj = intval($r->idZolnierza);
            } 
    }elseif(isset($kogo) && $kogo != $moj){
        $moj = "Brak dostępu.";
    }
    return $moj;
}

//Wyswietlanie imienia zalogowanego uzytkownika
function imie($kogo) {
    
    if( isset($kogo) ) {
        $uzytkownik = $kogo;
    }else{
        $uzytkownik = $_SESSION['user'];
    }
    
$simie = mysql_query("SELECT zolnierze.Imie FROM zolnierze, uzytkownicy WHERE zolnierze.idZolnierza = uzytkownicy.idZolnierza AND uzytkownicy.Login = '$uzytkownik'") 
or die('Błąd zapytania'); 
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 
*/ 
    if(mysql_num_rows($simie) > 0) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        while($imie = mysql_fetch_assoc($simie)) { 
            echo $imie['Imie']; 
        } 
    }  else {
    echo $_SESSION['user'];    //jezeli profil nie jest przypisany to wyswietl login
    }
}

//Wyswietlanie stopnia nazwiska imienia podanego
function st_nazwisko_imie($kogo) {
    
    if( isset($kogo) ) {
        $uzytkownik = $kogo;
    }else{
        $uzytkownik = $_SESSION['user'];
    }
    
$snazwisko = mysql_query("SELECT *, CONCAT_WS(' ',stopnie.Skrot, UPPER(zolnierze.Nazwisko), zolnierze.Imie) as st_nazwisko_imie FROM zolnierze left join eskadry using(idEskadry) left join stopnie using (idStopien) left join uzytkownicy using(idZolnierza) where uzytkownicy.Login = '$uzytkownik' or zolnierze.idZolnierza = '$uzytkownik' limit 1") 
or die('Błąd zapytania'); 
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 
*/ 
    if(mysql_num_rows($snazwisko) > 0) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        while($imie = mysql_fetch_object($snazwisko)) { 
            echo $imie->st_nazwisko_imie; 
        } 
    }  else {
    echo $uzytkownik;    //jezeli profil nie jest przypisany to wyswietl login
    }
}

//Wyswietlanie avatara uzytkownika po podaniu jego loginu lub id zolnierza jezeli jest przypisany profil do uzytkownika
function avatar($uzytkownik) {
$savatar = mysql_query("SELECT uzytkownicy.Avatar FROM uzytkownicy WHERE (uzytkownicy.Login = '$uzytkownik' OR uzytkownicy.idZolnierza='$uzytkownik') limit 1") 
or die('Błąd zapytania'); 
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 
*/ 
    if(mysql_num_rows($savatar) > 0) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        while($avatar = mysql_fetch_assoc($savatar)) { 
            echo $avatar['Avatar']; 
        } 
    }else{
        echo "avatar.png";
    }
}

//WYSWIETLAMY ZDJECIE PROFILOWE
function profilowe($idZolnierza) {
$zdjecie = mysql_query("SELECT zolnierze.Zdjecie FROM zolnierze WHERE zolnierze.idZolnierza='$idZolnierza' limit 1") 
or die(mysql_error()); 
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 
*/ 
    if(mysql_num_rows($zdjecie) > 0) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        while($nazwa = mysql_fetch_object($zdjecie)) { 
            return $nazwa->Zdjecie; 
        } 
    }  else {
        echo $nazwa="zdjecie.jpg";    //jezeli profil nie jest przypisany to wyswietl login
    }
}


//DODAWANIE I EDYCJA ZDJECIA W PROFILU
function edytujZdjecie($profil){
    
    if (isset($_POST['wyslane'])) {
        // Sprawdź ładowany obrazek.
        if (isset($_FILES['upload'])) {

            list($width, $height, $type, $attr) = getimagesize($_FILES['upload']['tmp_name']);

                // Sprawdz typ, pownien być JPEG lub PNG gifa tez dodalem.
                $allowed = array ('image/jpeg', 'image/pjpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif', 'image/GIF');
                if (in_array($_FILES['upload']['type'], $allowed)) {

                        //adres oryginalnego, zalaczonego zdjecia
                        $url = $_FILES['upload']['tmp_name'];

                            switch($type){
                              case '6': $zdjecie = imagecreatefromwbmp($url); break;
                              case '1': $zdjecie = imagecreatefromgif($url); break;
                              case '2': $zdjecie = imagecreatefromjpeg($url); break;
                              case '3': $zdjecie = imagecreatefrompng($url); break;
                              default : return "Nieobsługiwany typ zdjęcia!";
                            }
                            // pobieramy wysokosc i szerokosc oryginalnego zdjecia, chociaz mozna to wyciagnac z  list xD
                        $x = imagesx($zdjecie);
                        $y = imagesy($zdjecie);
                           // ustalamy szerokosc nowego zdjecia
                        $final_x = 200; 
                        $final_y = 200;
                        
                           // tymczasowe zmienne x y, deklaracja
                        $tmp_x = 0;
                        $tmp_y = 0;

                        // skalowanie zdjecia z zaokragleniem ceil do gornej wartosci
                        if($y<$x) $tmp_x = ceil(($x-$final_x*$y/$final_y)/2);
                        elseif($x<$y) $tmp_y = ceil(($y-$final_y*$x/$final_x)/2);

                        $nowe = imagecreatetruecolor($final_x, $final_y);

                        //gdy plik to gif albo png przetwarzamy jego warstwe przezroczystosci
                            if($type == 1 or $type == 3){
                                imagecolortransparent($nowe, imagecolorallocatealpha($nowe, 0, 0, 0, 127));
                                imagealphablending($nowe, false);
                                imagesavealpha($nowe, true);
                            }
                        imagecopyresampled($nowe, $zdjecie, 0, 0, $tmp_x, $tmp_y, $final_x, $final_y, $x-2*$tmp_x, $y-2*$tmp_y);

                        //tworzymy nowa nazwe dla zdjecia
                        if (empty($profil)){
                            $idZolnierza=id_zolnierza();
                        }else{
                            $idZolnierza=$profil;
                        }
                        
                        $temp_nazwa_zdjecia = $_FILES['upload']['name']; //pobieramy tymczasowa nazwe zdjecia
                        $extension = pathinfo($temp_nazwa_zdjecia, PATHINFO_EXTENSION);  //wyciagamy rozszerzenie z oryginalnego pliku
                        $nazwa_zdjecia = "photo-".$idZolnierza.".".$extension; //tworzymy nowa nazwe skladajaca sie z id zolnierza i rozszerzenia
                        
                        
                        $url = $_SERVER['DOCUMENT_ROOT'].'/img/profiles/thumbnail/'.$nazwa_zdjecia;
                        //wybieramy jaka bedzie metoda tworzenia
                          switch($type){
                            case '6': imagewbmp($nowe, $url, 100); break;
                            case '1': imagegif($nowe, $url, 100); break;
                            case '2': imagejpeg($nowe, $url, 100); break;
                            case '3': imagepng($nowe, $url); break;
                          }

                     // Koniec instrukcji if move...
                          // Przenieś oryginalny plik do docelowego katalogu.
                        move_uploaded_file($_FILES['upload']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/img/profiles/'.$nazwa_zdjecia); 
                        $picture = "<img src=\"/img/profiles/thumbnail/".$nazwa_zdjecia."\">";
                        //jezeli zdjecie zostaje dodane to wpisujemy nazwe zdjecia do bazy                      
                        
                        
                        
                        $sprawdzenie = mysql_query("SELECT * FROM `zolnierze` WHERE `idZolnierza`='$idZolnierza'");// zapytanie sprawdzajace zolnierz istnieje 
                            if((int)mysql_num_rows($sprawdzenie) > 0) { 
                                $dodajfote = mysql_query("UPDATE `zolnierze` SET `Zdjecie`='$nazwa_zdjecia' WHERE `idZolnierza`='$idZolnierza'");
                                $status="zaladowane";
                            }else{
                                $blad_usuniecia = "Nie ma co edytować";
                            }

                } else { // Niepoprawny typ.
                //tutaj mozemy wyrzucic komunikat o zlym formacie pliku
                    //echo '<p class="error">Proszę załadować plik typu JPEG lub PNG.</p>';
                }
        } // Koniec instrukcji if isset($_FILES['upload']).

        // Wyrzuć błąd.

        if ($_FILES['upload']['error'] > 0) {
            // Wyświetl odpowiedni komunikat w zależności od błędu.
            switch ($_FILES['upload']['error']) {
                case 1:
                    $blad = 'Rozmiar pliku większy niż pozwala serwer.';
                    break;
                case 2:
                    $blad = 'Nieprawidłowy rozmiar lub typ pliku.';
                    break;
                case 3:
                    $blad = 'Plik został częściowo załadowany.';
                    break;
                case 4:
                    $blad = 'Żaden plik nie został załadowany.';
                    break;
                case 6:
                    $blad = 'Katalog tymczasowy był niedostępny.';
                    break;
                case 7:
                    $blad = 'Brak możliwości zapisu na dysk.';
                    break;
                case 8:
                    $blad = 'Proces ładowania został wstrzymany.';
                    break;
                default:
                    $blad = 'Wystąpił błąd systemu.';
                    break;
            } // Koniec instrukcji switch.

            print '</strong></p>';

        } // Koniec instukcji if błędami.

        // Usuń plik jezeli jeszcze istnieje.
        if (file_exists ($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name']) ) {
            unlink ($_FILES['upload']['tmp_name']);
        }       
    } // Koniec głównej instrukcji if.
    
    //formularz
    echo "<form enctype=\"multipart/form-data\" action=\"\" method=\"post\">";
     echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1048576\">";
        echo "<div class=\"upload zaokraglij\"><div class=\"index-1\">";
         if (empty($picture) && empty($blad)){
                          echo "Kliknij tutaj<br>";
         }elseif(isset ($status)){
             echo "<div class=\"index-2\">Zapisano<br></div>";
         }
            if(isset($blad)){
                echo $blad;
             }else{
                 echo $picture;
             }
         echo "</div><input type=\"file\" name=\"upload\" accept=\"image/gif,image/jpeg,image/png\" pattern=\"([^\s]+(\.(?i)(jpg|png|gif|bmp))$)\" title=\"Kliknij i dodaj zdjęcie\"/></div>";
         if (empty($status)){
         echo "<div class=\"wysrodkuj\"><input class=\"zapisz\" type=\"submit\" name=\"submit\" value=\"Zapisz\" /></div>";
         }
     echo "<input type=\"hidden\" name=\"wyslane\" value=\"TRUE\"/>";
     echo "</form>";
    
    
}

function zrobAvatar($profil, $uzytkownik){
    
    if( !isset($uzytkownik) ) {
        $uzytkownik = $_SESSION['user']; // jezeli nie podane $kogo to wyswietl id zalogowanego uzytkownika
    }
    
    if (isset($_POST['wyslane'])) {
        // Sprawdź ładowany obrazek.
        if (isset($_FILES['upload'])) {

            list($width, $height, $type, $attr) = getimagesize($_FILES['upload']['tmp_name']);

                // Sprawdz typ, pownien być JPEG lub PNG gifa tez dodalem.
                $allowed = array ('image/jpeg', 'image/pjpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif', 'image/GIF');
                if (in_array($_FILES['upload']['type'], $allowed)) {

                        //adres oryginalnego, zalaczonego zdjecia
                        $url = $_FILES['upload']['tmp_name'];

                            switch($type){
                              case '6': $zdjecie = imagecreatefromwbmp($url); break;
                              case '1': $zdjecie = imagecreatefromgif($url); break;
                              case '2': $zdjecie = imagecreatefromjpeg($url); break;
                              case '3': $zdjecie = imagecreatefrompng($url); break;
                              default : return "Nieobsługiwany typ pliku!";
                            }
                            // pobieramy wysokosc i szerokosc oryginalnego zdjecia, chociaz mozna to wyciagnac z  list xD
                        $x = imagesx($zdjecie);
                        $y = imagesy($zdjecie);
                           // ustalamy szerokosc nowego zdjecia
                        $final_x = 60; 
                        $final_y = 60;
                        
                           // tymczasowe zmienne x y, deklaracja
                        $tmp_x = 0;
                        $tmp_y = 0;

                        // skalowanie zdjecia z zaokragleniem ceil do gornej wartosci
                        if($y<$x) $tmp_x = ceil(($x-$final_x*$y/$final_y)/2);
                        elseif($x<$y) $tmp_y = ceil(($y-$final_y*$x/$final_x)/2);

                        $nowe = imagecreatetruecolor($final_x, $final_y);

                        //gdy plik to gif albo png przetwarzamy jego warstwe przezroczystosci
                            if($type == 1 or $type == 3){
                                imagecolortransparent($nowe, imagecolorallocatealpha($nowe, 0, 0, 0, 127));
                                imagealphablending($nowe, false);
                                imagesavealpha($nowe, true);
                            }
                        imagecopyresampled($nowe, $zdjecie, 0, 0, $tmp_x, $tmp_y, $final_x, $final_y, $x-2*$tmp_x, $y-2*$tmp_y);

                        //tworzymy nowa nazwe dla zdjecia
                      
                        
                        $temp_nazwa_zdjecia = $_FILES['upload']['name']; //pobieramy tymczasowa nazwe zdjecia
                        $extension = pathinfo($temp_nazwa_zdjecia, PATHINFO_EXTENSION);  //wyciagamy rozszerzenie z oryginalnego pliku
                        $nazwa_zdjecia = "avek-".$uzytkownik.".".$extension; //tworzymy nowa nazwe skladajaca sie z id zolnierza i rozszerzenia
                        
                        
                        $url = $_SERVER['DOCUMENT_ROOT'].'/img/avatars/'.$nazwa_zdjecia;
                        //wybieramy jaka bedzie metoda tworzenia
                          switch($type){
                            case '6': imagewbmp($nowe, $url, 100); break;
                            case '1': imagegif($nowe, $url, 100); break;
                            case '2': imagejpeg($nowe, $url, 100); break;
                            case '3': imagepng($nowe, $url); break;
                          }

                     // Koniec instrukcji if move...
                          // Przenieś plik do docelowego katalogu. ORYGINALNY PLIK NIE BEDZIE PRZENOSZONY
                       // move_uploaded_file($_FILES['upload']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/img/avatars/'.$nazwa_zdjecia); 
                        $picture = "<img height=\"70px\" width=\"70px\"  src=\"/img/avatars/".$nazwa_zdjecia."\">";
                        //jezeli zdjecie zostaje dodane to wpisujemy nazwe zdjecia do bazy                      
                        
                        
                        
                        $sprawdzenie = mysql_query("SELECT * FROM `uzytkownicy` WHERE `Login`='$uzytkownik'");// zapytanie sprawdzajace zolnierz istnieje 
                            if((int)mysql_num_rows($sprawdzenie) > 0) { 
                                $dodajfote = mysql_query("UPDATE `uzytkownicy` SET `Avatar`='$nazwa_zdjecia' WHERE `Login`='$uzytkownik'");
                                $status="zaladowane";
                            }else{
                                $blad_dodania = "Ten żołnierz nie ma konta użytkownika.";
                                echo $blad_dodania;
                            }

                } else { // Niepoprawny typ.
                //tutaj mozemy wyrzucic komunikat o zlym formacie pliku
                    //echo '<p class="error">Proszę załadować plik typu JPEG lub PNG.</p>';
                }
        } // Koniec instrukcji if isset($_FILES['upload']).

        // Wyrzuć błąd.

        if ($_FILES['upload']['error'] > 0) {
            // Wyświetl odpowiedni komunikat w zależności od błędu.
            switch ($_FILES['upload']['error']) {
                case 1:
                    $blad = 'Rozmiar pliku większy niż pozwala serwer.';
                    break;
                case 2:
                    $blad = 'Nieprawidłowy rozmiar lub typ pliku.';
                    break;
                case 3:
                    $blad = 'Plik został częściowo załadowany.';
                    break;
                case 4:
                    $blad = 'Żaden plik nie został załadowany.';
                    break;
                case 6:
                    $blad = 'Katalog tymczasowy był niedostępny.';
                    break;
                case 7:
                    $blad = 'Brak możliwości zapisu na dysk.';
                    break;
                case 8:
                    $blad = 'Proces ładowania został wstrzymany.';
                    break;
                default:
                    $blad = 'Wystąpił błąd systemu.';
                    break;
            } // Koniec instrukcji switch.

            print '</strong></p>';

        } // Koniec instukcji if błędami.

        // Usuń plik jezeli jeszcze istnieje.
        if (file_exists ($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name']) ) {
            unlink ($_FILES['upload']['tmp_name']);
        }       
    } // Koniec głównej instrukcji if.
    
    //formularz
    
    echo "<form enctype=\"multipart/form-data\" action=\"\" method=\"post\" name=\"avatar\" id=\"avatar\">";
        echo "<div class=\"flex-container\"> ";
            echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1048576\">";
                echo "<div class=\"avatar zaokraglij\">";
                    echo "<div class=\"index-1\">";
                        if (empty($picture) && empty($blad)){
                                         echo "Kliknij tutaj<br>";
                        }elseif(isset ($status)){
                            echo "<div class=\"index-2\">Zapisano<br></div>";
                        }
                        if(!isset($blad)){
                            echo $picture;
                        }
                    echo "</div>";
                    echo "<input type=\"file\" name=\"upload\" accept=\"image/gif,image/jpeg,image/png\" pattern=\"([^\s]+(\.(?i)(jpg|png|gif|bmp))$)\" title=\"Kliknij i wybierz avatar\"/>";
                echo "</div>"; 
        echo "</div>";
        
        echo "<input class=\"zapisz small\" type=\"submit\" name=\"submit\" value=\"ok\" />";


     echo "<input type=\"hidden\" name=\"wyslane\" value=\"TRUE\"/>";
        if(isset($blad)){
            echo"<br>";
             echo "$blad";
        }
     echo "</form>";
    
    
}

//Wyswietlanie danych profiliwych zolnierza - podstrona profil
function profil($profil) {
//jeżeli istnieje zmienna profil sprawdzamy czy mamy dostep do danego profilu  
    $url = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; //generujemy aktualny adres wyswietlanej strony

    //ZAPISUJEMY ZMIENIONE DANE ZOLNIERZA
        if(isset($_POST['zapisz_dane'])){//najpierw sprawdzamy czy zmienna istnieje
            $stopien = $_POST['stopien'];
            $imie = mysql_real_escape_string($_POST['imie']);
            $nazwisko = mysql_real_escape_string($_POST['nazwisko']);
            $eskadra = $_POST['eskadra'];
            $klucz = $_POST['klucz'];
            $profil;
                    
                    
                $sprawdzenie = mysql_query("SELECT * FROM `zolnierze` WHERE `idZolnierza`='$profil'");// zapytanie sprawdzajace czy zolnierz o danym id jest w bazie 
                if((int)mysql_num_rows($sprawdzenie) > 0) {
                    if(empty($klucz)){
                      $edytuj = mysql_query("UPDATE `zolnierze` SET `idStopien`='$stopien', `Imie`='$imie', `Nazwisko`='$nazwisko', `idEskadry`='$eskadra', `idKlucza`=null WHERE `idZolnierza`='$profil';");  
                    }else{
                      $edytuj = mysql_query("UPDATE `zolnierze` SET `idStopien`='$stopien', `Imie`='$imie', `Nazwisko`='$nazwisko', `idEskadry`='$eskadra', `idKlucza`='$klucz' WHERE `idZolnierza`='$profil';");  
                    }

                }
                /*
                $url = explode("&dane", $url); //wyrzucamy deklaracje zmiennej get z adresu
                $url = $url[0];
                $adres=$url;
            header("Location: $adres");
            exit;  */
        }
    
    if(isset($profil) && $profil == mamDostepDo($profil)) {
        $zolnierz = mysql_query("SELECT stopnie.idStopien, stopnie.Pelna, zolnierze.Imie, zolnierze.Nazwisko, zolnierze.Zdjecie, eskadry.idEskadry, eskadry.Nazwa AS Eskadra, eskadry.Skrot AS SkrotEskadra, klucze.Nazwa AS Klucz FROM stopnie INNER join zolnierze USING (idStopien) LEFT join eskadry USING (idEskadry) LEFT JOIN klucze USING (idKlucza) WHERE zolnierze.idZolnierza='$profil'")
        //$zolnierz = mysql_query("SELECT stopnie.Pelna, zolnierze.Imie, zolnierze.Zdjecie, zolnierze.Nazwisko,  eskadry.Nazwa AS Eskadra, klucze.Nazwa AS Klucz FROM stopnie, zolnierze, eskadry, klucze WHERE stopnie.idStopien = zolnierze.idStopien AND eskadry.idEskadry=zolnierze.idEskadry AND klucze.idKlucza=zolnierze.idKlucza OR klucze.idKlucza IS NULL AND zolnierze.idZolnierza='$profil'") 
        or die('Błąd zapytania'); 
        /* 
        wyświetlamy wyniki, sprawdzamy, 
        czy zapytanie zwróciło wartość większą od 0 
        */ 
        /* 
        wyświetlamy wyniki, sprawdzamy, 
        czy zapytanie zwróciło wartość większą od 0 
        */ 
        if(mysql_num_rows($zolnierz) > 0) { 
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
            echo "<div class=\"flex-container\">"; 
            while($r = mysql_fetch_object($zolnierz)) {  
                echo "<div class=\"panel zdjecie\">"; 
                    if(isset($_GET['edytuj']) && !isset($_GET['dane'])){//edycja zdjecia w profilu
                        echo "<div class=\"zawartosc blekitne\">"; edytujZdjecie($_GET['profil']); echo "</div>";    
                    }else{
                        echo "<div class=\"zawartosc blekitne\"><img src=\"img/profiles/thumbnail/$r->Zdjecie\" width=\"200px\" align=\"absmiddle\" alt=\"Zdjęcie profiliwe\" class=\"zaokraglij\"></div>";
                    }
                echo "</div>";

                echo "<div class=\"panel dane\">"; 
                    if(isset($_GET['dane']) && $_SESSION['permissions']<5){//edycja danych w profilu
                        echo "<form name=\"zmien_dane\" method=\"post\" action=\"\">";
                        echo "<div class=\"zawartosc blekitne\"><input type=\"text\" class=\"prof\" name=\"nazwisko\" placeholder=\"$r->Nazwisko\" value=\"$r->Nazwisko\" required ></div>";
                        echo "<div class=\"zawartosc blekitne mb-10\"><input type=\"text\" class=\"prof\" name=\"imie\" placeholder=\"$r->Imie\" value=\"$r->Imie\" required ></div>";
                        echo "<div class=\"zawartosc blekitne\">";stopnie($r->idStopien,'prof');echo"</div>";
                            $dowodca_grupy = czyDowodcaGrupy($profil);
                            $dowodca = czyDowodca($profil);
                            $szef = czySzef($profil);
                        if(empty($dowodca_grupy)){
                            echo "<div class=\"zawartosc blekitne\">";listaEskadr(id_grupy($r->idZolnierza),$r->idEskadry,'prof');echo"</div>";   
                        }
                        if (empty($dowodca) or empty($szef)){
                            echo "<div class=\"zawartosc blekitne\">";listaKluczy(id_eskadry($profil),null,'prof');echo"</div>";
                        }

                        if(isset($dowodca_grupy)){
                            echo "<div class=\"zawartosc blekitne mt-10\">Dowódca "; echo skrotGrupy($dowodca_grupy); echo "</div>";
                        }
                        if(isset($dowodca)){
                            echo "<div class=\"zawartosc blekitne mt-10\">Dowódca "; echo skrotEskadry($dowodca); echo "</div>";
                        }
                        if (isset($szef)) {
                            echo "<div class=\"zawartosc blekitne mt-10\">Szef "; echo skrotEskadry($szef); echo" </div>";
                        }
                        echo "<div class=\"zawartosc wysrodkuj mt-10\"><input type=\"submit\" class=\"zapisz animacja\" name=\"zapisz_dane\" value=\"Zapisz\"/></div>";
                        echo "</form>"; 
                    }else{
                        echo "<div class=\"zawartosc blekitne mb-10\"><h2>".mb_convert_case($r->Nazwisko, MB_CASE_UPPER, "UTF-8")." ".$r->Imie."</h2></div>";
                        echo "<div class=\"zawartosc blekitne\">Stopień: ".$r->Pelna."</div>";

                        if(isset($r->Eskadra)){
                            echo "<div class=\"zawartosc blekitne\">Eskadra: ".$r->Eskadra."</div>";   
                        }
                        if (isset($r->Klucz)){
                            echo "<div class=\"zawartosc blekitne\">Klucz: ".$r->Klucz."</div>";
                        }
                            $dowodca_grupy = czyDowodcaGrupy($profil);
                            $dowodca = czyDowodca($profil);
                            $szef = czySzef($profil);
                        if(isset($dowodca_grupy)){
                            echo "<div class=\"zawartosc blekitne mt-10\">Dowódca "; echo skrotGrupy($dowodca_grupy); echo "</div>";
                        }
                        if(isset($dowodca)){
                            echo "<div class=\"zawartosc blekitne mt-10\">Dowódca "; echo skrotEskadry($dowodca); echo "</div>";
                        }
                        if (isset($szef)) {
                            echo "<div class=\"zawartosc blekitne mt-10\">Szef "; echo skrotEskadry($szef); echo" </div>";
                        }
                    }
                echo "</div>";
            } 
            echo "</div>"; 
        }
    }else{
        //wyrzucamy komunikat o braku dostepu z funkcji, lub mozemy napisac swoj
        echo mamDostepDo($profil);
    }
    
}

//wyswietlenie listy uprawnien podczas dodawania uzytkownika pobranej z bazy
function uprawnienia($multi) {
    
    if (isset($multi)){
        $uprawnienia = mysql_query("SELECT *  FROM uprawnienia") or die('Błąd zapytania'); 
        if(mysql_num_rows($uprawnienia) > 0) { 
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
            echo "<select name=\"uprawnienie[]\" required>"; 
            echo "<option value=\"\" selected disabled>Wybierz uprawnienie</option>";
            while($r = mysql_fetch_object($uprawnienia)) {  

                echo "<option value=\"$r->idUprawnienia\">".$r->Typ."</option>";

            } 
            echo "</select>"; 
        }
    }else{
        
        $uprawnienia = mysql_query("SELECT *  FROM uprawnienia") or die('Błąd zapytania'); 
        if(mysql_num_rows($uprawnienia) > 0) { 
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
            echo "<select name=\"uprawnienie\" required class=\"mb-10\">"; 
            echo "<option value=\"\" selected disabled>Wybierz uprawnienie</option>";
            while($r = mysql_fetch_object($uprawnienia)) {  

                echo "<option value=\"$r->idUprawnienia\">".$r->Typ."</option>";

            } 
            echo "</select>"; 
        }
        
    }

}

function stopnie($selected, $css) {
$stopnie = mysql_query("SELECT stopnie.idStopien, stopnie.Skrot  FROM stopnie") 
or die('Błąd zapytania'); 
if(mysql_num_rows($stopnie) > 0) { 
    /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
    echo "<select name=\"stopien\" class=\"";if(empty($css)){echo "fod";}else{echo $css;}echo"\">"; 
    while($r = mysql_fetch_object($stopnie)) {  
                if ($selected==$r->idStopien){
                    echo "<option selected value=\"$r->idStopien\">".$r->Skrot."</option>";
                }  else {
                    echo "<option value=\"$r->idStopien\">".$r->Skrot."</option>";
                }
    } 
    echo "</select>"; 
}
}


//wyswietlenie rozwijanej listy zolnierzy
function lista_zolnierzy($selected, $multi, $width) {
$datalist = mysql_query("SELECT CONCAT_WS(' ', UPPER(zolnierze.Nazwisko), zolnierze.Imie, stopnie.Skrot) AS Żołnierze, idZolnierza FROM zolnierze, stopnie WHERE stopnie.idStopien = zolnierze.idStopien ORDER BY Nazwisko ASC, idZolnierza DESC;") 
or die('Błąd zapytania'); 
    if(mysql_num_rows($datalist) > 0) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        echo "<select name=\"";if (isset($multi)){echo "zolnierze[]";}else{echo "zolnierze";}echo"\" ";if (isset($width)){echo "style=\"width:$width"."px\"";}else{echo "class=\"fod\"";}echo">";
        if (empty($selected)){
            echo "<option value=\"\" selected disabled>Wybierz zolnierza</option>";
        }
        echo "<option value=\"null\">Cywil</option>";
        while($r = mysql_fetch_object($datalist)) {  
                if ($selected==$r->idZolnierza){
                    echo "<option selected value=\"$r->idZolnierza\">".$r->Żołnierze."</option>";
                }  else {
                    echo "<option value=\"$r->idZolnierza\">".$r->Żołnierze."</option>";  
                }

        } 
        echo "</select>"; 
    }
}

////////////////////////
//PANEL ADMINISTRATORA//
////////////////////////

//Dodawanie uzytkownikow w panelu administratora
function dodajUzytkownika($przypisz){
    $dodajlogin = htmlspecialchars($_POST['dodajlogin']);
    $dodajhaslo = htmlspecialchars($_POST['dodajhaslo']);
    $zakodowane = md5($dodajhaslo);

        //formularz dodawania uzytkownika
        //przyjmujacy podane wartosci
        function formDodaj($errorpas, $errorlog, $placepas = "wpisz hasło", $placelog = "wpisz login")
        {
            echo "<form name=\"dodajUzytkownika\" method=\"post\" action=\"index.php?id=panele/admin/dodajUzytkownika\">";
            echo "<div class=\"zawartosc wysrodkuj\">";

            echo "<input type=\"text\" name=\"dodajlogin\"  required=\"true\" maxlength=\"40\" placeholder=\"$placelog...\" class=\"mb-10 fod pl-5 $errorlog\" pattern='^[a-zA-Z][a-zA-Z0-9-_\.]{3,20}$' title=\"Min. 4 znaki. Format: nazwa(bez znaków specjalnych)\"><br>";
            echo "<input type=\"password\" name=\"dodajhaslo\"  required=\"true\" maxlength=\"40\" placeholder=\"$placepas...\" class=\"mb-10 fod pl-5 $errorpas\" pattern='(?=^.{4,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[a-z]).*$'title=\"Min. 4 znaki, wielka i mała litera oraz znak specjalny\"><br>";  
            //Funkcja wyswietlajaca liste uprawnien
                uprawnienia();//wywolanie
                echo "<br>";
                lista_zolnierzy($_GET['profil']);
            
            echo "<br><input value=\"dodaj\" type=\"submit\" class=\"zapisz animacja mt-10\">";
            echo "</div>";
            echo "</form>";
        }
    
    if(empty($_POST['dodajlogin']) && empty($_POST['dodajhaslo']))
        { //echo "<div class=\"flex-container\">";
        formDodaj();
          //echo "</div>";
        } else {
        
            //sprawdzimy czy podany login juz istnieje
            if($dodajlogin==$dodajhaslo){
            formDodaj("error","error","hasło nie może być loginem","wpisz login");    
            }else{
            $istnieje = mysql_query("SELECT * FROM uzytkownicy where Login='$dodajlogin'") 
            or die('Błąd zapytania'); 
                if(mysql_num_rows($istnieje) == 0) { //jezeli nie istnieje to dodajemy
                    /* jeżeli wynik jest pozytywny, to dodajemy uzytkownika */ 
                        if($_POST['zolnierze']==0 || empty($_POST['uprawnienie'])){
                            $zapytanie = "INSERT INTO `uzytkownicy` (`Login`, `Haslo`, `DataUtworzenia`, `idUprawnienia`) VALUES('$dodajlogin','$zakodowane', NOW(), '{$_POST['uprawnienie']}')";
                        }else{
                            $zapytanie = "INSERT INTO `uzytkownicy` (`Login`, `Haslo`, `DataUtworzenia`, `idUprawnienia`, `idZolnierza`) VALUES('$dodajlogin','$zakodowane', NOW(), '{$_POST['uprawnienie']}','{$_POST['zolnierze']}')";
                        }
                    $wykonaj = mysql_query($zapytanie);
                    echo "Dodałeś użytkownika <strong>".$dodajlogin."</strong> do bazy danych. Teraz może się zalogować.<br>";
                    echo "<div class=\"zawartosc wysrodkuj\">";
                    echo "<br><p>Powodzenia!</p><br><hr><a href=\"index.php?id=panele/admin/dodajUzytkownika\" ><input value=\"nowy\" type=\"button\" class=\"zapisz animacja\"></a>";
                    echo "</div>";
                }else{ //jezeli istnieje to jeszcze raz wyrzucamy okienko

                    formDodaj($errorpas,"error","wpisz hasło","taki login już istnieje");

                }
        }  
    }
}

//Dodawanie uzytkownikow w panelu administratora
function dodajZolnierza($stopien, $imie, $nazwisko, $eskadra, $klucz){

    if(!empty($stopien)&&!empty($imie)&&!empty($nazwisko)) {
        
            //sprawdzimy czy podany login juz istnieje
            
                    /* jeżeli wynik jest pozytywny, to dodajemy uzytkownika */
            if(!empty ($eskadra) && empty($klucz)){
                $zapytanie = "INSERT INTO `zolnierze` (`idStopien`, `Imie`, `Nazwisko`, `idEskadry`, `idKlucza`) VALUES('$stopien','$imie','$nazwisko','$eskadra',NULL)";
            }elseif (empty ($eskadra) && empty ($klucz)) {
                $zapytanie = "INSERT INTO `zolnierze` (`idStopien`, `Imie`, `Nazwisko`, `idEskadry`, `idKlucza`) VALUES('$stopien','$imie','$nazwisko',NULL,NULL)";    
            }else{
                $zapytanie = "INSERT INTO `zolnierze` (`idStopien`, `Imie`, `Nazwisko`, `idEskadry`, `idKlucza`) VALUES('$stopien','$imie','$nazwisko','$eskadra','$klucz')";
            }
            
        $wykonaj = mysql_query($zapytanie);
        echo "Dodałeś żołnierza do bazy danych. Teraz utwórz mu konto.<br>";
        
        
        $przypisz = mysql_query("SELECT * FROM zolnierze WHERE idStopien='$stopien' AND Imie='$imie' AND Nazwisko='$nazwisko'  ORDER BY idZolnierza DESC LIMIT 1;") 
        or die('Błąd zapytania'); 

        //pobieramy z bazy id ostatnio dodanego zolnierza spelniajacego kryteria z zapytania $przypisz
            if(mysql_num_rows($przypisz) == 1) { 
                $r = mysql_fetch_object($przypisz);   
                $id = intval($r->idZolnierza);
            }
                 
        
        
        
        
        
        echo "<br><p>Powodzenia!</p><br><hr><a href=\"index.php?id=panele/admin/dodajUzytkownika&profil=$id\" ><input value=\"konto\" type=\"button\" class=\"zapisz animacja\" title=\"dodaj użytkownika\"></a>";
    }else{
        echo "Nie podałeś wszystkich danych";
    }
}

//wyswietlenie ostatnio dodanych sluzb zalogowanego uzytkownika
function ostatnieSluzby() {
$czyje_id = id_zolnierza();    
$zapytanie = mysql_query("SELECT *, DATE_FORMAT(kiedy, '%d-%m-%Y') AS ostatnie, DATE_FORMAT(DATE_ADD(DATE_ADD(kiedy, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS termin FROM sluzby WHERE kto_mial='$czyje_id' ORDER BY idSluzby DESC LIMIT 0,5") 
or die('Błąd zapytania'); 
    if(mysql_num_rows($zapytanie) > 0) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        echo "<table>";
            echo "<thead>";
                echo "<tr class=\"empty-cells\">";
                    echo "<th class=\"left\">służba</th>";
                    echo "<th>dni</th>";
                    echo "<th>ważna do</th>";
                    echo "<th class=\"right\">dodał</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while($r = mysql_fetch_object($zapytanie)) {
                echo "<tr class=\"blekitne\">";
                        echo "<td class=\"left\">$r->ostatnie</td>";   /*wyswietlamy daty*/ 
                        echo "<td>".(($r->ile)/480)."</td>"; /*wyswietlamy godziny*/
                        echo "<td>$r->termin</td>"; /*wyswietlamy do kiedy mamy czas wykorzystać nadgodziny*/
                        echo "<td class=\"right\">";
                            echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=".id_zolnierza($r->kto_dodal)."\">";
                            echo "<img src=\"img/avatars/";avatar($r->kto_dodal);
                            echo "\" class=\"zaokraglij\" height=\"26px\" title=\"Dodane: ".$r->kiedy_dodal."\" align=\"absmiddle\">";
                            echo "</a>";
                        echo "</td>";
                    echo "</tr>";
            }
            echo "</tbody>";
        echo "</table>";   
    }else{
        echo"Nie dodałeś jeszcze służb, skorzystaj z przycisku na górze, aby to zrobić lub kliknij <a href=\"index.php?id=panele/dodaj/sluzby\">tutaj</a>";
    }
}

//wyswietlenie ostatnio dodanych nadgodzin zalogowanego uzytkownika
function ostatnieNadgodziny() {
$czyje_id = id_zolnierza();    
$zapytanie = mysql_query("SELECT *, DATE_FORMAT(kiedy, '%d-%m-%Y') AS ostatnie, DATE_FORMAT(DATE_ADD(DATE_ADD(kiedy, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS termin FROM nadgodziny WHERE czyje_id='$czyje_id' ORDER BY idNadgodziny DESC LIMIT 0,5") 
or die('Błąd zapytania'); 
    if(mysql_num_rows($zapytanie) > 0) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        echo "<table>";
            echo "<thead>";
                echo "<tr class=\"empty-cells\">";
                    echo "<th class=\"left\">za dzień</th>";
                    echo "<th>godzin</th>";
                    echo "<th>ważne do</th>";
                    echo "<th class=\"right\">dodał</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while($r = mysql_fetch_object($zapytanie)) {
                echo "<tr class=\"blekitne\">";
                        echo "<td class=\"left\">$r->ostatnie</td>";   /*wyswietlamy daty*/ 
                        echo "<td>".(($r->ile)/60)."</td>"; /*wyswietlamy godziny*/
                        echo "<td>$r->termin</td>"; /*wyswietlamy do kiedy mamy czas wykorzystać nadgodziny*/
                        echo "<td class=\"right\">";
                            echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=".id_zolnierza($r->kto_dodal)."\">";
                            echo "<img src=\"img/avatars/";avatar($r->kto_dodal);
                            echo "\" class=\"zaokraglij\" height=\"26px\" title=\"Dodane: ".$r->kiedy_dodal."\" align=\"absmiddle\">";
                            echo "</a>";
                        echo "</td>";
                    echo "</tr>";
            }
            echo "</tbody>";
        echo "</table>";   
    }else{
        echo"Nie dodałeś jeszcze nadgodzin, skorzystaj z przycisku na górze, aby to zrobić lub kliknij <a href=\"index.php?id=panele/dodaj/nadgodziny\">tutaj</a>";
    }
}

//wyswietlenie nadgodzin zalogowanego uzytkownika
function mojeNadgodziny($kogo) {
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
    $idUsun = $_GET['usun'];
    $idEdytuj = $_GET['edytuj'];
    $idZapisz = $_POST['zapisz'];


    //ZMIENNE DO ZAPISANIA EDYTOWANYCH NADGODZIN
    //$data = $_POST['data'];
    $godzina = $_POST['godzina'];
    $godzina = str_replace(",",".",$godzina);
    $liczenie = count($godzina); //zliczenie ilosci wystapien pola data input
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
                
                while($check = mysql_fetch_object($sprawdzenie)) {  
                    $zgoda = intval($check->czyje_id);
                }
                    if($zgoda == mamDostepDo($zgoda)){
                        $usun = mysql_query("DELETE FROM `nadgodziny` WHERE `idNadgodziny`='$idUsun'");
                    }else{
                        echo "<p class='wysrodkuj'>Nie masz uprawnień.<br>Nie kombinuj.</p>"; 
                    }              
            }else{
                $blad_usuniecia = "Nie ma co usunąć, zrobiłeś to wcześniej"; //niewypisany, wiec go nie zobaczymy
            }
        }

    //ZAPISUJEMY ZMIENIONE NADGODZINY

        if(isset($idZapisz)){//najpierw sprawdzamy czy zmienna istnieje
                for($a=0;$a<$liczenie;$a++)
            {

                    $idZapisz=$zaznaczone[$a];
                   // echo $idZapisz."<br>";

                //$data = '12-22-2009';
                //$dataq = explode("-", $data[$a]);
                //$dataq = $dataq[2]."-".$dataq[1]."-".$dataq[0];
                $godzinaq = $godzina[$a]*60; //mnozymy podana ilosc godzin przez 60 minut i zapisujemy jako inty do bazy


                $sprawdzenie = mysql_query("SELECT * FROM `nadgodziny` WHERE `idNadgodziny`='$idZapisz'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
                if((int)mysql_num_rows($sprawdzenie) > 0) { 
                    $edytuj = mysql_query("UPDATE `nadgodziny` SET `ile`='$godzinaq' WHERE `idNadgodziny`='$idZapisz'");
                }else{
                    $blad_edycji = "Nie ma co edytować, nadgodzina nie istnieje";
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
    $zapytanie = mysql_query("SELECT *, DATE_FORMAT(kiedy, '%d-%m-%Y') AS ostatnie, DATE_FORMAT(DATE_ADD(DATE_ADD(kiedy, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS termin, powod.Skrot AS poSkrot FROM nadgodziny, powod WHERE nadgodziny.idPowod=powod.idPowod AND czyje_id='$czyje_id' ORDER BY idNadgodziny DESC LIMIT $ile OFFSET $strona") 
    or die('Błąd zapytania'); 


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
                                echo "\" class=\"zaokraglij\" height=\"26px\" title=\"Dodane: ".$r->kiedy_dodal." za ".$r->poSkrot."\" align=\"absmiddle\">";
                                echo "</a>";
                            echo "</td>";

                                if (isset($idEdytuj) && $idEdytuj==$r->idNadgodziny OR $zaznaczone[$liczEdytuj]==$r->idNadgodziny){/*jezeli istnieje zmienna edytuj i jest rowna id nadgodziny to wyswietl edycje*/
                                    echo "<td>";
                                        echo "<input type=\"checkbox\" name=\"edytuj[]\" value=\"$r->idNadgodziny\" checked>";
                                    echo "</td>";
                                    echo "<td><input type=\"text\" class=\"wysrodkuj\" name=\"data[]\" placeholder=\"$r->ostatnie\" value=\"$r->ostatnie\" pattern=\"(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-[0-9]{4}\" required=\"true\" size=\"10\" disabled></td>"; /*wyswietlamy edycje daty*/ 
                                    echo "<td><input type=\"text\" class=\"wysrodkuj ggodzin\" name=\"godzina[]\" placeholder=\"".(($r->ile)/60)."\" pattern='((\d{1,2}\.[5])|(\d{1,2}))' required=\"true\" size=\"4\" id=\"$r->idNadgodziny\"></td>"; /*wyswietlamy godziny*/
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
    }else{
        echo mamDostepDo($kogo); //funkcja zwraca blad
    }
}//koniec moich nadgodzin

function sumaNadgodzin ($czyje_id, $rozszerz){ //pobieramy id zolnierza oraz wybieramy opcje z dodatkowym opisem rozszerz po wpisaniu 1
    
        $zapytanie = mysql_query("SELECT Round(SUM(nadgodziny.ile)/60,1) AS sumagodzin, Round(SUM(nadgodziny.ile)/480,1) AS sumadni  FROM nadgodziny WHERE czyje_id='$czyje_id'") or die('Błąd zapytania');
        $r = mysql_fetch_object($zapytanie);
        
        
        if ($rozszerz==1){//wyswietla dodatkowo stan w przeliczeniu godziny, dni
            echo "<h1>".round(($r->sumagodzin),0)."</h1>";
            echo "godz.: ".$r->sumagodzin." | dni: ".$r->sumadni;     
        } else {
          echo "<h1><abbr title=\"uzbierane nadgodziny\">".round(($r->sumagodzin),0)."</abbr>/<abbr title=\"wykorzystane nadgodziny\">".$wykorzystane."</abbr>/<abbr title=\"do wykorzystania\">".((round(($r->sumagodzin),0))-$wykorzystane)."</abbr></h1>";  
        }
    
}

function zostaloNadgodzin ($czyje_id, $rozszerz){ //pobieramy id zolnierza oraz wybieramy opcje z dodatkowym opisem rozszerz po wpisaniu 1
    
        $zapytanie = mysql_query("SELECT Round(SUM(uzbierane)/60,1) AS sumagodzin, Round(SUM(wykorzystane)/60,1) AS wykorzystane, Round(SUM(pozostalo)/60,1) AS pozostalo, Round(SUM(pozostalo)/480,1) AS zostalo_dni FROM v_zestawienie_nadgodzin WHERE idZolnierza='$czyje_id'") or die('Zostalo nadgodzin'+mysql_error());
        $r = mysql_fetch_object($zapytanie);
        
        
        if ($rozszerz==1){//wyswietla dodatkowo stan w przeliczeniu godziny, dni
            echo "<h1>".round(($r->pozostalo),0)."</h1>";
            echo "godz.: ".$r->pozostalo." | dni: ".$r->zostalo_dni;     
        } else {
          echo "<h1><abbr title=\"uzbierane nadgodziny\">".round(($r->sumagodzin),0)."</abbr>/<abbr title=\"wykorzystane nadgodziny\">".round(($r->wykorzystane),0)."</abbr>/<abbr title=\"do wykorzystania\">".(round(($r->pozostalo),0))."</abbr></h1>";  
        }
    
}

function zostaloSluzb ($czyje_id, $rozszerz){ //pobieramy id zolnierza oraz wybieramy opcje z dodatkowym opisem rozszerz po wpisaniu 1
    
        $zapytanie = mysql_query("SELECT Round(SUM(uzbierane)/60,1) AS sumagodzin, Round(SUM(wykorzystane)/60,1) AS wykorzystane, Round(SUM(pozostalo)/60,1) AS pozostalo, Round(SUM(pozostalo)/480,1) AS zostalo_dni FROM v_zestawienie_sluzb WHERE idZolnierza='$czyje_id'") or die('Zostalo nadgodzin'+mysql_error());
        $r = mysql_fetch_object($zapytanie);
        
        
        if ($rozszerz==1){//wyswietla dodatkowo stan w przeliczeniu godziny, dni
            echo "<h1>".round(($r->zostalo_dni),0)."</h1>";
            echo "godz.: ".$r->pozostalo." | dni: ".$r->zostalo_dni;     
        } else {
          echo "<h1><abbr title=\"uzbierane służby\">".round(($r->sumagodzin)/8,0)."</abbr>/<abbr title=\"wykorzystane służby\">".round(($r->wykorzystane)/8,0)."</abbr>/<abbr title=\"do wykorzystania\">".(round(($r->zostalo_dni),0))."</abbr></h1>";  
        }
    
}

//wyswietlenie sluzb zalogowanego uzytkownika
function mojeSluzby($kogo) {
    if(isset($kogo)) { //&& $kogo != id_zolnierza()   to samo co w nadgodzinach
        $czyje_url = '&profil='.$kogo; //dopisujemy url do zalogowanego
            //zmienna pomocnicza do wyswietlania nadgodzin uzytkownika
        $czyje_id = $kogo;
    }elseif(empty ($kogo)){
        $kogo = id_zolnierza();
            //zmienna pomocnicza do wyswietlania nadgodzin uzytkownika
        $czyje_id = $kogo;
    }
//jeżeli istnieje zmienna profil sprawdzamy czy mamy dostep do danego profilu     
    if( isset($kogo) && $kogo == mamDostepDo($kogo)) {
    


    //Definiujemy zmienne pomocnicze do stronicowania
    $nazwa=$_SESSION['user']."-sluzby";
    if(isset($_COOKIE[$nazwa]) && !empty($_COOKIE[$nazwa])){
        $ile=$_COOKIE[$nazwa];
    }else{    
        $ile=10; //ilosc wyswietlanych wpisow na strone
    }
    $strona=0; //poczatkowy numer strony, jezeli nie zostal podany
    $idUsun = $_GET['usun'];
    $idEdytuj = $_GET['edytuj'];
    $idZapisz = $_POST['zapisz'];


    //ZMIENNE DO ZAPISANIA EDYTOWANYCH NADGODZIN
    //$data = $_POST['data'];
    $godzina = $_POST['godzina'];
    $godzina = str_replace(",",".",$godzina);
    $liczenie = count($godzina); //zliczenie ilosci wystapien pola data input
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
            $sprawdzenie = mysql_query("SELECT * FROM `sluzby` WHERE `idSluzby`='$idUsun'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
            
            if((int)mysql_num_rows($sprawdzenie) > 0) {
                
                while($check = mysql_fetch_object($sprawdzenie)) {  
                    $zgoda = intval($check->kto_mial);
                }
                    if($zgoda == mamDostepDo($zgoda)){
                    $usun = mysql_query("DELETE FROM `sluzby` WHERE `idSluzby`='$idUsun'");
                    }else{
                    echo "<p class='wysrodkuj'>Nie masz uprawnień.<br>Nie kombinuj.</p>"; 
                    }
                           
            }else{
                $blad_usuniecia = "Już usunąłeś wybraną służbę."; //niewypisany, wiec go nie zobaczymy
                echo "<p class='wysrodkuj'>".$blad_usuniecia."</p>";
            }
        }

    //ZAPISUJEMY ZMIENIONE NADGODZINY

        if(isset($idZapisz)){//najpierw sprawdzamy czy zmienna istnieje
                for($a=0;$a<$liczenie;$a++)
            {

                    $idZapisz=$zaznaczone[$a];
                    echo $idZapisz."<br>";

                //$data = '12-22-2009';
                //$dataq = explode("-", $data[$a]);
                //$dataq = $dataq[2]."-".$dataq[1]."-".$dataq[0];
                $godzinaq = $godzina[$a]*480; //mnozymy podana ilosc godzin przez 60 minut i zapisujemy jako inty do bazy


                $sprawdzenie = mysql_query("SELECT * FROM `sluzby` WHERE `idSluzby`='$idZapisz'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
                if((int)mysql_num_rows($sprawdzenie) > 0) { 
                    $edytuj = mysql_query("UPDATE `sluzby` SET `ile`='$godzinaq' WHERE `idSluzby`='$idZapisz'");
                }else{
                    $blad_edycji = "Nie ma co edytować, służba nie istnieje";
                }
            }

            $extra = 'index.php?id=panele/moje/sluzby&ile=';
            $extra2 = $ile;
            $extra3 = '&strona=';
            $extra4 = $_POST[strona];
            header("Location: $extra$extra2$extra3$extra4$czyje_url");
            exit;  

        }

     //POBIERAMY DODANE SLUZB
    $ilewpisow = mysql_query("SELECT *, DATE_FORMAT(kiedy, '%d-%m-%Y') AS ostatnie FROM sluzby WHERE kto_mial='$czyje_id'") or die('Błąd zapytania');
    $wpisow = (int)mysql_num_rows($ilewpisow);
    $stron = ceil($wpisow/$ile); //ilosc stron
    $zapytanie = mysql_query("SELECT *, DATE_FORMAT(kiedy, '%d-%m-%Y') AS ostatnie, DATE_FORMAT(DATE_ADD(DATE_ADD(kiedy, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS termin, dyzurny.Skrot AS poSkrot FROM sluzby, dyzurny WHERE sluzby.idTyp=dyzurny.idDyzurny AND kto_mial='$czyje_id' ORDER BY idSluzby DESC LIMIT $ile OFFSET $strona") 
    or die('Błąd zapytania wypisujacego sluzby'); 


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
                    echo '<option value="index.php?id=panele/moje/sluzby'.$czyje_url.'&ile='.$ile.'&strona='.$i.'">'.($i+1).'</option>';
                }
             }
             //echo '[...] <a href="index.php?id=panele/moje/sluzby&ile='.$ile.'&do='.($i-1).'">'.$i.' </a>';
             echo "</select>";
             echo "</div>";
             echo "<div class=\"order-1\">";
             //wygenerowanie linku do poprzedniej strony
                if ($poprzednia > -1) {
                echo '<a href="index.php?id=panele/moje/sluzby'.$czyje_url.'&ile='.$ile.'&strona='.$poprzednia.'" class="button blekitne">Poprzednia</a>';
                }else{
                    echo "<button class=\"button\" disabled>Poprzednia</button>";
                }
            echo "</div>";
            echo "<div class=\"order-3\">";
            //wygenerowanie linku do nastepnej strony
            if ($nastepna < $i) {
                echo '<a href="index.php?id=panele/moje/sluzby'.$czyje_url.'&ile='.$ile.'&strona='.$nastepna.'" class="button blekitne">Nastepna</a>';
            } else {
                echo "<button class=\"button\" disabled>Nastepna</button>";
            }
            echo "</div>";
        echo "</div>";
}
    /*Wypisanie danych sluzb z bazy mysql*/
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
                        echo "<th>wolnych dn.</th>";
                        echo "<th>ważne do</th>";
                        echo "<th colspan=\"2\">";
                            if ($zaznaczone[$liczEdytuj]==$r->idSluzby){
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
                                echo "\" class=\"zaokraglij\" height=\"26px\" title=\"Dodane: ".$r->kiedy_dodal." za ".$r->poSkrot."\" align=\"absmiddle\">";
                                echo "</a>";
                            echo "</td>";

                                if (isset($idEdytuj) && $idEdytuj==$r->idSluzby OR $zaznaczone[$liczEdytuj]==$r->idSluzby){/*jezeli istnieje zmienna edytuj i jest rowna id sluzby to wyswietl edycje*/
                                    echo "<td>";
                                        echo "<input type=\"checkbox\" name=\"edytuj[]\" value=\"$r->idSluzby\" checked>";
                                    echo "</td>";
                                    echo "<td><input type=\"text\" class=\"wysrodkuj\" name=\"data[]\" placeholder=\"$r->ostatnie\" value=\"$r->ostatnie\" pattern=\"(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-[0-9]{4}\" required=\"true\" size=\"10\" disabled></td>"; /*wyswietlamy edycje daty*/ 
                                    echo "<td><input type=\"radio\" value=\"1\" name=\"godzina[] $r->idSluzby\" class=\"iledni\" placeholder=\"1\" required=\"true\" id=\"dni1-$r->idSluzby\"><label for=\"dni1-$r->idSluzby\">1</label><input type=\"radio\" value=\"2\" name=\"godzina[] $r->idSluzby\" class=\"iledni\" placeholder=\"1\" required=\"true\" id=\"dni2-$r->idSluzby\"><label for=\"dni2-$r->idSluzby\">2</label></td>"; /*wyswietlamy godziny <input type=\"text\" class=\"wysrodkuj ggodzin\" name=\"godzina[]\" placeholder=\"".(($r->ile)/480)."\" pattern='((\d{1,2}\.[5])|(\d{1,2}))' required=\"true\" size=\"4\">*/
                                }else{
                                    echo "<td>";
                                        echo "<input type=\"checkbox\" name=\"edytuj[]\" value=\"$r->idSluzby\">";
                                    echo "</td>";
                                    echo "<td>$r->ostatnie</td>"; /*wyswietlamy daty*/
                                    echo "<td>".(($r->ile)/480)."</td>"; /*wyswietlamy godziny*/
                                }
                            echo "<td>$r->termin</td>"; /*wyswietlamy do kiedy mamy czas wykorzystać nadgodziny*/
                                if (isset($idEdytuj) && $idEdytuj==$r->idSluzby OR $zaznaczone[$liczEdytuj]==$r->idSluzby){
                                    echo "<input type=\"hidden\" name=\"zapisz\" value=\"$r->idSluzby\">";
                                    echo "<input type=\"hidden\" name=\"strona\" value=\"$dousuniecia\">";
                                    echo "<td><input type=\"submit\" class=\"aktualizuj\" name=\"dodajnadgodziny\" value=\"Zapisz\" title=\"Zapisz do bazy\"/></td>";
                                    echo "<td><a class=\"anuluj\" href=\"index.php?id=panele/moje/sluzby$czyje_url&ile=$ile&strona=$dousuniecia\">Anuluj</a></td>";
                                    $liczEdytuj++;
                                }else{
                                    echo "<td><a class=\"edytuj\" href=\"index.php?id=panele/moje/sluzby$czyje_url&ile=".$ile."&strona=".$dousuniecia."&edytuj=".$r->idSluzby."\">Edytuj</a></td>";
                                    echo "<td><a class=\"usun\" href=\"index.php?id=panele/moje/sluzby$czyje_url&ile=".$ile."&strona=".$dousuniecia."&usun=".$r->idSluzby."\">Usuń</a></td>";
                                }
                        echo "</tr>";
                    }
                echo "</tbody>";
                echo "</form>"; 
            echo "</table>";   
        }else{
            echo "Brak służb do wyświetlenia";
        }
            
    }else{
        echo mamDostepDo($kogo); //funkcja zwraca blad
    }
}//koniec moich sluzb

/*/////////////////////////*/
/* wyswietlanie sumy sluzb */
/*/////////////////////////*/

function sumaSluzb ($czyje_id, $rozszerz){ //pobieramy id zolnierza oraz wybieramy opcje z dodatkowym opisem rozszerz po wpisaniu 1
    
        $zapytanie = mysql_query("SELECT Round(SUM(sluzby.ile)/60,1) AS sumagodzin, Round(SUM(sluzby.ile)/480,1) AS sumadni  FROM sluzby WHERE kto_mial='$czyje_id'") or die('Błąd zapytania');
        $r = mysql_fetch_object($zapytanie);
        $wykorzystane=2;//ilosc wykorzystanych nadgodzin
        
        
        if ($rozszerz==1){//wyswietla dodatkowo stan w przeliczeniu godziny, dni
            echo "<h1>".round(($r->sumadni),0)."</h1>";
            echo "godz.: ".$r->sumagodzin." | dni: ".$r->sumadni;     
        } else {
          echo "<h1><abbr title=\"uzbierane dni za służby\">".round(($r->sumadni),0)."</abbr>/<abbr title=\"wykorzystane\">".$wykorzystane."</abbr>/<abbr title=\"do wykorzystania\">".((round(($r->sumadni),0))-$wykorzystane)."</abbr></h1>";  
        }
    
}

function dodajNadgodziny ($idZolnierza){
    //zmienne pobraane z formularza
$data = $_POST['data'];
$godzina = $_POST['godzina'];
$godzina = str_replace(",",".",$godzina); //zamiana przecinkana kropke w godzinie
$powody = $_POST['powod'];
$liczenie=count($data); //zliczenie ilosci wystapien pola data input

if(isset($_POST['data']) && isset($_POST['godzina']))//sprawdzamy czy pole data nie jest puste
{
    
    if(empty($idZolnierza)){
    $czyje_id = id_zolnierza();    // pobranie id zalogowanego zolnierza z konta uzytkownika
    }else{
        if($idZolnierza == mamDostepDo($idZolnierza)) {
        $czyje_id = $idZolnierza;    // pobranie id zolnierza podanego przez uzytkownika
            }else{
            //wyrzucamy komunikat o braku dostepu z funkcji, lub mozemy napisac swoj
            echo '<div class="flex-container">';
                echo '<div class="panel trzysta">';
                    echo '<div class="tytul"><p>błąd!</p></div>';
                    echo '<div class="zawartosc wysrodkuj">';
                    echo "Nie masz tutaj dostępu!<br> Przypadek? Nie sądzę.";    
                    echo '</div>';    
                echo '</div>';  
            echo '</div>'; 
        }
    }
    
    $kto_dodal = $_SESSION['user']; //wyciagniecie z sesji nazwy uzytkownika
    echo "<div class=\"flex-container\">";
        echo "<div class=\"panel szescset\">";
            echo "<div class=\"tytul\">";
                echo "<p>dodane nadgodziny</p>";
            echo "</div>";
    
    for($a=0;$a<$liczenie;$a++)
        {
            //$data = '12-22-2009';
            $dataq = explode("-", $data[$a]);
            $dataq = $dataq[2]."-".$dataq[1]."-".$dataq[0];
            $godzinaq = $godzina[$a]*60; //mnozymy podana ilosc godzin przez 60 minut i zapisujemy jako inty do bazy
            $powodyq = $powody[$a];
            
            //sprawdzenie czy podana data istnieje juz w bazie
            $sprawdzenie = mysql_query("SELECT * FROM nadgodziny, zolnierze WHERE czyje_id = '$czyje_id' AND idZolnierza = czyje_id AND kiedy LIKE '".$dataq."'");
            $wystapien = (int)mysql_num_rows($sprawdzenie); //zliczenie ilosci wystapien zapytania, powinno dac zero jezeli daty nie ma
                        
            if ($wystapien == 0){ //jezeli daty nie ma w bazie to ja doda
                $zapytanie = "INSERT INTO `nadgodziny` (`czyje_id`, `ile`, `kiedy`,`kto_dodal`, `kiedy_dodal`, `idPowod`) VALUES ( '$czyje_id', '$godzinaq', '$dataq', '$kto_dodal', NOW(), '$powodyq')";       
                $wykonaj = mysql_query($zapytanie); 
                echo "<div class=\"zawartosc blekitne\" >";
                echo("Za dzień ".$data[$a]." dodałeś ".$godzina[$a]." godz.<br>");
                echo "</div>";  
            }elseif ($wystapien > 0){ //jezeli data juz istnieje w bazie wyrzuci komunikat o jej istnieniu
                $r = mysql_fetch_object($sprawdzenie);
                echo "<div class=\"zawartosc blekitne\" >";
                echo "<strong>Ta data już istnieje: </strong>";
                echo("za dzień ".$data[$a]." dodałeś ".(($r->ile)/60)." godz.<br>");
                echo "</div>";   
            }  
        }
        echo "</div>";
    echo "</div>";
}


//Ukryte okienko dialog z przyciskami do umieszczania nadgodzin w inpucie
echo "<div id=\"dialog\" title=\"Ile nadgodzin?\">";

    //wypisanie przyciskow w okienku typu dialog jquery
    for ($i=1;$i<25;$i++){
    
        echo "<button>".$i."</button>";
        
    }

echo "</div>";
//koniec ukrytego okienka


}

//DODAWANIE SLUZB DODWANIE SLUZB DODAWANIE SLUZB
//Dodawanie sluzb sobie samemu
//DODAWANIE SLUZB DODWANIE SLUZB DODAWANIE SLUZB
function dodajSluzby ($idZolnierza){
    //zmienne pobraane z formularza
$data = $_POST['data'];
$iledni = $_POST['iledni'];
$dyzur = $_POST['sluzba'];
$liczenie=count($data); //zliczenie ilosci wystapien pola data input

if(isset($_POST['data']) && isset($_POST['iledni']))//sprawdzamy czy pole data nie jest puste
{
    if(empty($idZolnierza)){
    $czyje_id = id_zolnierza();    // pobranie id zalogowanego zolnierza z konta uzytkownika
    }else{
        if($idZolnierza == mamDostepDo($idZolnierza)) {
        $czyje_id = $idZolnierza;    // pobranie id zolnierza podanego przez uzytkownika
            }else{
            //wyrzucamy komunikat o braku dostepu z funkcji, lub mozemy napisac swoj
            echo '<div class="flex-container">';
                echo '<div class="panel trzysta">';
                    echo '<div class="tytul"><p>błąd!</p></div>';
                    echo '<div class="zawartosc wysrodkuj">';
                    echo "Nie masz tutaj dostępu!<br> Przypadek? Nie sądzę.";    
                    echo '</div>';    
                echo '</div>';  
            echo '</div>'; 
        }
    }
    $kto_dodal = $_SESSION['user']; //wyciagniecie z sesji nazwy uzytkownika
    echo "<div class=\"flex-container\">";
        echo "<div class=\"panel szescset\">";
            echo "<div class=\"tytul\">";
                echo "<p>dodane nadgodziny</p>";
            echo "</div>";
    
    for($a=0;$a<$liczenie;$a++)
        {
            //$data = '12-22-2009';
            $dataq = explode("-", $data[$a]);
            $dataq = $dataq[2]."-".$dataq[1]."-".$dataq[0];
            $godzinaq = $iledni[$a]*480; //mnozymy podana ilosc dni razy 8 godzin (1 dzien wolny) i zapisujemy jako inty do bazy
            $powodyq = $dyzur[$a];
            
            //sprawdzenie czy podana data istnieje juz w bazie
            $sprawdzenie = mysql_query("SELECT * FROM sluzby, zolnierze WHERE kto_mial = '$czyje_id' AND idZolnierza = kto_mial AND kiedy LIKE '".$dataq."'");
            $wystapien = (int)mysql_num_rows($sprawdzenie); //zliczenie ilosci wystapien zapytania, powinno dac zero jezeli daty nie ma
                        
            if ($wystapien == 0){ //jezeli daty nie ma w bazie to ja doda
                $zapytanie = "INSERT INTO `sluzby` (`kto_mial`, `ile`, `kiedy`,`kto_dodal`, `kiedy_dodal`, `idTyp`) VALUES ( '$czyje_id', '$godzinaq', '$dataq', '$kto_dodal', NOW(), '$powodyq')";       
                $wykonaj = mysql_query($zapytanie); 
                echo "<div class=\"zawartosc blekitne\" >";
                echo("Za dzień ".$data[$a]." dodałeś ".$iledni[$a]." dn.<br>");
                echo "</div>";  
            }elseif ($wystapien > 0){ //jezeli data juz istnieje w bazie wyrzuci komunikat o jej istnieniu
                $r = mysql_fetch_object($sprawdzenie);
                echo "<div class=\"zawartosc blekitne\" >";
                echo "<strong>Ta data już istnieje: </strong>";
                echo("za dzień ".$data[$a]." dodałeś ".(($r->ile)/480)." dn.<br>");
                echo "</div>";   
            }  
        }
        echo "</div>";
    echo "</div>";
}


}




//WYŚWIETLENIE LISTY POWODÓW DLA KTORYCH ODBIERAMY NADGODZINY
function listaPowodow() {
$powod = mysql_query("SELECT *  FROM powod") 
or die('Błąd zapytania'); 
    if(mysql_num_rows($powod) > 0) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        echo "<select name=\"powod[]\" required style=\"max-width:150px;\">"; 
        echo "<option value=\"\" selected disabled>Wybierz powód</option>";
        while($r = mysql_fetch_object($powod)) {  

            echo "<option value=\"$r->idPowod\">".$r->Skrot."</option>";

        } 
        echo "</select>"; 
    }
}

//WYŚWIETLENIE LISTY POWODÓW DLA KTORYCH ODBIERAMY NADGODZINY
function listaSluzb() {
$dyzur = mysql_query("SELECT *  FROM dyzurny") 
or die('Błąd zapytania'); 
    if(mysql_num_rows($dyzur) > 0) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        echo "<select name=\"sluzba[]\" required style=\"max-width:150px;\">"; 
        echo "<option value=\"\" selected disabled>Wybierz służbę</option>";
        while($r = mysql_fetch_object($dyzur)) {  

            echo "<option value=\"$r->idDyzurny\">".$r->Skrot."</option>";

        } 
        echo "</select>"; 
    }
}

//WYŚWIETLENIE LISTY GRUP DO PRZYPISANIA DOWODCY
function listaGrup($required) {
$esk = mysql_query("SELECT * FROM grupy") 
or die('Błąd zapytania'); 
    if(mysql_num_rows($esk) > 0) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        echo "<select name=\"grupa\" class=\"fod\" id=\"grupa\"";if($required==true){echo" required";}echo">"; 
        echo "<option value=\"\" selected disabled id=\"puste\">Wybierz grupę</option>";
        while($r = mysql_fetch_object($esk)) {  

            echo "<option value=\"$r->idGrupy\">".$r->Nazwa."</option>";

        } 
        echo "</select>"; 
    }
}

//WYŚWIETLENIE LISTY ESKADR DLA PRZYPISANIA ŻOŁNIERZA
function listaEskadr($idGrupy, $selected, $css) {
$esk = mysql_query("SELECT *  FROM eskadry WHERE idGrupy='".$idGrupy."'") 
or die('Błąd zapytania'); 
    if(mysql_num_rows($esk) > 0) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        echo "<select name=\"eskadra\" class=\"";if(empty($css)){echo "fod";}else{echo $css;}echo"\" id=\"eskadra\">"; 
        echo "<option value=\"\" selected disabled id=\"puste\">Wybierz eskadrę</option>";
        while($r = mysql_fetch_object($esk)) {  
                if ($selected==$r->idEskadry){
                    echo "<option selected value=\"$r->idEskadry\">".$r->Nazwa."</option>";
                }  else {
                    echo "<option value=\"$r->idEskadry\">".$r->Nazwa."</option>"; 
                }
        } 
        echo "</select>"; 
    }
}
//WYŚWIETLENIE LISTY ESKADR DLA PRZYPISANIA ŻOŁNIERZA
function listaKluczy($idEskadry, $selected, $css) {
$kl = mysql_query("SELECT *  FROM klucze WHERE idEskadry='".$idEskadry."'") 
or die('Błąd zapytania'); 
    if(mysql_num_rows($kl) > 0) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        echo "<select name=\"klucz\" class=\"";if(empty($css)){echo "fod";}else{echo $css;}echo"\" id=\"klucz\">"; 
        echo "<option value=\"\" selected disabled>Wybierz klucz</option>";
        while($r = mysql_fetch_object($kl)) {  
                if ($selected==$r->idKlucza){
                    echo "<option selected value=\"$r->idKlucza\">".$r->Nazwa."</option>";
                }  else {
                    echo "<option value=\"$r->idKlucza\">".$r->Nazwa."</option>"; 
                }
        } 
        echo "</select>"; 
    }
}


//PRZYPISANIE ZOLNIERZA DO UZYTKOWNIKA

function przypiszZolnierza(){
    $query = "SELECT * FROM uzytkownicy WHERE idZolnierza IS NULL order by DataUtworzenia DESC";
    $lista = mysql_query($query);
}

//DODAWANIE GRUPY/BATALIONU

function dodajGrupe($skrot_eskadry, $nazwa_eskadry) {
    //sprawdzamy czy ktos cos wyslal
    if( !empty( $_POST ) ) {
        //sprawdzamy czy cos wyslanego to nasz submit dodajEskadre
        if( array_key_exists( 'dodaj_grupe', $_POST ) ){
            $dodaj = "INSERT INTO `grupy` (`Skrot`, `Nazwa`) VALUES ('$skrot_eskadry', '$nazwa_eskadry');";
            $wykonaj = mysql_query($dodaj);
                //wyswietlamy informacje 
                echo "<div class=\"flex-container\">";
                    echo "<div class=\"panel piecset\">";
                        echo "<div class=\"tytul\">";
                            echo "<p>dodano grupę</p>";
                        echo "</div>";
                        echo "<div class=\"zawartosc\" >";
                            echo "Dodano Grupę: <acronym title=\"$nazwa_eskadry\">$skrot_eskadry</acronym> $nazwa_eskadry";
                        echo "</div>";    
                    echo "</div>";
                echo "</div>";
        }
        
    }
}
//////////////////
//OBSŁUGA ESKADR//
//////////////////

//DODAWANIE KLUCZA

function dodajKlucz($skrot_klucza, $nazwa_klucza, $id_eskadry) {
    //sprawdzamy czy ktos cos wyslal
    if( !empty( $_POST ) ) {
        //sprawdzamy czy cos wyslanego to nasz submit dodajEskadre
        if( array_key_exists( 'dodajKlucz', $_POST ) ){
            $dodaj = "INSERT INTO `klucze` (`Skrot`, `Nazwa`, `idEskadry`) VALUES ('$skrot_klucza', '$nazwa_klucza', '$id_eskadry');";
            $wykonaj = mysql_query($dodaj);
                //wyswietlamy informacje 
                echo "<div class=\"flex-container\">";
                    echo "<div class=\"panel piecset\">";
                        echo "<div class=\"tytul\">";
                            echo "<p>dodano klucz</p>";
                        echo "</div>";
                        echo "<div class=\"zawartosc\" >";
                            echo "Dodano Klucz: <acronym title=\"$nazwa_klucza\">$skrot_klucza</acronym> $nazwa_klucza";
                        echo "</div>";    
                    echo "</div>";
                echo "</div>";
        }
        
    }
}

//DODAWANIE ESKADRY

function dodajEskadre($skrot_eskadry, $nazwa_eskadry, $id_grupy) {
    //sprawdzamy czy ktos cos wyslal
    if( !empty( $_POST ) ) {
        //sprawdzamy czy cos wyslanego to nasz submit dodajEskadre
        if( array_key_exists( 'dodajEskadre', $_POST ) ){
            $dodaj = "INSERT INTO `eskadry` (`Skrot`, `Nazwa`, `idGrupy`) VALUES ('$skrot_eskadry', '$nazwa_eskadry', '$id_grupy');";
            $wykonaj = mysql_query($dodaj);
                //wyswietlamy informacje 
                echo "<div class=\"flex-container\">";
                    echo "<div class=\"panel piecset\">";
                        echo "<div class=\"tytul\">";
                            echo "<p>dodano eskadrę</p>";
                        echo "</div>";
                        echo "<div class=\"zawartosc\" >";
                            echo "Dodano Eskadrę: <acronym title=\"$nazwa_eskadry\">$skrot_eskadry</acronym> $nazwa_eskadry";
                        echo "</div>";    
                    echo "</div>";
                echo "</div>";
        }
        
    }
}

function dodaneGrupy($idGrupy, $idEdytuj){
   $url = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
   if(isset($_GET[grupa])){
        $url = explode("&grupa=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $adres=$url;
    }  else {
        $adres=$url;
    } 
    if(isset($_GET[edytuj_g])){
        $url = explode("&edytuj_g=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $edytuj_g=$url;
    }  else {
        $edytuj_g=$url;
    } 
    
    if(isset($_GET[usun_g])){
        $url = explode("&usun_g=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $usun_g=$url;
    }  else {
        $usun_g=$url;
    } 
    
//ZAPISUJEMY ZMIENIONE DANE W GRUPIE
    $idZapisz_g = $_POST['zapisz_g'];
        if(isset($idZapisz_g) && !empty($idZapisz_g)){//najpierw sprawdzamy czy zmienna istnieje
                    $dowodca = $_POST['zolnierze'];
                    $up_id_grupy = $_POST['grupa'];
                    $skrot = mysql_real_escape_string($_POST['skrot']);
                    $nazwa = mysql_real_escape_string($_POST['nazwa']);
                    
                    
                $sprawdzenie = mysql_query("SELECT * FROM `grupy` WHERE `idGrupy`='$up_id_grupy'");// zapytanie sprawdzajace czy uzytkownik o danym loginie jest w bazie 
                if((int)mysql_num_rows($sprawdzenie) > 0) {
                    if(empty($dowodca)){
                      $edytuj = mysql_query("UPDATE `grupy` SET `Skrot`='$skrot', `Nazwa`='$nazwa' WHERE `idGrupy`='$up_id_grupy';");  
                    }else{
                      $edytuj = mysql_query("UPDATE `grupy` SET `Skrot`='$skrot', `Nazwa`='$nazwa', `DcaGrupy`='$dowodca' WHERE `idGrupy`='$up_id_grupy';");  
                    }
                    
                }else{
                    $komunikat = "Nie ma co edytować, grupa nie istnieje";
                }
            
            header("Location: $edytuj_g");
            exit;  
        }
    
    
    
     //USUWAMY GRUPE
$idUsun_g = mysql_real_escape_string($_GET['usun_g']);
        if(isset($idUsun_g) && !empty($idUsun_g)){//najpierw sprawdzamy czy zmienna istnieje
            $sprawdzenie = mysql_query("SELECT * FROM `grupy` WHERE `idGrupy`='$idUsun_g'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
            if((int)mysql_num_rows($sprawdzenie) > 0) {
                
                while($check = mysql_fetch_object($sprawdzenie)) {  
                    $zgoda = $check->Skrot;
                }
                        $usun = mysql_query("DELETE FROM `grupy` WHERE `idGrupy`='$idUsun_g'");
                                    
            header("Location: $usun_g");
            exit;
                        $komunikat = "Usunięto eskadrę: $zgoda";
                     
            }else{
                $komunikat = "Nie można usunąć, lub nie istnieje."; //niewypisany, wiec go nie zobaczymy
            }

        }
    
    
    
    $eskadry = mysql_query("SELECT * FROM grupy") or die('Błąd zapytania'); 
       
        if(mysql_num_rows($eskadry) > 0) { //jezeli zapytanie zwrocilo wiecej zapytan od 0 to wykonaj sie
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */    
            echo "<table>";
                echo "<caption class=\"mb-10\">Dodanych grup: ".mysql_num_rows($eskadry)."<br>$komunikat</caption>";
                echo "<form class=\"nadgodzinki\" name=\"edytuj_g\" method=\"post\" action=\"\">";
                echo "<thead>";
                    echo "<tr class=\"blekitne empty-cells\">";
                        echo "<th class=\"left\">skrót</th>"; 
                        echo "<th class=\"left\">pełna nazwa</th>";
                        echo "<th class=\"left\">dowódca</th>";
                        echo "<th colspan=\"2\"></th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                    while($r = mysql_fetch_object($eskadry)) {
                        echo "<tr ";if(isset($_GET['grupa']) &&  $_GET['grupa']==$r->idGrupy){echo'class="triada-1"';}else{echo "class=\"blekitne\"";}echo" >";

                                if (isset($idEdytuj) && $idEdytuj==$r->idGrupy){/*jezeli istnieje zmienna edytuj i jest rowna id nadgodziny to wyswietl edycje*/
                                    echo "<td><input type=\"text\" class=\"wysrodkuj\" name=\"skrot\" placeholder=\"$r->Skrot\" value=\"$r->Skrot\" required=\"true\" size=\"10\"></td>";
                                    echo "<td><input type=\"text\" class=\"wysrodkuj\" name=\"nazwa\" placeholder=\"$r->Nazwa\" value=\"$r->Nazwa\" required=\"true\" size=\"30\"></td>";
                                    echo "<td class=\"left\">";
                                    lista_zolnierzy($r->DcaGrupy, null);
                                    echo"</td>"; /*wyswietlamy zolnierzy*/
                                }else{
                                    echo "<td class=\"left\"><p class=\"nowrap\">$r->Skrot</p></td>";   /*wyswietlamy daty*/ 
                                    echo "<td class=\"left\"><a href=\"$adres&grupa=$r->idGrupy\">$r->Nazwa</a></td>"; /*wyswietlamy godziny*/
                                    echo "<td class=\"left\">";
                                    if(isset($r->DcaGrupy)){
                                    echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaGrupy\">";
                                    echo "<img src=\"img/avatars/";avatar($r->DcaGrupy);
                                    echo "\" class=\"zaokraglij mr-10\" height=\"48px\" title=\"Profil\" align=\"absmiddle\">";
                                    st_nazwisko_imie($r->DcaGrupy);
                                    echo "</a>";
                                    }
                                    echo "</td>";
                                }

                                if (isset($idEdytuj) && $idEdytuj==$r->idGrupy){
                                    echo "<input type=\"hidden\" name=\"grupa\" value=\"$r->idGrupy\">";
                                    echo "<td class=\"w60\"><input type=\"submit\" class=\"aktualizuj\" name=\"zapisz_g\" value=\"Zapisz\" title=\"Zapisz do bazy\"/></td>";
                                    echo "<td class=\"w60\"><a class=\"anuluj\" href=\"$edytuj_g\">Anuluj</a></td>";
       
                                }else{
                                    echo "<td class=\"w60\"><a class=\"edytuj\" href=\"$adres&edytuj_g=".$r->idGrupy."\">Edytuj</a></td>";
                                    echo "<td class=\"w60\"><a class=\"usun\" href=\"$adres&usun_g=".$r->idGrupy."\">Usuń</a></td>";
                                }
                        echo "</tr>";
                    }
                echo "</tbody>";
                echo "</form>"; 
            echo "</table>";   
        }else{
            echo"Nie dodałeś jeszcze grup, skorzystaj z PA, aby to zrobić lub kliknij <a href=\"index.php?id=panele/admin/dodajGrupe\">tutaj</a>";
        }
    
    
    
}

function dodaneEkadry($idGrupy, $idEdytuj){
    
   $url = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
   if(isset($_GET[eskadra])){
        $url = explode("&eskadra=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $adres=$url;
    }  else {
        $adres=$url;
    } 
    
    if(isset($_GET[edytuj_e])){
        $url = explode("&edytuj_e=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $edytuj_e=$url;
    }  else {
        $edytuj_e=$url;
    } 
    
    if(isset($_GET[usun_e])){
        $url = explode("&usun_e=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $usun_e=$url;
    }  else {
        $usun_e=$url;
    } 
    

//ZAPISUJEMY ZMIENIONE DANE
    $idZapisz_e = $_POST['zapisz_e'];
        if(isset($idZapisz_e) && !empty($idZapisz_e)){//najpierw sprawdzamy czy zmienna istnieje
                    $zolnierze = $_POST['zolnierze'];
                    echo $dowodca=$zolnierze[0];
                    echo $szef=$zolnierze[1];
                    $up_id_eskadry = $_POST['eskadra'];
                    $skrot = mysql_real_escape_string($_POST['skrot']);
                    $nazwa = mysql_real_escape_string($_POST['nazwa']);
                    
                    
                $sprawdzenie = mysql_query("SELECT * FROM `eskadry` WHERE `idEskadry`='$up_id_eskadry'");// zapytanie sprawdzajace czy uzytkownik o danym loginie jest w bazie 
                if((int)mysql_num_rows($sprawdzenie) > 0) {
                    if(empty($dowodca) && !empty($szef)){
                      $edytuj = mysql_query("UPDATE `eskadry` SET `Skrot`='$skrot', `Nazwa`='$nazwa', `SzefEskadry`='$szef' WHERE `idEskadry`='$up_id_eskadry';");  
                    }
                    if(empty($szef) && !empty($dowodca)){
                      $edytuj = mysql_query("UPDATE `eskadry` SET `Skrot`='$skrot', `Nazwa`='$nazwa', `DcaEskadry`='$dowodca' WHERE `idEskadry`='$up_id_eskadry';");  
                    }
                    if(empty($dowodca) && empty($szef)){
                      $edytuj = mysql_query("UPDATE `eskadry` SET `Skrot`='$skrot', `Nazwa`='$nazwa' WHERE `idEskadry`='$up_id_eskadry';");  
                    }
                    if(!empty($dowodca) && !empty($szef)){
                      $edytuj = mysql_query("UPDATE `eskadry` SET `Skrot`='$skrot', `Nazwa`='$nazwa', `DcaEskadry`='$dowodca', `SzefEskadry`='$szef' WHERE `idEskadry`='$up_id_eskadry';");  
                    }
                    
                }else{
                    $komunikat = "Nie ma co edytować, eskadra nie istnieje";
                }
            
            header("Location: $edytuj_e");
            exit;  
        }
        
 //USUWAMY ESKADRE
$idUsun_e = mysql_real_escape_string($_GET['usun_e']);
        if(isset($idUsun_e) && !empty($idUsun_e)){//najpierw sprawdzamy czy zmienna istnieje
            $sprawdzenie = mysql_query("SELECT * FROM `eskadry` WHERE `idEskadry`='$idUsun_e'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
            if((int)mysql_num_rows($sprawdzenie) > 0) {
                
                while($check = mysql_fetch_object($sprawdzenie)) {  
                    $zgoda = $check->Skrot;
                }
                        $usun = mysql_query("DELETE FROM `eskadry` WHERE `idEskadry`='$idUsun_e'");
                                    
            header("Location: $usun_e");
            exit;
                        $komunikat = "Usunięto eskadrę: $zgoda";
                     
            }else{
                $komunikat = "Nie można usunąć, lub nie istnieje."; //niewypisany, wiec go nie zobaczymy
            }

        }
        
    
    $eskadry = mysql_query("SELECT * FROM eskadry where idGrupy LIKE '$idGrupy'") 
or die('Błąd zapytania'); 
    
    if(mysql_num_rows($eskadry) > 0) { //jezeli zapytanie zwrocilo wiecej zapytan od 0 to wykonaj sie
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */    
            echo "<table>";
                echo "<caption class=\"mb-10\">Dodanych eskadr: ".mysql_num_rows($eskadry)."<br>$komunikat</caption>";
                echo "<form class=\"nadgodzinki\" name=\"edytuj_e\" method=\"post\" action=\"\">";
                echo "<thead>";
                    echo "<tr class=\"blekitne empty-cells\">";
                        echo "<th class=\"left\">skrót</th>"; 
                        echo "<th class=\"left\">pełna nazwa</th>";
                        echo "<th>dowódca</th>";
                        echo "<th>szef</th>";
                        echo "<th colspan=\"2\"></th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                    while($r = mysql_fetch_object($eskadry)) {
                        echo "<tr ";if(isset($_GET['eskadra']) &&  $_GET['eskadra']==$r->idEskadry){echo'class="triada-1"';}else{echo "class=\"blekitne\"";}echo" >";
                            

                                if (isset($idEdytuj) && $idEdytuj==$r->idEskadry){/*jezeli istnieje zmienna edytuj i jest rowna id nadgodziny to wyswietl edycje*/
                                    echo "<td class=\"left\"><input type=\"text\" class=\"wysrodkuj\" name=\"skrot\" placeholder=\"$r->Skrot\" value=\"$r->Skrot\" required=\"true\" size=\"10\"></td>";
                                    echo "<td class=\"left\"><input type=\"text\" class=\"wysrodkuj\" name=\"nazwa\" placeholder=\"$r->Nazwa\" value=\"$r->Nazwa\" required=\"true\" size=\"30\"></td>";
                                    echo "<td class=\"left\">";
                                    lista_zolnierzy($r->DcaEskadry, "multi", 200);
                                    echo"</td>"; /*wyswietlamy zolnierzy*/
                                    echo "<td class=\"left\">";
                                    lista_zolnierzy($r->SzefEskadry, "multi", 200);
                                    echo"</td>"; /*wyswietlamy zolnierzy*/
                                }else{
                                    echo "<td class=\"left\"><p class=\"nowrap\">$r->Skrot</p></td>";   /*wyswietlamy daty*/ 
                                    echo "<td class=\"left\" \"><a href=\"$adres&eskadra=$r->idEskadry\">$r->Nazwa</a></td>"; /*wyswietlamy godziny*/
                                    echo "<td>";
                                    if(isset($r->DcaEskadry)){
                                    echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaEskadry\">";
                                    echo "<img src=\"img/avatars/";avatar($r->DcaEskadry);
                                    echo "\" class=\"zaokraglij\" height=\"48px\" title=\"";st_nazwisko_imie($r->DcaEskadry);echo"\" align=\"absmiddle\">";
                                    echo "</a>";
                                    }
                                    echo "</td>";
                                    echo "<td>";
                                    if(isset($r->SzefEskadry)){
                                    echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->SzefEskadry\">";
                                    echo "<img src=\"img/avatars/";avatar($r->SzefEskadry);
                                    echo "\" class=\"zaokraglij\" height=\"48px\" title=\"";st_nazwisko_imie($r->SzefEskadry);echo"\" align=\"absmiddle\">";
                                    echo "</a>";
                                    }
                                    echo "</td>";
                                }

                                if (isset($idEdytuj) && $idEdytuj==$r->idEskadry){
                                    echo "<input type=\"hidden\" name=\"eskadra\" value=\"$r->idEskadry\">";
                                    echo "<td><input type=\"submit\" class=\"aktualizuj\" name=\"zapisz_e\" value=\"Zapisz\" title=\"Zapisz do bazy\"/></td>";
                                    echo "<td><a class=\"anuluj\" href=\"$edytuj_e\">Anuluj</a></td>";
                                }else{
                                    echo "<td class=\"w60\"><a class=\"edytuj\" href=\"$adres&edytuj_e=".$r->idEskadry."\">Edytuj</a></td>";
                                    echo "<td class=\"w60\"><a class=\"usun\" href=\"$usun_e&usun_e=".$r->idEskadry."\">Usuń</a></td>";
                                }
                        echo "</tr>";
                    }
                echo "</tbody>";
                echo "</form>"; 
            echo "</table>";   
        }else{
            echo"Nie dodałeś jeszcze eskadr, skorzystaj z PA, aby to zrobić lub kliknij <a href=\"index.php?id=panele/admin/dodajEskadre\">tutaj</a>";
        }
    
}

function dodaneKlucze($idEskadry, $idEdytuj){
$url = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
   if(isset($_GET[klucz])){
        $url = explode("&klucz=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $adres=$url;
    }  else {
        $adres=$url;
    } 
    if(isset($_GET[edytuj_k])){
        $url = explode("&edytuj_k=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $edytuj_k=$url;
    }  else {
        $edytuj_k=$url;
    } 
    
    if(isset($_GET[usun_k])){
        $url = explode("&usun_k=", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $usun_k=$url;
    }  else {
        $usun_k=$url;
    } 
    
//ZAPISUJEMY ZMIENIONE DANE W GRUPIE
    $idZapisz_k = $_POST['zapisz_k'];
        if(isset($idZapisz_k) && !empty($idZapisz_k)){//najpierw sprawdzamy czy zmienna istnieje
                    $dowodca = $_POST['zolnierze'];
                    $up_id_klucze = $_POST['klucz'];
                    $skrot = mysql_real_escape_string($_POST['skrot']);
                    $nazwa = mysql_real_escape_string($_POST['nazwa']);
                    
                    
                $sprawdzenie = mysql_query("SELECT * FROM `klucze` WHERE `idKlucza`='$up_id_klucze'");// zapytanie sprawdzajace czy uzytkownik o danym loginie jest w bazie 
                if((int)mysql_num_rows($sprawdzenie) > 0) {
                    if(empty($dowodca)){
                      $edytuj = mysql_query("UPDATE `klucze` SET `Skrot`='$skrot', `Nazwa`='$nazwa' WHERE `idKlucza`='$up_id_klucze';");  
                    }else{
                      $edytuj = mysql_query("UPDATE `klucze` SET `Skrot`='$skrot', `Nazwa`='$nazwa', `DcaKlucza`='$dowodca' WHERE `idKlucza`='$up_id_klucze';");  
                    }
                    
                }else{
                    $komunikat = "Nie ma co edytować, klucz nie istnieje";
                }
            
            header("Location: $edytuj_k");
            exit;  
        }
    
    
    
     //USUWAMY GRUPE
$idUsun_k = mysql_real_escape_string($_GET['usun_k']);
        if(isset($idUsun_k) && !empty($idUsun_k)){//najpierw sprawdzamy czy zmienna istnieje
            $sprawdzenie = mysql_query("SELECT * FROM `klucze` WHERE `idKlucza`='$idUsun_k'");// zapytanie sprawdzajace czy nadgodzina o danym id jest w bazie 
            if((int)mysql_num_rows($sprawdzenie) > 0) {
                
                while($check = mysql_fetch_object($sprawdzenie)) {  
                    $zgoda = $check->Skrot;
                }
                        $usun = mysql_query("DELETE FROM `klucze` WHERE `idKlucza`='$idUsun_k'");
                                    
            header("Location: $usun_k");
            exit;
                        $komunikat = "Usunięto Klucz: $zgoda";
                     
            }else{
                $komunikat = "Nie można usunąć, lub nie istnieje."; //niewypisany, wiec go nie zobaczymy
            }

        }
    
    
    
    $klucze = mysql_query("SELECT * FROM klucze where idEskadry LIKE '$idEskadry'") or die('Błąd zapytania'); 
       
        if(mysql_num_rows($klucze) > 0) { //jezeli zapytanie zwrocilo wiecej zapytan od 0 to wykonaj sie
            /* jeżeli wynik jest pozytywny, to wyświetlamy dane */    
            echo "<table>";
                echo "<caption class=\"mb-10\">Dodanych kluczy: ".mysql_num_rows($klucze)."<br>$komunikat</caption>";
                echo "<form class=\"nadgodzinki\" name=\"edytuj_g\" method=\"post\" action=\"\">";
                echo "<thead>";
                    echo "<tr class=\"blekitne empty-cells\">";
                        echo "<th class=\"left\">skrót</th>"; 
                        echo "<th class=\"left\">pełna nazwa</th>";
                        echo "<th class=\"left\">dowódca</th>";
                        echo "<th colspan=\"2\"></th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                    while($r = mysql_fetch_object($klucze)) {
                        echo "<tr class=\"blekitne\" >";
                                if (isset($idEdytuj) && $idEdytuj==$r->idKlucza){/*jezeli istnieje zmienna edytuj i jest rowna id nadgodziny to wyswietl edycje*/
                                    echo "<td><input type=\"text\" class=\"wysrodkuj\" name=\"skrot\" placeholder=\"$r->Skrot\" value=\"$r->Skrot\" required=\"true\" size=\"10\"></td>";
                                    echo "<td><input type=\"text\" class=\"wysrodkuj\" name=\"nazwa\" placeholder=\"$r->Nazwa\" value=\"$r->Nazwa\" required=\"true\" size=\"30\"></td>";
                                    echo "<td class=\"left\">";
                                    lista_zolnierzy($r->DcaKlucza, null);
                                    echo"</td>"; /*wyswietlamy zolnierzy*/
                                }else{
                                    echo "<td class=\"left\"><p class=\"nowrap\">$r->Skrot</p></td>";   /*wyswietlamy daty*/ 
                                    echo "<td class=\"left\">$r->Nazwa</td>"; /*wyswietlamy godziny*/
                                    echo "<td class=\"left\">";
                                    if(isset($r->DcaKlucza)){
                                    echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaKlucza\">";
                                    echo "<img src=\"img/avatars/";avatar($r->DcaKlucza);
                                    echo "\" class=\"zaokraglij mr-10\" height=\"48px\" title=\"Profil\" align=\"absmiddle\">";
                                    st_nazwisko_imie($r->DcaKlucza);
                                    echo "</a>";
                                    }
                                    echo "</td>";
                                }

                                if (isset($idEdytuj) && $idEdytuj==$r->idKlucza){
                                    echo "<input type=\"hidden\" name=\"klucz\" value=\"$r->idKlucza\">";
                                    echo "<td class=\"w60\"><input type=\"submit\" class=\"aktualizuj\" name=\"zapisz_k\" value=\"Zapisz\" title=\"Zapisz do bazy\"/></td>";
                                    echo "<td class=\"w60\"><a class=\"anuluj\" href=\"$edytuj_k\">Anuluj</a></td>";
       
                                }else{
                                    echo "<td class=\"w60\"><a class=\"edytuj\" href=\"$adres&edytuj_k=".$r->idKlucza."\">Edytuj</a></td>";
                                    echo "<td class=\"w60\"><a class=\"usun\" href=\"$adres&usun_k=".$r->idKlucza."\">Usuń</a></td>";
                                }
                        echo "</tr>";
                    }
                echo "</tbody>";
                echo "</form>"; 
            echo "</table>";   
        }else{
            echo"Nie dodałeś jeszcze klucza, skorzystaj z PA, aby to zrobić lub kliknij <a href=\"index.php?id=panele/admin/dodajKlucz\">tutaj</a>";
        }
    
}

//dodane w niosku widoczne na pulpicie uzytkownika
function dodaneWnioski($czyje_id, $idUsun) {
    //USUWAMY DODANE NADGODZINY    
        if(!empty($idUsun)){//najpierw sprawdzamy czy zmienna nie jest pusta
            $sprawdzenie = mysql_query("SELECT * FROM `wnioski` WHERE `idWniosku`='$idUsun'");// zapytanie sprawdzajace czy wniosek o danym id jest w bazie 
            if((int)mysql_num_rows($sprawdzenie) > 0) {
                
                while($check = mysql_fetch_object($sprawdzenie)) {  
                    $zgoda = intval($check->kogo);
                }
                    if($zgoda == mamDostepDo($zgoda)){
                        $usun = mysql_query("DELETE FROM `wnioski` WHERE `idWniosku`='$idUsun'");
                    }else{
                        echo "<p class='wysrodkuj'>Nie masz uprawnień.<br>Nie kombinuj.</p>"; 
                    }              
            }else{
                $komunikat = "Nie ma co usunąć, zrobiłeś to wcześniej"; //niewypisany, wiec go nie zobaczymy
            }
        }
        
$czyje_id = id_zolnierza();    
$zapytanie = mysql_query("SELECT *, DATE_FORMAT(wolne, '%d-%m-%Y') AS termin, DATE_FORMAT(kiedy_zlozyl, '%d.%m.%Y') AS zlozone, DATEDIFF(NOW(), kiedy_zlozyl) AS dni FROM wnioski WHERE kogo='$czyje_id' ORDER BY wolne ASC") 
or die('Błąd zapytania'); 
    if(mysql_num_rows($zapytanie) > 0) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        echo "<table>";
            echo "<thead>";
                echo "<tr class=\" blekitne empty-cells\">";
                    echo "<th></th>";
                    echo "<th class=\"left\">w dniu</th>";
                    echo "<th>godzin</th>";
                    echo "<th>dni od złożenia</th>";
                    echo "<th class=\"\"></th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while($r = mysql_fetch_object($zapytanie)) {
                echo "<tr class=\"blekitne\">";  
                        echo "<td class=\"left\">";
                            echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->kogo\">";
                            echo "<img src=\"img/avatars/";avatar($r->kogo);
                            echo "\" class=\"zaokraglij\" height=\"26px\" title=\"Dodane: ".$r->kiedy_zlozyl."\" align=\"absmiddle\">";
                            echo "</a>";
                        echo "</td>";
                        echo "<td class=\"left\">$r->termin</td>";   /*wyswietlamy daty*/ 
                        echo "<td>".(($r->ile)/60)."</td>"; /*wyswietlamy godziny*/
                        echo "<td><abbr title=\"$r->zlozone\">$r->dni</abbr></td>"; /*ilosc dni od zlozenia*/
                        echo "<td><a class=\"usun\" href=\"index.php?id=pulpit&usun=".$r->idWniosku."#wnioski\">Usuń</a></td>";
                    echo "</tr>";
            }
            echo "</tbody>";
        echo "</table>";   
    }else{
        echo"Brak wniosków.";
    }
}

//wyswietlenie liczby oczekujacych wnioskow

function licz_oczekujace(){
    $dzisiaj = date("Y-m-d");
    switch ($_SESSION['permissions']){
        case 1:
            //admin
            $wnioski = mysql_query("SELECT * FROM wnioski left join zolnierze on kogo=idZolnierza left join stopnie using (idStopien)") 
            or die('Błąd zapytania');
            $licz_terminy = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' GROUP BY idZolnierza
                                         union all
                                         SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' GROUP BY idZolnierza") 
            or die('Błąd zapytania liczenia terminow');             
            break;
        case 2:
            //dowodca grupy
            $idGrupy = czyDowodcaGrupy();
            $wnioski = mysql_query("SELECT * FROM wnioski left join zolnierze on kogo=idZolnierza left join eskadry using(idEskadry) left join stopnie using (idStopien) where idGrupy='$idGrupy'") 
            or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do grupy');
            $licz_terminy = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin left join zolnierze using (idZolnierza) left join eskadry using(idEskadry) left join grupy using (idGrupy) WHERE idGrupy='$idGrupy' and DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' GROUP BY idZolnierza
                                         union all
                                         SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb left join zolnierze using (idZolnierza) left join eskadry using(idEskadry) left join grupy using (idGrupy) WHERE idGrupy='$idGrupy' and DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' GROUP BY idZolnierza") 
            or die('Błąd zapytania liczenia'); 
            break; 
        case 3:
            //dowodca eskadry
            $idDowodcy = id_zolnierza();
            $idEskadry = id_eskadry();
            $wnioski = mysql_query("SELECT * FROM wnioski left join zolnierze on kogo=idZolnierza left join stopnie using (idStopien) where idEskadry='$idEskadry'") 
            or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do eskadry');
            $licz_terminy = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idEskadry='$idEskadry' GROUP BY idZolnierza
                                         union all
                                         SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idEskadry='$idEskadry' GROUP BY idZolnierza") 
            or die('Błąd zapytania liczenia'); 
            break;
        case 4:
            //szef eskadry
            $idEskadry = id_eskadry();
            $wnioski = mysql_query("SELECT * FROM wnioski left join zolnierze on kogo=idZolnierza left join stopnie using (idStopien) where idEskadry='$idEskadry'") 
            or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do eskadry');
            $licz_terminy = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idEskadry='$idEskadry' GROUP BY idZolnierza
                                         union all
                                         SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idEskadry='$idEskadry' GROUP BY idZolnierza") 
            or die('Błąd zapytania liczenia');
            break;
        case 5:
            //dowodca klucza
            $idKlucza = id_klucza();
            $wnioski = mysql_query("SELECT * FROM wnioski left join zolnierze on kogo=idZolnierza left join stopnie using (idStopien) where idKlucza='$idKlucza'") 
            or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do eskadry');
            $licz_terminy = mysql_query("SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_nadgodzin left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idKlucza='$idKlucza' GROUP BY idZolnierza
                                         union all
                                         SELECT *, sum(pozostalo) as ma_wykorzystac, DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%d-%m-%Y') AS waznosc FROM v_zestawienie_sluzb left join zolnierze using (idZolnierza) WHERE DATE_FORMAT(DATE_ADD(DATE_ADD(termin, INTERVAL 4 MONTH),INTERVAL 1 DAY), '%Y-%m-%d')<'$dzisiaj' and pozostalo!='0' and idKlucza='$idKlucza' GROUP BY idZolnierza") 
            or die('Błąd zapytania liczenia');
            break;
        case 6:
            //zolnierz
            $czyje_id = id_zolnierza(); 
            $wnioski = mysql_query("SELECT * FROM wnioski WHERE kogo='$czyje_id'") 
or die('Błąd zapytania'); 
            break;
    }
    $oczekujace_w = mysql_num_rows($wnioski);
    $oczekujace_t = mysql_num_rows($licz_terminy);
    return $oczekujace_w + $oczekujace_t;
}


//////////////////
//  USTAWIENIA  //
//////////////////

//ZMIANA HASLA

function zmienHaslo($kogo) {
    
    if( empty($kogo) ) {
        $kogo = $_SESSION['user']; // jezeli nie podane $kogo to pobierze login zalogowanego uzytkownika
    }
    
    
    

        //formularz zmiany hasla
        function formZmiana($errorpas, $placepas = "wpisz nowe hasło")
        {
            echo "<form name=\"zmienHaslo\" method=\"post\" action=\"\">";
            echo "<div class=\"zawartosc wysrodkuj\">";
            echo "<input type=\"password\" name=\"podajhaslo\" maxlength=\"40\" placeholder=\"$placepas...\" class=\"mb-10 pl-5 zh $errorpas\" pattern='(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$' title=\"Min. 8 znaków, wielka i mała litera oraz znak specjalny\"><br>";  
            
            echo "<input value=\"zmień\" type=\"submit\" class=\"zapisz animacja mt-10\">";
            echo "</div>";
            echo "</form>";
        }
        $zmienhaslo = htmlspecialchars($_POST['podajhaslo']);
        $uppercase = preg_match('@[A-Z]@', $zmienhaslo);
        $lowercase = preg_match('@[a-z]@', $zmienhaslo);
        $number    = preg_match('@[0-9]@', $zmienhaslo);
        
        $zakodowane = md5($zmienhaslo);
    
    if(!isset($_POST['podajhaslo']))
        { //echo "<div class=\"flex-container\">";
        

                formZmiana();
        
          //echo "</div>";
        } else {
            

                //sprawdzimy czy haslo to login
                if($zmienhaslo==$_SESSION['user']){
                    formZmiana("error","hasło nie może być loginem");    
                }elseif(empty ($_POST['podajhaslo']) && !$uppercase || !$lowercase || !$number || strlen($zmienhaslo) < 7) {
                    formZmiana("error","nie wysyłaj pustego formularza");
                }else{ //jezeli nie jest to zmieniamy
                        /* jeżeli wynik jest pozytywny, to dodajemy uzytkownika */ 
                        $zapytanie = "UPDATE `uzytkownicy` SET `Haslo`='$zakodowane' WHERE `Login`='$kogo';";
                        $wykonaj = mysql_query($zapytanie);
                        formZmiana("correct","hasło zostało zmienione");
                    }
            
        }  
    }









}//koniec warunku, ktory zabrania uruchomic funkcje jezeli nie jestesmy zalogowani

?>

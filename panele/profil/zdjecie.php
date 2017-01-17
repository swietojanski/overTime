<?php


if (isset($_POST['wyslane'])) {

    // Sprawdź ładowany obrazek.
    if (isset($_FILES['upload'])) {
        
        list($width, $height, $type, $attr) = getimagesize($_FILES['upload']['tmp_name']);



            // SprawdĽ typ, pownien być JPEG lub PNG.
            $allowed = array ('image/jpeg', 'image/pjpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif', 'image/GIF');
            if (in_array($_FILES['upload']['type'], $allowed)) {

                    //adres oryginalnego, zalaczonego zdjecia
                    $url = $_FILES['upload']['tmp_name'];
                    
                        switch($type){
                          case '6': $zdjecie = imagecreatefromwbmp($url); break;
                          case '1': $zdjecie = imagecreatefromgif($url); break;
                          case '2': $zdjecie = imagecreatefromjpeg($url); break;
                          case '3': $zdjecie = imagecreatefrompng($url); break;
                          default : return "Unsupported picture type!";
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
                    
                    $url = $_SERVER['DOCUMENT_ROOT'].'/img/profiles/thumbnail/'.$_FILES['upload']['name'];
                    //wybieramy jaka bedzie metoda tworzenia
                      switch($type){
                        case '6': imagewbmp($nowe, $url, 100); break;
                        case '1': imagegif($nowe, $url, 100); break;
                        case '2': imagejpeg($nowe, $url, 100); break;
                        case '3': imagepng($nowe, $url); break;
                      }
                    
                 // Koniec instrukcji if move...
                      // Przenieś plik do docelowego katalogu.
                    move_uploaded_file($_FILES['upload']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/img/profiles/'.$_FILES['upload']['name']); 
                    echo '<p><em>Plik został załadowany!</em></p>';

            } else { // Niepoprawny typ.
                echo '<p class="error">Proszę załadować plik typu JPEG lub PNG.</p>';
            }
           
   $picture = "<img src=\"/img/profiles/thumbnail/".$_FILES['upload']['name']."\">";  
    } // Koniec instrukcji if isset($_FILES['upload']).
    
    // Wyrzuć błąd.
    
    if ($_FILES['upload']['error'] > 0) {
        echo '<p class="error">Plik nie został załadowany pomyślnie: <strong>';
    
        // Wyświetl odpowiedni komunikat w zależności od błędu.
        switch ($_FILES['upload']['error']) {
            case 1:
                print 'Rozmiar pliku większy niż pozwala serwer.';
                break;
            case 2:
                print 'Nieprawidłowy rozmiar lub typ pliku.';
                break;
            case 3:
                print 'Plik został czę&#182;ciowo załadowany.';
                break;
            case 4:
                print 'Żaden plik nie został załadowany.';
                break;
            case 6:
                print 'Katalog tymczasowy był niedostępny.';
                break;
            case 7:
                print 'Brak możliwości zapisu na dysk.';
                break;
            case 8:
                print 'Proces ładowania został wstrzymany.';
                break;
            default:
                print 'Wystąpił błąd systemu.';
                break;
        } // Koniec instrukcji switch.
        
        print '</strong></p>';
       
    } // Koniec instukcji if zwi&#177;zanej z obsług&#177; błędów.
    
    // Usuń plik je&#182;li jeszcze istnieje.
    if (file_exists ($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name']) ) {
        unlink ($_FILES['upload']['tmp_name']);
    }
    
   
            
} // Koniec głównej instrukcji if.
?>
    
<form enctype="multipart/form-data" action="" method="post">

    <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
    
    <fieldset><legend>Załaduj plik JPEG lub PNG o wielkości do 1MB:</legend>
    
        <p><b>Plik:</b><div class="upload zaokraglij"><div class="index-1"><?php echo $picture; ?></div><input type="file" name="upload" accept="image/gif,image/jpeg,image/png" pattern="([^\s]+(\.(?i)(jpg|png|gif|bmp))$)" /></div>
</p>
    
    </fieldset>
    <div align="center"><input type="submit" name="submit" value="Załaduj" /></div>
    
    <input type="hidden" name="wyslane" value="TRUE"/>
</form>


  
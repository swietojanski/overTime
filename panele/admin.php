<?php if($_SESSION['permissions']==1){ //wpusc jezeli ma prawa admina?>
<h1> Panel admina</h1>
<!--<h2 class="podpowiedzi zaokraglij">Zarządzaj, dodawaj, edytuj, usuwaj...</h2>-->

<div class="flex-container">
        <ul class="flex-container">
            <a href="index.php?id=panele/admin/dodajUzytkownika"><li class="flex-box dopelniajacy-1">dodaj użytkownika</li></a>
            <a href="index.php?id=panele/admin/dodajEskadre"><li class="flex-box dopelniajacy-2">dodaj eskadre</li></a>
            <a href="index.php?id=panele/admin/eskadry"><li class="flex-box dopelniajacy-1">przeglądaj eskadry</li></a>
            <a href="index.php?id=panele/admin/uzytkownicy"><li class="flex-box zlozony-3">przeglądaj użytkowników</li></a>
            <a href="index.php?id=panele/dodaj/sluzby"><li class="flex-box triada-4"></li></a>
            <a href="index.php?id=panele/dodaj/sluzby"><li class="flex-box dopelniajacy-2"></li></a>
            <a href="index.php?id=panele/dodaj/sluzby"><li class="flex-box dopelniajacy-1"></li></a>
            <a href="index.php?id=panele/dodaj/sluzby"><li class="flex-box triada-2"></li></a>
            <a href="index.php?id=panele/dodaj/sluzby"><li class="flex-box dopelniajacy-1"></li></a>
            <a href="index.php?id=panele/dodaj/sluzby"><li class="flex-box dopelniajacy-2"></li></a>
        </ul>
    </div>
<?php


//przypisanie POST z formularza do zmiennych
$skrot = mysql_real_escape_string($_POST[skrot]);
$nazwa = mysql_real_escape_string($_POST[nazwa]);

}  else { //jezeli to nie admin powiadom go o tym
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
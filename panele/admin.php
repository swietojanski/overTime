<?php if($_SESSION['permissions']==1){ //wpusc jezeli ma prawa admina?>
<h1> Panel administracyjny </h1>
<h2 class="podpowiedzi zaokraglij">Zarządzaj, dodawaj, edytuj, usuwaj...</h2>

<div class="flex-container">
        <ul class="flex-container">
            <a href="index.php?id=panele/admin/dodajUzytkownika"><li class="flex-box dopelniajacy-1">dodaj użytkownika</li></a>
            <a href="index.php?id=panele/admin/dodajEskadry"><li class="flex-box dopelniajacy-2">dodaj eskadry</li></a>
            <a href="index.php?id=panele/admin/eskadry"><li class="flex-box dopelniajacy-1">przeglądaj eskadry a może nie</li></a>
            <a href="index.php?id=panele/dodaj/sluzby"><li class="flex-box zlozony-3">dodaj żołnierza</li></a>
            <a href="index.php?id=panele/dodaj/sluzby"><li class="flex-box triada-4">dodaj żołnierza</li></a>
            <a href="index.php?id=panele/dodaj/sluzby"><li class="flex-box dopelniajacy-2">dodaj żołnierza</li></a>
            <a href="index.php?id=panele/dodaj/sluzby"><li class="flex-box dopelniajacy-1">dodaj żołnierza</li></a>
            <a href="index.php?id=panele/dodaj/sluzby"><li class="flex-box triada-2">dodaj żołnierza</li></a>
            <a href="index.php?id=panele/dodaj/sluzby"><li class="flex-box dopelniajacy-1">dodaj żołnierza</li></a>
            <a href="index.php?id=panele/dodaj/sluzby"><li class="flex-box dopelniajacy-2">dodaj żołnierza</li></a>
        </ul>
    </div>
</div>
<?php


//przypisanie POST z formularza do zmiennych
$skrot = mysql_real_escape_string($_POST[skrot]);
$nazwa = mysql_real_escape_string($_POST[nazwa]);

}  else { //jezeli to nie admin powiadom go o tym
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
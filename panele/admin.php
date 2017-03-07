<?php if($_SESSION['permissions']==1){ //wpusc jezeli ma prawa admina?>
<h1> Panel admina</h1>
<!--<h2 class="podpowiedzi zaokraglij">Zarządzaj, dodawaj, edytuj, usuwaj...</h2>-->

<div class="flex-container">
        <ul class="flex-container">
            <li><a href="index.php?id=panele/admin/dodajGrupe" class="flex-box dopelniajacy-2">dodaj grupę</a></li>
            <li><a href="index.php?id=panele/admin/dodajEskadre" class="flex-box dopelniajacy-2">dodaj eskadrę</a></li>
            <li><a href="index.php?id=panele/admin/dodajKlucz" class="flex-box dopelniajacy-2">dodaj klucz</a></li>
            <li><a href="index.php?id=panele/admin/struktury" class="flex-box dopelniajacy-1">przeglądaj struktury</a></li>
            <li><a href="index.php?id=panele/admin/dodajZolnierza" class="flex-box triada-4">dodaj żołnierza</a></li>
            <li><a href="index.php?id=panele/admin/dodajUzytkownika" class="flex-box dopelniajacy-1">dodaj użytkownika</a></li>
            <li><a href="index.php?id=panele/admin/uzytkownicy" class="flex-box dopelniajacy-1">przeglądaj użytkowników</a></li>    
            <li><a href="index.php?id=panele/admin/stopnie" class="flex-box dopelniajacy-2">stopnie wojskowe</a></li>
            <li><a href="index.php?id=panele/admin/powody" class="flex-box dopelniajacy-1">powody nadgodzin</a></li>
            <li><a href="index.php?id=panele/admin/dyzury" class="flex-box triada-2">nazwy służb</a></li>
        </ul>
    </div>
<?php


}  else { //jezeli to nie admin powiadom go o tym
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
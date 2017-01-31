<?php if($_SESSION['permissions']==1){ //wpusc jezeli ma prawa admina?>
<h1> Dodane eskadry </h1>
<h2 class="podpowiedzi zaokraglij">Lista eskadr, które możesz usunąc lub edytować</h2>

<div class="flex-container">
    <div class="panel osiemset">
        <div class="tytul">
            <p>wszystkie eskadry</p>
        </div>
        <div class="zawartosc wysrodkuj">
            <?php dodaneEkadry(); ?>
        </div>  
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
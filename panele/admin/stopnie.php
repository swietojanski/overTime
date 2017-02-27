<?php if($_SESSION['permissions']==1){ //wpusc jezeli ma prawa admina
//przypisanie POST z formularza do zmiennych
$id_grupy = mysql_real_escape_string($_GET['grupa']);
$id_eskadry = mysql_real_escape_string($_GET['eskadra']);
$edytuj_g = mysql_real_escape_string($_GET['edytuj_g']);
$edytuj_e = mysql_real_escape_string($_GET['edytuj_e']);
$edytuj_k = mysql_real_escape_string($_GET['edytuj_k']);
    
?>
<h1> Struktury </h1>
<h2 class="podpowiedzi zaokraglij">Lista grup, eskadr, kluczy, które możesz usunąc lub edytować</h2>

<div class="flex-container">
    <div class="panel tysiac">
        <div class="tytul">
            <p>grupy</p>
        </div>
        <div class="zawartosc wysrodkuj">
            <?php dodaneGrupy(null,$edytuj_g); ?>
        </div>  
    </div>
</div>
<?php if(isset($_GET['dodaj'])){ ?>
<div class="flex-container">
    <div class="panel tysiac">
        <div class="tytul">
            <p>dodaj stopien</p>
        </div>
        <div class="zawartosc wysrodkuj">
            
        </div>  
    </div>
</div>
<?php } ?>
<?php

}  else { //jezeli to nie admin powiadom go o tym
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
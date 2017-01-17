<?php 
$url = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; //generujemy aktualny adres wyswietlanej strony
if(isset($_GET[edytuj])){
        $url = explode("&edytuj", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $adres=$url;
        $akcja="wyjdź";
}  else {
        $adres=$url."&edytuj";
        $akcja="edytuj";
}
?>

<h1> Profil żołnierza </h1>
<h2 class="podpowiedzi zaokraglij">Przeglądasz swoje dane profilowe.</h2>

<div class="flex-container">
    <div class="panel szescset">
        <div class="tytul"><p>dane profilowe</p><p class="right"><a href="<?php echo $adres?>" class="mr-10"><?php echo $akcja?></a></p></div>
        <div class="zawartosc">
            <?php profil(id_zolnierza());?>
        </div>    
    </div>
</div>
<div class="flex-container">
    <div class="panel trzysta">
       <div class="tytul"><p>nadgodziny</p></div>
        <div class="zawartosc wysrodkuj">
            <span class="wlinii">
        <?php sumaNadgodzin(id_zolnierza(), 0);?></span>
        </div>    
    </div>
    <div class="panel trzysta">
       <div class="tytul"><p>służby</p></div>
        <div class="zawartosc">
            
            
przykladowa zawartosc sluzb
        </div>    
    </div>
</div>

<?php 
$url = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; //generujemy aktualny adres wyswietlanej strony
if(isset($_GET[edytuj])){
        $url = explode("&edytuj", $url); //wyrzucamy deklaracje zmiennej get z adresu
        $url = $url[0];
        $adres=$url;
        $akcja="wyjdź";
        $class="anuluj";
}  else {
        $adres=$url."&edytuj";
        $akcja="edytuj";
        $class="edytuj";
}
?>

<h1> Profil żołnierza </h1>
<h2 class="podpowiedzi zaokraglij">Przeglądasz swoje dane profilowe.</h2>

<div class="flex-container">
    <div class="panel szescset">
        <div class="tytul"><p>dane profilowe</p><p class="right"><a href="<?php echo $adres?>" class="pl-10 pr-10 <?php echo $class ?> valing40" title="<?php echo $akcja?>"><?php echo $akcja?></a></p></div>
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
        <?php zostaloNadgodzin(id_zolnierza(), 0);?></span>
        </div>    
    </div>
    <div class="panel trzysta">
       <div class="tytul"><p>służby</p></div>
        <div class="zawartosc wysrodkuj">
        <span class="wlinii">
        <?php sumaSluzb(id_zolnierza(), 0);?></span>
        </div>    
    </div>
</div>

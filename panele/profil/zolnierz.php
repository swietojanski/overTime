<?php require_once 'panele/profil/kalendarzyk.php'; ?>
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

<?php

    if( isset($_GET['profil']) && $_GET['profil'] == mamDostepDo($_GET['profil'])) {
        
?>

<h1> Profil żołnierza </h1>
<h2 class="podpowiedzi zaokraglij">Przeglądaj najważniejsze dane o żołnierzu.</h2>

<div class="flex-container">
    <div class="panel czterdziesci">
        <div class="tytul triada-2"><p>+ nadgodziny</p></div>
    </div>
    <div class="panel czterdziesci">
       <div class="tytul triada-1"><p>+ służby</p></div>
    </div>
    <div class="panel czterdziesci"><a href="index.php?id=panele/moje/nadgodziny&profil=<?php echo $_GET['profil'];?>" title="przeglądaj nadgodziny">
       <div class="tytul zlozony-3"><p>przeglądaj nadgodziny</p></div></a>
    </div>
    <div class="panel czterdziesci"><a href="index.php?id=panele/moje/sluzby&profil=<?php echo $_GET['profil'];?>" title="przeglądaj służby">
            <div class="tytul zlozony-4"><p>przeglądaj służby</p></div></a>
    </div>
    <div class="panel czterdziesci">
       <div class="tytul dopelniajacy-1"><p>n</p></div>
    </div>
    <div class="panel czterdziesci">
       <div class="tytul dopelniajacy-2"><p>sł</p></div>
    </div>
    <div class="panel czterdziesci">
       <div class="tytul dopelniajacy-3"><p>sł</p></div>
    </div>
    <div class="panel czterdziesci">
       <div class="tytul dopelniajacy-4"><p>sł</p></div>
    </div>
    
    <div class="panel czterdziesci">
       <div class="tytul triada-1"><p>n</p></div>
    </div>
    <div class="panel czterdziesci">
       <div class="tytul triada-2"><p>sł</p></div>
    </div>

    
</div>

<div class="flex-container">
    <div class="panel szescset">
        <div class="tytul"><p>dane profilowe</p><p class="right"><a href="<?php echo $adres?>" class="pl-10 pr-10 <?php echo $class ?> valing40" title="<?php echo $akcja?>"><?php echo $akcja?></a></p></div>
        <div class="zawartosc">
            <?php profil($_GET['profil']);?>
        </div>    
    </div>
</div>
<div class="flex-container">
    <div class="panel trzysta">
       <div class="tytul"><p>nadgodziny</p></div>
        <div class="zawartosc wysrodkuj">
            <span class="wlinii">
        <?php zostaloNadgodzin($_GET['profil'], 0);?></span>
        </div>    
    </div>
    <div class="panel trzysta">
       <div class="tytul"><p>służby</p></div>
        <div class="zawartosc wysrodkuj">
            <span class="wlinii">
        <?php zostaloSluzb($_GET['profil'], 0);?></span>
        </div>    
    </div>
</div>

<div class="flex-container">
    <div class="panel szescset">
       <div class="tytul">
          <p id="kalendarz">kalendarz</p>
<!--          <p class="right"><a href="#index.php?id=panele/admin/uzytkownicy" class="pl-10 pr-10 edytuj valing40" title="wyświetl wszystkich użytkowników">opcja</a></p>-->
       </div>
       <div class="zawartosc" >
            <?php kalendarz($_GET['profil']); ?>
       </div>    
    </div>
</div>
<?php if(isset($_GET['zobacz']) and !isset($_POST['wybrane'])){ ?>
<div class="flex-container">
    <div class="panel szescset">
       <div class="tytul">
          <p>szczegóły</p>
<!--          <p class="right"><a href="#index.php?id=panele/admin/uzytkownicy" class="pl-10 pr-10 edytuj valing40" title="wyświetl wszystkich użytkowników">opcja</a></p>-->
       </div>
       <div class="zawartosc" >
            <?php szczegoly($_GET['profil'],mysql_real_escape_string($_GET['zobacz'])); ?>
       </div>    
    </div>
</div>
<?php } ?>
<div class="flex-container">
    <div class="panel bez-tla szescset mt-10">
        <div class="white">
           <span class="zlozony-4 pall-10">dni wolne</span><span class="triada-2 pall-10 ml-10">dni w pracy</span>
       </div>    
    </div>
</div>
<br>




<?php
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


?>
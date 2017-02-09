<?php
$akceptacja=$_SESSION['user']."-cookieaccept";
    if(isset($_GET['ciasteczko']) && $$_GET['ciasteczko']='zgoda'){
        setcookie($akceptacja, 'zgoda', time() + 2 * 356 * 86400);
    }elseif(empty($_COOKIE[$akceptacja]) && !isset($_GET['ciasteczko'])){
?>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="flex-container">
             <div class="panel tysiac zaokraglij">
             <div class="tytul"><p>uwaga: ta strona wykorzystuje pliki cookie!!!</p><p class="right"><a href="index.php?ciasteczko=zgoda" class="pl-10 pr-10 usun valing40" title="akceptuj i zamknij">zamknij</a></p></div>
                 <div class="zawartosc justuj akapit" >
                     Używamy informacji zapisanych za pomocą plików cookies w&nbsp;celu zapewnienia maksymalnej wygody w&nbsp;korzystaniu z&nbsp;aplikacji. Korzystając z&nbsp;witryny wyrażasz zgodę na zapisywanie informacji zawartej w&nbsp;cookies. Jeśli nie wyrażasz zgody, ustawienia dotyczące plików cookies możesz zmienić w&nbsp;swojej przeglądarce.
                 </div>
             </div>
         </div>
    </form>
<?php
    }
    
    
?>

<br>
<ul class="flex-container">
<a href="index.php?id=panele/dodaj/nadgodziny"><li class="flex-item"><span>dodaj nadgodziny</span><div class="zegar"><div class="minutnik"></div><div class="godzinka"></div></div></li></a>
<a href="index.php?id=panele/wykorzystaj/nadgodziny"><li class="flex-item"><span>wykorzystaj nadgodziny</span><div class="zegardom"><div class="mindom"></div><div class="godzdom"></div></div><div class="domek"><div class="daszek"></div><div class="sciany">Home</div></div></li></a>
<a href="index.php?id=panele/moje/nadgodziny"><li class="flex-item"><span>moje nadgodziny</span><div class="glowagodz"><div class="mindom"></div><div class="godzdom"></div></div><div class="ubranie"><div class="cialo"></div></div></li></a>

<a href="index.php?id=panele/dodaj/sluzby"><li class="flex-item"><span>dodaj służby</span><div class="kalendarz"><div class="dni">12</div></div></li></a>
<a href="index.php?id=panele/wykorzystaj/sluzby"><li class="flex-item"><span>wykorzystaj służby</span><div class="minkalendarz"><div class="dni">12</div></div><div class="domek"><div class="daszek"></div><div class="sciany">Home</div></div></li></a>
<a href="index.php?id=panele/moje/sluzby"><li class="flex-item"><span>moje służby</span><div class="glowakal"><div class="dni">12</div></div><div class="ubranie"><div class="cialo"></div></div></li></a>
</ul>
<?php

/*echo "<h1> Szybki przegląd </h1>";
echo "<h2 class=\"podpowiedzi zaokraglij\">Twoje ostatnio dodane nadgodziny, służby oraz statystyki wykorzystania.</h2>";*/
$ostdod=$_SESSION['user']."-ostdod";
if (isset($_COOKIE[$ostdod]) && $_COOKIE[$ostdod]=='ukryj'){
    //tutaj treaz jezeli panele ostatnio dodane sa ukryte
}else{
echo "<div class=\"flex-container\">";
//panel z ostatnio dodanymi nadgodzinami
        echo "<div class=\"panel szescset\">";
            echo "<div class=\"tytul\">";
                echo "<p>ostatnio dodane nadgodziny</p>";
            echo "</div>";
            echo "<div class=\"zawartosc\" >";
                ostatnieNadgodziny();
            echo "</div>";
        echo "</div>";
//panel z ostatnio dodanymi sluzbami
        echo "<div class=\"panel szescset\">";
            echo "<div class=\"tytul\">";
                echo "<p>ostatnio dodane służby</p>";
            echo "</div>";
            
            echo "<div class=\"zawartosc\" >";
                ostatnieSluzby();
                /*for ($i=0; $i<5; $i++){
                echo "<ul><li type=\"1\" class=\"zawartosc blekitne\">Służba dyżurny got - 1 dzień</li></ul>";
                }*/ 
            echo "</div>";  
        echo "</div>";
echo "</div>";
}
//panele ze statystykami nadgodzin
echo "<div class=\"flex-container\">";
//pierwszy
        echo "<div class=\"panel trzysta\">";
            echo "<div class=\"tytul\"><p>uzbierane nadgodziny</p></div>";
            echo "<div class=\"zawartosc wysrodkuj\">";
            sumaNadgodzin(id_zolnierza(), 1);

            echo "</div>";    
            echo "</div>";
//drugi
        echo "<div class=\"panel trzysta\">";
            echo "<div class=\"tytul\"><p>pozostało nadgodzin</p></div>";
            echo "<div class=\"zawartosc wysrodkuj\">";
                echo "<h1>40</h1>";
                echo "godz.: 40.0 | dni: 5";
            echo "</div>";
        echo "</div>";
//trzeci
        echo "<div class=\"panel trzysta\">";
            echo "<div class=\"tytul\"><p>uzbierane służby</p></div>";
            echo "<div class=\"zawartosc wysrodkuj\">";
                sumaSluzb(id_zolnierza(), 1);
            echo "</div>";    
            echo "</div>";
//czwarty
        echo "<div class=\"panel trzysta\">";
            echo "<div class=\"tytul\"><p>pozostało służb</p></div>";
            echo "<div class=\"zawartosc\">";
                echo "przykladowa zawartosc sluzb";
            echo "</div>";
        echo "</div>";      
echo "</div>";

echo "<div class=\"flex-container\">";
//panel z ostatnio dodanymi nadgodzinami
        echo "<div class=\"panel tysiac\">";
            echo "<div class=\"tytul\"><p>złożone wnioski</p></div>";
            echo "<div class=\"zawartosc\">";
                echo "tutaj wyświetlimy wszystkie wnioski";
            echo "</div>";
        echo "</div>";
echo "</div>";


?> 
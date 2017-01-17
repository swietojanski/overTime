<h1> Witaj, to Twój pulpit </h1>
<ul class="flex-container">
<a href="index.php?id=panele/dodaj/nadgodziny"><li class="flex-item"><span>dodaj nadgodziny</span><div class="zegar"><div class="minutnik"></div><div class="godzinka"></div></div></li></a>
<li class="flex-item"><span>wykorzystaj nadgodziny</span><div class="zegardom"><div class="mindom"></div><div class="godzdom"></div></div><div class="domek"><div class="daszek"></div><div class="sciany">Home</div></div></li>
<a href="index.php?id=panele/moje/nadgodziny"><li class="flex-item"><span>moje nadgodziny</span><div class="glowagodz"><div class="mindom"></div><div class="godzdom"></div></div><div class="ubranie"><div class="cialo"></div></div></li></a>
<a href="index.php?id=panele/dodaj/sluzby"><li class="flex-item"><span>dodaj służby</span><div class="kalendarz"><div class="dni">12</div></div></li></a>
<li class="flex-item"><span>wykorzystaj służby</span><div class="minkalendarz"><div class="dni">12</div></div><div class="domek"><div class="daszek"></div><div class="sciany">Home</div></div></li>
<li class="flex-item"><span>moje służby</span><div class="glowakal"><div class="dni">12</div></div><div class="ubranie"><div class="cialo"></div></div></li>
</ul>
<?php

/*echo "<h1> Szybki przegląd </h1>";
echo "<h2 class=\"podpowiedzi zaokraglij\">Twoje ostatnio dodane nadgodziny, służby oraz statystyki wykorzystania.</h2>";*/
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
//panele ze statystykami nadgodzin
echo "<div class=\"flex-container\">";
//pierwszy
        echo "<div class=\"panel trzysta\">";
            echo "<div class=\"tytul\"><p>wykorzystane nadgodziny</p></div>";
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
            echo "<div class=\"tytul\"><p>wykorzystane służby</p></div>";
            echo "<div class=\"zawartosc\">";
                echo "Przykladowa zawartosc nadgodzin";
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


switch ($_SESSION['permissions']) {
case '1';
echo "jestes adminem";
break;
case '2';
echo "jestes dowodca grupy";
break;
case '3';
echo "jestes dowodca eskadry";
break;
case '4';
echo "jestes szefem eskadry";
break;
case '5';
echo "jestes dowodca klucza";
break;
case '6';
echo "jestes zołnierzem";
break;
default;
print("skontaktuj sie z administratorem");
break;
}

?> 
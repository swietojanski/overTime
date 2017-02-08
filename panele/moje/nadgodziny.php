<?php 
//Zamiana przecinka na kropke
//
// $liczba = $_POST['godzina'];
//$liczba = str_replace(",",".",$liczba);
//echo $liczba; 
?>
<h1> Nadgodziny  </h1>
<h2 class="podpowiedzi zaokraglij">Przeglądasz nadgodziny żołnierza.</h2>
<?php
echo "<div class=\"flex-container\">";
        echo "<div class=\"panel siedemset\">";
            echo "<div class=\"tytul\">";
                echo "<p>wszystkie nadgodziny</p>";
            echo "</div>";
            echo "<div class=\"zawartosc\" >";
                mojeNadgodziny($_GET[profil]);
            echo "</div>";   
        echo "</div>";
echo "</div>";

//EDYCJA NADGODZIN



?>
<?php 
//Zamiana przecinka na kropke
//
// $liczba = $_POST['godzina'];
//$liczba = str_replace(",",".",$liczba);
//echo $liczba; 
?>
<h1> Służby </h1>
<h2 class="podpowiedzi zaokraglij">Przeglądasz służby żołnierza.</h2>
<?php
echo "<div class=\"flex-container\">";
        echo "<div class=\"panel siedemset\">";
            echo "<div class=\"tytul\">";
                echo "<p>wszystkie służby</p>";
            echo "</div>";
            echo "<div class=\"zawartosc\" >";
                mojeSluzby($_GET[profil]);
            echo "</div>";   
        echo "</div>";
echo "</div>";

//EDYCJA NADGODZIN



?>
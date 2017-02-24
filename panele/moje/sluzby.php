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
                echo "<p class=\"right mr-5\">";
                    if(!empty($_GET['profil'])){
                        echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=".$_GET['profil']."\">";
                            echo "<img src=\"img/profiles/thumbnail/";echo profilowe($_GET['profil']);                              
                            echo "\" class=\"zaokraglij\" height=\"26px\" title=\"";
                            st_nazwisko_imie($_GET['profil']); echo "\" align=\"absmiddle\">";
                        echo "</a>";
                    }   
                echo "</p>";
            echo "</div>";
            echo "<div class=\"zawartosc\" >";
                mojeSluzby($_GET[profil]);
            echo "</div>";   
        echo "</div>";
echo "</div>";

//EDYCJA NADGODZIN



?>
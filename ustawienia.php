<?php
if($_SESSION['permissions']>0 && $_SESSION['permissions']<10){
echo "<h1> Ustawienia </h1>";
echo "<h2 class=\"podpowiedzi zaokraglij\">Tutaj zmienisz swoje ustawienia oraz hasło do konta.</h2>";
echo "<div class=\"flex-container\">";
    echo "<div class=\"panel\">";
        echo "<div class=\"tytul\">";
            echo "<p>zmiana hasła</p>";
        echo "</div>";
        echo "<div class=\"zawartosc\" >";
        zmienHaslo();
        echo "</div>";
    echo "</div>";
echo "</div>";
echo "<div class=\"flex-container\">";
    echo "<div class=\"panel\">";
        echo "<div class=\"tytul\">";
            echo "<p>dodaj avatar</p>";
        echo "</div>";
        echo "<div class=\"zawartosc\" >";
        zrobAvatar();
        echo "</div>";
    echo "</div>";
echo "</div>";
}  else {
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
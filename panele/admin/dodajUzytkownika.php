<?php
if($_SESSION['permissions']==1){
echo "<h1> Dodaj użytkownika </h1>";
echo "<h2 class=\"podpowiedzi zaokraglij\">Dodając użytkownika pamiętaj o dobrym doborze jego uprawnień.</h2>";
echo "<div class=\"flex-container\">";
    echo "<div class=\"panel\">";
        echo "<div class=\"tytul\">";
            echo "<p>dodawanie użytkownika</p>";
        echo "</div>";
        echo "<div class=\"zawartosc\" >";
               dodajUzytkownika();
        echo "</div>";    
    echo "</div>";
echo "</div>";
}  else {
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
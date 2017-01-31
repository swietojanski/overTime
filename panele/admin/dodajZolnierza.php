<?php if($_SESSION['permissions']==1){ //wpusc jezeli ma prawa admina
$progres=0;

//przypisanie POST z formularza do zmiennych
$stopien = $_POST['stopien'];
$imie = mysql_real_escape_string($_POST['imie']);
$nazwisko = mysql_real_escape_string($_POST['nazwisko']);
$eskadra = $_POST['eskadra'];
$klucz = $_POST['klucz'];


echo "<h1> Dodaj żołnierza </h1>";
echo "<h2 class=\"podpowiedzi zaokraglij\">Po dodaniu żołnierza możesz utworzyć mu konto do zalogowania.</h2>";


echo "<div class=\"flex-container\">";
echo "    <div class=\"panel piecset\">";
echo "        <div class=\"tytul\">";
echo "            <p>dodaj żołnierza</p>";
echo "        </div>";
echo "        <div class=\"zawartosc wysrodkuj\">";

    if (!isset($_POST[krok1]) && empty($_POST[dodaj])){
    echo "            <form name=\"krok1\" method=\"post\">";      
    echo "                <table>";
    echo "                    <thead>";
    echo "                    </thead>";
    echo "                    <tbody>";
    echo "                        <tr class=\"blekitne\">";
    echo "                            <th class=\"right\">stopien</th>";
    echo "                            <td>";
                                       stopnie();
    echo "                            </td>";
    echo "                        </tr>";
    echo "                        <tr class=\"blekitne\">";
    echo "                            <th class=\"right\">imię</th>";
    echo "                            <td><input class=\"fod\" type=\"text\" name=\"imie\" placeholder=\"np. Jan\" required=\"true\"></td>";
    echo "                        </tr>";
    echo "                        <tr class=\"blekitne\">";
    echo "                            <th class=\"right\">nazwisko</th>";    
    echo "                            <td><input class=\"fod\" type=\"text\" name=\"nazwisko\" placeholder=\"np. Kowalski\" required=\"true\"></td>";
    echo "                        </tr>";
    echo "                        <tr class=\"blekitne\">";
    echo "                            <th class=\"right\">eskadra</th>";
    echo "                            <td>";
                                        listaEskadr();
    echo "                            </td>";
    echo "                        </tr>";
    echo "                    </tbody>";
    echo "                </table>";
    echo "                    <input type=\"submit\" name=\"krok1\" class=\"zapisz animacja\" value=\"dalej\" title=\"Krok 2\"/>"; 
    echo "            </form>";
    
    } elseif(isset ($_POST[krok1])) {
    $progres=+50;    
    echo "            <form name=\"dodaj\" method=\"post\">";
    echo "                  <input type=\"hidden\" name=\"stopien\" value=\"$stopien\">";
    echo "                  <input type=\"hidden\" name=\"imie\" value=\"$imie\">";
    echo "                  <input type=\"hidden\" name=\"nazwisko\" value=\"$nazwisko\">";
    echo "                  <input type=\"hidden\" name=\"eskadra\" value=\"$eskadra\">";
    echo "                <table>";
    echo "                    <thead>";
    echo "                    </thead>";
    echo "                    <tbody>";
    echo "                        <tr class=\"blekitne\">";
    echo "                            <th class=\"right\">klucz</th>";
    echo "                            <td>";
                                        listaKluczy($eskadra);
    echo "                            </td>";
    echo "                        </tr>";

    echo "                    </tbody>";
    echo "                </table>";
    echo "                    <input type=\"submit\" name=\"dodaj\" class=\"zapisz animacja\" value=\"zapisz\" title=\"Krok 2\"/>"; 
    echo "            </form>";
    
    }
    
    if(isset ($_POST[dodaj])){
        echo "przeszlo<br>";
        echo intval($_POST['stopien']), $_POST['imie'], $_POST['nazwisko'], intval($_POST['eskadra']), intval($_POST['klucz']);
        
        $progres=+100;
        dodajZolnierza(intval($_POST['stopien']), $_POST['imie'], $_POST['nazwisko'], intval($_POST['eskadra']), intval($_POST['klucz']));
    }

echo "        </div>";
echo "    </div>";
echo "</div>";


echo "<div class=\"flex-container\">";
echo "    <div class=\"panel piecset\">";
echo "        <div class=\"zawartosc wysrodkuj\">";
echo "          <progress value=\"".$progres."\" max=\"100\" style=\"width:100%\"></progress>";
echo "        </div>";
echo "    </div>";
echo "</div>";




//wywołanie funkcji
//dodajZolnierza($skrot, $nazwa);

}  else { //jezeli to nie admin powiadom go o tym
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
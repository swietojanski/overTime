<?php if($_SESSION['permissions']==1){ //wpusc jezeli ma prawa admina
$progres=0;

//przypisanie POST z formularza do zmiennych
$stopien = $_POST['stopien'];
$imie = mysql_real_escape_string($_POST['imie']);
$nazwisko = mysql_real_escape_string($_POST['nazwisko']);
$grupa = (int)$_POST['grupa'];
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

if (!isset($_POST[grupa])){
                
            $esk = mysql_query("SELECT * FROM grupy") or die('Błąd zapytania'); 
            if(mysql_num_rows($esk) > 0) { 
               
    echo "            <form name=\"krok0\" method=\"post\">";      
    echo "                <table>";
    echo "                    <thead>";
    echo "                    </thead>";
    echo "                    <tbody>";
    echo "                        <tr class=\"blekitne\">";
    echo "                            <th class=\"right\">grupa</th>";
    echo "                            <td>";
                                            listaGrup(true);
    echo "                            </td>";
    echo "                        </tr>"; 
 
    echo "                    </tbody>";
    echo "                </table>";
    echo "                    <input type=\"submit\" name=\"krok0\" class=\"zapisz animacja\" value=\"dalej\" title=\"Krok 1\"/>"; 
    echo "            </form>";
    }else{
            echo"Nie dodałeś jeszcze grup, skorzystaj z PA, aby to zrobić lub kliknij <a href=\"index.php?id=panele/admin/dodajGrupe\">tutaj</a>";
    }
}


    if (!isset($_POST[krok1]) && isset($_POST[krok0])){
        $progres=20;
        $esk = mysql_query("SELECT * FROM eskadry") or die('Błąd zapytania'); 
        if(mysql_num_rows($esk) > 0) { 
    echo "            <form name=\"krok1\" method=\"post\">";
    echo "                  <input type=\"hidden\" name=\"grupa\" value=\"$grupa\">";
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
                
                                        
                                        listaEskadr($grupa);
                                        
    echo "                            </td>";
    echo "                        </tr>";
    echo "                        <tr class=\"blekitne\">";
    echo "                            <th class=\"right\"><input type=\"checkbox\" name=\"dcagrupy\" value=\"1\" id=\"dcacheck\"></th>";
    echo "                            <td>";
    echo "                              <label for=\"dcacheck\">dowódca grupy</label>";
    echo "                            </td>";
    echo "                        </tr>";
  
    echo "                    </tbody>";
    echo "                </table>";
    echo "                    <input type=\"submit\" name=\"krok1\" class=\"zapisz animacja\" value=\"dalej\" title=\"Krok 2\"/>"; 
    echo "            </form>";
                }else{
                    echo"Nie dodałeś jeszcze eskadr, skorzystaj z PA, aby to zrobić lub kliknij <a href=\"index.php?id=panele/admin/dodajEskadre\">tutaj</a>";
                }
    
    } elseif(isset ($_POST[krok1])) {
    $progres=70; 
        $esk = mysql_query("SELECT * FROM klucze") or die('Błąd zapytania'); 
        if(mysql_num_rows($esk) > 0) { 
    echo "            <form name=\"dodaj\" method=\"post\">";
    echo "                  <input type=\"hidden\" name=\"stopien\" value=\"$stopien\">";
    echo "                  <input type=\"hidden\" name=\"imie\" value=\"$imie\">";
    echo "                  <input type=\"hidden\" name=\"nazwisko\" value=\"$nazwisko\">";
    echo "                  <input type=\"hidden\" name=\"grupa\" value=\"$grupa\">";
    echo "                  <input type=\"hidden\" name=\"eskadra\" value=\"$eskadra\">";  
    echo "                <table>";
    echo "                    <thead>";
    echo "                    </thead>";
    echo "                    <tbody>";
                                if($_POST[dcagrupy]!=1){
    echo "                        <tr class=\"blekitne\">";
    echo "                            <th class=\"right\">klucz</th>";
    echo "                            <td>";
                                            listaKluczy($eskadra);
    echo "                            </td>";
    echo "                        </tr>";
    echo "                        <tr class=\"blekitne\">";
    echo "                            <th class=\"right\"><input type=\"checkbox\" name=\"dcaeskadry\" value=\"1\" id=\"dcaeskcheck\"></th>";
    echo "                            <td>";
    echo "                              <label for=\"dcaeskcheck\">dowódca eskadry</label>";
    echo "                            </td>";
    echo "                        </tr>";
    echo "                        <tr class=\"blekitne\">";
    echo "                            <th class=\"right\"><input type=\"checkbox\" name=\"szefeskadry\" value=\"1\" id=\"szefcheck\"></th>";
    echo "                            <td>";
    echo "                              <label for=\"szefcheck\">szef eskadry</label>";
    echo "                            </td>";
    echo "                        </tr>";
                                } else {
                                        echo "Wybrałeś opcje <strong>dowódca grupy</strong>.<br>";
                                        echo "Zapisz żołnierza i przypisz go do grupy";
                                }


    echo "                    </tbody>";
    echo "                </table>";
    echo "                    <input type=\"submit\" name=\"dodaj\" class=\"zapisz animacja\" value=\"zapisz\" title=\"Krok 3\"/>"; 
    echo "            </form>";
                }else{
                    echo"Nie dodałeś jeszcze kluczy, skorzystaj z PA, aby to zrobić lub kliknij <a href=\"index.php?id=panele/admin/dodajKlucz\">tutaj</a>";
                }
    
    }

    if(isset ($_POST[dodaj])){   
        $progres=100;
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

<script>
$('#eskadra').attr('required', true);
$('#dcacheck').click(function() {
    $('#eskadra').attr('disabled',this.checked)
    $("#eskadra").val("").change();
    //$( "select#eskadra option:checked" ).text("Wybierz eskadrę").attr('disabled',!this.checked);
});

$('#klucz').attr('required', true);
$('#dcaeskcheck').click(function() {
    $('#klucz').attr('disabled',this.checked)
    $('#szefcheck').attr('disabled',this.checked)
    $("#klucz").val("").change();
    //$( "select#eskadra option:checked" ).text("Wybierz eskadrę").attr('disabled',!this.checked);
});
$('#szefcheck').click(function() {
    $('#klucz').attr('disabled',this.checked)
    $('#dcaeskcheck').attr('disabled',this.checked)
    $("#klucz").val("").change();
    //$( "select#eskadra option:checked" ).text("Wybierz eskadrę").attr('disabled',!this.checked);
});
</script>
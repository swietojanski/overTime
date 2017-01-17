<?php
function stopnie() {
$stopnie = mysql_query("SELECT stopnie.idStopien, stopnie.Skrot  FROM stopnie") 
or die('Błąd zapytania'); 
if(mysql_num_rows($stopnie) > 0) { 
    /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
    echo "<select name=\"stopnie\">"; 
    while($r = mysql_fetch_object($stopnie)) {  
         
        echo "<option value=\"$r->idStopien\">".$r->Skrot."</option>";

    } 
    echo "</select>"; 
}
}
?>

<form>
<label>Imię </label><input name="imie" type="text" placeholder="Krzysztof" />
<label>Nazwisko </label><input name="imie" type="text" placeholder="Świętojański" />
<label>Stopień 
<?php   stopnie()?>
</label>
<label>Telefon </label><input type="tel" pattern="[0-9]{9}" placeholder="603 403 395" />
</form>


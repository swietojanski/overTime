<?php if($_SESSION['permissions']==1){ //wpusc jezeli ma prawa admina?>
<h1> Dodaj grupę </h1>
<h2 class="podpowiedzi zaokraglij">Po dodaniu grupy możesz dodać do niej eskadry</h2>

<div class="flex-container">
    <div class="panel piecset">
        <div class="tytul">
            <p>dodaj grupę</p>
        </div>
        <div class="zawartosc wysrodkuj">
            <form name="dodajEskadre" method="post">        
                <table>
                    <thead>

                    </thead>
                    <tbody>
                        <tr class="blekitne">
                            <th class="right">skrót</th>
                            <td><input class="pl-5" type="text" name="skrot" placeholder="np. GO" required="true" size="35"></td>
                        </tr>
                        <tr class="blekitne">
                            <th class="right nowrap">pełna nazwa</th>    
                            <td><input class="pl-5" type="text" name="nazwa" placeholder="np. Grupa Obsługi" required="true" size="35"></td>
                        </tr>
                    </tbody>
                </table>
                    <input type="submit" name="dodaj_grupe" class="zapisz animacja" value="zapisz" title="Dodaj grupę"/> 
            </form>
        </div>  
    </div>
</div>
<?php


//przypisanie POST z formularza do zmiennych
$skrot = mysql_real_escape_string($_POST[skrot]);
$nazwa = mysql_real_escape_string($_POST[nazwa]);
//wywołanie funkcji
dodajGrupe($skrot, $nazwa);

}  else { //jezeli to nie admin powiadom go o tym
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
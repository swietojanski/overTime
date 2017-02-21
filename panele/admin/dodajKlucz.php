<?php if($_SESSION['permissions']==1){ //wpusc jezeli ma prawa admina
    
//przypisanie POST z formularza do zmiennych
$skrot = mysql_real_escape_string($_POST[skrot]);
$nazwa = mysql_real_escape_string($_POST[nazwa]);
$grupa = mysql_real_escape_string($_POST[grupa]);
$eskadra = mysql_real_escape_string($_POST[eskadra]);
//wywołanie funkcji
?>
<h1> Dodaj klucz </h1>
<h2 class="podpowiedzi zaokraglij">Po dodaniu klucza możesz przypisywać żołnierzy</h2>

<div class="flex-container">
    <div class="panel piecset">
        <div class="tytul">
            <p>dodaj klucz</p>
        </div>
        <div class="zawartosc wysrodkuj">
            <form name="dodajKlucz" method="post">        
                <table>
                    <thead>

                    </thead>
                    <tbody>
                        <?php if(!isset($_POST[grupa])) { ?>
                        <tr class="blekitne">
                            <th class="right">grupa</th>
                            <td><?php listaGrup(true);?></td>
                        </tr>
                        <?php } ?>
                        <?php if(isset($_POST[grupa])) { ?>
                        <tr class="blekitne">
                            <th class="right">eskadra</th>
                            <td><?php listaEskadr($grupa) ?></td>
                        </tr>
                        <tr class="blekitne">
                            <th class="right">skrót</th>
                            <td><input class="pl-5 fod" type="text" name="skrot" placeholder="np. KS" required="true"></td>
                        </tr>
                        <tr class="blekitne">
                            <th class="right nowrap">pełna nazwa</th>    
                            <td><input class="pl-5 fod" type="text" name="nazwa" placeholder="np. Klucz Specjalistyczny" required="true"></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php if(isset($_POST[grupa])) { ?>
                    <input type="hidden" value="<?php echo"$grupa"; ?>" name="grupa">
                    <input type="submit" name="dodajKlucz" class="zapisz animacja" value="zapisz" title="Dodaj klucz"/> 
                <?php }else{ ?>
                    <input type="submit" name="pobierz" class="zapisz animacja" value="dalej" title="Przejdź dalej"/>
                <?php } ?>
            </form>
        </div>  
    </div>
</div>
<?php



if(!empty($eskadra)&&!empty($skrot)&&!empty($nazwa)){
    dodajKlucz($skrot, $nazwa, $eskadra);
}

}  else { //jezeli to nie admin powiadom go o tym
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
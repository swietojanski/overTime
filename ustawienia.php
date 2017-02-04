<?php
if($_SESSION['permissions']>0 && $_SESSION['permissions']<10){

$nadco=$_SESSION['user']."-nadgodziny";    
if(!empty($_POST['nadgodzin'])){
    $ciastko_godzin=$_POST['nadgodzin'];
    setcookie($nadco, $ciastko_godzin, time() + 2 * 356 * 86400); //nazwa ciastka, wartosc ciastka, czas trwania ciastka
}
$sluco=$_SESSION['user']."-sluzby";
if(!empty($_POST['sluzb'])){
    $ciastko_sluzb=$_POST['sluzb'];
    setcookie($sluco, $ciastko_sluzb, time() + 2 * 356 * 86400); // jezeli czas ciastka ustawimy na zero skasuje sie z zamknieciem przegladarki
}

$ostco=$_SESSION['user']."-ostdod";
if(!empty($_POST['ukryj'])){
    $ciastko_ostdod=$_POST['ukryj'];
    setcookie($ostco, $ciastko_ostdod, time() + 2 * 356 * 86400); // jezeli czas ciastka ustawimy na zero skasuje sie z zamknieciem przegladarki
}

?>
<h1> Ustawienia </h1>
<h2 class="podpowiedzi zaokraglij">Tutaj zmienisz swoje ustawienia oraz hasło do konta.</h2>
<div class="flex-container">
    <div class="panel">
        <div class="tytul">
            <p>zmiana hasła</p>
        </div>
        <div class="zawartosc" >
        <?php zmienHaslo(); ?>
        </div>
    </div>

    <div class="panel dwiescie">
        <div class="tytul mb-10">
            <p class="mr-10">dodaj avatar</p>
        </div>
        <div class="zawartosc wysrodkuj" >
        <?php zrobAvatar(); ?>
        </div>
    </div>
</div>
<div class="flex-container">
    <div class="panel szescset">
        <div class="tytul">
            <p>dodatkowe ustawienia</p>
        </div>
        <div class="zawartosc wysrodkuj" >

            <form class="nadgodzinki" name="ustawienia" method="post">        
                <table id="tabela">
                    <thead>
                        <tr>
                            <th>Opis</th>
                            <th>Decyzja</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="blekitne">
                            <td class="left">Ilość wpisów na stronie moje nadgodziny</td>
                            <td>
                                <input size="5" type="number" min="10" max="100" step="10" placeholder="10" name="nadgodzin" <?php if(isset($_COOKIE[$nadco]) && empty($_POST['nadgodzin'])){echo "value=\"".$_COOKIE[$nadco]."\"";} elseif(isset ($_POST['nadgodzin'])) {echo "value=\"$ciastko_godzin\"";} ?>>
                            </td>
                        </tr>
                        <tr class="blekitne">
                            <td class="left">Ilość wpisów na stronie moje służby</td>
                            <td>
                                <input type="number" min="10" max="100" step="10" placeholder="10" name="sluzb" <?php if(isset($_COOKIE[$sluco]) && empty($_POST['sluzb'])){echo "value=\"".$_COOKIE[$sluco]."\"";} elseif(isset ($_POST['sluzb'])) {echo "value=\"$ciastko_sluzb\"";} ?>>
                            </td>
                        </tr>
                        <tr class="blekitne">
                            <td class="left">Ukryć panel ostatnio dodane?</td>
                            <td>
                                <input type="radio" value="ukryj" name="ukryj" title="Ukrywaj" class="iledni" id="tak" <?php if(isset($_COOKIE[$ostco]) && $_COOKIE[$ostco]=='ukryj' && !isset ($_POST['ukryj'])){echo "checked";} elseif(isset ($_POST['ukryj']) && $_POST['ukryj']=='ukryj') {echo "checked";} ?>><label for="tak" title="Tak">T</label>
                                <input type="radio" value="1" name="ukryj" title="Pokazuj" class="iledni" id="nie" <?php if(isset($_COOKIE[$ostco]) && $_COOKIE[$ostco]=='1' && !isset ($_POST['ukryj'])){echo "checked";} elseif(isset ($_POST['ukryj']) && $_POST['ukryj']=='1') {echo "checked";} ?>><label for="nie" title="Nie">N</label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                    <input type="submit" name="dodajnadgodziny" class="zapisz animacja" value="zapisz" title="Zapisz do bazy"/> 
            </form>
            
        </div>
    </div>
</div>
<?php
}  else {
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
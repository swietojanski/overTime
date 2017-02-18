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

$wpisuzy=$_SESSION['user']."-wpisuzy";
if(!empty($_POST['wpisuzy'])){
    $ciastko_wpisuzy=$_POST['wpisuzy'];
    setcookie($wpisuzy, $ciastko_wpisuzy, time() + 2 * 356 * 86400); // jezeli czas ciastka ustawimy na zero skasuje sie z zamknieciem przegladarki
}

$wpiszol=$_SESSION['user']."-wpiszol";
if(!empty($_POST['wpiszol'])){
    $ciastko_wpiszol=$_POST['wpiszol'];
    setcookie($wpiszol, $ciastko_wpiszol, time() + 2 * 356 * 86400); // jezeli czas ciastka ustawimy na zero skasuje sie z zamknieciem przegladarki
}

$wnioski=$_SESSION['user']."-u-wnioski";
if(!empty($_POST['u-wnioski'])){
    $ciastko_wnioski=$_POST['u-wnioski'];
    setcookie($wnioski, $ciastko_wnioski, time() + 2 * 356 * 86400); // jezeli czas ciastka ustawimy na zero skasuje sie z zamknieciem przegladarki
}

switch ($_SESSION['permissions']) {    
    case '1';
        $uprawnienie = "admin";
    break;
    case '2';
        $uprawnienie = "dowódca grupy";
    break;
    case '3';
        $uprawnienie = "dowódca eskadry";
    break;
    case '4';
        $uprawnienie = "dowódca eskadry";
    break;
    case '5';
        $uprawnienie = "dowódca klucza";
    break;
    case '6';
        $uprawnienie = "żołnierz";
    break;
    default;
        print("skontaktuj sie z administratorem");
    break;
}

?>
<h1> Ustawienia </h1>
<h2 class="podpowiedzi zaokraglij">Tutaj zmienisz swoje ustawienia oraz hasło do konta. Twój poziom uprawnienia to: <?php echo $uprawnienie ?></h2>
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
                        <?php if($_SESSION['permissions']<6){?>
                        <tr class="blekitne">
                            <td class="left">Ilość wniosków na stronie alertów</td>
                            <td>
                                <input type="number" min="10" max="100" step="10" placeholder="10" name="u-wnioski" <?php if(isset($_COOKIE[$wnioski]) && empty($_POST['sluzb'])){echo "value=\"".$_COOKIE[$wnioski]."\"";} elseif(isset ($_POST['u-wnioski'])) {echo "value=\"$ciastko_wnioski\"";} ?>>
                            </td>
                        </tr>
                        <?php }?>
                        <tr class="blekitne">
                            <td class="left">Ukryć panel ostatnio dodane?</td>
                            <td>
                                <input type="radio" value="ukryj" name="ukryj" title="Ukrywaj" class="iledni" id="tak" <?php if(isset($_COOKIE[$ostco]) && $_COOKIE[$ostco]=='ukryj' && !isset ($_POST['ukryj'])){echo "checked";} elseif(isset ($_POST['ukryj']) && $_POST['ukryj']=='ukryj') {echo "checked";} ?>><label for="tak" title="Tak">T</label>
                                <input type="radio" value="1" name="ukryj" title="Pokazuj" class="iledni" id="nie" <?php if(isset($_COOKIE[$ostco]) && $_COOKIE[$ostco]=='1' && !isset ($_POST['ukryj'])){echo "checked";} elseif(isset ($_POST['ukryj']) && $_POST['ukryj']=='1') {echo "checked";} ?>><label for="nie" title="Nie">N</label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                    <input type="submit" name="dodajnadgodziny" class="zapisz animacja" value="zapisz" title="Zapisz ustawienia"/> 
            </form>
            
        </div>
    </div>
</div>
<?php if($_SESSION['permissions']==1){?>
<div class="flex-container">
    <div class="panel szescset">
        <div class="tytul">
            <p>ustawienia admina</p>
        </div>
        <div class="zawartosc wysrodkuj" >

            <form class="nadgodzinki" name="ustawienia_admina" method="post">        
                <table id="tabela">
                    <thead>
                        <tr>
                            <th>Opis</th>
                            <th>Decyzja</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="blekitne">
                            <td class="left">Ilość wpisów na stronie użytkownicy</td>
                            <td>
                                <input size="5" type="number" min="10" max="100" step="10" placeholder="10" name="wpisuzy" <?php if(isset($_COOKIE[$wpisuzy]) && empty($_POST['wpisuzy'])){echo "value=\"".$_COOKIE[$wpisuzy]."\"";} elseif(isset ($_POST['wpisuzy'])) {echo "value=\"$ciastko_wpisuzy\"";} ?>>
                            </td>
                        </tr>
                        <tr class="blekitne">
                            <td class="left">Ilość wpisów na stronie żołnierze</td>
                            <td>
                                <input type="number" min="10" max="100" step="10" placeholder="10" name="wpiszol" <?php if(isset($_COOKIE[$wpiszol]) && empty($_POST['wpiszol'])){echo "value=\"".$_COOKIE[$wpiszol]."\"";} elseif(isset ($_POST['wpiszol'])) {echo "value=\"$ciastko_wpiszol\"";} ?>>
                            </td>
                        </tr>
                    </tbody>
                </table>
                    <input type="submit" name="ustawienia_admina" class="zapisz animacja" value="zapisz" title="Zapisz do ciasteczka"/> 
            </form>
            
        </div>
    </div>
</div>
<?php }?>
<?php
}  else {
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
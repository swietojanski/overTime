<?php
if($_SESSION['permissions']>0 && $_SESSION['permissions']<10){
    
if(!empty($_POST['nadgodzin'])){
    $ciastko_godzin=$_POST['nadgodzin'];
    setcookie('wpisow_godzin', $ciastko_godzin);
}
if(!empty($_POST['sluzb'])){
    $ciastko_godzin=$_POST['sluzb'];
    setcookie('wpisow_sluzb', $ciastko_godzin);
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
                                <input size="5" type="number" min="10" max="100" step="10" name="nadgodzin">
                            </td>
                        </tr>
                        <tr class="blekitne">
                            <td class="left">Ilość wpisów na stronie moje służby</td>
                            <td>
                                <input type="number" min="10" max="100" step="10" name="sluzb">
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

echo "Ciastko godzin: ".$_COOKIE['wpisow_godzin'];
echo "<br>Ciastko sluzb: ".$_COOKIE['wpisow_sluzb'];
echo "<br>Post: ".$ciastko_godzin;
}  else {
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
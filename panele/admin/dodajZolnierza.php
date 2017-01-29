<?php if($_SESSION['permissions']==1){ //wpusc jezeli ma prawa admina?>
<h1> Dodaj żołnierza </h1>
<h2 class="podpowiedzi zaokraglij">Po dodaniu żołnierza możesz utworzyć mu konto do zalogowania.</h2>

<div class="flex-container">
    <div class="panel piecset">
        <div class="tytul">
            <p>dodaj żołnierza</p>
        </div>
        <div class="zawartosc wysrodkuj">
            <form name="dodajZolnierza" method="post">        
                <table>
                    <thead>

                    </thead>
                    <tbody>
                        <tr class="blekitne">
                            <th class="right">stopien</th>
                            <td><?php stopnie(); ?></td>
                        </tr>
                        <tr class="blekitne">
                            <th class="right">imię</th>
                            <td><input class="pl-5" type="text" name="skrot" placeholder="np. 1. EO" required="true" size="38"></td>
                        </tr>
                        <tr class="blekitne">
                            <th class="right">nazwisko</th>    
                            <td><input class="pl-5" type="text" name="nazwa" placeholder="np. Pierwsza Eskadra Obsługi" required="true" size="38"></td>
                        </tr>
                        <tr class="blekitne">
                            <th class="right">eskadra</th>    
                            <td><input class="pl-5" type="text" name="nazwa" placeholder="np. Pierwsza Eskadra Obsługi" required="true" size="38"></td>
                        </tr>
                        <tr class="blekitne">
                            <th class="right">klucz</th>    
                            <td><input class="pl-5" type="text" name="nazwa" placeholder="np. Pierwsza Eskadra Obsługi" required="true" size="38"></td>
                        </tr>
                    </tbody>
                </table>
                    <input type="submit" name="dodajEskadre" class="zapisz animacja" value="zapisz" title="Dodaj eskadrę"/> 
            </form>
        </div>  
    </div>
</div>
<?php


//przypisanie POST z formularza do zmiennych
$skrot = mysql_real_escape_string($_POST[skrot]);
$nazwa = mysql_real_escape_string($_POST[nazwa]);
//wywołanie funkcji
//dodajZolnierza($skrot, $nazwa);

}  else { //jezeli to nie admin powiadom go o tym
    echo "Czego tutaj szukasz? Nie masz wystarczających uprawnień!";
}
?>
<?php

/* 
 * Funkcja wykorzystujaca sluzby
 * Sluzby do skladania wniosow
 * i pozniejsza akceptacje ich.
 */


function wykorzystaj_sluzby($czyje_id, $data, $godzina){
        //zmienne pobraane z formularza


$godzina = str_replace(",",".",$godzina); //zamiana przecinkana kropke w godzinie
$liczenie=count($data); //zliczenie ilosci wystapien pola data input


if(!empty($data) && !empty($godzina))//sprawdzamy czy pole data nie jest puste
{
    
    if(isset($czyje_id)) { //&& $kogo != id_zolnierza() drugi warunek ale nie pamietam po co go dalem
            //zmienna pomocnicza do wyswietlania nadgodzin uzytkownika
        $czyje_id;
    }elseif(empty ($czyje_id)){
        $kogo = id_zolnierza();
            //zmienna pomocnicza do wyswietlania nadgodzin uzytkownika
        $czyje_id = $kogo;
    }
    $kto_dodal = $_SESSION['user']; //wyciagniecie z sesji nazwy uzytkownika
    echo "<div class=\"flex-container\">";
        echo "<div class=\"panel szescset\">";
            echo "<div class=\"tytul\">";
                echo "<p>komunikat</p>";
            echo "</div>";
            
        for($a=0;$a<$liczenie;$a++){
            $godzinaq = $godzina[$a]*60; //mnozymy podana ilosc godzin przez 60 minut i zapisujemy jako inty do bazy w postaci minut
            $nowy_wniosek=$nowy_wniosek+$godzinaq;
            //sprawdzenie czy zolnierz ma wystarczajacą ilość nadgodzin zapytanie do stwrzonego widoku
        }
            $pozostalo = mysql_query("SELECT sum(pozostalo) as pozostalo FROM v_zestawienie_sluzb where idZolnierza = '$czyje_id'");
            $r = mysql_fetch_object($pozostalo);
            $nakoncie = $r->pozostalo;
            $wnioski = mysql_query("SELECT sum(ile) as wnioski FROM wnioski WHERE kogo='$czyje_id' and za_co='2';");
            $re = mysql_fetch_object($wnioski);
            $oczekujace = $re->wnioski;
            //$wystapien = (int)mysql_num_rows($sprawdzenie); //zliczenie ilosci wystapien zapytania, powinno dac zero jezeli daty nie ma
            echo "Oczekujące: $oczekujace<br>";
            (int)$zdolny=$nakoncie-$nowy_wniosek-$oczekujace;
            echo "Do wykorzystania: ".(($nakoncie-$oczekujace)/60)."<br>";
            
            
            if($zdolny>=0){
                    for($a=0;$a<$liczenie;$a++)
        {
            //$data = "12-22-2009';
            $dataq = explode("-", $data[$a]);
            $dataq = $dataq[2]."-".$dataq[1]."-".$dataq[0];
            $godzinaq = $godzina[$a]*60; //mnozymy podana ilosc godzin przez 60 minut i zapisujemy jako inty do bazy w postaci minut
            
            //sprawdzenie czy zolnierz juz ma wypisane wniosek na ta date
            $spr_wykorzystanych = mysql_query("
                                        select czyje_id, kiedy from (SELECT czyje_id, wykorzystane_nadgodziny.kiedy FROM wykorzystane_nadgodziny left join nadgodziny using (idNadgodziny) left join wnioski on czyje_id=kogo
                                        union all
                                        SELECT kto_mial as czyje_id, wykorzystane_sluzby.kiedy FROM wykorzystane_sluzby left join sluzby using (idSluzby) left join wnioski on kto_mial=kogo) as sprawdzenie
                                        WHERE czyje_id='$czyje_id' AND kiedy LIKE '".$dataq."'"
                                      );
            $spr_wnioskow = mysql_query("SELECT * FROM wnioski where kogo='$czyje_id' and wolne like '".$dataq."'");
            $wystapien = ((int)mysql_num_rows($spr_wykorzystanych))+((int)mysql_num_rows($spr_wnioskow)); //zliczenie ilosci wystapien zapytania, powinno dac zero jezeli daty nie ma
                        
            if ($wystapien == 0){ //jezeli daty nie ma w bazie to ja doda
                $zapytanie = "INSERT INTO `wnioski` (`kogo`, `wolne`, `ile`,`kiedy_zlozyl`,`za_co`) VALUES ( '$czyje_id', '$dataq', '$godzinaq', NOW(), '2')";       
                $wykonaj = mysql_query($zapytanie); 
                echo "<div class=\"zawartosc blekitne\" >";
                echo("W dniu ".$data[$a]." chcesz ".$godzina[$a]." godz. wolnego.<br>");
                echo "</div>";  
            }elseif ($wystapien > 0){ //jezeli data juz istnieje w bazie wyrzuci komunikat o jej istnieniu
                echo "<div class=\"zawartosc blekitne\" >";
                echo "<strong>Ta data już istnieje: </strong>";
                echo("na dzień ".$data[$a]." dodałeś już wniosek.<br>");
                echo "</div>";   
            }  
        }
            }else{
                echo "<div class=\"zawartosc blekitne\" >";
                echo "Nie możesz złożyć wniosku. Brakuje Ci ".(-1*$zdolny/60)." godz.<br>Dodaj najpierw nadgodziny.";
                echo "</div>";  
            }

    

    echo "</div>";
}
}


?>

<?php 
//Zamiana przecinka na kropke
//
// $liczba = $_POST["godzina'];
//$liczba = str_replace(",",".",$liczba);
//echo $liczba; 
?>
<h1> Składanie wniosku: wolne za służby </h1>
<h2 class="podpowiedzi zaokraglij">Skorzystaj z formularza i wykorzystaj czas ponadnormatywny za pełnione słuzby.</h2>

<div class="flex-container">
    <div class="panel szescset">
        <div class="tytul">
            <p>propozycja</p>
        </div>
        <div class="zawartosc wysrodkuj">
            <form class="nadgodzinki" name="nadgodzinki" method="post">        
                <table id="tabela">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Data</th>
                            <th>Ile godzin</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="blekitne">
                            <td>1</td>
                            <td><input type="text" name="data[]" class="datanadgodzin" placeholder="20-05-2016" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-[0-9]{4}" required="true" size="19"></td>
                            <td><input type="number" name="godzina[]" class="ggodzin" placeholder="2.5" pattern="((\d{1,2}\.[5])|(\d{1,2}))" required="true" size="19" id="godzina-1" min="1" max="8" step="0.5"></td>
                        </tr>
                    </tbody>
                </table>
                    <input type="submit" name="dodajnadgodziny" class="zapisz animacja" value="złóż" title="Zapisz do bazy"/> 
                    <input type="button" id="dodajWiersz" class="zapisz animacja" value="więcej"/>  
            </form>
        </div>  
    </div>
</div>
<?php
wykorzystaj_sluzby($_GET['profil'], $_POST['data'], $_POST['godzina']);
    //dodajNadgodziny();
    
    echo "<div id=\"dialog\" title=\"Ile godzin?\">";

    //wypisanie przyciskow w okienku typu dialog jquery
    for ($i=1;$i<9;$i++){
    
        echo "<button>".$i."</button>";
        
    }

echo "</div>";
//koniec ukrytego okienka

/*
foreach($data as $idkiedy) {
	echo '<input type="text" name="data[]" value="' . $idkiedy . '" />' . PHP_EOL;
}
 */
?>
<script>
//kalendarzyk po kliknieciu w input
 $( ".datanadgodzin" ).datepicker({dateFormat: 'dd-mm-yy'});//tutaj jest datapicker, ktory odpowiada za kalendarz, dziala tylko w pierwszym inpucie
    var delete_handler = function (e) {
e.preventDefault();
        //znajdz najblizszy wiersz bedacy elementem nadrzednym dla linka usuwajacego ten wiersz
        //i wykonaj animacje
        $(this).closest('tr').animate({'backgroundColor':'#C40000','color':'#fff'},300,function(){
            //usun dany wiersz
            $(this).remove();
            //aktualizuj numery pozostalych wierszy
            //dzieki temu gdy usuniemy wiersz w srodku tabeli
            //to nie bedzie istniala dziura w numeracji wierszy
            $('#tabela > tbody > tr').each(function(i) {
                //wpisz nowy numer wewnatrz pierwszej komorki danego wiersza
                $(this).find('td:first-child').text(i+1);
            });
        });
}
$(document).ready(function() {
    //funkcja odczytujaca klikniecie w element o id: dodajWiersz
    //i wykonujaca akcje dodawania nowego wiersza do tabeli
    $('#dodajWiersz').click(function() {
    
        //policz ile jest wierszy w tabeli
        var liczba = $('#tabela tr').length;
 
        //pierwsza kom�rka
        var f1  = '<td><input type="text" name="data[]" class="datanadgodzin" placeholder="20-05-2016" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-[0-9]{4}" required="true" size="19"></td>';
 
        //druga kom�rka
        var f2  = '<td><input type="number" name="godzina[] '+liczba+'" class="ggodzin" placeholder="2.5" required="true" size="19" id="godzina-'+liczba+'" min="1" max="8" step="0.5"></td>';
 
        //trzecia kom�rka
        var f3  = '<td><a class="delete plr-5" href="#">Usuń</a></td>';
        //trzecia kom�rka
        

        //w tej zmiennej definiujemy nowy wiersz w tabeli
        var row = '<tr class="blekitne"><td>'+liczba+'</td>'+f1+f2+f3+'</tr>';
 
        //dolacz nowy wiersz na koncu tabeli
        $('#tabela').find('tbody').append(row);
	var daterow =  $('#tabela').find('tbody').find('.datanadgodzin').last()
	daterow.datepicker({dateFormat: 'dd-mm-yy'});
	daterow.parent().parent().find('.delete').first().on('click',delete_handler);
        //usuwamy klase: none z wiersza oraz animujemy efekt dodawania wiersza
        $('tr.none').removeClass('none').animate({'backgroundColor':'#336666','color':'#fff'},300,function(){
           $(this).animate({'backgroundColor':'#59B2B2','color':'#fff'},300);
        });
    });

    //funkcja odczytujaca klikniecie w element o klasie: delete
    //i wykonujaca akcje usuwania danego wiersza z tabeli
    //oraz dokonuje przeliczenia numerów wierszy w tabeli
    $('.delete').on('click',function() {
        //znajdz najblizszy wiersz bedacy elementem nadrzednym dla linka usuwajacego ten wiersz
        //i wykonaj animacje
        $(this).closest('tr').animate({'backgroundColor':'#C40000','color':'#fff'},300,function(){
 
            //usun dany wiersz
            $(this).remove();

        });
    });
});

( function( factory ) {
	if ( typeof define === "function" && define.amd ) {

		// AMD. Register as an anonymous module.
		define( [ "../widgets/datepicker" ], factory );
	} else {

		// Browser globals
		factory( jQuery.datepicker );
	}
}( function( datepicker ) {

datepicker.regional.pl = {
	closeText: "Zamknij",
	prevText: "&#x3C;Poprzedni",
	nextText: "Następny&#x3E;",
	currentText: "Dziś",
	monthNames: [ "Styczeń","Luty","Marzec","Kwiecień","Maj","Czerwiec",
	"Lipiec","Sierpień","Wrzesień","Październik","Listopad","Grudzień" ],
	monthNamesShort: [ "Sty","Lu","Mar","Kw","Maj","Cze",
	"Lip","Sie","Wrz","Pa","Lis","Gru" ],
	dayNames: [ "Niedziela","Poniedziałek","Wtorek","Środa","Czwartek","Piątek","Sobota" ],
	dayNamesShort: [ "Nie","Pn","Wt","Śr","Czw","Pt","So" ],
	dayNamesMin: [ "N","Pn","Wt","Śr","Cz","Pt","So" ],
	weekHeader: "Tydz",
	dateFormat: "dd.mm.yy",
	firstDay: 1,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: "" };
datepicker.setDefaults( datepicker.regional.pl );

return datepicker.regional.pl;

} ) );

</script>
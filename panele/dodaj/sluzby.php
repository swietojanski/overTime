<?php 
//Zamiana przecinka na kropke
//
// $liczba = $_POST['godzina'];
//$liczba = str_replace(",",".",$liczba);
//echo $liczba; 
?>
<h1> Dodaj sluzby </h1>
<h2 class="podpowiedzi zaokraglij">Skorzystaj z formularza i dodaj sluzby.</h2>

<div class="flex-container">
    <div class="panel szescset">
        <div class="tytul">
            <p>nadgodzinki</p>
        </div>
        <div class="zawartosc wysrodkuj">
            <form class="nadgodzinki" name="nadgodzinki" method="post">        
                <table id="tabela">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Data</th>
                            <th>Ilosc dni</th>
                            <th>Służba</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="blekitne">
                            <td>1</td>
                            <td><input type="text" name="data[]" class="datanadgodzin" placeholder="20-05-2016" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-[0-9]{4}" required="true" size="19"></td>
                            <td>
                                <input type="radio" value="480" name="iledni" class="iledni" placeholder="1" required="true" id="dni1"><label for="dni1">1</label>
                                <input type="radio" value="960" name="iledni" class="iledni" placeholder="1" required="true" id="dni2"><label for="dni2">2</label>
                            </td>
                            <td>
                                <?php //listaRodzajow()?> 
                            </td>
                        </tr>
                    </tbody>
                </table>
                    <input type="submit" name="dodajnadgodziny" class="zapisz animacja" value="zapisz" title="Zapisz do bazy"/> 
                    <input type="button" id="dodajWiersz" class="zapisz animacja" value="więcej"/>  
            </form>
        </div>  
    </div>
</div>
<?php
    //dodajSluzby();

/*
foreach($data as $idkiedy) {
	echo '<input type="text" name="data[]" value="' . $idkiedy . '" />' . PHP_EOL;
}
 */


//sprawdzamy czy podana data do sobota, niedziela lub piatek
for ($j=1;$j<13;$j++) { 
    for ($i=1;$i<32;$i++) { 
    $mk = mktime(0, 0, 0, $j, $i, 2016); 
    $datex = date ("l", $mk); 
    $daterest = date ("j-n-Y", $mk); 
        if ($datex == "Sunday" or $datex == "Saturday" or $datex == "Friday") { 
        echo $datex." - - - ".$daterest."<br>"; 
        } 
    } 
} 

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
        var f2  = '<td><input type="text" name="godzina[]" class="ggodzin" placeholder="2.5" required="true" size="19" id="godzina-'+liczba+'"></td>';
 
        //trzecia kom�rka
        var f3  = '<td><?php listaPowodow()?></td>';
        //trzecia kom�rka
        
        var f4  = '<td><a class="delete plr-5" href="#" title="Usuń wiersz">Usuń</a></td>';
        //w tej zmiennej definiujemy nowy wiersz w tabeli
        var row = '<tr class="blekitne"><td>'+liczba+'</td>'+f1+f2+f3+f4+'</tr>';
 
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
 
            //aktualizuj numery pozostalych wierszy
            //dzieki temu gdy usuniemy wiersz w srodku tabeli 
            //to nie bedzie istniala dziura w numeracji wierszy
            /*$('#tabela > tbody > tr').each(function(i) {
                //wpisz nowy numer wewnatrz pierwszej komórki danego wiersza
                $(this).find('td:first-child').text(i+1);
            });*/
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
	prevText: "Poprzedni",
	nextText: "Następny",
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
<h1> Dodaj nadgodziny </h1>
<h2 class="podpowiedzi zaokraglij">Skorzystaj z formularza i dodaj czas ponadnormatywny.</h2>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<div class="panel">
   <div class="tytul">
      <p class="tytul">nadgodzinki</p>
   </div>
   <div class="zawartosc" >
     <form class="nadgodzinki" name="nadgodzinki" method="post">        
		      <table id="tabela">
	<thead>
		<tr>
			<th>Lp.</th>
			<th>Data</th>
			<th>Ilosc godzin</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<tr id="wiersz-1">
			<td>1</td>
			<td><input type="text" name="data[]" class="datanadgodzin" placeholder="e.g. 20-05-2016"></td>
			<td><input type="text" name="godzina[]" class="ggodzin" placeholder="e.g. 2.5"></td>

		</tr>
	</tbody>
</table>
               <input type="submit" name="dodajnadgodziny" class="zapisz animacja" value="zapisz"/ title="Zapisz do bazy"> 
               <input type="button" id="dodajWiersz" name="data0" class="zapisz animacja" value="dodaj" title="Dodaj wiersz"/> 
             
     </form>
   </div>
</div>

<script type="text/javascript">
 $( ".datanadgodzin" ).datepicker({dateFormat: 'dd-mm-yy'});//tutaj jest datapicker, ktory odpowiada za kalendarz, dziala tylko w pierwszym inpucie
$(document).ready(function() {
    //funkcja odczytujaca klikniecie w element o id: dodajWiersz
    //i wykonujaca akcje dodawania nowego wiersza do tabeli
	
    $('#dodajWiersz').click(function() {
    
        //policz ile jest wierszy w tabeli
        var liczba = $('#tabela tr').length;
 
        //pierwsza komórka
        var f1  = '<td><input type="text" name="data[]" class="datanadgodzin" placeholder="e.g. 20-05-2016"></td>';
 
        //druga komórka
        var f2  = '<td><input type="text" name="godzina[]" class="ggodzin" placeholder="e.g. 2.5"></td>';
 
        //trzecia komórka
        var f3  = '<td><a class="button delete animacja" href="#" title="Usun wiersz">-</a></td>';
 
        //w tej zmiennej definiujemy nowy wiersz w tabeli
        var row = '<tr class="none" id="wiersz-'+liczba+'"><td>'+liczba+'</td>'+f1+f2+f3+'</tr>';
 
        //dolacz nowy wiersz na koncu tabeli
        $('#tabela').find('tbody').append(row);
 
        //usuwamy klase: none z wiersza oraz animujemy efekt dodawania wiersza
        $('tr.none').removeClass('none').animate({'backgroundColor':'#336666','color':'#fff'},300,function(){
           $(this).animate({'backgroundColor':'#59B2B2','color':'#fff'},300);
        });
    });

    //funkcja odczytujaca klikniecie w element o klasie: delete
    //i wykonujaca akcje usuwania danego wiersza z tabeli
    //oraz dokonuje przeliczenia numerów wierszy w tabeli
    $('.delete').live('click',function() {
        //znajdz najblizszy wiersz bedacy elementem nadrzednym dla linka usuwajacego ten wiersz
        //i wykonaj animacje
        $(this).closest('tr').animate({'backgroundColor':'#C40000','color':'#fff'},300,function(){
 
            //usun dany wiersz
            $(this).remove();
 
            //aktualizuj numery pozostalych wierszy
            //dzieki temu gdy usuniemy wiersz w srodku tabeli 
            //to nie bedzie istniala dziura w numeracji wierszy
            $('#tabela > tbody > tr').each(function(i) {
                //wpisz nowy numer wewnatrz pierwszej komórki danego wiersza
                $(this).find('td:first-child').text(i+1);
            });
        });
    });
});

</script>
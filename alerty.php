<?php

/* 
 * Wyswietlamy alerty i powiadomienia zolnierzy, dowodcow
 */

/*ALERTY DLA DOWODCOW*/
//wybieramy dowodce wg uprawnien
switch ($_SESSION['permissions']){
    case 1:
        //admin
        $wnioski = mysql_query("SELECT *, CONCAT_WS(' ',stopnie.Skrot, UPPER(zolnierze.Nazwisko), zolnierze.Imie) as wnioskujacy FROM wnioski_nadgodziny left join zolnierze on kogo=idZolnierza left join stopnie using (idStopien) ORDER BY wolne") 
        or die('Błąd zapytania'); 
        break;
    case 2:
        //dowodca grupy
        $idGrupy = id_grupy();
        $wnioski = mysql_query("SELECT *, CONCAT_WS(' ',stopnie.Skrot, UPPER(zolnierze.Nazwisko), zolnierze.Imie) as wnioskujacy FROM wnioski_nadgodziny left join zolnierze on kogo=idZolnierza left join eskadry using(idEskadry) left join stopnie using (idStopien) where idGrupy='$idGrupy' ORDER BY wolne") 
        or die('Błąd zapytania');
        break; 
    case 3:
        //dowodca eskadry
        $idDowodcy = id_zolnierza();
        $idEskadry = id_eskadry();
        $wnioski = mysql_query("SELECT *, CONCAT_WS(' ',stopnie.Skrot, UPPER(zolnierze.Nazwisko), zolnierze.Imie) as wnioskujacy FROM wnioski_nadgodziny left join zolnierze on kogo=idZolnierza left join stopnie using (idStopien) where idEskadry='$idEskadry' ORDER BY wolne") 
        or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do eskadry'); 
        break;
    case 4:
        //szef eskadry
        $idEskadry = id_eskadry();
        $wnioski = mysql_query("SELECT *, CONCAT_WS(' ',stopnie.Skrot, UPPER(zolnierze.Nazwisko), zolnierze.Imie) as wnioskujacy FROM wnioski_nadgodziny left join zolnierze on kogo=idZolnierza left join stopnie using (idStopien) where idEskadry='$idEskadry' ORDER BY wolne") 
        or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do eskadry'); 
        break;
    case 5:
        //dowodca klucza
        $idKlucza = id_klucza();
        $wnioski = mysql_query("SELECT *, CONCAT_WS(' ',stopnie.Skrot, UPPER(zolnierze.Nazwisko), zolnierze.Imie) as wnioskujacy FROM wnioski_nadgodziny left join zolnierze on kogo=idZolnierza left join stopnie using (idStopien) where idKlucza='$idKlucza' ORDER BY wolne") 
        or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do eskadry'); 
        break;
    case 6:
        //zolnierz
        header('Location: index.php');
        break;
}

?>
<h1>Centrum powiadomień</h1>
<h2 class="podpowiedzi zaokraglij">Powiadomienia, wnioski</h2>
<div class="flex-container">
    <div class="panel tysiac">
       <div class="tytul">
          <p>wnioski</p>
          <p class="right"><a href="#index.php?id=panele/admin/uzytkownicy" class="pl-10 pr-10 edytuj valing40" title="wyświetl wszystkich użytkowników">opcja</a></p>
       </div>
       <div class="zawartosc" >

       </div>    
    </div>
    </div>
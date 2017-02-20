<?php
  
function szukaj($go) {
$wyrazenia = mysql_real_escape_string($go);

//wybieramy wyszukiwarke wg uprawnien
switch ($_SESSION['permissions']){
    case 1:
        //admin
        $szukaj = mysql_query("SELECT *, stopnie.Skrot AS StSkrot FROM zolnierze, stopnie WHERE stopnie.idStopien = zolnierze.idStopien AND CONCAT_WS(' ',stopnie.Skrot, zolnierze.Nazwisko, zolnierze.Imie) LIKE '%".$wyrazenia."%' ORDER BY Nazwisko") 
        or die('Błąd zapytania'); 
        break;
    case 2:
        //dowodca grupy
        $szukaj = mysql_query("SELECT *, stopnie.Skrot AS StSkrot FROM zolnierze, stopnie WHERE stopnie.idStopien = zolnierze.idStopien AND CONCAT_WS(' ',stopnie.Skrot, zolnierze.Nazwisko, zolnierze.Imie) LIKE '%".$wyrazenia."%' ORDER BY Nazwisko") 
        or die('Błąd zapytania');
        break; 
    case 3:
        //dowodca eskadry
        $idDowodcy = id_zolnierza();
        $idEskadry = id_eskadry();
        $szukaj = mysql_query("SELECT *, stopnie.Skrot AS StSkrot  FROM stopnie left join zolnierze USING (idStopien) left join eskadry USING (idEskadry) WHERE zolnierze.idEskadry = '$idEskadry' AND CONCAT_WS(' ',stopnie.Skrot, zolnierze.Nazwisko, zolnierze.Imie) LIKE '%$wyrazenia%' ORDER BY Nazwisko") 
        or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do eskadry'); 
        break;
    case 4:
        //szef eskadry
        $idEskadry = id_eskadry();
        $szukaj = mysql_query("SELECT *, stopnie.Skrot AS StSkrot  FROM stopnie left join zolnierze USING (idStopien) left join eskadry USING (idEskadry) WHERE zolnierze.idEskadry = '$idEskadry' AND CONCAT_WS(' ',stopnie.Skrot, zolnierze.Nazwisko, zolnierze.Imie) LIKE '%$wyrazenia%' ORDER BY Nazwisko") 
        or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do eskadry'); 
        break;
    case 5:
        //dowodca klucza
        $idKlucza = id_klucza();
        $szukaj = mysql_query("SELECT *, stopnie.Skrot AS StSkrot  FROM stopnie left join zolnierze USING (idStopien) left join eskadry USING (idEskadry) WHERE zolnierze.idKlucza = '$idKlucza' AND CONCAT_WS(' ',stopnie.Skrot, zolnierze.Nazwisko, zolnierze.Imie) LIKE '%$wyrazenia%' ORDER BY Nazwisko") 
        or die('Masz uprawnienia dowódcy, ale nie jesteś przypisany jako dowódca do eskadry'); 
        break;
    case 6:
        //zolnierz
        header('Location: index.php');
        break;
}

$znalezionych = mysql_num_rows($szukaj);
  
/* 
wyświetlamy wyniki, sprawdzamy, 
czy zapytanie zwróciło wartość większą od 0 
*/ 
    if(mysql_num_rows($szukaj) == 1) {
           $kogo = mysql_fetch_object($szukaj);
           //echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php?id=panele/profil/zolnierz&profil=$kogo->idZolnierza\">";
           header('Location: index.php?id=panele/profil/zolnierz&profil='.$kogo->idZolnierza);
    } elseif (mysql_num_rows($szukaj) > 1) { 
        /* jeżeli wynik jest pozytywny, to wyświetlamy dane */ 
        echo "<div class=\"flex-container\">";
        while($kogo = mysql_fetch_object($szukaj)) {  
            echo "<div class=\"flex-container\">";
                echo "<a href=\"index.php?id=panele/profil/zolnierz&profil=$kogo->idZolnierza\" title=\"Profil: ".$kogo->StSkrot." ".$kogo->Nazwisko." ".$kogo->Imie."\" id=\"profil\"><div class=\"panel\">";
                    echo "<div class=\"zawartosc blekitne wybrane\" >";
                        echo "<img src=\"img/profiles/thumbnail/$kogo->Zdjecie\" width=\"200px\" align=\"absmiddle\" alt=\"Zdjęcie profiliwe\" class=\"zaokraglij\">";
                    echo "</div>";   
                    echo "<div class=\"podpis\">";
                        echo "<p class=\"dane\"><abbr title=\"$kogo->Pelna\">".$kogo->StSkrot."</abbr><br> ".$kogo->Nazwisko." ".$kogo->Imie."</p>";
                    echo "</div>";
                echo "</div></a>";
            echo "</div>";    
        }
        echo "</div>"; 
    } else{
        echo "Brak wyników wyszukiwania";
    }
    
    
   
    
    
}



if(!empty($_GET['zolnierze'])){
echo "<h1>Wyszukiwanie żołnierzy</h1>";
echo "<h2 class=\"podpowiedzi zaokraglij\">Szukana fraza: ".$_GET['zolnierze']."</h2>";
}else{
echo "<h1>Twoi żołnierze</h1>";
echo "<h2 class=\"podpowiedzi zaokraglij\">Aby przefiltorwać wyniki skorzystaj z wyszukiwarki</h2>";    
}
?>

<div class="panel">
   <div class="tytul">
      <p>znalezieni</p>
   </div>
   <div class="zawartosc" >
       <?php if (isset($_GET['zolnierze'])){szukaj($_GET['zolnierze']);}else{header('Location: index.php');}?>
   </div>    
</div>
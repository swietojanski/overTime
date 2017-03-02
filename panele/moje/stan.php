<?php
                        
function ile_na_wolnym($data){
    switch ($_SESSION['permissions']){
    case 1:
        $stan = mysql_query("SELECT *, sum(ile) as minut FROM v_dni_wolne where kiedy='$data' group by idZolnierza;") or die(mysql_error()); 
    break;
    case 2:
        //dowodca grupy
        $idGrupy = czyDowodcaGrupy();
        if (empty($idGrupy)){
            $idGrupy = id_grupy();
        }
        $idZol=id_zolnierza(null);
        $stan = mysql_query("SELECT *, sum(ile) as minut FROM v_dni_wolne left join zolnierze using(idZolnierza) left join eskadry using(idEskadry) where idGrupy='$idGrupy' and kiedy='$data' or zolnierze.idZolnierza='$idZol' and kiedy='$data' group by idZolnierza;") or die(mysql_error()); 
    break;
    case 3:
        //dowodca eskadry
        $idEskadry = id_eskadry();
        $stan = mysql_query("SELECT *, sum(ile) as minut FROM v_dni_wolne left join zolnierze using(idZolnierza) left join eskadry using(idEskadry) where idEskadry='$idEskadry' and kiedy='$data' group by idZolnierza;") or die(mysql_error()); 
    break;
    case 4:
        //szef eskadry
        $idEskadry = id_eskadry();
        $stan = mysql_query("SELECT *, sum(ile) as minut FROM v_dni_wolne left join zolnierze using(idZolnierza) left join eskadry using(idEskadry) where idEskadry='$idEskadry' and kiedy='$data' group by idZolnierza;") or die(mysql_error()); 
    break; 
    case 5:
        //dowodca klucza
        $idKlucza = id_klucza();
        $stan = mysql_query("SELECT *, sum(ile) as minut FROM v_dni_wolne left join zolnierze using(idZolnierza) left join klucze using (idKlucza) where klucze.idKlucza='$idKlucza' and kiedy='$data' group by idZolnierza;") or die(mysql_error()); 
    break;
    case 6:
    //zolnierz
    header('Location: index.php');
    break;
    }
    
    $ilosc_nieobecnych = mysql_num_rows($stan);
    $ile_na_wolnym=0;
        while($r = mysql_fetch_object($stan)) {
            if(($r->minut)==480){
                $ile_na_wolnym++;
            }elseif (($r->minut)<480 && ($r->minut)>0) {
                $time=time()+60*$r->minut; //aktualna godzina plus ilosc godzin odbierana przez zolnierza
                $koniec_pracy=date("15:30");
                    if($time>strtotime($koniec_pracy)){ //sprawdzamy czy aktualna godzina jest mniejsza od aktualnej plus odbierane godziny zolnierza
                        $ile_na_wolnym++; //dodajemy kolejna nieobecna osobe
                    }                          
            }              
        }
    return $ile_na_wolnym;
}

function stan_ewidencyjny(){
    switch ($_SESSION['permissions']){
    case 1:
        $stan = mysql_query("SELECT * FROM zolnierze where Ubyl is NULL") or die(mysql_error());    
    break;
    case 2:
        //dowodca grupy
        $idGrupy = czyDowodcaGrupy();
        if (empty($idGrupy)){
            $idGrupy = id_grupy();
        }
        $idZol=id_zolnierza(null);
        $stan = mysql_query("SELECT * FROM zolnierze left join eskadry using(idEskadry) where idGrupy='$idGrupy' or zolnierze.idZolnierza='$idZol' and Ubyl is NULL") or die(mysql_error()); 
            
    break;
    case 3:
        //dowodca eskadry
    $idEskadry = id_eskadry();    
    $stan = mysql_query("SELECT * FROM zolnierze left join eskadry using(idEskadry) where idEskadry='$idEskadry' and Ubyl is NULL") or die(mysql_error());   
    break;
    case 4:
        //szef eskadry
    $szefEskadry = id_eskadry();    
    $stan = mysql_query("SELECT * FROM zolnierze left join eskadry using(idEskadry) where idEskadry='$szefEskadry' and Ubyl is NULL") or die(mysql_error());   
    break;
    case 5:
        //dowodca klucza
        $idKlucza = id_klucza();
        $stan = mysql_query("SELECT * FROM zolnierze where idKlucza='$idKlucza' and Ubyl is NULL") or die(mysql_error());
    break;
    }
    $ilosc_zolnierzy = mysql_num_rows($stan); 
    return $ilosc_zolnierzy;
}
    
function stan_osobowy_lista($data){  
switch ($_SESSION['permissions']){
    case 1:
        //admin
        $grupy = mysql_query("SELECT * FROM grupy order by Nazwa") or die(mysql_error()); 
        $ilosc_grup = mysql_num_rows($grupy);
        if ($ilosc_grup>0){
            
            echo"<ul>";
                while($r = mysql_fetch_object($grupy)) {
                    echo "<h2>$r->Nazwa</h2>";
                    if(!empty($r->DcaGrupy)){
                        $wolnego = na_wolnym($r->DcaGrupy, $data);
                        $zdjęcie=profilowe($r->DcaGrupy);
                        echo" <li class=\"mt-5 mb-10\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                        echo "\" class=\"zaokraglij\" height=\"50px\" title=\"Dowódca $r->Skrot\" align=\"absmiddle\"> "; st_nazwisko_imie($r->DcaGrupy);
                        if($wolnego>0 && $wolnego<8){
                            echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaGrupy&zobacz=$data#kalendarz\">";
                            echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";     
                        }elseif($wolnego==8){
                            echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaGrupy&zobacz=$data#kalendarz\">";
                            echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";}
                    }else{
                        echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                        echo "\" class=\"zaokraglij\" height=\"50px\" title=\"\" align=\"absmiddle\"> Brak dowódcy grupy";
                    }
                        $eskadry = mysql_query("SELECT * FROM eskadry WHERE idGrupy='$r->idGrupy' order by Nazwa") or die(mysql_error()); 
                        $ilosc_eskadr = mysql_num_rows($eskadry);
                            if ($ilosc_eskadr>0){
                                    while($r = mysql_fetch_object($eskadry)) {                                       
                                        echo"<ul>";
                                        echo "<h3>$r->Nazwa</h3>";
                                        if(!empty($r->DcaEskadry)){
                                            $wolnego = na_wolnym($r->DcaEskadry, $data);
                                            $zdjęcie=profilowe($r->DcaEskadry);
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                            echo "\" class=\"zaokraglij\" height=\"40px\" title=\"Dowódca $r->Skrot\" align=\"absmiddle\"> "; st_nazwisko_imie($r->DcaEskadry);
                                            if($wolnego>0 && $wolnego<8){//wyswietlenie statusu obecnosci
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                            }elseif($wolnego==8){
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                            }
                                            
                                        }else{
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                                            echo "\" class=\"zaokraglij\" height=\"40px\" title=\"\" align=\"absmiddle\"> Brak dowódcy eskadry";
                                        }
                                        echo"<ul>";
                                        if(!empty($r->SzefEskadry)){
                                            $wolnego = na_wolnym($r->SzefEskadry, $data);
                                            $zdjęcie=profilowe($r->SzefEskadry);
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                            echo "\" class=\"zaokraglij\" height=\"37px\" title=\"Szef $r->Skrot\" align=\"absmiddle\"> "; st_nazwisko_imie($r->SzefEskadry);
                                            if($wolnego>0 && $wolnego<8){
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->SzefEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                            }elseif($wolnego==8){
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->SzefEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>"; 
                                            }
                                            
                                        }else{
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                                            echo "\" class=\"zaokraglij\" height=\"37px\" title=\"\" align=\"absmiddle\"> Brak szefa eskadry";
                                        }
                                            $klucze = mysql_query("SELECT * FROM klucze WHERE idEskadry='$r->idEskadry' order by Nazwa") or die(mysql_error()); 
                                            $ilosc_kluczy = mysql_num_rows($klucze);
                                                if ($ilosc_kluczy>0){
                                                    echo"<ul>";
                                                        while($r = mysql_fetch_object($klucze)) {
                                                            if(!empty($r->DcaKlucza)){
                                                                $wolnego = na_wolnym($r->DcaKlucza, $data);
                                                                $zdjęcie=profilowe($r->DcaKlucza);
                                                                $dowodca_klucza=$r->DcaKlucza;
                                                                echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                                                echo "\" class=\"zaokraglij\" height=\"35px\" title=\"Dowódca $r->Skrot\" align=\"absmiddle\"> ";st_nazwisko_imie($r->DcaKlucza);
                                                                        if($wolnego>0 && $wolnego<8){
                                                                            echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaKlucza&zobacz=$data#kalendarz\">";
                                                                            echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                                                        }elseif($wolnego==8){
                                                                            echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaKlucza&zobacz=$data#kalendarz\">";
                                                                            echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                                                        }
                                                                    echo"</li>";
                                                                    }else{
                                                                        echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                                                                        echo "\" class=\"zaokraglij\" height=\"35px\" title=\"\" align=\"absmiddle\"> Brak dowódcy klucza</li>";
                                                                    }
                                                                $zolnierze = mysql_query("SELECT * FROM zolnierze left join stopnie using (idStopien) WHERE idKlucza='$r->idKlucza' and idZolnierza!='$dowodca_klucza' order by stopnie.Waga desc, Nazwisko") or die(mysql_error()); 
                                                                $ilosc_zolnierzy = mysql_num_rows($zolnierze);
                                                                if ($ilosc_zolnierzy>0){
                                                                    echo"<ul>";
                                                                        while($r = mysql_fetch_object($zolnierze)) {
                                                                                $wolnego = na_wolnym($r->idZolnierza, $data);
                                                                                $zdjęcie=profilowe($r->idZolnierza);
                                                                                echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                                                                echo "\" class=\"zaokraglij\" height=\"30px\" title=\"\" align=\"absmiddle\"> ";st_nazwisko_imie($r->idZolnierza);
                                                                                if($wolnego>0 && $wolnego<8){
                                                                                    echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->idZolnierza&zobacz=$data#kalendarz\">";
                                                                                    echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                                                                    
                                                                                }elseif($wolnego==8){
                                                                                    echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->idZolnierza&zobacz=$data#kalendarz\">";
                                                                                    echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                                                                    
                                                                                }
                                                                                echo"</li>";
                                                                        }
                                                                    echo"</ul>";

                                                                }
                                                            

                                                        }
                                                    echo"</ul>";
                                                    echo"</li>";

                                                }
                                                 echo"</ul>";
                                             echo"</li>";
                                        echo"</ul>";        
                                    }
                                

                            }
                echo"</li>";
                }
        echo"</ul>";

        }

        break;
    case 2:
        //dowodca grupy
        $idGrupy = czyDowodcaGrupy();
            if (empty($idGrupy)){
                $idGrupy = id_grupy();
            }
        $grupy = mysql_query("SELECT * FROM grupy where idGrupy='$idGrupy' order by Nazwa") or die(mysql_error()); 
        $ilosc_grup = mysql_num_rows($grupy);
        if ($ilosc_grup>0){
            
            echo"<ul>";
                while($r = mysql_fetch_object($grupy)) {
                    echo "<h2>$r->Nazwa</h2>";
                    if(!empty($r->DcaGrupy)){
                        $wolnego = na_wolnym($r->DcaGrupy, $data);
                        $zdjęcie=profilowe($r->DcaGrupy);
                        echo" <li class=\"mt-5 mb-10\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                        echo "\" class=\"zaokraglij\" height=\"50px\" title=\"Dowódca $r->Skrot\" align=\"absmiddle\"> "; st_nazwisko_imie($r->DcaGrupy);
                        if($wolnego>0 && $wolnego<8){echo "&nbsp;<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div>";}elseif($wolnego==8){echo "&nbsp;<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div>";}
                    }else{
                        echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                        echo "\" class=\"zaokraglij\" height=\"50px\" title=\"\" align=\"absmiddle\"> Brak dowódcy grupy";
                    }
                        $eskadry = mysql_query("SELECT * FROM eskadry WHERE idGrupy='$r->idGrupy' order by Nazwa") or die(mysql_error()); 
                        $ilosc_eskadr = mysql_num_rows($eskadry);
                            if ($ilosc_eskadr>0){
                                    while($r = mysql_fetch_object($eskadry)) {                                       
                                        echo"<ul>";
                                        echo "<h3>$r->Nazwa</h3>";
                                        if(!empty($r->DcaEskadry)){
                                            $wolnego = na_wolnym($r->DcaEskadry, $data);
                                            $zdjęcie=profilowe($r->DcaEskadry);
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                            echo "\" class=\"zaokraglij\" height=\"40px\" title=\"Dowódca $r->Skrot\" align=\"absmiddle\"> "; st_nazwisko_imie($r->DcaEskadry);
                                            if($wolnego>0 && $wolnego<8){//wyswietlenie statusu obecnosci
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                            }elseif($wolnego==8){
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                            }
                                            
                                        }else{
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                                            echo "\" class=\"zaokraglij\" height=\"40px\" title=\"\" align=\"absmiddle\"> Brak dowódcy eskadry";
                                        }
                                        echo"<ul>";
                                        if(!empty($r->SzefEskadry)){
                                            $wolnego = na_wolnym($r->SzefEskadry, $data);
                                            $zdjęcie=profilowe($r->SzefEskadry);
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                            echo "\" class=\"zaokraglij\" height=\"37px\" title=\"Szef $r->Skrot\" align=\"absmiddle\"> "; st_nazwisko_imie($r->SzefEskadry);
                                            if($wolnego>0 && $wolnego<8){
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->SzefEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                            }elseif($wolnego==8){
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->SzefEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>"; 
                                            }
                                            
                                        }else{
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                                            echo "\" class=\"zaokraglij\" height=\"37px\" title=\"\" align=\"absmiddle\"> Brak szefa eskadry";
                                        }
                                            $klucze = mysql_query("SELECT * FROM klucze WHERE idEskadry='$r->idEskadry' order by Nazwa") or die(mysql_error()); 
                                            $ilosc_kluczy = mysql_num_rows($klucze);
                                                if ($ilosc_kluczy>0){
                                                    echo"<ul>";
                                                        while($r = mysql_fetch_object($klucze)) {
                                                            if(!empty($r->DcaKlucza)){
                                                                $wolnego = na_wolnym($r->DcaKlucza, $data);
                                                                $zdjęcie=profilowe($r->DcaKlucza);
                                                                $dowodca_klucza=$r->DcaKlucza;
                                                                echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                                                echo "\" class=\"zaokraglij\" height=\"35px\" title=\"Dowódca $r->Skrot\" align=\"absmiddle\"> ";st_nazwisko_imie($r->DcaKlucza);
                                                                        if($wolnego>0 && $wolnego<8){
                                                                            echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaKlucza&zobacz=$data#kalendarz\">";
                                                                            echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                                                        }elseif($wolnego==8){
                                                                            echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaKlucza&zobacz=$data#kalendarz\">";
                                                                            echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                                                        }
                                                                    echo"</li>";
                                                                    }else{
                                                                        echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                                                                        echo "\" class=\"zaokraglij\" height=\"35px\" title=\"\" align=\"absmiddle\"> Brak dowódcy klucza</li>";
                                                                    }
                                                                $zolnierze = mysql_query("SELECT * FROM zolnierze left join stopnie using (idStopien) WHERE idKlucza='$r->idKlucza' and idZolnierza!='$dowodca_klucza' order by stopnie.Waga desc, Nazwisko") or die(mysql_error()); 
                                                                $ilosc_zolnierzy = mysql_num_rows($zolnierze);
                                                                if ($ilosc_zolnierzy>0){
                                                                    echo"<ul>";
                                                                        while($r = mysql_fetch_object($zolnierze)) {
                                                                                $wolnego = na_wolnym($r->idZolnierza, $data);
                                                                                $zdjęcie=profilowe($r->idZolnierza);
                                                                                echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                                                                echo "\" class=\"zaokraglij\" height=\"30px\" title=\"\" align=\"absmiddle\"> ";st_nazwisko_imie($r->idZolnierza);
                                                                                if($wolnego>0 && $wolnego<8){
                                                                                    echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->idZolnierza&zobacz=$data#kalendarz\">";
                                                                                    echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                                                                    
                                                                                }elseif($wolnego==8){
                                                                                    echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->idZolnierza&zobacz=$data#kalendarz\">";
                                                                                    echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                                                                    
                                                                                }
                                                                                echo"</li>";
                                                                        }
                                                                    echo"</ul>";

                                                                }
                                                            

                                                        }
                                                    echo"</ul>";
                                                    echo"</li>";

                                                }
                                                 echo"</ul>";
                                             echo"</li>";
                                        echo"</ul>";        
                                    }
                                

                            }
                echo"</li>";
                }
        echo"</ul>";

        }
        break; 
    case 3:
        //dowodca eskadry
        $idEskadry = id_eskadry();
            echo"<ul>";
                        $eskadry = mysql_query("SELECT * FROM eskadry WHERE idEskadry='$idEskadry' order by Nazwa") or die(mysql_error()); 
                        $ilosc_eskadr = mysql_num_rows($eskadry);
                            if ($ilosc_eskadr>0){
                                    while($r = mysql_fetch_object($eskadry)) {                                       
                                        echo "<h2>$r->Nazwa</h2>";
                                        if(!empty($r->DcaEskadry)){
                                            $wolnego = na_wolnym($r->DcaEskadry, $data);
                                            $zdjęcie=profilowe($r->DcaEskadry);
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                            echo "\" class=\"zaokraglij\" height=\"40px\" title=\"Dowódca $r->Skrot\" align=\"absmiddle\"> "; st_nazwisko_imie($r->DcaEskadry);
                                            if($wolnego>0 && $wolnego<8){//wyswietlenie statusu obecnosci
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                            }elseif($wolnego==8){
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                            }
                                            
                                        }else{
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                                            echo "\" class=\"zaokraglij\" height=\"40px\" title=\"\" align=\"absmiddle\"> Brak dowódcy eskadry";
                                        }
                                        echo"<ul>";
                                        if(!empty($r->SzefEskadry)){
                                            $wolnego = na_wolnym($r->SzefEskadry, $data);
                                            $zdjęcie=profilowe($r->SzefEskadry);
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                            echo "\" class=\"zaokraglij\" height=\"37px\" title=\"Szef $r->Skrot\" align=\"absmiddle\"> "; st_nazwisko_imie($r->SzefEskadry);
                                            if($wolnego>0 && $wolnego<8){
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->SzefEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                            }elseif($wolnego==8){
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->SzefEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>"; 
                                            }
                                            
                                        }else{
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                                            echo "\" class=\"zaokraglij\" height=\"37px\" title=\"\" align=\"absmiddle\"> Brak szefa eskadry";
                                        }
                                            $klucze = mysql_query("SELECT * FROM klucze WHERE idEskadry='$r->idEskadry' order by Nazwa") or die(mysql_error()); 
                                            $ilosc_kluczy = mysql_num_rows($klucze);
                                                if ($ilosc_kluczy>0){
                                                    echo"<ul>";
                                                        while($r = mysql_fetch_object($klucze)) {
                                                            echo "<h3>$r->Nazwa</h3>";
                                                            if(!empty($r->DcaKlucza)){
                                                                $wolnego = na_wolnym($r->DcaKlucza, $data);
                                                                $zdjęcie=profilowe($r->DcaKlucza);
                                                                $dowodca_klucza=$r->DcaKlucza;
                                                                echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                                                echo "\" class=\"zaokraglij\" height=\"35px\" title=\"Dowódca $r->Skrot\" align=\"absmiddle\"> ";st_nazwisko_imie($r->DcaKlucza);
                                                                        if($wolnego>0 && $wolnego<8){
                                                                            echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaKlucza&zobacz=$data#kalendarz\">";
                                                                            echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                                                        }elseif($wolnego==8){
                                                                            echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaKlucza&zobacz=$data#kalendarz\">";
                                                                            echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                                                        }
                                                                    echo"</li>";
                                                                    }else{
                                                                        echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                                                                        echo "\" class=\"zaokraglij\" height=\"35px\" title=\"\" align=\"absmiddle\"> Brak dowódcy klucza</li>";
                                                                    }
                                                                $zolnierze = mysql_query("SELECT * FROM zolnierze left join stopnie using (idStopien) WHERE idKlucza='$r->idKlucza' and idZolnierza!='$dowodca_klucza' order by stopnie.Waga desc, Nazwisko") or die(mysql_error()); 
                                                                $ilosc_zolnierzy = mysql_num_rows($zolnierze);
                                                                if ($ilosc_zolnierzy>0){
                                                                    echo"<ul>";
                                                                        while($r = mysql_fetch_object($zolnierze)) {
                                                                                $wolnego = na_wolnym($r->idZolnierza, $data);
                                                                                $zdjęcie=profilowe($r->idZolnierza);
                                                                                echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                                                                echo "\" class=\"zaokraglij\" height=\"30px\" title=\"\" align=\"absmiddle\"> ";st_nazwisko_imie($r->idZolnierza);
                                                                                if($wolnego>0 && $wolnego<8){
                                                                                    echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->idZolnierza&zobacz=$data#kalendarz\">";
                                                                                    echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                                                                    
                                                                                }elseif($wolnego==8){
                                                                                    echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->idZolnierza&zobacz=$data#kalendarz\">";
                                                                                    echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                                                                    
                                                                                }
                                                                                echo"</li>";
                                                                        }
                                                                    echo"</ul>";

                                                                }
                                                            

                                                        }
                                                    echo"</ul>";
                                                    echo"</li>";

                                                }
                                                 
                                             echo"</li>";
                                                
                                    }
                                    echo"</ul>";
                
        }
        break;
    case 4:
        //szef eskadry
        $idEskadry = id_eskadry();
            echo"<ul>";
                        $eskadry = mysql_query("SELECT * FROM eskadry WHERE idEskadry='$idEskadry' order by Nazwa") or die(mysql_error()); 
                        $ilosc_eskadr = mysql_num_rows($eskadry);
                            if ($ilosc_eskadr>0){
                                    while($r = mysql_fetch_object($eskadry)) {                                       
                                        echo "<h2>$r->Nazwa</h2>";
                                        if(!empty($r->DcaEskadry)){
                                            $wolnego = na_wolnym($r->DcaEskadry, $data);
                                            $zdjęcie=profilowe($r->DcaEskadry);
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                            echo "\" class=\"zaokraglij\" height=\"40px\" title=\"Dowódca $r->Skrot\" align=\"absmiddle\"> "; st_nazwisko_imie($r->DcaEskadry);
                                            if($wolnego>0 && $wolnego<8){//wyswietlenie statusu obecnosci
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                            }elseif($wolnego==8){
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                            }
                                            
                                        }else{
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                                            echo "\" class=\"zaokraglij\" height=\"40px\" title=\"\" align=\"absmiddle\"> Brak dowódcy eskadry";
                                        }
                                        echo"<ul>";
                                        if(!empty($r->SzefEskadry)){
                                            $wolnego = na_wolnym($r->SzefEskadry, $data);
                                            $zdjęcie=profilowe($r->SzefEskadry);
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                            echo "\" class=\"zaokraglij\" height=\"37px\" title=\"Szef $r->Skrot\" align=\"absmiddle\"> "; st_nazwisko_imie($r->SzefEskadry);
                                            if($wolnego>0 && $wolnego<8){
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->SzefEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                            }elseif($wolnego==8){
                                                echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->SzefEskadry&zobacz=$data#kalendarz\">";
                                                echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>"; 
                                            }
                                            
                                        }else{
                                            echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                                            echo "\" class=\"zaokraglij\" height=\"37px\" title=\"\" align=\"absmiddle\"> Brak szefa eskadry";
                                        }
                                            $klucze = mysql_query("SELECT * FROM klucze WHERE idEskadry='$r->idEskadry' order by Nazwa") or die(mysql_error()); 
                                            $ilosc_kluczy = mysql_num_rows($klucze);
                                                if ($ilosc_kluczy>0){
                                                    echo"<ul>";
                                                        while($r = mysql_fetch_object($klucze)) {
                                                            echo "<h3>$r->Nazwa</h3>";
                                                            if(!empty($r->DcaKlucza)){
                                                                $wolnego = na_wolnym($r->DcaKlucza, $data);
                                                                $zdjęcie=profilowe($r->DcaKlucza);
                                                                $dowodca_klucza=$r->DcaKlucza;
                                                                echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                                                echo "\" class=\"zaokraglij\" height=\"35px\" title=\"Dowódca $r->Skrot\" align=\"absmiddle\"> ";st_nazwisko_imie($r->DcaKlucza);
                                                                        if($wolnego>0 && $wolnego<8){
                                                                            echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaKlucza&zobacz=$data#kalendarz\">";
                                                                            echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                                                        }elseif($wolnego==8){
                                                                            echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaKlucza&zobacz=$data#kalendarz\">";
                                                                            echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                                                        }
                                                                    echo"</li>";
                                                                    }else{
                                                                        echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                                                                        echo "\" class=\"zaokraglij\" height=\"35px\" title=\"\" align=\"absmiddle\"> Brak dowódcy klucza</li>";
                                                                    }
                                                                $zolnierze = mysql_query("SELECT * FROM zolnierze left join stopnie using (idStopien) WHERE idKlucza='$r->idKlucza' and idZolnierza!='$dowodca_klucza' order by stopnie.Waga desc, Nazwisko") or die(mysql_error()); 
                                                                $ilosc_zolnierzy = mysql_num_rows($zolnierze);
                                                                if ($ilosc_zolnierzy>0){
                                                                    echo"<ul>";
                                                                        while($r = mysql_fetch_object($zolnierze)) {
                                                                                $wolnego = na_wolnym($r->idZolnierza, $data);
                                                                                $zdjęcie=profilowe($r->idZolnierza);
                                                                                echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                                                                echo "\" class=\"zaokraglij\" height=\"30px\" title=\"\" align=\"absmiddle\"> ";st_nazwisko_imie($r->idZolnierza);
                                                                                if($wolnego>0 && $wolnego<8){
                                                                                    echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->idZolnierza&zobacz=$data#kalendarz\">";
                                                                                    echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                                                                    
                                                                                }elseif($wolnego==8){
                                                                                    echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->idZolnierza&zobacz=$data#kalendarz\">";
                                                                                    echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                                                                    
                                                                                }
                                                                                echo"</li>";
                                                                        }
                                                                    echo"</ul>";

                                                                }
                                                            

                                                        }
                                                    echo"</ul>";
                                                    echo"</li>";

                                                }
                                                 
                                             echo"</li>";
                                                
                                    }
                                    echo"</ul>";
                
        }
        break;
    case 5:
        //dowodca klucza
        $idKlucza = id_klucza();
                                        echo"<ul>";
                                            $klucze = mysql_query("SELECT * FROM klucze WHERE idKlucza='$idKlucza' order by Nazwa") or die(mysql_error()); 
                                            $ilosc_kluczy = mysql_num_rows($klucze);
                                                if ($ilosc_kluczy>0){
                                                    
                                                        while($r = mysql_fetch_object($klucze)) {
                                                            echo "<h2>$r->Nazwa</h2>";
                                                            if(!empty($r->DcaKlucza)){
                                                                $wolnego = na_wolnym($r->DcaKlucza, $data);
                                                                $zdjęcie=profilowe($r->DcaKlucza);
                                                                $dowodca_klucza=$r->DcaKlucza;
                                                                echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                                                echo "\" class=\"zaokraglij\" height=\"35px\" title=\"Dowódca $r->Skrot\" align=\"absmiddle\"> ";st_nazwisko_imie($r->DcaKlucza);
                                                                        if($wolnego>0 && $wolnego<8){
                                                                            echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaKlucza&zobacz=$data#kalendarz\">";
                                                                            echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                                                        }elseif($wolnego==8){
                                                                            echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->DcaKlucza&zobacz=$data#kalendarz\">";
                                                                            echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                                                        }
                                                                    echo"</li>";
                                                                    }else{
                                                                        echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/zdjecie.jpg";
                                                                        echo "\" class=\"zaokraglij\" height=\"35px\" title=\"\" align=\"absmiddle\"> Brak dowódcy klucza</li>";
                                                                    }
                                                                $zolnierze = mysql_query("SELECT * FROM zolnierze left join stopnie using (idStopien) WHERE idKlucza='$r->idKlucza' and idZolnierza!='$dowodca_klucza' order by stopnie.Waga desc, Nazwisko") or die(mysql_error()); 
                                                                $ilosc_zolnierzy = mysql_num_rows($zolnierze);
                                                                if ($ilosc_zolnierzy>0){
                                                                    echo"<ul>";
                                                                        while($r = mysql_fetch_object($zolnierze)) {
                                                                                $wolnego = na_wolnym($r->idZolnierza, $data);
                                                                                $zdjęcie=profilowe($r->idZolnierza);
                                                                                echo" <li class=\"mt-5\"><img src=\"img/profiles/thumbnail/$zdjęcie";
                                                                                echo "\" class=\"zaokraglij\" height=\"30px\" title=\"\" align=\"absmiddle\"> ";st_nazwisko_imie($r->idZolnierza);
                                                                                if($wolnego>0 && $wolnego<8){
                                                                                    echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->idZolnierza&zobacz=$data#kalendarz\">";
                                                                                    echo "<div class=\"kolo kilka_godzin\" title=\"$wolnego godz.\"></div></a>";
                                                                                    
                                                                                }elseif($wolnego==8){
                                                                                    echo "&nbsp;<a href=\"index.php?id=panele/profil/zolnierz&profil=$r->idZolnierza&zobacz=$data#kalendarz\">";
                                                                                    echo "<div class=\"kolo wdomu\" title=\"$wolnego godz.\"></div></a>";
                                                                                    
                                                                                }
                                                                                echo"</li>";
                                                                        }
                                                                    echo"</ul>";

                                                                }
                                                            

                                                        }
                                        echo"</ul>";      
        }
        break;
    case 6:
        //zolnierz
        header('Location: index.php');
        break;
}



}
    
    ?>
<h1>Stan osobowy</h1>
<h2 class="podpowiedzi zaokraglij">Lista żołnierzy przedstawiająca aktualny stan osobowy.</h2>

            <div class="flex-container">
                <div class="panel trzysta">
                    <div class="tytul">
                        <p>stan ewidencyjny</p>
                    </div>
                    <div class="zawartosc wysrodkuj">
                        <h1><?php echo stan_ewidencyjny(); ?> os.</h1>   
                    </div> 
                </div>
                <div class="panel trzysta">
                    <div class="tytul">
                        <p>stan faktyczny</p>
                    </div>
                    <div class="zawartosc wysrodkuj">
                        <h1><?php echo stan_ewidencyjny()-ile_na_wolnym(date("Y-m-d")); ?> os.</h1>   
                    </div> 
                </div>
                <div class="panel trzysta">
                    <div class="tytul">
                        <p>nieobecnych</p>
                    </div>
                    <div class="zawartosc wysrodkuj">
                        <h1><?php echo ile_na_wolnym(date("Y-m-d")); ?> os.</h1>   
                    </div> 
                </div>
            </div>

<div class="flex-container stan">
    <div class="panel bez-tla">
        <?php stan_osobowy_lista(date("Y-m-d")); ?>
    </div>
</div>

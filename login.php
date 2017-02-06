<!DOCTYPE html>
<?php session_start();
          require_once('config.php');
?>
<?php include("langi.php"); ?>
<html>
<head>
<!-- Mimic Internet Explorer Edge -->
<meta http-equiv="x-ua-compatible" content="IE=edge" >
<meta charset="UTF-8" />
<link href="login.css" rel="stylesheet" type="text/css">
<title><?php print $title;?></title>
</head>
<!-- Początek tła strony -->

<?php

for ($i=1; $i<21; $i++){
    if($i%2){
        echo "<div class=\"pasek kolor1 poz".$i."\"></div>"; //jak jest reszta to nieparzyste
    }else{
        echo "<div class=\"pasek kolor2 poz".$i."\"></div>"; //nie ma reszty to parzyste
    } 
}

?>

<!-- koniec tła strony -->
<!-- Początek okna logowania strony -->
	<div class="middle">
  		<div class="lewo">
           <img src="img/lay/logowanie.png" width="203" height="202" alt="OverTime" longdesc="System zarządzania wymiarem czasu pracy żołnierzy zawodowych">
        </div>
        <div class="prawo"> 
           <?php
             /* jeżeli nie wypełniono formularza - to znaczy nie istnieje zmienna login, hasło i sesja auth
             * to wyświetl formularz logowania
             */
              if (!isset($_POST['login']) && !isset($_POST['password']) && $_SESSION['auth'] == FALSE) {
           ?>
           <form name="form-logowanie" action="login.php" method="post">
              <input type="text" name="login" class="zaloguj" placeholder="Login" minlength='4' maxlength='30' required='true' pattern="[A-Za-z-_0-9][A-Za-z-_0-9\\s]+" autofocus title="podaj prawidłowy login, bez polskich znaków"><br>
              <input type="password" name="password" class="zaloguj" placeholder="Hasło" required='true' title="jeżeli nie pamiętasz hasła, skontaktuj się z administratorem"><br>
              <input type="submit" name="zaloguj" value="Zaloguj" id="zaloguj" class="animacja">
           </form>
           <?php
              }
              /* jeżeli istnieje zmienna login oraz password i sesja z autoryzacją użytkownika jest FALSE to wykonaj
              * skrypt logowania
              */
              elseif (isset($_POST['login']) && isset($_POST['password']) && $_SESSION['auth'] == FALSE) {
      
              // jeżeli pole z loginem i hasłem nie jest puste      
                 if (!empty($_POST['login']) && !empty($_POST['password'])) {
          
                 // dodaje znaki unikowe dla potrzeb poleceń SQL
                    $login = mysql_real_escape_string($_POST['login']);
                    $password = mysql_real_escape_string($_POST['password']);
                 // szyfruję wpisane hasło za pomocą funkcji md5()
                    $password = md5($password);
        
                 /* zapytanie do bazy danych
                 * mysql_num_rows - sprawdzam ile wierszy odpowiada zapytaniu mysql_query
                 * mysql_query - pobierz wszystkie dane z tabeli user gdzie login i hasło odpowiadają wpisanym danym
                 */
                    $sql = mysql_num_rows(mysql_query("SELECT * FROM `uzytkownicy` WHERE `Login` = '$login' AND `Haslo` = '$password'"));
                    
                 // jeżeli powyższe zapytanie zwraca 1, to znaczy, że dane zostały wpisane poprawnie i rejestruję sesję
                       if ($sql == 1) {
              
                        //ustawienie 
                          $permissions = mysql_query("SELECT uprawnienia.Poziom FROM uzytkownicy, uprawnienia WHERE uprawnienia.idUprawnienia=uzytkownicy.idUprawnienia AND Login = '$login'")or die('Błąd zapytania o uprawnienia'); 
                          $dper = mysql_fetch_object($permissions);
                          $prawa=$dper->Poziom; 
                           
                           
                       // zmienne sesysje user (z loginem zalogowanego użytkownika)oraz permissions (z numerem uprawnien uzytkownika)  oraz sesja autoryzacyjna ustawiona na TRUE
                          $_SESSION['user'] = $login;
                          $_SESSION['permissions'] = $prawa;
                          $_SESSION['auth'] = TRUE;
                
                          //przekierwuję użytkownika na stronę z ukrytymi informacjami
                          echo '<meta http-equiv="refresh" content="1; URL=index.php">';
                          echo '<p class="logowanie"><strong>'.$wait.'</strong><br>trwa logowanie i wczytywanie danych<p></p>';
                        }
            
                        // jeżeli zapytanie nie zwróci 1, to wyświetlam komunikat o błędzie podczas logowania
                        else {
                            
                                                   ?>                      
                        <form name="form-logowanie" action="login.php" method="post">
                            <input type="text" name="login" class="zaloguj" placeholder="Login" minlength='4' maxlength='30' required='true' pattern="[A-Za-z-_0-9][A-Za-z-_0-9\\s]+" autofocus title="podaj prawidłowy login, bez polskich znaków"><br>
                            <input type="password" name="password" class="zaloguj error" placeholder="Złe hasło" required='true' title="jeżeli nie pamiętasz hasła, skontaktuj się z administratorem"><br>
                            <input type="submit" name="zaloguj" value="Zaloguj" id="zaloguj" class="animacja">
                        </form>
                       <?php
                            
                        //   echo '<p class="logowanie">'.$blad.'<br>';
                        //   echo '<a href="login.php">Wróć do formularza</a></p>';
                        }
                  }
        
                   // jeżeli pole login lub hasło nie zostało uzupełnione wyświetlam błąd
                   else {
                      echo '<p class="logowanie">'.$blad.'<br>';
                      echo '<a href="login.php">Wróć do formularza</a></p>';    
                        }
                  }
	
	              // jeżeli sesja auth jest TRUE to przekieruj na ukrytą podstronę
                  elseif ($_SESSION['auth'] == TRUE && !isset($_GET['logout'])) {
                     echo '<meta http-equiv="refresh" content="1; URL=index.php">';
                     echo '<p class="logowanie"><strong>'.$wait.'</strong><br>trwa wczytywanie danych<p></p>';
                  }

                  // wyloguj się
                  elseif ($_SESSION['auth'] == TRUE && isset($_GET['logout'])) {
                     $_SESSION['user'] = '';
                     $_SESSION['permissions'] = '';
                     $_SESSION['auth'] = FALSE;
                     echo '<meta http-equiv="refresh" content="1; URL=login.php">';
                     echo '<p class="logowanie"><strong>'.$wait.'</strong><br>zostałeś wylogowany<p></p>';
                  }
               ?>
        </div>
     </div>
<!-- Koniec okna logowania -->
     
 </body>
</html>
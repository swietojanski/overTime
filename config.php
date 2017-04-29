<?php
  $dbhost = 'overtime';     //adres serwera sql
  $dblogin = 'overtime';    //login uzytkownika bazy danych
  $dbpass = 'overtime';     //haslo
  $dbselect = 'sqlover';   //nazwa bazy danych
  mysql_connect($dbhost,$dblogin,$dbpass);
  mysql_select_db($dbselect) or die("Błąd przy wyborze bazy danych");
  mysql_query("SET CHARACTER SET UTF8");
?>
<?php
    date_default_timezone_set('Europe/Warsaw');
?>
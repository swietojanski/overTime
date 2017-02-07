<?php

mysql_query("SET AUTOCOMMIT=0");
mysql_query("START TRANSACTION");

$r1 = mysql_query("INSERT INTO `uzytkownicy` (`Login`, `Haslo`, `idZolnierza`, `idUprawnienia`) VALUES ('udana', '21232f297a57a5a743894a0e4a801fc3', '22', '1');")
    or die(mysql_error());

$r2 = mysql_query("INSERT INTO `uzytkownicy` (`Login`, `Haslo`, `idZolnierza`, `idUprawnienia`) VALUES ('nieudana', '21232f297a57a5a743894a0e4a801fc3', '99', '1');")
    or die("cos poszlo nie tak");


if ($r1 and $r2) {
    mysql_query("COMMIT");
    echo "udalo sie";
} else {
    echo "nie udalo sie";
    mysql_query("ROLLBACK");  
}

mysql_query('SET AUTOCOMMIT = 1');

?>





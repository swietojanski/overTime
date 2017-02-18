
<?php require_once 'panele/alerty/wnioski.php'; ?>
<?php //require_once 'panele/alerty/sluzby.php'; ?>

<h1>Centrum powiadomień</h1>
<h2 class="podpowiedzi zaokraglij">Powiadomienia, wnioski</h2>
<div class="flex-container">
    <div class="panel tysiac">
       <div class="tytul">
          <p>wnioski</p>
<!--          <p class="right"><a href="#index.php?id=panele/admin/uzytkownicy" class="pl-10 pr-10 edytuj valing40" title="wyświetl wszystkich użytkowników">opcja</a></p>-->
       </div>
       <div class="zawartosc" >
            <?php wnioski($_GET['profil']); ?>
       </div>    
    </div>
</div>
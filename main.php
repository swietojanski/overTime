<?php  include ("function.php"); ob_start();?>
<div class="wrapper">
    <header class="header">
        <div id="header">
                 <div id="headleft">
             <div id="menu">
                 <a href="index.php" class="logo" title="Pulpit"></a>
             <a href="index.php?id=panele/dodaj/nadgodziny" class="addovertime">nadgodziny</a>
             <a href="index.php?id=panele/dodaj/sluzby" class="addovertime">służby</a>
             <a href="index.php?id=panele/dodaj/wniosek" class="addovertime">wniosek</a>
             <a href="index.php?id=panele/moje/wolne" class="addovertime noimg">twoje wolne</a>
          <?php if($_SESSION['permissions']<6){
             echo "<a href=\"index.php?id=panele/moje/moi&zolnierze\" class=\"addovertime noimg\">moi zołnierze</a>";
          }
          ?>
                </div>
      <!--               Gorny pasek z wyszukiwarka i reszta danych-->
                <a href="index.php" class="logo"></a>

             </div>
          <div id="headcenter">
      <?php
          if($_SESSION['permissions']==6){
             }else{
                  echo "<form action=\"index.php?id=szukaj\" class=\"displaynone\" method=\"get\">";
                  echo "<input type=\"hidden\" name=\"id\" value=\"szukaj\"/><input type=\"search\" required=\"true\" results=\"5\" minlength=\"2\" maxlength=\"50\" autosave=\"some_unique_value\" placeholder=\"Szukaj żołnierza...\" name=\"zolnierze\" title=\"Wpisz nazwisko, imię lub stopień żołnierza\"/><input type=\"submit\" value=\"Szukaj\" class=\"szukaj\" />";
                  echo "</form>";
                  echo "<a href=\"index.html?id=szukaj\" class=\"szukajmin displaynonemax\"></a>";
             }
      ?>
            </div>
             <div id="headright">
                 <?php if($_SESSION['permissions']<6){?>
                 <a href="index.php?id=alerty" class="alerty" title="Alerty"><span><?php echo licz_oczekujace(); ?></span></a>
                 <?php } ?>
                 <a href="index.php?id=profil" class="profil"><img src="img/avatars/<?php avatar($_SESSION['user']);?>" width="30" align="absmiddle" height="30" alt="Avatar" class="avatar"><span class="displaynone"> <?php imie();?></span></a>
                 <?php if($_SESSION['permissions']==1){
                 echo "<a href=\"index.php?id=panele/admin\" class=\"panadmin\" id=\"pa\"></a>";
                         }
          ?>
                 <a href="index.php?id=ustawienia" class="ustawienia" title="Ustawienia"></a>
                <a href="login.php?logout" class="wyloguj">wyloguj</a>
                <div id="paneladmina" class="pl-10 pb-10"><?php include 'panele/admin.php'; ?></div>
             </div>
         </div>
    </header>

    <article class="main">
                <div class="aside">  
                    <ul>
<?php if($_SESSION['permissions']<6){?>
                        <a href="index.php?id=alerty"><li <?php if(isset($_GET['id']) &&  $_GET['id']=="alerty"){echo'class="wybrany"';}?>>Centrum powiadomień <span><?php echo licz_oczekujace(); ?></span></li></a>
                          <a href="index.php?id=panele/moje/moi&zolnierze"><li <?php if(isset($_GET['id']) &&  $_GET['id']=="panele/moje/moi"){echo'class="wybrany"';}?>>Moi żołnierze</li></a>
<?php } ?>
<?php if($_SESSION['permissions']<4){?>
                        <a href="index.php?id=rozkaz"><li <?php if(isset($_GET['id']) &&  $_GET['id']=="rozkaz"){echo'class="wybrany"';}?>>Rozkaz dzienny <span>punkty</span></li></a>
<?php } ?>
                          <li <?php if(isset($_GET['id']) &&  $_GET['id']=="dowodcy"){echo'class="wybrany"';}?>>Dowódcy</li>
                          <a href="index.php?id=panele/moje/wolne"><li <?php if(isset($_GET['id']) &&  $_GET['id']=="panele/moje/wolne"){echo'class="wybrany"';}?>>Twoje wolne <span>kalendarz</span></li></a>

                    <ul>
                </div>
                <div class="mainContent ladowanie">  
                    <?php 
                        if(isset($_GET['id'])) {
                            if(file_exists($_GET['id'].'.php')) {
                                $plik=$_GET['id'].'.php';
                                include($plik);
                            } else {
                                echo "Wystąpił <b>Błąd</b>, może być on spowodowany aktualnym brakiem danego pliku lub z powodu konserwacji serwisu. Za wszystko przepraszamy w najbliższym czasie zostanie to naprawione.</b>.";
                            }
                        } else {
                            include("pulpit.php");
                        }
                    ?>
                </div>
    </article>


    <footer class="footer">
            <div id="footer">
                <p>OverTime 2017<br>Krzysztof Świętojański</p>
            </div><!-- koniec stopki -->
    </footer>
</div>
<script>

 //dymki z podpowiedziami
$('div').tooltip({
    
        content: function () {return $(this).prop('title');},
        onShow: function() {this.getTrigger().fadeTo("slow", 0.5);},
        position: {
                  my: "center bottom",
                  at: "center top-10",
                  collision: "flipfit"
               }

});


      //zamieniamy 
      
$(".ggodzin").bind("change keyup input",function(){
    text = this.value.replace(",",".");
    $(".ggodzin").val(text);
});
      
$( function() {

    $( ".ggodzin" ).on( "click", function() {
      $( "#dialog" ).dialog( "open" );
      $(".ui-dialog-titlebar-close").hide();
      $("span.ui-dialog-title").text('Ile nadgodzin?'); 
      $( "#dialog" ).dialog( "option", "position", { my: "left top", at: "left bottom", of: ".ggodzin" } );

    $( ".mainContent" ).mousedown(function() {
      $( "#mainContent" ).dialog( "close" );
      $( "#dialog" ).dialog( "close" );
      $('.ggodzin').datepicker('destroy');
});
      
    $( "button" ).click(function() {
      var text = $( this ).text();
      $( ".ggodzin" ).val( text );
      $( "#dialog" ).dialog( "close" );
      });  
    });
} );

$( "#dialog" ).dialog({
      dialogClass: "addhour",
      autoOpen: false,
      draggable: false,
      modal: false,//przyciemnienei okna i wylaczenie aktywnych elementow
      
      show: {
        effect: "fade",
        duration: 200
      },
      hide: {
        effect: "fade",
        duration: 100
      }
    }
);


//funkcja pobierajaca nazwe id oraz nazwe klasy css xD

$(document).ready(function() {

	$("input.ggodzin").bind("click", function() {

		var klasa = "";

		var identyfikator = "";


		if ($(this).attr("class") != null) {

			klasa = $(this).attr("class");

		}


		if ($(this).attr("id") != null) {

			identyfikator = $(this).attr("id");

		}


		//alert($(this).text() + " " + klasa +" "+ identyfikator);

	});

});





            
            


</script>
<?php include ("function.php");?>
<div id="container">
  <div id="header">
  	   <div id="headleft">
       <div id="menu">
           <a href="index.php" class="logo" title="Pulpit"></a>
       <a href="index.php?id=panele/dodaj/nadgodziny" class="addovertime">nadgodziny</a>
       <a href="index.php?id=panele/dodaj/sluzby" class="addovertime">służby</a>
       <a href="index.php?id=panele/dodaj/wniosek" class="addovertime">wniosek</a>
    <?php if($_SESSION['permissions']==1 OR $_SESSION['permissions']==2){
       echo "<a href=\"index.php?id=panele/admin/dodajUzytkownika\" class=\"addovertime\">uzytkownika</a>";
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
            echo "<input type=\"hidden\" name=\"id\" value=\"szukaj\"/><input type=\"search\" required=\"true\" results=\"5\" minlength=\"2\" maxlength=\"50\" autosave=\"some_unique_value\" placeholder=\"Szukaj żołnierza...\" name=\"wyrazenie\" title=\"Wpisz nazwisko, imię lub stopień żołnierza\"/><input type=\"submit\" value=\"Szukaj\" class=\"szukaj\" />";
            echo "</form>";
            echo "<a href=\"index.html?id=szukaj\" class=\"szukajmin displaynonemax\"></a>";
       }
?>
      </div>
       <div id="headright">
           <a href="index.php?id=profil" class="profil"><img src="img/avatars/<?php avatar($_SESSION['user']);?>" width="30" align="absmiddle" height="30" alt="Avatar" class="avatar"><span class="displaynone"> <?php imie();?></span></a>
          <a href="index.php?id=ustawienia" class="ustawienia"></a>
          <a href="login.php?logout" class="wyloguj">wyloguj</a>
       </div>
   </div>
  <br class="clearfloat" />
  <div id="mainContent" class="ladowanie">

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
    
<h2>Przydatne </h2>
<p>Coś tutaj będzie.</p> 
	<!-- koniec #mainContent --></div>



  <div id="footer">
    <p>OverTime 2016</p>
  <!-- koniec stopki --></div>
<!-- koniec kontenera --></div>
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

    $( "#mainContent" ).mousedown(function() {
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




            $("#headleft").hover(
                    function(){
                $("#menu").css("display", "block");
            });
            $("#headleft").mouseleave(
                    function(){
                $("#menu").css("display", "none");
            });



</script>
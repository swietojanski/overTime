<?php 
//Zamiana przecinka na kropke
//
// $liczba = $_POST['godzina'];
//$liczba = str_replace(",",".",$liczba);
//echo $liczba; 
?>
<h1> Służby </h1>
<h2 class="podpowiedzi zaokraglij">Przeglądasz służby żołnierza.</h2>
<?php
echo "<div class=\"flex-container\">";
        echo "<div class=\"panel siedemset\">";
            echo "<div class=\"tytul\">";
                echo "<p>wszystkie służby</p>";
            echo "</div>";
            echo "<div class=\"zawartosc\" >";
                mojeSluzby($_GET[profil]);
            echo "</div>";   
        echo "</div>";
echo "</div>";

//EDYCJA NADGODZIN



?>



<!--Skrypt wykonujacy przejscie do wybranej podstrony z option select-->
<script>
jQuery(function () {
    // remove the below comment in case you need chnage on document ready
    // location.href=jQuery("#selectbox").val(); 
    jQuery("#selectbox").change(function () {
        location.href = jQuery(this).val();
    })
})
$('#select-all').prop("disabled", false);
$('#select-all').click(function(event) {   
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;    
        });
    }else {
    $(':checkbox').each(function() {
          this.checked = false;
      });
  }
});
$('#edytujgodzinki').prop("disabled", true);
$("#edytujgodzinki").css("cursor", "no-drop");
$(':checkbox').click(function() {
    
        if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            $('#edytujgodzinki').prop("disabled", false);
            $("#edytujgodzinki").css("cursor", "pointer");
        });
    }else {
    $(':checkbox').each(function() {
        $('#edytujgodzinki').prop("disabled", true);
        $("#edytujgodzinki").css("cursor", "no-drop");
      });
  }
    
    //$( "select#eskadra option:checked" ).text("Wybierz eskadrę").attr('disabled',!this.checked);
});
</script>
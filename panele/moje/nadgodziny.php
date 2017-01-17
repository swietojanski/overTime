<?php 
//Zamiana przecinka na kropke
//
// $liczba = $_POST['godzina'];
//$liczba = str_replace(",",".",$liczba);
//echo $liczba; 
?>
<h1> Moje nadgodziny </h1>
<h2 class="podpowiedzi zaokraglij">Przeglądasz swoje nadgodziny.</h2>
<?php
echo "<div class=\"flex-container\">";
        echo "<div class=\"panel siedemset\">";
            echo "<div class=\"tytul\">";
                echo "<p>wszystkie nadgodziny</p>";
            echo "</div>";
            echo "<div class=\"zawartosc\" >";
                mojeNadgodziny($_GET[czyje]);
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
</script>
//Skrypt wykonujacy przejscie do wybranej podstrony z option select || plik od przeglądania nadgodzin sluzb i uzytkownikow
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
$('#odrzuczaz').prop("disabled", true);
$('#odrzuczaz').css("cursor", "no-drop");
$(':checkbox').click(function() {
    
        if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            $('#edytujgodzinki').prop("disabled", false);
            $("#edytujgodzinki").css("cursor", "pointer");
            $('#odrzuczaz').prop("disabled", false);
            $("#odrzuczaz").css("cursor", "pointer");
        });
    }else {
    $(':checkbox').each(function() {
        $('#edytujgodzinki').prop("disabled", true);
        $("#edytujgodzinki").css("cursor", "no-drop");
        $('#odrzuczaz').prop("disabled", true);
        $("#odrzuczaz").css("cursor", "no-drop");
      });
  }
    
    //$( "select#eskadra option:checked" ).text("Wybierz eskadrę").attr('disabled',!this.checked);
});

$(document).ready(function(){
            $("#headleft").hover(
                    function(){
                $("#menu").css("display", "block");
            });
            $("#headleft").mouseleave(
                    function(){
                $("#menu").css("display", "none");
            });
            $("#pa").hover(
                    function(){
                $("#paneladmina").css("display", "block");
            });
            $("#paneladmina").mouseleave(
                    function(){
                $("#paneladmina").css("display", "none");
            });
              $("#container").click(
                    function(){
                $("#paneladmina").css("display", "none");
            });
});
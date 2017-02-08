<?php session_start();
          require_once('config.php');
?>
<!DOCTYPE html>
<html>
<head>
<!-- Mimic Internet Explorer Edge -->

<meta http-equiv="x-ua-compatible" content="IE=edge" >
<meta charset="UTF-8" />
<link href="style.css" rel="stylesheet" type="text/css">
<link href="js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet" type="text/css">
<script src="js/jquery-ui-1.11.4/jquery.min.js"></script>
<script src="js/jquery-ui-1.11.4/jquery-ui.js"></script>
<?php include("langi.php"); ?>
<title><?php print $title;?></title>
<body class="overTime">

<?php if ($_SESSION['auth'] == TRUE) {          
	    include("main.php");
	 }
	  
      else {
		      echo '<meta http-equiv="refresh" content="0; URL=login.php">';
      }
      ?>

<script src="js/dodatki.js"></script>
</body>
</html>

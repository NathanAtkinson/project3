<?php 

	// session_start();
	require_once('validator.php');
	require_once('htmllogic.php');
 ?>
 


 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<title>PHP Project 3</title>
 	<style>
		.valid {
			background-color: rgba(200, 255, 200, .7);
			border: 1px dashed rgba(30, 150, 30, 1);
		}

		.invalid {
			background-color: rgba(255, 200, 200, .7);
			border: 1px dashed rgba(150, 30, 30, 1);
		}

 	</style>
 </head>
 <body>

		<?php echo $form ?>


 </body>
 </html>
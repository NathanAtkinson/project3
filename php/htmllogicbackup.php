<?php 

	$fieldtypes = ['email', 'username', 'password', 'phone', 'number'];

	$form = '<form action="usevalidate.php" >
				<label for="input">Enter number of fields:</label>
				<input type="text" name="numfields"><br>
				<button>Submit</button>
 			</form>';

 	$inputinfo = [];

	function createFields($newfields, $types) {
		$form = '<form action="" method="POST">' . "\n";
		for ($i = 0; $i < $newfields; $i++) {
			$input = 'tf' . $i;
			$fieldtype = 'field' . $i;

			/*was throwing an error...  even though echoing $fieldtype showed "field0" it was truly field0<br>*/
			if (isset($_SESSION[$fieldtype]) == 1) {
				$fieldtype = $_SESSION[$fieldtype];
			}
			
			$form .= '<select name="field' . $i . '">';


// var names as descriptive as possible

// be sure to change phone number regex
// get rid of sessions.  Use an array.  
			foreach ($types as $type) {
				$form .= "\n" . '<option' . /*$i .*/ ' value="' . $type . '"';

				/*if something is selected on a prior submission, inserts into string so that it's the default value*/
				if ($fieldtype == $type) {
					$form .= ' selected';
				}
				// had an extra " and it was causing select to not work
				$form .= '>' . $type . '</option>';
			}

			$form .= "\n" . '</select>' . "\n" . '<input name="tf' . $i . '" type="text" ';

			// if it's not set, will not validate it
			if (isset($_SESSION[$input]) == 1) {
				$validatorFact = new ValidatorFactory();
				$invalid = '';
				$validator = $validatorFact->createValidator($fieldtype);

/*with protected functions, can only refer to them inside the class.  Even though the object is of the same class, can't call a protected function oustdie of the class*/

				try {
					$validator->validate($_SESSION[$input]);

				} catch (ValidationException $e) {
					$invalid = true;
				} 

				if (!$invalid) {
					$form .= 'value="' . $_SESSION[$input] . '" class="valid"><br>' . "\n" ;
				} elseif ($invalid){
					$form .= 'value="" class="invalid">'  . "\n";
					$form .= $e->getMessage() . $_SESSION[$input] . "\n" . '<br>';
				} else {
					$form .= '><br>' . "\n";
				}
			} else {
				$form .= '><br>' . "\n";
			}
		}
		$form .= '<br><button action="">Submit</button></form>';
		return $form;
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['numfields'] > 0) {
		$i=0;
		$max = count($_POST);
	
		for ($i = 0; $i < count($_POST); $i++) {
			$fieldtype = 'field' . $i;
			$input = 'tf' . $i;
			// echo $i;
			if (isset($_POST[$fieldtype]) == true){
				$_SESSION[$fieldtype] = $_POST[$fieldtype];
			}
			if (isset($_POST[$input]) == true) {
				$_SESSION[$input] = $_POST[$input];
			}
		}
		// print_r($_SESSION);
		// did not have numfields or the form calling... so after submitting info the form was never reset from the initial page.  The two below fixed that.
		$numfields = $_SESSION['numfields'];
		$form = createFields($numfields, $fieldtypes);
	}

	if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['numfields']) == true) {
		if ($_GET['numfields'] > 0) {
		$numfields = $_GET['numfields'];
		$form = createFields($numfields, $fieldtypes);
		$_SESSION['numfields'] = $_GET['numfields'];
		}
	}

	if ($_SERVER['REQUEST_METHOD'] == null) {
		if (isset($_GET['numfields'])) {
			// $_SESSION[] = '';
			$numfields = $_GET['numfields'];
			$_SESSION['numfields'] = $_GET['numfields'];
		}
	} else {
		$numfields = 0;
	}
?>
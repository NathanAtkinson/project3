<?php 

/*don't forget short circuiting in if( a && b && c && d).
b, c, and d won't be tested if a isn't true. Same for c and d, if b isn't true...and so on.
Put like things together.*/

// JS: w/o var = global variable

// No must rework, but should do so for the practice.  Use if/else's to capture
// every possibility

// functions at top
/*
is this a get?
	populate fields
else if a post?
	populate fields
	vlaidate info
else
	default of ask for numfields*/

// then spitout html

// breakdown createfields...doesn't do just one thing



/*mistake I was making in redoing this from $_SESSION to an array that wasn't persistent was that
SESSION is a global var.  Once I passed in inputinfo to the createFields function, had no issues*/

	// possible types of data.  Array is used to populate the dropdowns
	$fieldtypes = ['email', 'username', 'password', 'phone', 'number'];

	// sets up a default form that will be overwritten should if statements be true below.
	$form = '<form action="usevalidate.php" >
				<label for="input">Enter number of fields:</label>
				<input type="text" name="numfields"><br>
				<button>Submit</button>
 			</form>';

	/*if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
		if (isset($_GET['numfields'])) {
			// echo 'post or get' . "\n";
			// $inputinfo[] = '';
			$numfields = $_GET['numfields'];
			$inputinfo['numfields'] = $_GET['numfields'];
		} else {
		// echo 'null else';
		$numfields = 0;
		$inputinfo['numfields'] = $numfields;
		}
	}*/



	/*creates fields by taking the number of fields to create, array of the types of fields,
	and also the array or already existing info (inputinfo).  */
	function createFields($newfields, $types, $inputinfo) {
		$form = '<form action="" method="POST">' . "\n";

		/*do this for each new field needed*/
		for ($i = 0; $i < $newfields; $i++) {
			$tfinput = 'tf' . $i;
			$fieldtype = 'field' . $i;

			/*was throwing an error...  even though echoing $fieldtype showed "field0" it was truly field0<br>*/
			if (isset($inputinfo[$fieldtype]) == 1) {
				$fieldtype = $inputinfo[$fieldtype];
			}
			
			$form .= '<select name="field' . $i . '">' . "\n";

			// creates an option for each type in the array for the dropdown menu
			foreach ($types as $type) {
				$form .= '<option' . /*$i .*/ ' value="' . $type . '"';

				if ($fieldtype == $type) {
					$form .= ' selected';
				}
				// had an extra " and it was causing select to not work
				$form .= '>' . $type . '</option>' . "\n";
			}

			$form .=  '</select>' . "\n" . '<input name="tf' . $i . '" type="text" ';

			// if it's not set, will not validate it
/*with protected functions, can only refer to them inside the class.  Even though the object is of the same class, can't call a protected function oustdie of the class*/
			/*validates data if it was submitted.  Creates a validator with the validator factory. The
			factory creates a subclass validator based on the type passed to it.  It then uses the new
			validator, passes it the value, and tests it.  If it's invalid, then it throws an exception
			which is caught.  When caught, the invalid boolean is set to true.  When this is true, the 
			if statement below is used to determine how the form should be modified.*/
			if (isset($inputinfo[$tfinput]) == 1) {
				$validatorFact = new ValidatorFactory();
				$invalid = '';
				$validator = $validatorFact->createValidator($fieldtype);

				try {
					$validator->validate($inputinfo[$tfinput]);
					/*if there's an exception thrown, goes to catch.  otherwise will continue
						with the current try block...so no need to have an invalid boolean that 
					is used in an if/else that sets the form one way or another.  Try/catch is 
					an if/else on its own if used correctly.*/
					$form .= 'value="' . $inputinfo[$tfinput] . '" class="valid"><br><br>' . "\n" ;
				} catch (ValidationException $e) {
					$invalid = true;
					$form .= 'value="" class="invalid">'  . "\n";
					$form .= $e->getMessage() . $inputinfo[$tfinput] . "\n" . '<br><br>';
				} 

				/*These are used to determine how the form should be changed.  Output is valid,
				invalid, it's true or not.*/
				/*if (!$invalid) {
					$form .= 'value="' . $inputinfo[$tfinput] . '" class="valid"><br><br>' . "\n" ;
				} else{
					$form .= 'value="" class="invalid">'  . "\n";
					$form .= $e->getMessage() . $inputinfo[$tfinput] . "\n" . '<br><br>';
				}*/ /*else {
					$form .= '><br>' . "\n";
				}*/
			/*if there's no input, then it's not checked with a validator.  The field is not
				valid or invalid then, so the tag is just closed.*/
			} else {
				$form .= '><br><br>' . "\n";
			}
		}
		$form .= '<button action="">Submit</button>' . "\n" . '</form>';
		return $form;
	}

 	/*if the request from the server isn't a post, then attempts to get numfield.
 	  This sets numfields if it's entered, otherwise into the url.*/
 	if($_SERVER['REQUEST_METHOD'] !== 'GET' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
 		// echo 'post or get, foreach to go';
 		if(isset($_GET['numfields']) == 1){
		 	$numfields = $_GET['numfields'];
		 	$inputinfo['numfields'] = $numfields;
 		}
	 }

	/*If the method is post and there's a number of fields set, then carry out the below, which 
	calls createfields*/
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['numfields']) == 1) {
		$i=0;
		$max = count($_POST);
		$inputinfo['numfields'] = $_GET['numfields'];
		$numfields = $_GET['numfields'];
	
		for ($i = 0; $i < count($_POST); $i++) {
			$fieldtype = 'field' . $i;
			$tfinput = 'tf' . $i;
			// echo $i;
			if (isset($_POST[$fieldtype]) == true){
				$inputinfo[$fieldtype] = $_POST[$fieldtype];
			}
			if (isset($_POST[$tfinput]) == true) {
				$inputinfo[$tfinput] = $_POST[$tfinput];
			}
		}
		// print_r($inputinfo);
		// did not have numfields or the form calling... so after submitting info the form was never reset from the initial page.  The two below fixed that.
		// echo $inputinfo['numfields'];
		$numfields = $inputinfo['numfields'];
		$form = createFields($numfields, $fieldtypes, $inputinfo);
	}

	/*if it's a get, and number of fields is set, then sets numfields if there's a positive
		value.  It also calls get fields if positive, otherwise the form stays in default
	which will ask for the number of fields.*/
	if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['numfields']) == 1) {
		

/*if it's a get, and number of fields is set, then sets numfields if there's a positive
		value.  It also calls get fields if positive, otherwise the form stays in default
	which will ask for the number of fields.  If it's a full positive integer,*/
		// modulo of an integer never had a remainder...  had ==1 and it didn't work then
		if($_GET['numfields'] > 0 && $_GET['numfields'] % 1 == 0) {
		$numfields = $_GET['numfields'];
		// echo 'get and numfields<br>' . $_GET['numfields'];
		$form = createFields($numfields, $fieldtypes, $inputinfo);
		$inputinfo['numfields'] = $_GET['numfields'];
		// print_r($inputinfo);
			
		}
	}

?>
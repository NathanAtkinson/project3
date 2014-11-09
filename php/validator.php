<?php 

/*do not want to start the session in this file...want the user of the library to make that decision.*/

	abstract class Validator {
		// forgot the word function!!...  
		/*abstract means it doesn't have a body defined.  and if try to call it
		php throws the exception. No body at all (removal of {} and ; is added c
			Any addition of an abstract method means the class has to be abstract.
			Properties can't be abstract*/
		abstract protected function validateParam($param); /*{
			// throw new Exception('Cannot call protected function');
		}*/

		public function validate($value) {
			return $this->validateParam($value);
		}
	}

	class ValidationException extends Exception {

	}

	class ValidatorFactory {

		public function createValidator($type) {
			if ($type == 'email') {
				return new EmailValidator();
			} elseif ($type == 'number') {
				return new NumberValidator();
			} elseif ($type == 'phone') {
				return new PhoneNumberValidator();
			} elseif ($type == 'username') {
				return new UserNameValidator();
			} elseif ($type == 'password') {
				return new PasswordValidator();
			} else {
				throw new Exception('Unknown Validator type: '. $type);
			}
		}
	}

/*
*subclasses inherit validate from the master class...which calls validateParam.  Overwriting the validateParam 
*means that when the public and inherited validate() is called, it calls its own overwritten validateParam class
*/

	class UserNameValidator extends Validator {
			
		protected function validateParam($value) {
			if (preg_match('/^[A-Za-z0-9]+$/', $value) ==  0) {
				throw new ValidationException("Invalid Username: ");
			} 
		}
	}

	class EmailValidator extends Validator {

		protected function validateParam($value) {
			if (preg_match('/^[A-Za-z0-9]+@[A-Za-z0-9]+\.[A-Za-z]+$/', $value) == 0){
				throw new ValidationException('Invalid email: ');
			} else {
				return $value;
			}
		}
	}

	class PasswordValidator extends Validator {

		protected function validateParam($value) {
			if (strlen($value) < 8) {
				throw new ValidationException('Invalid Password: ');
			} else {
				return $value;
			}
		}
	}

	class PhoneNumberValidator extends Validator {

		protected function validateParam($value) {
			// 3-3-4 digits needed...that's it.  write it myself
			if (preg_match('/[0-9]{3}[ .-]?[0-9]{3}[ .-][0-9]{4}/', $value) == 0) {
				throw new ValidationException('Invalid phone number: ');
			} else {
				return $value;
			}
		}
	}

	class NumberValidator extends Validator {

		protected function validateParam($value) {
			if(!is_numeric($value)) {
				throw new ValidationException('Invalid number: ');
			} else {
				return $value;
			}
		}
	}
?>
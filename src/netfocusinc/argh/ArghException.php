<?php
	
/**
	* ArghException.php
	*/
	
namespace netfocusinc\argh;
	
/**
 	* An exception that is thrown by Argh
 	*
 	* A subclass of Exception that is thrown by Argh.
 	* 
 	* @author Benjamin Hough
 	*
 	* @since 1.0.0
 	* 
 	*/
class ArghException extends \Exception
{
		/**
			* Contruct a new ArghException with a required message
			*
			* @since 1.0.0
			*
			* @param string $message Custom message describing this Exception
			* @param int $code Custom error code
			* @param Exception $previous The previous Exception to occurr
			*
			*/
    public function __construct($message, $code = 0, Exception $previous = null) {
        // some code
    
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }


		/**
			* Returns a descriptive string indentifying this Exception
			*
			* @since 1.0.0
			*
			* @return string
			*
			*/
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
    
}
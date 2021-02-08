<?php
   
   /**
    * Check if data is a positive integer
    * @param data
    * @param data_name a string describing the data read, so that it can be used in the error message if it is not a positive integer
    * @return the data to be used or NULL if it does not pass the validation
    */
    function validate_positive_int($data, $data_name){
        
        if (empty($data)) {
            // data is empty
            $_SESSION['messages'][] = array('type' => 'error', 'content' => $data_name . ' is required.');
            return NULL;
        } 
        else {
            $finalData = test_input($data);
            // Check if it is a positive integer
            if (!preg_match("/^\d+$/",$finalData)) {
                $_SESSION['messages'][] = array('type' => 'error', 'content' => $data_name . ' is invalid.');
            }
        }
        return $finalData;
    }

    /**
    * Check if data is a valid string - only has letters and white spaces and is not empty
    * @param data
    * @param data_name a string describing the data read, so that it can be used in the error message if it is not a valid string
    * @return the data to be used or NULL if it does not pass the validation
   */
    function validate_string($data, $data_name){
        if (empty($data)) {
            // data is empty
            $_SESSION['messages'][] = array('type' => 'error', 'content' => $data_name . ' is required.');
            return NULL;
        } else {
            $finalData = test_input($data);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ ]{3,}$/",$data)) {
                $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Min 3 characters. Only letters and white space allowed in ' . $data_name);
            }
        }
        return $finalData;
    }

    /**
    * Check if data is a string which only contains letters, white spaces, punctuation and numbers
    * @param data
    * @param data_name a string describing the data read, so that it can be used in the error message if it is not valid
    * @return the data to be used or NULL if it does not pass the validation
   */
    function validate_complex_string($data, $data_name){
        if (empty($data)) {
            $_SESSION['messages'][] = array('type' => 'error', 'content' => $data_name . ' is required');
            return NULL;
        } else {
            $finalData = test_input($data);
            // check if string only contains letters, white spaces, punctuation and numbers
            if (!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ0-9.,:;()\/'!? ]+$/",$data)) {
                $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Min 1 character. Only letters, white space, punctuation and numbers allowed in ' . $data_name);
            }
        }
        return $finalData;
    }

    /**
    * Check if data is a string which only contains letters, white spaces, punctuation, numbers and has at least 10 characters
    * @param data
    * @param data_name a string describing the data read, so that it can be used in the error message if it is not valid
    * @return the data to be used or NULL if it does not pass the validation
   */
    function validate_long_string($data, $data_name){
        if (empty($data)) {
            $_SESSION['messages'][] = array('type' => 'error', 'content' => $data_name . ' is required');
            return NULL;
        } else {
            $finalData = test_input($data);
            // check if string only contains letters, white spaces, punctuation, numbers and has at least 10 characters
            if (!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ0-9.,:;()\/'!? ]{10,}$/",$data)) {
                $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Min 10 characters. Only letters, white space, punctuation and numbers allowed in ' . $data_name);
            }
        }
        return $finalData;
    }

    /**
    * Check if data is a string or empty string which only contains letters and white spaces
    * @param data
    * @param data_name a string describing the data read, so that it can be used in the error message if it is not valid
    * @return the data to be used or NULL if it does not pass the validation
   */
    function isSimpleString($data){
        return (preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ ]*$/",$data));
    }
    
    /**
    * Check if data is an integer
    * @param data
    * @param data_name a string describing the data read, so that it can be used in the error message if it is not valid
    * @return the data to be used or NULL if it does not pass the validation
   */
    function isInt($data){
        return (preg_match("/^-?\d+$/",$data));
    }
    
    /**
    * Parse HTML string
    * @param data
    * @param data_name a string describing the data read, so that it can be used in the error message if it is not valid
    * @return the data to be used or NULL if it does not pass the validation
   */
    function test_input($data) {
        $data = trim($data); // Remove white spaces from beginning and end of a string
        $data = stripslashes($data); // Un-quote a quoted string.
        $data = htmlspecialchars($data); // Convert special characters to HTML entities
        return $data;
    }

    /**
     * Copy Session messages array to new array and clean session messages
     * @return the new array with the messages
     */
    function getApiMessages(){
        $error_messages = $_SESSION['messages'];
        clearMessages();

        return $error_messages;
    }
?>
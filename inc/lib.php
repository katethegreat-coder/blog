<?php
 /**
* Universal function to check forms
*
* @param array $superglobale variables $_GET & $_POST
* @param array $fields tables to be checked
* @return bool
*/
function verifForm($superglobale, $fields) {
   // loop fields
   foreach ($fields as $field) {
       // check if the field exists and is not empty
       if(isset($superglobale[$field]) && !empty ($superglobale [$field])){
       $response=true;
   } else {
       return false;
   }
}
return $response;
}  
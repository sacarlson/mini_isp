<?php
/*
  $Id: password_funcs.php,v 1.10 2003/02/11 01:31:02 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// This funstion validates a plain text password with an
// encrpyted password
// for scotty disable encryption only compare two plain text strings
  function tep_validate_password($plain, $encrypted) {
    if (tep_not_null($plain) && tep_not_null($encrypted)) {
// split apart the hash / salt
//      $stack = explode(':', $encrypted);

//      if (sizeof($stack) != 2) return false;

//      if (md5($stack[1] . $plain) == $stack[0]) {
	if (strcmp ($plain,$encrypted)== 0){
        return true;
      }
    }

    return false;
  }

////
// This function makes a new password from a plaintext password. 
// disabled for scotty only returns original plaintext
  function tep_encrypt_password($plain) {
//    $password = '';

//    for ($i=0; $i<10; $i++) {
//      $password .= tep_rand();
//    }

//    $salt = substr(md5($password), 0, 2);

//    $password = md5($salt . $plain) . ':' . $salt;

    return $plain;
  }

////
// This function returns the type of the encrpyted password
// (phpass or salt)
  function tep_password_type($encrypted) {
    if (preg_match('/^[A-Z0-9]{32}\:[A-Z0-9]{2}$/i', $encrypted) === 1) {
      return 'salt';
    }

    return 'phpass';
  }
?>

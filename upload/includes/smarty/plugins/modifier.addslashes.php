<?php
/**
 * Smarty plugin	-- Stormtrooper at psychostats dot com
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty addslashes modifier plugin
 *
 * Type:     modifier<br>
 * Name:     addslashes<br>
 * Purpose:  backslashes strings using addslashes()
 * @param string
 * @return string
 */
function smarty_modifier_addslashes($string)
{
  return addslashes($string);
}

?>

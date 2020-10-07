<?php 
/* 
* Smarty plugin 
* -------------------------------------------------------- 
* File: outputfilter.compress.php 
* Type: outputfilter 
* Name: compress 
* Version: 1.0 
* Date: 5 May 2012
* Purpose: Compress Html for output to browser {strip} 
* Install: Place in your (local) plugins directory and 
* add the call: 
* $smarty->load_filter('output', 'compress'); 
* Author: Carlos Barbosa Chinas FBP Team
* development@flightbackpack.com
* -------------------------------------------------------- 
*/ 
function smarty_outputfilter_compress( $source, &$smarty ) 
{
    
// remove all html comments
$source = preg_replace('/<!--[^\[](.|\s)*?-->/', '', $source);
    
// compress all whitespace (tabs, newlines, spaces etc) down to one space 
$source = preg_replace("`\s+`ms", " ", $source); 

// compress <tag> ... <tag> to <tag>...</tag> 
$source = preg_replace("`>\s*(.*)\s*<`Ums", ">\\1<", $source); 

// trim the result 
$source = trim($source); 

return $source; 
} 

?>
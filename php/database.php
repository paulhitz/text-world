<?php
/**
* This file contains common functions used by the system.
*
* @author Paul Hitz
* @version 020312
*/


/**
* Opens a persistent connection to the database.
*
* @return true if successfully connects; false otherwise.
*/
function connect()
{
    $server = "";
    $username = "";
    $password = "";
    $database_name = "";

    $result = mysql_pconnect($server, $username, $password);

    if ( !mysql_select_db($database_name) || !$result )
    {
        //Display error message.
        echo ("<h3>Database Error</h3><p>Could not connect to the database!<br />Please try again later.</p>");
        return false;
    }
    else
    {
        return true;
    }
}

?>

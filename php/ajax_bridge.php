<?php
header( 'Content-type: text/xml' );

require_once("database.php");           //Load global library file.
require_once("TextWorld_class.php");    //Load companion class file.
connect();                              //Connect to database.

$SIZE_OF_WORLD = 25;                    //The total number of squares/areas in the world.
$DEFAULT_START_LOCATION = 1;
$DISPLAY_INTRO = "display_intro_text";  //A flag indicating that the introductory text should be returned.
$STATUS_SAME_AREA = 0;                  //Indicates that the input has NOT caused the location to change.
$STATUS_NEW_AREA = 1;                   //Indicates that the location has changed.
$STATUS_EXIT = 2;                       //Indicates that the current game has ended.

//Start the session so we can get the TextWorld object (if it exists).
session_start();

//Check if the object is on the session.
$world = $_SESSION['text_world_object'];
$user_input = $_GET['user_input'];
if (empty($user_input) || !isset($world))
{
    /*
     * Create a new TextWorld object. Since this is not already on the session, it must 
     * be the starting location so the default values can be used. We store the object 
     * on the session at the end of this script after we have finished modifying it.
     */
    $world = new TextWorld($SIZE_OF_WORLD, $DEFAULT_START_LOCATION);
    $response = $world->generateAreaInfo() . "\n\n";
}
else
{
    if (isset($world))
    {
        //We must call 'unserialize' to recreate the object from the String we retrieved from the session.
        $world = unserialize($world);
    }
}

if (!empty($user_input))
{
    if ($user_input == $DISPLAY_INTRO)
    {
        //Retrieve the text for the introduction screen.
        $response = $world->getIntroText();
        $world->setStatus($STATUS_NEW_AREA);
    }
    else
    {
        $original_area_id = $world->getCurrentAreaId();
        $response = $world->getCommandResponse($user_input); //This will change area_id if a valid direction was provided.
        $new_area_id = $world->getCurrentAreaId();

        //Check if the location has changed.
        if ($original_area_id == $new_area_id)
        {
            //User stays in same area...
            $response = "> " . $user_input . "\n" . $response . "\n\n";
        }
        else
        {
            //User goes to a new area...
            $response = $world->generateAreaInfo() . "\n\n";
        }
    }
}

$status = $world->getStatus();
if ($status == $STATUS_EXIT)
{
    //Destroy the current session since the game has ended.
    session_destroy();
}
else
{
    //We must serialize the object so that it can be saved on the session.
    $_SESSION['text_world_object'] = serialize($world);
}

//Output the XML response...
?>

<response>
  <status><?php echo $status ?></status>
  <text><?php echo stripslashes($response) ?></text>
</response>

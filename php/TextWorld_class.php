<?php
/**
 * This class represents a text world where a user can move around from one square to another.
 * It determines all valid moves and communicates them to the user. The user can enter commands 
 * and the class will generate a response and (if required) act on that command.
 *
 * So far, the user is only able to move in the world. They are NOT able to interact with it.
 * Further functionality could be added in future versions.
 *
 * NOTE: This is reliant on a MySQL database.
 *
 * @author Paul Hitz
 * @version 040824
 */
class TextWorld
{
    //Constants.
    var $INVALID_MOVE_TEXT = "You are not able to move that way. Valid moves from this location are: ";
    var $VOCABULARY_TEXT = "The vocabulary in this version is extremely limited. Direction commands like South, South-east, North etc are available (and abbreviations like 'se' for South-East). Other commands like talk, look, use, examine etc are recognised but not implemented. All commands are case-insensitive.\n\nHelp, ?                -    Displays help text\nVocabulary, Vocab, V   -    Displays available commands\nQuit, Exit, Bye        -    Exits the game\nLook, L                -    Displays information about the current area\nCredits                -    Displays developer information\nVersion                -    Displays the version number of the game\nTalk, Say              -    &lt;command not implemented&gt;\nJump                   -    &lt;command not implemented&gt;\nUse                    -    &lt;command not implemented&gt;\nExamine                -    &lt;command not implemented&gt;\nMap                    -    &lt;command not implemented&gt;\nGet                    -    &lt;command not implemented&gt;\nDrop                   -    &lt;command not implemented&gt;\nSave                   -    &lt;command not implemented&gt;\nLoad                   -    &lt;command not implemented&gt;";
    var $HELP_TEXT = "Type a direction (e.g. South) to move to another location. Type 'v' for a full list of commands. That's about it really!";
    var $INVALID_COMMAND_TEXT = "Command not recognised. Type vocabulary (or 'v' for short) to see a list of available commands.";
    var $HORIZ_OFFSET = 1;
    var $VERT_OFFSET;
    var $SIZE_OF_WORLD;
    var $STATUS_SAME_AREA = 0;    //Indicates that the input has NOT caused the location to change.
    var $STATUS_NEW_AREA = 1;     //Indicates that the location has changed.
    var $STATUS_EXIT = 2;         //Indicates that the current game has ended.

    //Variables.
    var $response;
    var $current_status = 1;      //The current status. The default is 1 ($STATUS_NEW_AREA).
    var $quit_current_game = false;
    var $current_area_id;
    var $valid_moves;             //A hash array. Human-Readable Direction => Numeric Direction.


    /**
     * Creates a new TextWorld object with the specified size and current position.
     *
     * @param $size The number of locations in the world. 
     * @param $area_id The current location of the user in the world.
     */
    function TextWorld($size, $area_id)
    {
        $this->SIZE_OF_WORLD = $size;
        $this->VERT_OFFSET = sqrt($size);
        $this->setCurrentAreaId($area_id);
    }

    /**
     * Generates all valid moves from the current location. First it determines 
     * the area_id of each possible direction and then it checks which of those 
     * are valid.
     *
     * @param $area_id The current location of the user in the world.
     */
    function generateValidMoves($area_id)
    {
        $all_moves["North"] = $area_id - $this->VERT_OFFSET;            //North is x - diagonal offset.
        $all_moves["South"] = $area_id + $this->VERT_OFFSET;            //South is x + diagonal offset.
        $all_moves["East"] = $area_id + 1;                              //East is x + 1.
        $all_moves["West"] = $area_id - 1;                              //West is x - 1.
        $all_moves["North-East"] = $area_id - $this->VERT_OFFSET + 1;   //North-East is x - diagonal offset + 1.
        $all_moves["North-West"] = $area_id - $this->VERT_OFFSET - 1;   //North-West is x - diagonal offset - 1.
        $all_moves["South-East"] = $area_id + $this->VERT_OFFSET + 1;   //South-East is x + diagonal offset + 1.
        $all_moves["South-West"] = $area_id + $this->VERT_OFFSET - 1;   //South-West is x + diagonal offset - 1.

        //Now check which are valid.
        foreach ($all_moves as $key => $single_move)
        {
            //If the current element is NOT a valid move then remove it from the array.
            if (!$this->isValidMove($single_move, $area_id))
            {
                unset($all_moves[$key]);
            }
        }

        //The array now contains only valid moves so set it.
        $this->valid_moves = $all_moves;
    }

    /**
     * Checks if the supplied location is a valid move from the current location.
     *
     * @param $new The location the user wants to move TO
     * @param $current The current location.
     * @return True if it is a valid move; otherwise return false.
     */
    function isValidMove($new, $current)
    {
        //Check if the computed area ID is a possible value.
        if ($new < 1 || $new > $this->SIZE_OF_WORLD)
        {
            return false;
        }
     
        //Check if the current area is NOT on the edge. If not then all the computed IDs should be valid.
        if (!$this->isOnEdge($current))
        {
            return true;
        }
        else
        {
            //The current area is on the edge of the grid.
            if(!$this->isCorner($current))
            {
                //Check edge on the right.
                if ($current % $this->VERT_OFFSET == 0)
                {
                    if ($new == $current + 1 || $new == $current - $this->VERT_OFFSET + 1 || $new == $current + $this->VERT_OFFSET + 1)
                    {
                        return false;
                    }
                }
                
                //Check edge on the left.
                if (($current - 1) % $this->VERT_OFFSET == 0)
                {
                    if ($new == $current - 1 || $new == $current - $this->VERT_OFFSET - 1 || $new == $current + $this->VERT_OFFSET - 1)
                    {
                        return false;
                    }
                }               
                return true;
            }
            else
            {
                //Check first corner. Top Left.
                if ($current == 1)
                {
                    if ($new == $current + 1 || $new == $current + $this->VERT_OFFSET || $new == $current + $this->VERT_OFFSET + 1)
                    {
                        return true;
                    }
                }

                //Check second corner. Top Right.
                if ($current == $this->VERT_OFFSET)
                {
                    if ($new == $current - 1 || $new == $current + $this->VERT_OFFSET || $new == $current + $this->VERT_OFFSET - 1)
                    {
                        return true;
                    }
                }

                //Check third corner. Bottom Left.
                if ($current == $this->SIZE_OF_WORLD - ($this->VERT_OFFSET - 1))
                {
                    if ($new == $current + 1 || $new == $current - $this->VERT_OFFSET || $new == $current - $this->VERT_OFFSET + 1)
                    {
                        return true;
                    }
                }

                //Check fourth corner. Bottom Right.
                if ($current == $this->SIZE_OF_WORLD)
                {
                    if ($new == $current - 1 || $new == $current - $this->VERT_OFFSET || $new == $current - $this->VERT_OFFSET - 1)
                    {
                        return true;
                    }
                }
                return false;
            }
        }
        return true;
    }

    /**
     * Checks if the supplied location is on a corner of the map. If so, 
     * special consideration will have to be given to it (in the calling method).
     *
     * @param $area_id The location to check.
     * @return True if the supplied location is on a corner; otherwise return false.
     */
    function isCorner($area_id)
    {
        //Check the first and last squares.
        if ($area_id == 1 || $area_id == $this->SIZE_OF_WORLD)
        {
            return true;
        }
        
        //Check the other 2 corners.
        if ($area_id == $this->VERT_OFFSET || $area_id == ($this->SIZE_OF_WORLD - ($this->VERT_OFFSET - 1)))
        {
            return true;
        }
        return false;
    }

    /**
     * Checks if the supplied location is on an edge of the map. If so, 
     * special consideration will have to be given to it (in the calling method).
     *
     * @param $area_id The location to check.
     * @return True if the supplied location is on an edge; otherwise return false.
     */
    function isOnEdge($area_id)
    {
        //Check if the area is on the very top or very bottom.
        if ($area_id < $this->VERT_OFFSET || $area_id > ($this->SIZE_OF_WORLD - $this->VERT_OFFSET))
        {
            return true;
        }

        //Check if the area is on one of the sides.
        if ($area_id % $this->VERT_OFFSET == 0 || ($area_id - 1) % $this->VERT_OFFSET == 0)
        {
            return true;
        }
        return false;
    }

    /**
     * Combines all the separate information about the specified area into one string.
     *
     * @return Information about the area.
     */
    function generateAreaInfo()
    {
        $area = $this->getCurrentAreaId();

        //Get information about the current area.
        $area_query = mysql_query("select * from wb_areas where area_id = '$area'");
        $area_row = mysql_fetch_array($area_query);

        //Get the name of the current region.
        $region_query = mysql_query("select * from wb_regions where region_id = '$area_row[region_id]'");
        $region_row = mysql_fetch_array($region_query);

        //Construct a string to store information about the current area.
        $info = "Region: " . $region_row[name] . "\n";
        $info .= "Area: " . $area_row[name] . "\n";
        $info .= "Description: " . $area_row[description] . "\n\n";

        //Get the resources of the current area.
        $resources_query = mysql_query("select * from wb_resources_lookup_table where area_id = '$area'");
        $num_of_resources = mysql_num_rows($resources_query);

        if ($num_of_resources == 0)
        {
            $resources[] = "none";
        }
        else
        {
            while ( $resources_row = mysql_fetch_array($resources_query) )
            {
                $resource_query = mysql_query("select * from wb_resources where resource_id = '$resources_row[resource_id]'");
                $resource_row = mysql_fetch_array($resource_query);
                $resources[] = $resource_row[name];
            }
        }

        $info .= "Area Resources: " . implode(", ", $resources) . ".";
        $info .= "\nValid Moves: " . $this->getValidMovesString();
        return $info;
    }

    /**
     * Generate and return a response to the specified command.
     *
     * @param $command The specified command.
     * @return A response to the command.
     */
    function getCommandResponse($command)
    {
        $command = strtolower($command);

        /*
         * Get the first word of the command and ignore the rest.
         *
         * Note: This will have to be changed in the future because 
         * we will have to know what the user is referring to.
         */
        $command_words = explode(" ", $command);
        $command = $command_words[0];

        //Check if a quit command was previously sent.
        if ($this->quit_current_game)
        {
            if ($command == "yes" || $command == "y")
            {
                //Quit the game. Return the game intro text and set the status accordingly.
                $this->current_status = $this->STATUS_EXIT;
                $this->response = $this->getIntroText();
            }
            else
            {
                //Abort the quit command.
                $this->response = "Exit Aborted";
            }
            $this->quit_current_game = false;
        }
        else
        {
            //Check if it is a direction. if not, check what other command it could be.
            if (!$this->isDirection($command))
            {
                switch ($command)
                {
                    case "examine":
                        $this->response = "&lt;examine command not implemented&gt;";
                        break;
                    case "vocabulary":
                        $this->response = $this->VOCABULARY_TEXT;
                        break;
                    case "vocab":
                        $this->response = $this->VOCABULARY_TEXT;
                        break;
                    case "v":
                        $this->response = $this->VOCABULARY_TEXT;
                        break;
                    case "look":
                        $this->response = "You look at your surroundings...\n\n" . $this->generateAreaInfo();
                        break;
                    case "l":
                        $this->response = "You look at your surroundings...\n\n" . $this->generateAreaInfo();
                        break;
                    case "use":
                        $this->response = "&lt;use command not implemented&gt;";
                        break;
                    case "jump":
                        $this->response = "WHEEEEEE!";
                        break;
                    case "quit":
                        $this->quit_current_game = true;
                        $this->response = "Are you sure you want to quit this game? (yes/no)";
                        break;
                    case "exit":
                        $this->quit_current_game = true;
                        $this->response = "Are you sure you want to quit this game? (yes/no)";
                        break;
                    case "bye":
                        $this->quit_current_game = true;
                        $this->response = "Are you sure you want to quit this game? (yes/no)";
                        break;
                    case "get":
                        $this->response = "Get it yourself. &lt;get command not implemented&gt;";
                        break;
                    case "drop":
                        $this->response = "&lt;drop command not implemented&gt;";
                        break;
                    case "hello":
                        $this->response = "Hi, I think you're looking for Eliza. She's not here at the moment. She's the talkative one.";
                        break;
                    case "hi":
                        $this->response = "Hi, I think you're looking for Eliza. She's not here at the moment. She's the talkative one.";
                        break;
                    case "talk":
                        $this->response = "Hi, I think you're looking for Eliza. She's not here at the moment. She's the talkative one.";
                        break;
                    case "say":
                        $this->response = "Hi, I think you're looking for Eliza. She's not here at the moment. She's the talkative one.";
                        break;
                    case "map":
                        $this->response = "&lt;map command not implemented&gt;";
                        break;
                    case "credits":
                        $this->response = "Created By: Paul Hitz\nASCII Art: www.chris.com";
                        break;
                    case "version":
                        $this->response = "Version 0.3 (060628) - technology prototype";
                        break;
                    case "help":
                        $this->response = $this->HELP_TEXT;
                        break;
                    case "?":
                        $this->response = $this->HELP_TEXT;
                        break;
                    case "save":
                        $this->response = "&lt;save command not implemented&gt;";
                        break;
                    case "load":
                        $this->response = "&lt;load command not implemented&gt;";
                        break;
                    case "quick_exit":
                        //Quit the game. Return the game intro text and set the status accordingly.
                        $this->current_status = $this->STATUS_EXIT;
                        $this->response = $this->getIntroText();
                        break;
                    default:
                        $this->response = $this->INVALID_COMMAND_TEXT;
                }
            }
        }
        return $this->response;
    }

    /**
     * Checks if the supplied command is a direction. If so, it checks that 
     * the direction is valid and changes the current location.
     *
     * @param $possible_direction The supplied command that may be a direction.
     * @return True if the supplied command is a direction; otherwise return false.
     */
    function isDirection($possible_direction)
    {
        //Determine the diagonal offsets. Used to determine some of the directions.
        $diag_offset_large = $this->VERT_OFFSET + 1;
        $diag_offset_small = $this->VERT_OFFSET - 1;

        $current = $this->getCurrentAreaId();
        switch ($possible_direction)
        {
            case "north":
                $new = $current - $this->VERT_OFFSET;
                break;
            case "n":
                $new = $current - $this->VERT_OFFSET;
                break;
            case "south":
                $new = $current + $this->VERT_OFFSET;
                break;
            case "s":
                $new = $current + $this->VERT_OFFSET;
                break;
            case "east":
                $new = $current + $this->HORIZ_OFFSET;
                break;
            case "e":
                $new = $current + $this->HORIZ_OFFSET;
                break;
            case "west":
                $new = $current - $this->HORIZ_OFFSET;
                break;
            case "w":
                $new = $current - $this->HORIZ_OFFSET;
                break;
            case "north-west":
                $new = $current - $diag_offset_large;
                break;
            case "nw":
                $new = $current - $diag_offset_large;
                break;
            case "south-west":
                $new = $current + $diag_offset_small;
                break;
            case "sw":
                $new = $current + $diag_offset_small;
                break;
            case "north-east":
                $new = $current - $diag_offset_small;
                break;
            case "ne":
                $new = $current - $diag_offset_small;
                break;
            case "south-east":
                $new = $current + $diag_offset_large;
                break;
            case "se":
                $new = $current + $diag_offset_large;
                break;
            default : 
                $this->current_status = $this->STATUS_SAME_AREA;
                return false;        
        }

        //Check if this is a valid move. If so, set it as the current area and generate new valid moves.
        if ($this->isValidMove($new, $current))
        {
            $this->setCurrentAreaId($new);
            $this->generateValidMoves($new);
            $this->current_status = $this->STATUS_NEW_AREA;
        }
        else
        {
            $this->generateValidMoves($current);
            $this->response = $this->INVALID_MOVE_TEXT . $this->getValidMovesString();
            $this->current_status = $this->STATUS_SAME_AREA;
        }
        
        return true;
    }

    /**
     * Get the array of all valid moves.
     *
     * @return The array of all valid moves.
     */
    function getValidMoves()
    {
        return $this->valid_moves;
    }

    /**
     * Gets the String representation of all valid moves. E.g. "North, South, East" etc
     * 
     * @return The String representation of all valid moves.
     */
    function getValidMovesString()
    {
        $moves = $this->getValidMoves();
        
        //Check that valid moves have been computed. They will not have been if this is the first go.
        if(empty($moves))
        {
            $this->generateValidMoves($this->getCurrentAreaId());
            $moves = $this->getValidMoves();
        }

        /*
         * The human-readable direction is stored as the key so create a new array containing just 
         * the keys so we can use 'implode'. Alternatively we could have swapped the keys and values
         * when creating the array but it's no biggie.
         */
        foreach ($moves as $key => $single_move)
        {
            $movement_strings[] = $key;
        }
        return implode(", ", $movement_strings) . ".";
    }

    /**
     * Set the current location of the user in the world.
     *
     * @param $area_id The current location of the user in the world.
     */
    function setCurrentAreaId($area_id)
    {
        $this->current_area_id = $area_id;
    }

    /**
     * Get the current location of the user.
     *
     * @return The current location of the user.
     */
    function getCurrentAreaId()
    {
        return $this->current_area_id;
    }

    /**
     * Get the response to a command.
     *
     * @return The response to a command.
     */
    function getResponse()
    {
        return $this->response;
    }

    /**
     * Get the introduction text from the database. The introduction text is stored at 
     * position zero in the table.
     *
     * @return The introduction text that was received from the database.
     */
    function getIntroText()
    {
        $area = 0; //Intro text is stored with an ID of zero. 
        $area_query = mysql_query("select * from wb_areas where area_id = '$area'");
        $area_row = mysql_fetch_array($area_query);
        return $area_row[description];
    }

    /**
     * Get the current status. The status indicates if the UI should use a new screen or 
     * if the game has been ended.
     *
     * @return The current status.
     */
    function getStatus()
    {
        return $this->current_status;
    }

    /**
     * Set the current status.
     *
     * @param $supplied_status The status provided by the user.
     */
    function setStatus($supplied_status)
    {
        $this->current_status = $supplied_status;
    }
}//end class

?>
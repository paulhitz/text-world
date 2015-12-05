/**
 * This JavaScript is responsible for facilitating Ajax communication 
 * between the UI and server. It is also responsible for configuring the UI.
 *
 * @author Paul Hitz
 * @version 060628
 */

//The php script that is responsible for processing the commands and outputting the XML response.
var AJAX_URL = "php/ajax_bridge.php";

//The xmlHttpRequest will have a ready state of 4 when it has received a response.
var RESPONSE_RECEIVED_STATE = 4;

//These constants indicate if the response should be displayed on its own or with the previous entries.
var STATUS_SAME_AREA = 0;
var STATUS_NEW_AREA = 1;
var STATUS_EXIT = 2;

//The xmlHttpRequest object which gets the response from the php script.
var xmlHttp = getXmlHttpObject(stateChanged);

//Indicates that the current page is the introductory page.
var isIntroPage = true;

/**
 * Setup the page by applying focus to the user input text field
 * and by displaying the introduction screen.
 */
function setupDisplay()
{
    //Display the introduction in the console.
    var console = document.getElementById("console");
    console.value = "";
    processResponse("display_intro_text");

    //Put the focus on the user input text field.
    var userInput = document.getElementById("user_input");
    userInput.focus();
}

/**
 * Retrieve the user-supplied command and pass it to the xmlHttpRequest object.
 *
 * @return false always
 */
function processCommand()
{
    var userInput = document.getElementById("user_input");
    if (userInput.value !== "")
    {
        processResponse(userInput.value); //Send the user input.
        userInput.value = "";             //Manually clear the textbox.
    }

    //We never want this form to be submitted so always return false.
    return false;
}

/**
 * This is called every time the user presses a key. If the intro is currently 
 * displayed and the user presses space then the game will start.
 * Otherwise the key stroke will be ignored.
 *
 * @param keyStroke The key that the user pressed.
 */
function startGame(keyStroke)
{
    if (isIntroPage)
    {
        var userInput = document.getElementById("user_input");
        if (keyStroke == " ") //only if it's space
        {
            isIntroPage = false;
            var console = document.getElementById("console");
            console.style.color = "#ffffff";
            processResponse("");
        }
        else
        {
            //Ignore key press.
        }
        userInput.value = "";
    }
}

/**
 * End the game without prompting the user. This will destroy the session.
 */
function endGame()
{
    processResponse("quick_exit");
}

/**
 * Construct the request URL containing the user-supplied input. Initialise the 
 * xmlHttpRequest object and open a connection to the server using this URL.
 *
 * @param input The user-supplied input.
 */
function processResponse(input)
{
    var url = AJAX_URL + "?user_input=" + encodeURIComponent(input);
    xmlHttp = getXmlHttpObject(stateChanged); 
    xmlHttp.open("GET", url , true);
    xmlHttp.send(null);
}

/**
 * This function gets called whenever the readystate of the xmlHttpRequest object changes. 
 * If the readystate is set to response received, then the function gets the XML response and 
 * uses this to update the console.
 */
function stateChanged() 
{ 
    if (xmlHttp.readyState == RESPONSE_RECEIVED_STATE || xmlHttp.readyState == "complete")
    {
        //Retrieve the XML response.
        var xmlMessage = xmlHttp.responseXML;
        var response = xmlMessage.getElementsByTagName("text")[0].firstChild.nodeValue;
        var statusType = xmlMessage.getElementsByTagName("status")[0].firstChild.nodeValue;

        //Display the response either on it's own or concatenated with the existing text.
        var console = document.getElementById("console");
        if (statusType == STATUS_EXIT)
        {
            console.value = response;
            console.style.color = "yellow";
            isIntroPage = true;
        }
        else if (statusType == STATUS_SAME_AREA)
        {
            console.value = console.value + response;
        }
        else
        {
            console.value = response;
        }

        //Scroll the textarea to the bottom.
        console.scrollTop = console.scrollHeight;
    } 
}


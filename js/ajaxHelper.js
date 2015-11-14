/**
 * Returns the XmlHttpRequest object specific to the user's browser.
 *
 * Tested in Internet Explorer 6, Firefox 1.5 and Opera 9.
 *
 * @param handler The JavaScript function to call when the readyState changes.
 * @return the XmlHttpRequest object.
 */
function getXmlHttpObject(handler)
{ 
    var objXmlHttp = null;

    if (navigator.userAgent.indexOf("MSIE") >=0 )   //Internet Explorer
    {
        var strName = "Msxml2.XMLHTTP";
        if (navigator.appVersion.indexOf("MSIE 5.5") >=0 )
        {
            strName = "Microsoft.XMLHTTP";
        } 
        try
        { 
            objXmlHttp = new ActiveXObject(strName);
            objXmlHttp.onreadystatechange = handler;
            return objXmlHttp;
        } 
        catch(e)
        { 
            alert("Ajax Error. ActiveX may be disabled. \nEnsure that ActiveX is enabled and retry.");
            return;
        }
    }
    else if(window.XMLHttpRequest)                  //Mozilla, Opera, Safari etc.
    {
        objXmlHttp = new XMLHttpRequest();
        objXmlHttp.onload = handler;
        objXmlHttp.onerror = handler; 
        return objXmlHttp;
    }
    else
    {
        alert("Your browser is not supported.");
    }
}

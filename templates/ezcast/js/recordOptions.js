window.execScript = undefined;

function requestUrl(url, method = null) {
    let http_request = false;
    let send = "";
    const fields   = document.getElementsByName("courseid[]");
    const recordid = document.getElementsByName("recordid")[0].value;
    const cmdtype  = document.getElementsByName("cmdtype")[0].value;

    method = (method) ? "POST" : "GET";

    if (window.XMLHttpRequest) {
        http_request = new XMLHttpRequest();
        if (http_request.overrideMimeType) {
            http_request.overrideMimeType('text/xml');
        }
    } else if (window.ActiveXObject) { // IE
        try {
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            let http_request;
            try {
                http_request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
            }
        }
    }
    if (!http_request) {
        alert('This option is not support by your browser please update your browser !');
        return false;
    }
    http_request["onreadystatechange"]=function(){
        if (http_request.readyState===4 && http_request.status===200){
            const div_element = document.getElementById(cmdtype + "_ans");
            div_element.innerHTML=http_request.responseText;

            //makes sure the scripts contained in the page are executed after being
            //loaded by ajax
            const scripts = div_element.getElementsByTagName('script');
            for(var i=0; i < scripts.length;i++)
            {
                // if IE, we have to use execScript to define functions as global
                if (window.execScript)
                {
                    //Replaces the HTML comments because IE doesn't handle them well
                    window.execScript(scripts[i].text.replace('<!--',''));
                }
                // if any other web browser, we use a simple window.eval()
                else
                {
                    window.eval(scripts[i].text);
                }
            }
        }
    };

    http_request.open(method, url, true);
    if(method === "POST"){
        http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    }

    for(let i = 0; i < fields.length; i++) {
        if(fields[i].checked){
            send += "courseid[]=" + fields[i].value + "&";
        }
    }
    send += "recordid=" + recordid;
    send += "&type=" + cmdtype;
    http_request.send(send);
}

function recordOptions(recordID,option,sessionID){
    const xhr = new XMLHttpRequest();
    if(option === "hideshow"){
        document.getElementById("hideshow_" + recordID).disabled = true;
        xhr.open("GET", "ajax/records/visibility.php?id=" + recordID + "&sessionID=" + sessionID, false);
        xhr.send();
        var answer = parseInt(xhr.response);
        if(answer === 0) {
            document.getElementById("visibility_" + recordID).classList.remove("fa-eye");
            document.getElementById("visibility_" + recordID).classList.add("fa-eye-slash");
            document.getElementById("record_field_" + recordID).style.background = "none";
        }
        else if(answer === 1){
            document.getElementById("visibility_" + recordID).classList.remove("fa-eye-slash");
            document.getElementById("visibility_" + recordID).classList.add("fa-eye");
            document.getElementById("record_field_" + recordID).style.background = "rgba(255,0,0,0.02)";
        }
        else{
            document.getElementById("visibility_" + recordID).classList.remove("fa-eye");
            document.getElementById("visibility_" + recordID).classList.add("fa-eye-slash");
            document.getElementById("record_field_" + recordID).style.background = "none";
        }
        document.getElementById("hideshow_" + recordID).disabled = false;
    }
    else if(option === "copy" || option === "move"){
        var answerDiv = document.getElementById( option + "_content");
        xhr.open("GET", "ajax/records/course_list.php?id=" + recordID + "&type=" + option + "&sessionID=" + sessionID, false);
        xhr.send();

        answerDiv.innerHTML = xhr.response;
    }

    else if(option === "delete"){

    }
}

function recordCommand(sessionID) {
    requestUrl("ajax/records/copy.php?sessionID=" + sessionID,"POST");
    return false;
}
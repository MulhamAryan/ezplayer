function getComments(recordid,hash) {
    var comments = document.getElementById("comments");
    var xhr = new XMLHttpRequest();
    xhr.onload = function (){
        if(xhr.status === 200){
            comments.innerHTML = xhr.response;
        }
    };
    xhr.open("GET","ajax/comment/loadcomment.php?recordid=" + recordid + "&hash=" + hash,true);
    xhr.send();
}

function refreshCommentZone(xhr,data) {
    var commentErrorZone = document.getElementById("comment_error_zone");
    var commentText = document.getElementById("commentText");
    commentText.value = "";
    var alert;
    var objResponse = JSON.parse(xhr.responseText);
    alert = (objResponse.error === true ? "alert-danger" : "alert-success");
    commentErrorZone.innerHTML = '<div class="alert ' + alert + ' mt-2">' + objResponse.msg + '</div>';
    getComments(data.get("recordid"),data.get("hash"));
}

function xhrPost(link,event) {
    const data = new FormData;
    for (const [key, val] of Object.entries(event)) {
        data.append(key, val);
    }
    const xhr = new XMLHttpRequest();
    xhr.open("POST",link,false);
    xhr.onload = function (evt) {
        if(xhr.status === 200){
            refreshCommentZone(xhr,data);
        }
        else{
            document.getElementById("noconnection").style.display = "block";
        }
    };
    xhr.send(data);
}

function addComment(event) {
    let commentZone = document.getElementById("comments");
    let recordid    = event[0];
    let sessionid   = event[1];
    let hash        = event[2];
    let comment     = event[3];
    if(comment.value) {
        let data = {
            recordid  : recordid.value,
            sessionid : sessionid.value,
            comment   : comment.value,
            hash      : hash.value
        };
        xhrPost(event.action,data);
    }
    else{
        comment.style.border = "1px solid #FF0000";
    }
    return false;
}
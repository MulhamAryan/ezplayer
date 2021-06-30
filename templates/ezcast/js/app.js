window.onload = function () {
    const sideBarBtn      = document.getElementById("leftSideBarButton");
    const homeLeftSideBar = document.getElementById("homeLeftSideBar");
    const leftSideBarLink = document.getElementById("leftSideBarLink");
    const selectAll       = document.getElementById("selectAll");
    var leftIsBarOpen;

    sideBarBtn.onclick = function () {
        if (homeLeftSideBar.style.display === "none") {
            homeLeftSideBar.style.display = "block";
            leftIsBarOpen = 1;
        } else {
            homeLeftSideBar.style.display = "none";
            leftIsBarOpen = 0;
        }
    };

    leftSideBarLink.onclick = function () {
        if (leftIsBarOpen === 1){
            homeLeftSideBar.style.display = "none";
            leftIsBarOpen = 0;
        }
    };
};

function getDocumentCooke(cname) {
    let cookieName = cname + "=";
    let decodeCookie = decodeURIComponent(document.cookie);
    let valueArray = decodeCookie.split(";");
    for(var i = 0; i < valueArray.length; i++){
        var value = valueArray[i];
        while (value.charAt(0) === ' ') {
            value = value.substring(1);
        }
        if (value.indexOf(cookieName) === 0) {
            return value.substring(cookieName.length, value.length);
        }
    }
    return "";
}

function selectAll() {
    var recordsList = document.getElementsByName("recordlist");
    const selectAll = document.getElementById("selectAll");
    if(selectAll.checked === true){
        document.getElementById("group_delete").disabled = false;
        document.getElementById("group_move").disabled = false;
        document.getElementById("group_hide").disabled = false;
        for (var checkRecord of recordsList){
            checkRecord.checked = true;
        }
    }
    else {
        document.getElementById("group_delete").disabled = true;
        document.getElementById("group_move").disabled = true;
        document.getElementById("group_hide").disabled = true;
        for (var uncheckedRecord of recordsList){
            uncheckedRecord.checked = false;
        }
    }
}

function loadCourse(title,id) {
    window.history.pushState("object or string", title, "course.php?id=" + id);
    return HttpGetRequest("course.php?id=" + id + "&ajax=1","homepage");
    //return false;
}

function HttpGetRequest(url, div) {
    const xhr = new XMLHttpRequest();
    const divAns = document.getElementById(div);
    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            divAns.innerHTML = xhr.responseText;
        }
    };
    let token = getDocumentCooke("PHPSESSID");
    xhr.open("GET", url, true);
    xhr.setRequestHeader('Accept','text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8;coursetoken=' + token);
    xhr.send();
}

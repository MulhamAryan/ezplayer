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

function loadCourse(id) {
    window.history.pushState("object or string", "Title", "course.php?id=" + id);
    return HttpRequest("course.php?id=" + id + "&ajax=1");
    //return false;
}

function HttpRequest(url) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", url, false);
    xhr.send();
    document.getElementById("homepage").innerHTML = xhr.responseText;
    return xhr;
}

window.onload = function () {
    let addnewbookmark  = document.getElementById("addnewbookmark");
    let newbookmarkzone = document.getElementById("newbookmarkzone");
    let bookmarkForm    = document.getElementById("bookmarkForm");
    addnewbookmark.addEventListener('click',function () {
        newbookmarkzone.style.display = "block";
    });
    
    bookmarkForm.addEventListener('submit', function (event) {
        event.preventDefault();
        let bookmark_title = document.getElementById("bookmark_title");
        let bookmark_description = document.getElementById("bookmark_description");
        let bookmark_timecode = document.getElementById("bookmark_timecode");
        let bookmark_public = document.getElementById("bookmark_public");
        bookmark_public.value = (bookmark_public.checked === true) ? 1 : 0;

        let record_id = document.getElementById("record_id");
        if(bookmark_title.value !== "" || bookmark_timecode.value !== "" ){
            let bookmarkData = {
                record_id: record_id.value,
                hash: hash,
                bookmark_title: bookmark_title.value,
                bookmark_description: bookmark_description.value,
                bookmark_timecode: bookmark_timecode.value,
                bookmark_public: bookmark_public.value
            };

            let bookmarkDataForm = new FormData;
            for (const [key, val] of Object.entries(bookmarkData)) {
                bookmarkDataForm.append(key, val);
            }

            const bookmarkxhr = new XMLHttpRequest();
            bookmarkxhr.open("POST", "ajax/bookmark/add_bookmark.php", false);
            bookmarkxhr.onload = function (evt) {
                if (bookmarkxhr.status === 200) {
                    bookmark_title.value = "";
                    bookmark_timecode.value = "00:00:00";
                    bookmark_description.value = "";
                    getBookmarks();
                } else {
                    document.getElementById("noconnection").style.display = "block";
                }
            };
            bookmarkxhr.send(bookmarkDataForm);
        }
        else{
            bookmark_title.style.border = "1px solid #FF0000";
            bookmark_timecode.style.border = "1px solid #FF0000";
        }
        return false;
    });
    getBookmarks();
};

function hms(seconds) {
    return [3600, 60]
        .reduceRight(
            (p, b) => r => [Math.floor(r / b)].concat(p(r % b)),
            r => [r]
        )(seconds)
        .map(a => a.toString().padStart(2, '0'))
        .join(':');
}

function getBookmarks() {
    let bookmarkErrorZone = document.getElementById("bookmarkErrorZone");
    let bookmarksZone = document.getElementById("bookmarksZone");

    const loadBookmarks = new XMLHttpRequest();
    loadBookmarks.open("GET", "ajax/bookmark/get_bookmarks.php?record_id=" + record_id + "&hash=" + hash, false);
    loadBookmarks.onload = function (evt) {
        if (loadBookmarks.status === 200) {
            const objBookmarks = JSON.parse(loadBookmarks.responseText);
            if(objBookmarks.error === true) {
                bookmarkErrorZone.innerHTML = '<div class="alert alert-danger mt-2">' + objBookmarks.msg + '</div>';
                bookmarksZone.style.display = "none";
            }
            else{
                document.getElementById("public-bookmark").innerHTML = "";
                document.getElementById("private-bookmark").innerHTML = "";
                for(const bookmark in objBookmarks){
                    let mainDiv = (objBookmarks[bookmark]["public"] === 0) ? "private-bookmark" : "public-bookmark";
                    let bookmarkDiv = document.createElement('div');

                    bookmarkDiv.className = "list-group-item list-group-item-action flex-column align-items-start border-top-0 border-right-0 border-left-0 border-bottom rounded-0 btn btn-success";

                    let newDiv = document.createElement('div');
                    newDiv.className = "d-flex w-100 justify-content-between";
                    newDiv.onclick = function(timeEvent){
                        setCurrentTime(objBookmarks[bookmark]["timecode"]); //This function is coming from ezcasthls.js
                    };
                    let newTitle = document.createElement('h6');
                    newTitle.className = "mb-1";

                    let timeCode = document.createElement('small');
                    let newDescription = document.createElement('p');
                    newDescription.className = "mb-1";

                    let divClear = document.createElement("div");
                    divClear.className = "clearfix";

                    let descriptionText = document.createTextNode(objBookmarks[bookmark]["description"]);
                    let TitleText = document.createTextNode(objBookmarks[bookmark]["title"]);
                    let timeCodeText = document.createTextNode(hms(objBookmarks[bookmark]["timecode"]));

                    newTitle.appendChild(TitleText);
                    timeCode.appendChild(timeCodeText);
                    newDescription.appendChild(descriptionText);

                    newDiv.appendChild(newTitle);
                    newDiv.appendChild(timeCode);
                    bookmarkDiv.appendChild(newDescription);
                    bookmarkDiv.appendChild(newDiv);

                    if(objBookmarks[bookmark]["canedit"] !== false){
                        let deleteBookmarks = document.createElement('i');
                        deleteBookmarks.style.color = "#ff0000";
                        deleteBookmarks.className = "float-right fas fa-times-circle";
                        deleteBookmarks.onclick = function (deleteEvent){
                            delete_bookmark(objBookmarks[bookmark]["id"],'' + objBookmarks[bookmark]["canedit"] + '');
                        };
                        bookmarkDiv.appendChild(deleteBookmarks);
                    }
                    bookmarkDiv.appendChild(divClear);

                    document.getElementById(mainDiv).appendChild(bookmarkDiv);

                }
            }
        } else {
            document.getElementById("noconnection").style.display = "block";
        }
    };
    loadBookmarks.send();
}

function delete_bookmark(bookmarkid,hashid){
    if(confirm(lang["delete_bookmark"])){
        const deleteBookmarkXhr = new XMLHttpRequest();
        deleteBookmarkXhr.open("GET", "ajax/bookmark/delete_bookmark.php?bookmarkid=" + bookmarkid + "&hash=" + hashid, false);
        deleteBookmarkXhr.onload = function (evt) {
            if (deleteBookmarkXhr.status === 200) {
                getBookmarks();
            }
        };
        deleteBookmarkXhr.send();
    }
}
$(document).ready(function (){
    // Need login start here
    const leftSideBarButton = $("#leftSideBarButton");
    const toggleLeftSideBar = $("#toggleLeftSideBar");
    const leftSideBarLink = $("#leftSideBarLink");
    const expandBarButton = $("#expandBarButton");
    const homeLeftSideBar = $("#homeLeftSideBar");
    var homeContent = $("#homeContent");
    var leftIsBarOpen;

    leftSideBarButton.on("click", function(){
        var myStyle = homeLeftSideBar.css("display");
        if(myStyle !== "none") {
            leftSideBarButton.find("i").toggleClass("fa-bars fa-times");
            homeLeftSideBar.css("display", "none");
            leftIsBarOpen = 0;
        }
        else {
            leftSideBarButton.find("i").toggleClass("fa-times fa-bars");
            homeLeftSideBar.css("display", "block");
            leftIsBarOpen = 1;
        }
    });

    leftSideBarLink.on("click",function(){
        if(leftIsBarOpen === 1){
            homeLeftSideBar.css("display", "none");
            leftSideBarButton.find("i").toggleClass("fa-times fa-bars");
        }
    });

    /*$(".openCourse").on("click",function (e) {
        var courseID = $(this).attr("id");
        homeContent.html( "loading ... " );
        $.ajax({
            url : 'ajax.php',
            type : 'GET',
            data : 'action=show_album&courseid=' + courseID + "&perpage=25&page=1rand=" + Date.now(),
            dataType : 'html',
            success : function(data,status){
                homeContent.html( data );
                window.history.pushState("object or string", "Title", "?action=show_album&courseid=" + courseID);
            },
            error : function (result,status,errror) {
                homeContent.html( "error" );
            }
        });
    });*/
    $('#select-all').click(function(event) {
        var album = $(this).attr("album");
        if(this.checked) {
            // Iterate each checkbox

            $(':checkbox').each(function() {
                //var album = $(this).attr("album");
                //alert(album);
                this.checked = true;
            });
        } else {
            $(':checkbox').each(function() {
                this.checked = false;
            });
        }
    });

    //End of login needed
});
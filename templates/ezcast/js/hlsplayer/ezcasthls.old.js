/*!
  * EZcastHLS Player v1.0.0-beta (https://ezcast.ulb.be/)
  * Copyright 2009-2021 The Bootstrap Authors (https://github.com/twbs/bootstrap/graphs/contributors)
  * Licensed under MIT (https://opensource.org/licenses/MIT)
  * Written by Mulham ARYAN <mulham.aryan@ulb.be>
  */

var progressBar   = document.getElementById("progressBar");
var volumeRange   = document.getElementById("volume");
var sound         = document.getElementById("sound_" + urlConfig["id"]);

video.addEventListener("dblclick",toggleFullScreen);

progressBar.onclick = function (e) {
    let childProgressBar = progressBar.children;
    let percentValue   = (e.pageX  - (this.offsetLeft + player.offsetParent.offsetLeft + 2)) / this.offsetWidth;
    childProgressBar[0].style.width = percentValue * 100 + "%";
    let currentTime = (percentValue * video.duration);
    setCurrentTime(currentTime,urlConfig["id"]);
};

sound.onclick = function (e){
    if(sound.children[0].className === "fas fa-volume-up"){
        sound.children[0].className = "fas fa-volume-mute";
        video.muted = true;
        volumeRange.value = 0;
    }
    else{
        sound.children[0].className = "fas fa-volume-up";
        video.muted = false;
        volumeRange.value = getCookie("videoVolume");
    }
};

setInterval(
    function () {
        updateStatus();
    }, 1000);

if(Hls.isSupported()){
    var configs = {
        startPosition:0,
        startLevel:0
    };
    let videoUrl = generateVideoURL(urlConfig);
    var hls = new Hls(configs);
    hls.loadSource(videoUrl);
    hls.attachMedia(video);
    hls.on(Hls.Events.MANIFEST_PARSED,function() {
        video.play();
    });
}
else if (video.canPlayType('application/vnd.apple.mpegurl')){
    video.src = videoUrl;
    video.addEventListener('loadedmetadata',function() {
        video.play();
    });
}
video.volume = getCookie("videoVolume");
volumeRange.value = getCookie("videoVolume");

function changeVolume(videoid,value){
    video.volume = value;
    setCookie("videoVolume",value);
}

function updateStatus() {
    updateTime(urlConfig["id"]);
    updateProgressBar(urlConfig["id"]);
    video.removeAttribute("controls");
}

function updateProgressBar(videoid){
    let answerID         = document.getElementById("progressBar_" + videoid);
    answerID.style.width = 100 * video.currentTime / video.duration + "%";
}

function setIconControl(videoid,value){
    let button = document.getElementById("play_" + videoid);
    let icon = button.children;
    let bigPlayBtn  = document.getElementsByClassName("middleplaybtn");
    if(value === "play"){
        button.setAttribute("current","play");
        icon[0].className = "fas fa-pause";
        bigPlayBtn[0].style.display = "none";
    }
    else{
        button.setAttribute("current","pause");
        icon[0].className = "fas fa-play";
        bigPlayBtn[0].style.display = "block";
    }
}

function playControl(videoid) {
    let button      = document.getElementById("play_" + videoid);
    let currentMode = button.getAttribute("current");
    let playButton  = document.getElementById("playButton_" + videoid);
    if(currentMode === "play"){
        hls.stopLoad  ();
        video.pause();
        setIconControl(videoid,"pause");
        playButton.style.display = "block";
    }
    else{
        hls.startLoad();
        video.play();
        setIconControl(videoid,"play");
        playButton.style.display = "none";
    }
}

function updateTime(videoid){
    let getDuration    = video.duration;
    let getCurrentTime = playingCurrentTime(videoid);
    let answerID       = document.getElementById("timer_" + videoid);
    Number.prototype.toHHMMSS = function() {
        var sec_num = parseInt(this, 10); // don't forget the second param
        var hours   = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        var seconds = sec_num - (hours * 3600) - (minutes * 60);

        if (hours   < 10) {hours   = "0"+hours;}
        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}
        return hours + ":" + minutes + ":" + seconds;
    };
    answerID.innerHTML = getCurrentTime.toHHMMSS() + " / " + getDuration.toHHMMSS();
}

function playingCurrentTime(videoid) {
    return video.currentTime;
}

function setCurrentTime(val,videoid) {
    video.currentTime = val;
}

function generateVideoURL(urlConfig) {
    return urlConfig["mainUrl"] + "?recordid=" + urlConfig["id"] + "&recordType=" + urlConfig["type"] + "&dir=" + urlConfig["folder"];
}

function toggleFullScreen(videoid) {
    let element    = document.getElementById("player_" + videoid);
    let collapse   = document.getElementById("collapse_" + videoid);
    let fullScreen = collapse.getAttribute("fullscreen");

    if(fullScreen === "false") {
        collapse.setAttribute("fullScreen","true");
        collapse.childNodes[0].className = "fas fa-compress";

        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.webkitRequestFullscreen) { /* Safari */
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) { /* IE11 */
            element.msRequestFullscreen();
        }
    }
    else{
        collapse.childNodes[0].className = "fas fa-expand";

        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) { /* Safari */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { /* IE11 */
            document.msExitFullscreen();
        }
    }
}

function changeQuality(videoid) {
    var qual = document.querySelector("#quality");
    currentQual = player.getAttribute("currentquality");
    if(currentQual === "high"){
        qual.innerHTML = "SD";
        qual.backgroundColor = "#414141";
        player.setAttribute("currentquality","low");
        hls.nextLevel = 1;
    }
    else{
        qual.innerHTML = "HD";
        qual.backgroundColor = "#a80000";
        player.setAttribute("currentquality","high");
        hls.nextLevel = 0;
    }
}

function changeView(view,videoid){
    let player = document.getElementById("player_" + videoid);
    let currentRecord = player.getAttribute("currentrecord");
    if(currentRecord !== view) {
        let videoCurrentTime = playingCurrentTime(videoid) - 2;

        urlConfig["type"] = (view === "camrecord") ? "camrecord" : "sliderecord";
        currentView = urlConfig["type"];

        videoUrl = generateVideoURL(urlConfig);

        player.setAttribute("currentrecord",view);
        hls.stopLoad();
        hls.destroy();

        hls = new Hls(configs);
        hls.loadSource(videoUrl);
        hls.attachMedia(video);
        setCurrentTime(videoCurrentTime,videoid);
        video.play();
    }
}

function setCookie(columnName,columnValue) {
    const date = new Date();
    date.setTime(date.getTime() + 31536000);
    const expires = "expires=" + date.toUTCString();
    document.cookie = columnName + "=" + columnValue + ";" + expires + ";path=/;SameSite=Lax";
}

function getCookie(columnName) {
    let cName = columnName + "=";
    let decodeCookie = decodeURIComponent(document.cookie);
    let valueArray = decodeCookie.split(";");
    for(var i = 0; i < valueArray.length; i++){
        var value = valueArray[i];
        while (value.charAt(0) === ' ') {
            value = value.substring(1);
        }
        if (value.indexOf(cName) === 0) {
            return value.substring(cName.length, value.length);
        }
    }
    return "";
}

video.onplaying = function () {
    setIconControl(urlConfig["id"],"play");
};

if(video.paused){
    setIconControl(urlConfig["id"],"pause");
}

player.addEventListener("contextmenu", function () {
    return false;
});

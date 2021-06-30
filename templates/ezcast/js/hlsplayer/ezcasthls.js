/*!
  * EZcastHLS Player v1.0.0-beta (https://ezcast.ulb.be/)
  * Copyright 2009-2021 The Bootstrap Authors (https://github.com/twbs/bootstrap/graphs/contributors)
  * Licensed under MIT (https://opensource.org/licenses/MIT)
  * Written by Mulham ARYAN <mulham.aryan@ulb.be>
  */

var currentQuality    = urlConfig["quality"];
var currentRecord     = urlConfig["type"];
var fullScreen        = false;
var player            = document.getElementById("player");
var timer             = document.getElementById("timer");
var video             = document.getElementById("video");
var quality           = document.getElementById("quality");
var camView           = document.getElementById("cam");
var slideView         = document.getElementById("slide");
var playToggleBtn     = document.getElementById("playToggleBtn");
var BigPlayButton     = document.getElementById("playButton");
var volumeRange       = document.getElementById("volume");
var sound             = document.getElementById("sound");
var collapse          = document.getElementById("collapse");
const duration        = document.getElementById('duration');
const seek            = document.getElementById('seek');
const seekTooltip     = document.getElementById('seek-tooltip');
var currentMode;

if(video.paused){
    currentMode = "pause";
}
else{
    currentMode = "play";
}

video.addEventListener("dblclick",toggleFullScreen);
video.addEventListener("click",playControl);
playToggleBtn.addEventListener("click",playControl);
BigPlayButton.addEventListener("click",playControl);
collapse.addEventListener("click",toggleFullScreen);
quality.addEventListener("click",changeQuality);
sound.addEventListener("click",toggleMute);
video.addEventListener('timeupdate',updateTime);
video.addEventListener('timeupdate',updateProgressBar);

volumeRange.addEventListener("change",function (evt) {
    changeVolume(this.value);
});
if(camView) {
    camView.addEventListener("click", function () {
        changeView("cam");
    });
}
if(slideView) {
    slideView.addEventListener("click", function () {
        changeView("sliderecord");
    });
}

if(Hls.isSupported()){
    var configs = {
        startPosition: urlConfig["startPosition"],
        startLevel: urlConfig["startLevel"]
    };
    let videoUrl = generateVideoURL();
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

video.addEventListener('waiting', function () {
    document.getElementById("wait").style.display = "block";
});

video.addEventListener('canplay', function () {
    document.getElementById("wait").style.display = "none";
});

video.addEventListener('loadedmetadata',initVideo);

seek.addEventListener('mousemove', updateSeekTooltip);
seek.addEventListener('input', skipTo);

function updateSeekTooltip(event) {
    const skipTo = Math.round((event.offsetX / event.target.clientWidth) * parseInt(event.target.getAttribute('max'), 10));
    seek.setAttribute('data-seek', skipTo);
    const t = formatTime(skipTo);
    seekTooltip.textContent = `${t.hours}:${t.minutes}:${t.seconds}`;
    const rect = video.getBoundingClientRect();
    seekTooltip.style.left = `${event.pageX - rect.left}px`;
}

function skipTo(event) {
    const skipTo = event.target.dataset.seek
        ? event.target.dataset.seek
        : event.target.value;
    video.currentTime = skipTo;
    seek.value = skipTo;
}


function formatTime(timeInSeconds) {
    const result = new Date(timeInSeconds * 1000).toISOString().substr(11, 8);

    return {
        hours: result.substr(0, 2),
        minutes: result.substr(3, 2),
        seconds: result.substr(6, 2),
    };
}
function initVideo(){
    const videoDuration = Math.round(video.duration);
    seek.setAttribute('max', videoDuration);
    const time = formatTime(videoDuration);
    if(duration) {
        duration.innerText = `${time.hours}:${time.minutes}:${time.seconds}`;
        duration.setAttribute('datetime', `${time.hours}h:${time.minutes}m ${time.seconds}s`);
    }
}

function changeVolume(value){
    if(value >= 1)
        value = 1;

    video.volume = value;
    volumeRange.value = value;
    setCookie("videoVolume",value);
}

function updateProgressBar(){
    seek.value = Math.floor(video.currentTime);
    let thisValue = seek.value * 100 / video.duration;
    seek.style.background = "linear-gradient(to right, #ffffff 0%, #ffffff " + thisValue + "%, #858585 " + thisValue + "%, #858585 100%)";
}

function setIconControl(value){
    let icon = playToggleBtn.children;
    let bigPlayBtn  = document.getElementsByClassName("middleplaybtn");
    if(value === "play"){
        currentMode = "play";
        icon[0].className = "fas fa-pause";
        bigPlayBtn[0].style.display = "none";
    }
    else{
        currentMode = "pause";
        icon[0].className = "fas fa-play";
        bigPlayBtn[0].style.display = "block";
    }
}

function playControl() {
    let playButton  = document.getElementById("playButton");
    if(currentMode === "play"){
        hls.stopLoad();
        video.pause();
        setIconControl("pause");
        playButton.style.display = "block";
    }
    else{
        hls.startLoad();
        video.play();
        setIconControl("play");
        playButton.style.display = "none";
    }
}

function updateTime(){
    let getDuration    = video.duration;
    let getCurrentTime = getVideoCurrentTime();
    let answerID       = document.getElementById("timer");
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

function getVideoCurrentTime() {
    return video.currentTime;
}

function setCurrentTime(val) {
    video.currentTime = val;
}

function generateVideoURL() {
    return urlConfig["mainUrl"] + "?recordid=" + urlConfig["id"] + "&recordType=" + urlConfig["type"] + "&dir=" + urlConfig["folder"] + "&hash=" + urlConfig["hashid"];
}

function toggleFullScreen() {
    let element    = document.getElementById("player");
    let collapse   = document.getElementById("collapse");
    if(fullScreen === false) {
        fullScreen = true;
        collapse.childNodes[0].className = "fas fa-compress";

        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }
    }
    else{
        collapse.childNodes[0].className = "fas fa-expand";

        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}

function changeQuality() {
    if(currentQuality === "high"){
        quality.innerHTML = "SD";
        quality.backgroundColor = "#414141";
        currentQuality = "low";
        hls.nextLevel = 1;
    }
    else{
        quality.innerHTML = "HD";
        quality.backgroundColor = "#a80000";
        currentQuality = "high";
        hls.nextLevel = 0;
    }
}

function changeView(view){
    if(currentRecord !== view) {
        let videoCurrentTime = getVideoCurrentTime() - 2;

        urlConfig["type"] = (view === "cam") ? "cam" : "slide";
        currentView = urlConfig["type"];

        videoUrl = generateVideoURL();

        currentRecord = "view";
        hls.stopLoad();
        hls.destroy();

        hls = new Hls(configs);
        hls.loadSource(videoUrl);
        hls.attachMedia(video);
        setCurrentTime(videoCurrentTime);
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

function toggleMute(){
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
}

player.addEventListener('contextmenu', function(ev) {
    ev.preventDefault();
    return false;
}, false);

video.onplaying = function () {
    setIconControl("play");
};

if(video.paused){
    setIconControl("pause");
}

document.addEventListener('keydown', (event) => {
    const keyName = event.key;
    if(document.activeElement.tagName === "TEXTAREA" || document.activeElement.tagName === "INPUT") {
        return false;
    }
    else{
        if (keyName === " ") {
            playControl();
        } else if (keyName === "q") {
            changeQuality();
        } else if (keyName === "c") {
            changeView("cam");
        } else if (keyName === "v") {
            changeView("slide");
        } else if (keyName === "m") {
            toggleMute();
        } else if (keyName === "ArrowUp") {
            changeVolume(video.volume + 0.05);
        } else if (keyName === "ArrowDown") {
            changeVolume(video.volume - 0.05);
        } else if (keyName === "ArrowRight") {
            setCurrentTime(video.currentTime + 10);
        } else if (keyName === "ArrowLeft") {
            setCurrentTime(video.currentTime - 5);
        } else if (keyName === "f") {
            toggleFullScreen();
        }
    }
}, false);

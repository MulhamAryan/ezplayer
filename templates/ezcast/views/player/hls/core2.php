<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://ezcasttest.ulb.ac.be/newezplayer//templates/ezcast/css//bootstrap/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://ezcasttest.ulb.ac.be/newezplayer//templates/ezcast/css//fontawesome/css/all.min.css" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

    <style>

        .container{
            position: relative;
            height: 100%;
        }

        .player{
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            background-color: #000;
        }

        .player .controlbar{
            padding: 10px 20px 10px 20px;
            color: #FFFFFF;
            background: -moz-linear-gradient(top, rgba(0,0,0,0) 0%, rgba(0,0,0,0.65) 100%); /* FF3.6-15 */
            background: -webkit-linear-gradient(top, rgba(0,0,0,0) 0%,rgba(0,0,0,0.65) 100%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to bottom, rgba(0,0,0,0) 0%,rgba(0,0,0,0.65) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#a6000000',GradientType=0 ); /* IE6-9 */
            width: 100%;
            position: absolute;
            bottom: 0;
        }
        .player:hover .controlbar{
            display: block;
        }
        .player .controlbar .timer{
            font-size: 13px;
        }
        .player .controlbar .float-right,.player .controlbar .float-left{
            margin-top: 10px;
        }
        .player video{
            height: 100%;
        }
        .player .controlbar span{
            margin-left:10px;
        }
        .player .controlbar span i{
            cursor: pointer;
            font-size: 20px;
        }
        .player .controlbar span i:hover{
            color: #dfe6e9;
        }

        .player .progress{
            border-radius: 0 !important;
            height: 0.4rem;
            cursor: pointer;
            -webkit-transition: all 0.2s ease;
            -moz-transition: all 0.2s ease;
            -ms-transition: all 0.2s ease;
            -o-transition: all 0.2s ease;
            transition: all 0.2s ease;
            opacity: 80%;
        }

        .progress:hover{
            height: 0.5rem !important;
            opacity: 100%;
        }

        .player .audiocontrol{
            display: inline-block;
            top: 0;
            margin: 0;
            -webkit-transition: all 0.2s ease;
            -moz-transition: all 0.2s ease;
            -ms-transition: all 0.2s ease;
            -o-transition: all 0.2s ease;
            transition: all 0.2s ease;
            vertical-align: middle;
        }

        .player .audiocontrol input[type=range] {
            height: 21px;
            -webkit-appearance: none;
            margin: 0;
            width: 80px;
            background-color: transparent;
        }
        .player .audiocontrol input[type=range]:focus {
            outline: none;
        }
        /*Chrome*/
        .player .audiocontrol input[type=range]::-webkit-slider-runnable-track {
            height: 4px;
            cursor: pointer;
            animate: 0.2s;
            box-shadow: 0 0 0 #000000;
            background: #FFFFFF;
            border-radius: 0;
            border: 0 solid #000000;
        }
        .player .audiocontrol input[type=range]::-webkit-slider-thumb {
            border: 0 solid #000000;
            height: 15px;
            width: 15px;
            border-radius: 50px;
            background: #FFFFFF;
            cursor: pointer;
            -webkit-appearance: none;
            margin-top: -5.5px;
        }
        .player .audiocontrol input[type=range]:focus::-webkit-slider-runnable-track {
            background: #FFFFFF;
        }
        /*Firefox*/
        .player .audiocontrol input[type="range"]::-moz-range-progress {
            background-color: #ffffff;
        }
        .player .audiocontrol input[type=range]::-moz-range-track {
            height: 4px;
            cursor: pointer;
            animate: 0.2s;
            box-shadow: 0 0 0 #000000;
            background: #858585;
            border-radius: 0;
            border: 0 solid #000000;
        }
        .player .audiocontrol input[type=range]::-moz-range-thumb {
            box-shadow: 0 0 0 #000000;
            border: 0 solid #000000;
            height: 15px;
            width: 15px;
            border-radius: 50px;
            background: #FFFFFF;
            cursor: pointer;
        }
        /*MS*/
        .player .audiocontrol input[type=range]::-ms-track {
            height: 4px;
            cursor: pointer;
            animate: 0.2s;
            background: transparent;
            border-color: transparent;
            color: transparent;
        }
        .player .audiocontrol input[type=range]::-ms-fill-lower {
            background: #FFFFFF;
            border-radius: 0;
            box-shadow: 0 0 0 #000000;
        }
        .player .audiocontrol input[type=range]::-ms-fill-upper {
            background: #FFFFFF;
            border: 0 solid #000000;
            border-radius: 0;
            box-shadow: 0 0 0 #000000;
        }
        .player .audiocontrol input[type=range]::-ms-thumb {
            margin-top: 1px;
            box-shadow: 0 0 0 #000000;
            border: 0 solid #000000;
            height: 15px;
            width: 15px;
            border-radius: 50px;
            background: #FFFFFF;
            cursor: pointer;
        }
        .player .audiocontrol input[type=range]:focus::-ms-fill-lower {
            background: #FFFFFF;
        }
        .player .audiocontrol input[type=range]:focus::-ms-fill-upper {
            background: #858585;
        }

        .player .quality{
            opacity: 50%;
            background-color: #a80000;
            padding: 5px;
            border-radius: 3px;
            cursor: pointer;
            -webkit-transition: all 0.2s ease;
            -moz-transition: all 0.2s ease;
            -ms-transition: all 0.2s ease;
            -o-transition: all 0.2s ease;
            transition: all 0.2s ease;
        }

        .player .controlbar:hover .quality{
            opacity:100%;
        }

        .player .playbackrate{
            border:1px solid #929292;
            font-size: 12px;
            padding: 4px;
            border-radius: 2px;
            cursor: pointer;
        }

        .videoPlayer{
            position: relative;
            width: 700px;
            height: auto;
            background-color: #000;
        }

        .videoPlayer video{
            width: 100%;
        }

        .videoPlayer .control{
            cursor: pointer;
            background-color: #000;
        }
        #cam, #slide{
            cursor: pointer;
        }
    </style>
    <script>

    </script>
    </head>
<body>
    <div class="container">
        <div class="player" id="player_7082" currentrecord="sliderecord" album="2020_11_13_15h52_EDUC-E-520" recorid="7082" currentquality="high">
            <video id="video_7082" ondblclick="toggleFullScreen(7082)" onclick="playControl(7082)" autoplay="autoplay"></video>

            <div id="controls" class="controlbar">
                <div class="progress" id="progressBar">
                    <div class="progress-bar asset1" role="progressbar" id="progressBar_7082" style="width:0%"></div>
                </div>
                <div class="float-left">
                    <span id="play_7082" onclick="playControl(7082);" current="play"><i class="fas fa-pause"></i></span>
                    <span class="sound" onclick="">
                        <i class="fas fa-volume-up" id="soundcontrol_7082"></i>
                    </span>
                    <span class="audiocontrol">
                        <input type="range" min="0" max="1" step="0.01" onchange="changeVolume(7082,this.value)">
                    </span>
                    <span class="timer" id="timer_7082">00:00:00 / 00:00:00</span>
                </div>
                <div class="float-right">
                    <span id="quality" class="quality" onclick="changeQuality(7082)">HD</span>
                    <span id="cam" onclick="changeView('camrecord',7082)"><i class="fas fa-camera"></i> Caméra</span>
                    <span id="slide" onclick="changeView('sliderecord',7082)"><i class="fas fa-file-alt"></i> Diapositif</span>
                    <span id="collapse_7082" onclick="toggleFullScreen(7082)" fullscreen="false"><i class="fas fa-expand"></i></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        var player        = document.getElementById("player_7082");
        var timer         = document.getElementById("timer_7082");
        var video         = document.getElementById("video_7082");
        let collapse      = document.getElementById("collapse_7082");

        var currentQual   = player.getAttribute("currentquality");
        var currentAlbum  = player.getAttribute("album");
        var currentRecord = player.getAttribute("currentrecord");
        var progressBar   = document.getElementById("progressBar");

        var urlConfig = {
            mainUrl : "https://ezcasttest.ulb.ac.be/newezplayer/player/m3u.php",
            id      : player.getAttribute("recorid"),
            type    : currentRecord,
            folder  : currentAlbum,
            quality : currentQual
        };

        progressBar.onclick = function (e) {
            let childProgressBar = progressBar.children;
            let percentValue   = (e.pageX  - (this.offsetLeft + player.offsetParent.offsetLeft + 2)) / this.offsetWidth;
            childProgressBar[0].style.width = percentValue * 100 + "%";
            let currentTime = (percentValue * video.duration);
            setCurrentTime(currentTime,urlConfig["id"]);
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
            video.play();
        }
        else if (video.canPlayType('application/vnd.apple.mpegurl')){
            video.src = videoUrl;
            video.addEventListener('loadedmetadata',function() {
                video.play();
            });
        }

        function changeVolume(videoid,value){
            video.volume = value;
            console.log(video.volume);
        }

        function updateStatus() {
            updateTime(urlConfig["id"]);
            updateProgressBar(urlConfig["id"]);
        }

        function updateProgressBar(videoid){
            let answerID         = document.getElementById("progressBar_" + videoid);
            answerID.style.width = 100 * video.currentTime / video.duration + "%";
        }

        function playControl(videoid) {
            let button = document.getElementById("play_" + videoid);
            let currentMode = button.getAttribute("current");
            let icon = button.children;
            if(currentMode === "play"){
                hls.stopLoad();
                video.pause();
                button.setAttribute("current","pause");
                icon[0].className = "fas fa-play";
            }
            else{
                hls.startLoad();
                video.play();
                button.setAttribute("current","play");
                icon[0].className = "fas fa-pause";
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
    </script>

</body>
</html>
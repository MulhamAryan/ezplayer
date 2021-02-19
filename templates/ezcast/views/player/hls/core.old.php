<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://ezcasttest.ulb.ac.be/newezplayer//templates/ezcast/css//bootstrap/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://ezcasttest.ulb.ac.be/newezplayer//templates/ezcast/css//fontawesome/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://ezcasttest.ulb.ac.be/newezplayer//templates/ezcast/css/ezcasthls.css" crossorigin="anonymous">
    <script src="https://ezcasttest.ulb.ac.be/newezplayer/templates/ezcast/js/hlsplayer/hls.min.js"></script>

    </head>
<body>
        <div class="player embed-responsive embed-responsive-16by9" id="player_7082" currentrecord="camrecord" album="2020_11_13_15h52_EDUC-E-520" recorid="7082" currentquality="high">
            <video id="video_7082" ondblclick="toggleFullScreen(7082)" onclick="playControl(7082)" autoplay></video>
            <div class="middleplaybtn" id="playButton_7082" onclick="playControl(7082)">
                <i class="fas fa-play" aria-hidden="true"></i>
            </div>
            <div id="controls" class="controlbar">
                <div class="progress" id="progressBar">
                    <div class="progress-bar asset1" role="progressbar" id="progressBar_7082" style="width:0%"></div>
                </div>
                <div class="float-left">
                    <span id="play_7082" onclick="playControl(7082);" current="play"><i class="fas fa-pause"></i></span>
                    <span class="sound" id="sound_7082">
                        <i class="fas fa-volume-up" id="soundcontrol_7082"></i>
                    </span>
                    <span class="audiocontrol">
                        <input type="range" min="0" max="1" step="0.01" onchange="changeVolume(7082,this.value)" id="volume">
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

    <script type="text/javascript">
        var player        = document.getElementById("player_7082");
        var timer         = document.getElementById("timer_7082");
        var video         = document.getElementById("video_7082");
        let collapse      = document.getElementById("collapse_7082");
        var currentQual   = player.getAttribute("currentquality");
        var currentAlbum  = player.getAttribute("album");
        var currentRecord = player.getAttribute("currentrecord");

        var urlConfig = {
            mainUrl : "https://ezcasttest.ulb.ac.be/newezplayer/player/m3u.php",
            id      : player.getAttribute("recorid"),
            type    : currentRecord,
            folder  : currentAlbum,
            quality : currentQual
        };
        video.play();
    </script>

    <script type="text/javascript" src="https://ezcasttest.ulb.ac.be/newezplayer/templates/ezcast/js/hlsplayer/ezcasthls.js"></script>

</body>
</html>
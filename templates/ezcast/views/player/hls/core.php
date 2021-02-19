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
    <div class="player embed-responsive embed-responsive-16by9" id="player">
        <video id="video" autoplay></video>
        <div class="middleplaybtn" id="playButton"><i class="fas fa-play" aria-hidden="true"></i></div>
        <div class="waitSpin" type="button" id="wait" disabled><span class="spinner-border" role="status" aria-hidden="true"></span></div>
        <div id="controls" class="controlbar">
            <div class="progress" id="mainProgressBar">
                <div class="progress-bar" role="progressbar" id="progressBar" style="width:0"></div>
                <div class="progress-bar bg-success" role="progressbar" id="progressBarLoaded" style="width:0"></div>
            </div>
            <div class="float-left">
                <span id="playToggleBtn"><i class="fas fa-pause"></i></span>
                <span class="sound" id="sound"><i class="fas fa-volume-up" id="soundcontrol"></i></span>
                <span class="audiocontrol"><input type="range" min="0" max="1" step="0.01" id="volume"></span>
                <span class="timer" id="timer">00:00:00 / 00:00:00</span>
            </div>
            <div class="float-right">
                <span id="quality" class="quality">HD</span>
                <span id="cam"><i class="fas fa-camera"></i> Caméra</span>
                <span id="slide"><i class="fas fa-file-alt"></i> Diapositif</span>
                <span id="collapse"><i class="fas fa-expand"></i></span>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var urlConfig = {
            mainUrl : "https://ezcasttest.ulb.ac.be/newezplayer/player/m3u.php",
            id      : 7082,
            type    : "camrecord",
            folder  : "2020_11_13_15h52_EDUC-E-520",
            quality : "high",
            live    : false,
            startPosition : 0,
            startLevel: 0
        };
    </script>

    <script type="text/javascript" src="https://ezcasttest.ulb.ac.be/newezplayer/templates/ezcast/js/hlsplayer/ezcasthls.js"></script>

</body>
</html>
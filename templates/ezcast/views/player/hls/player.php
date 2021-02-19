<link rel="stylesheet" href="<?=$this->config->template["css"];?>/ezcasthls.css" crossorigin="anonymous">
<script src="<?=$this->config->template["js"];?>/hlsplayer/hls.min.js"></script>
<div class="player embed-responsive embed-responsive-16by9" id="player">
    <video id="video" autoplay></video>
    <div class="middleplaybtn" id="playButton"><i class="fas fa-play" aria-hidden="true"></i></div>
    <div class="waitSpin" type="button" id="wait"><span class="spinner-border" role="status" aria-hidden="true"></span></div>
    <div id="controls" class="controlbar">
        <div class="progressContainer">
            <input class="seek" id="seek" value="0" min="0" type="range" step="1">
            <div class="seek-tooltip" id="seek-tooltip">00:00:00</div>
        </div>
        <div class="float-left">
            <span id="playToggleBtn"><i class="fas fa-pause"></i></span>
            <span class="sound" id="sound"><i class="fas fa-volume-up" id="soundcontrol"></i></span>
            <span class="audiocontrol"><input type="range" min="0" max="1" step="0.01" id="volume"></span>
            <span class="timer" id="timer">00:00:00 / 00:00:00</span>
        </div>
        <div class="float-right">
            <span id="quality" class="quality"><?=($this->config->defaultQuality == "low" ? "SD" : "HD");?></span>
            <span id="cam"><i class="fas fa-camera"></i> <?=$this->lang["cam"];?></span>
            <span id="slide"><i class="fas fa-file-alt"></i> <?=$this->lang["slide"];?></span>
            <span id="collapse"><i class="fas fa-expand"></i></span>
        </div>
    </div>
</div>
<script type="text/javascript">
    <?php
        $randomValue = $auth->getInfo(LOGIN_USER_ID) . "_" . $recordInfo["record_id"];
        $videoHashId = $auth->getSecHash($randomValue);
    ?>
    var urlConfig = {
        mainUrl : "<?=$this->config->streamurl;?>/m3u.php",
        hashid  : "<?=$videoHashId;?>",
        id      : <?=$recordInfo["record_id"]?>,
        type    : "camrecord", //TODO Make it compatible with old courses
        folder  : "2020_11_13_15h52_EDUC-E-520", //TODO Create directory references system
        quality : "<?=$this->config->defaultQuality;?>",
        live    : false, //TODO FOR future release (Streaming) current only false
        startPosition : 0,
        startLevel: <?=($this->config->defaultQuality == "low" ? 1:0);?>
    };
</script>

<script type="text/javascript" src="<?=$this->config->template["js"];?>/hlsplayer/ezcasthls.js"></script>

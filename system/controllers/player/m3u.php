<?php
    include "player_config.php";
    $stream = "#EXTM3U" . PHP_EOL;
    $stream .= "#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH=1280000,CODECS=\"avc1.42e00a,mp4a.40.2\"" . PHP_EOL;
    $stream .= "{$this->config->url}/player/m3u8.php?recordid={$recordid}&recordType={$recordType}&quality={$this->config->qualities[0]}&dir={$dir}" . PHP_EOL;
    $stream .= "#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH=7680000,CODECS=\"avc1.42e00a,mp4a.40.2\"" . PHP_EOL;
    $stream .= "{$this->config->url}/player/m3u8.php?recordid={$recordid}&recordType={$recordType}&quality={$this->config->qualities[0]}&dir={$dir}" . PHP_EOL;

    echo $stream;
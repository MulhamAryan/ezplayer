<div class="mt-2 p-3 mb-2 bg-white text-dark border">
    <b><?=$lang["admin"]["auditorium_list"];?></b>
    <hr>
    <form method="post" action="">
        <div class="form-group">
            <label for="auditorium_name"><?=$lang["auditorium"]["auditorium_name"];?></label>
            <input type="text" class="form-control" id="auditorium_name" aria-describedby="auditorium_name" placeholder="" name="auditorium_name" required>
        </div>
        <div class="form-group">
            <label for="machine_username"><?=$lang["auditorium"]["machine_username"];?></label>
            <input type="text" class="form-control" id="machine_username" aria-describedby="machine_username" placeholder="" name="machine_username" required>
        </div>
        <div class="form-group">
            <label for="auditorium_ip"><?=$lang["auditorium"]["auditorium_ip"];?></label>
            <input type="text" class="form-control" id="auditorium_ip" placeholder="" name="auditorium_ip" required>
        </div>
        <hr>
        <div class="form-group">
            <label for="installation_dir"><?=$lang["auditorium"]["installation_dir"];?></label>
            <input type="text" class="form-control" id="installation_dir" placeholder="/usr/local/" name="installation_dir" value="/usr/local/" required>
        </div>
        <div class="form-group">
            <label for="apache_dir"><?=$lang["auditorium"]["apache_dir"];?></label>
            <input type="text" class="form-control" id="apache_dir" placeholder="/var/www/html/" name="apache_dir" value="/var/www/html/" required>
        </div>
        <div class="form-group">
            <label for="record_dir"><?=$lang["auditorium"]["record_dir"];?></label>
            <input type="text" class="form-control" id="record_dir" placeholder="/var/www/recorderdata/" name="record_dir" value="/var/www/recorderdata/" required>
        </div>
        <hr>
        <div class="form-group">
            <label for="record_dir">GIT EZrecorder</label>
            <input type="text" class="form-control" id="record_dir" placeholder="/var/www/recorderdata/" name="giturl" value="/var/www/recorderdata/" required>
        </div>
        <div class="form-group">
            <label for="php_cli">PHP CLI</label>
            <input type="text" class="form-control" id="php_cli" placeholder="/usr/bin/php" name="php_cli" value="/usr/bin/php" required>
        </div>
        <div class="form-group">
            <label for="ffmpeg_cli">FFMPEG CLI</label>
            <input type="text" class="form-control" id="ffmpeg_cli" placeholder="/usr/bin/ffmpeg" name="ffmpeg_cli" value="/usr/bin/ffmpeg" required>
        </div>
        <div class="form-group">
            <label for="ffprobe_cli">FFPROBE CLI</label>
            <input type="text" class="form-control" id="ffprobe_cli" placeholder="/usr/bin/ffprobe" name="ffprobe_cli" value="/usr/bin/ffprobe" required>
        </div>
        <hr>
        <div class="form-group">
            <label for="api_key"><?=$lang["auditorium"]["api_key"];?></label>
            <input type="text" class="form-control" id="api_key" placeholder="" name="api_key" value="<?=$sys->generateToken(28);?>" required>
        </div>
        <div class="form-check form-switch font-weight-bold">
            <input class="form-check-input" type="checkbox" id="offline_install" name="offline_install" value="1">
            <label class="form-check-label" for="offline_install"><?=$lang["auditorium"]["offline_installation"];?></label>
        </div>
        <input name="submit" value="<?=$lang["admin"]["start_installation"];?>" type="submit" class="btn btn-primary">
    </form>
</div>

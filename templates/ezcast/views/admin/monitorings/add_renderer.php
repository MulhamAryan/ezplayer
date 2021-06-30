<?php if($do == "add" || $do == "edit"):?>
    <div class="p-3 mt-2 mb-2 bg-white text-dark border">
        <b><?=($do == "add" ? $lang["admin"]["add_renderer"] : $lang["admin"]["edit_renderer"]);?></b>
        <hr>
        <?php
        if(isset($error) == true) {
            echo $tmp->getError($ssh->errorMessage["message"]);
        }
        $getRenderer["name"]           = (!empty($getRenderer["name"]) ? $getRenderer["name"] : "");
        $getRenderer["username"]       = (!empty($getRenderer["username"]) ? $getRenderer["username"] : "");
        $getRenderer["hosturl"]        = (!empty($getRenderer["hosturl"]) ? $getRenderer["hosturl"] : "");
        $getRenderer["encode_program"] = (!empty($getRenderer["encode_program"]) ? $getRenderer["encode_program"] : "");
        $getRenderer["maxjob"]         = (!empty($getRenderer["maxjob"]) ? $getRenderer["maxjob"] : "");
        ?>
        <form method="post" action="">
            <div class="row">
                <div class="form-group mb-2">
                    <label for="render_name" class="font-weight-bold"><?=$lang["admin"]["server_name"];?></label>
                    <input type="text" class="form-control" id="render_name" aria-describedby="render_name" placeholder="<?=$lang["admin"]["server_name"];?>" value="<?=$getRenderer["name"];?>" name="render_name" required>
                </div>
                <div class="form-group mb-2">
                    <label for="renderer_url" class="font-weight-bold"><?=$lang["admin"]["renderer_url"];?></label>
                    <input type="text" class="form-control" id="renderer_url" aria-describedby="renderer_url" placeholder="<?=$lang["admin"]["renderer_url"];?>" value="<?=$getRenderer["hosturl"];?>" name="renderer_url" required>
                </div>
                <div class="form-group mb-2">
                    <label for="git_repo" class="font-weight-bold"><?=$lang["admin"]["git_url"];?></label>
                    <input type="text" class="form-control" id="git_repo" aria-describedby="git_repo" placeholder="<?=$lang["admin"]["git_url"];?>" value="<?=$config->admin["git_renderers"];?>" name="git_repo" required>
                </div>
                <div class="form-group mb-2 border-top border-bottom p-2 font-weight-bold bg-light">Parametres de connexion</div>
                <div class="form-group mb-2">
                    <label for="render_username" class="font-weight-bold"><?=$lang["admin"]["render_username"];?></label>
                    <input type="text" class="form-control" id="renderer_url" aria-describedby="render_username" placeholder="<?=$lang["admin"]["render_username"];?>" value="<?=$getRenderer["username"];?>" name="render_username" required>
                </div>
                <div class="form-group mb-2">
                    <label for="rsa_key" class="font-weight-bold"><?=trim($lang["admin"]["rsa_key"]);?></label>
                    <textarea type="text" class="form-control" id="renderer_url" aria-describedby="rsa_key" placeholder="<?=$lang["admin"]["rsa_key"];?>" name="rsa_key" style="height: 100px"><?=$rsa_key;?></textarea>
                </div>
                <div class="form-group mb-2 border-top border-bottom p-2 font-weight-bold bg-light">Parametres du renderer</div>
                <div class="form-group mb-2">
                    <label for="server_path" class="font-weight-bold"><?=$lang["admin"]["server_path"];?></label>
                    <input type="text" class="form-control" id="server_path" aria-describedby="server_path" placeholder="<?=$lang["admin"]["server_path"];?>" value="~/ezrenderer/" name="server_path" required>
                </div>
                <div class="form-group mb-2">
                    <label for="php_cli" class="font-weight-bold">PHP CLI</label>
                    <input type="text" class="form-control" id="php_cli" aria-describedby="php_cli" placeholder="PHP CLI" value="/usr/bin/php" name="php_cli" required>
                </div>
                <div class="form-group mb-2">
                    <label for="git_cli" class="font-weight-bold">GIT CLI</label>
                    <input type="text" class="form-control" id="git_cli" aria-describedby="git_cli" placeholder="GIT CLI" value="/usr/bin/git" name="git_cli" required>
                </div>
                <div class="form-group mb-2">
                    <label for="rsync_cli" class="font-weight-bold">RSYNC CLI</label>
                    <input type="text" class="form-control" id="rsync_cli" aria-describedby="rsync_cli" placeholder="RSYNC CLI" value="/usr/bin/rsync" name="rsync_cli" required>
                </div>
                <div class="form-group mb-2">
                    <label for="ffmpeg_cli" class="font-weight-bold">FFMPEG CLI</label>
                    <input type="text" class="form-control" id="ffmpeg_cli" aria-describedby="ffmpeg_cli" placeholder="FFMPEG CLI" value="/usr/bin/ffmpeg" name="ffmpeg_cli" required>
                </div>
                <div class="form-group mb-2">
                    <label for="ffprobe_cli" class="font-weight-bold">FFPROBE CLI</label>
                    <input type="text" class="form-control" id="ffprobe_cli" aria-describedby="ffprobe_cli" placeholder="FFMPEG CLI" value="/usr/bin/ffprobe" name="ffprobe_cli" required>
                </div>
                <div class="form-group mb-2">
                    <label for="max_job" class="font-weight-bold">MAX JOB</label>
                    <input type="text" class="form-control" id="max_job" aria-describedby="max_job" placeholder="MAX JOB" value="<?=$getRenderer["maxjob"];?>" name="max_job" required>
                </div>
                <div class="form-group mb-2">
                    <input type="submit" class="btn btn-primary float-right" value="<?=$lang["admin"]["start_installation"];?>" name="start_installation">
                </div>
            </div>
        </form>
    </div>
<?php endif;?>
<div class="mt-2 p-3 mb-2 bg-white text-dark border">
    <h5><b><?=$courseInfo["course_code"];?></b> : <?=$courseInfo["course_name"];?></h5>
    <hr>
    <div class="p-2">
        <div class="btn-group float-right" role="group">
            <?php if($canAdd == true):
                $copieRecord = array(
                    "id" => "copieRecord",
                    "class" => "modal-dialog",
                    "form" =>
                        array(
                            "action" => "ajax/records/copy.php",
                            "method" => "POST",
                            "onsubmit" => "recordCommand('{$auth->getSessionID()}'); return false;",
                            "name" => "copie_record",
                            "id" => "submit_copie_record",
                        ),
                    "title" => $this->lang["copy_record"],
                    "content" => '<div id="copy_content"><div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div><span>' . $this->lang["please_wait"] . '</span></div>',
                );
                $this->tmp->getModal($copieRecord);

                $moveRecord = array(
                    "id" => "moveRecord",
                    "class" => "modal-dialog",
                    "form" =>
                        array(
                            "action" => "ajax/records/copy.php",
                            "method" => "POST",
                            "onsubmit" => "recordCommand('{$auth->getSessionID()}'); return false;",
                            "name" => "move_record",
                            "id" => "submit_move_record",
                        ),
                    "title" => $this->lang["move_record"],
                    "content" => '<div id="move_content"><div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div><span>' . $this->lang["please_wait"] . '</span></div><div id="move_ans"></div>',
                );
                $this->tmp->getModal($moveRecord);

                ?>
                <a href="<?=$this->url(array("file" => System::fileCourse, "parameters" => array("id" => $courseInfo["id"],"edit" => "upload", "sessionid" => $auth->getSessionID())));?>" class="btn btn-secondary"><i class="fas fa-upload"></i> <?=$this->lang["add_content"];?></a>
            <?php endif; ?>
            <?php if($canEdit == true):?>
                <script src="<?=$this->config->template["js"];?>/recordOptions.js" crossorigin="anonymous"></script>
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i> </a>
                <div class="dropdown-menu shadow" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="<?=$this->url(array("file" => System::fileCourse, "parameters" => array("id" => $courseInfo["id"],"edit" => "access", "sessionid" => $auth->getSessionID())));?>"><i class="fas fa-universal-access"></i> <?=$this->lang["access_management"];?></a>
                    <a class="dropdown-item" href="#"><i class="fas fa-edit"></i> <?=$this->lang["edit_course"];?></a>
                    <?php if($canDelete == true) : ?><a class="dropdown-item" href="#" style="color: #FF0000"><i class="fas fa-times"></i> <?=$this->lang["delete_course"];?></a><?php endif;?>
                </div>
            <?php endif;?>
        </div>
        <?php if($canEdit == true && $edit == ""):?>
            <div class="form-check">
                <input type="checkbox" name="selectAll" class="form-check-input" id="selectAll" onclick="selectAll()">
                <label class="form-check-label" for="selectAll"><?=$this->lang["grouped_action"];?></label>
                <button type="submit" name="group_hide" id="group_hide" class="btn btn-secondary" disabled><i class="fas fa-eye-slash"></i> <?=$this->lang["hide"];?></button>
                <button type="submit" name="group_move" id="group_move" class="btn btn-secondary" disabled><i class="fas fa-arrow-left"></i> <?=$this->lang["move"];?></button>
                <button type="submit" name="group_delete" id="group_delete" class="btn btn-secondary" disabled><i class="fas fa-times"></i> <?=$this->lang["delete"];?></button>
            </div>
            <hr>
        <?php endif;?>
    </div>

    <div style="clear: both"></div>
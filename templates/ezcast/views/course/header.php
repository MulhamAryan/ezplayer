<div class="mt-2 p-3 mb-2 bg-white text-dark border">
    <h5><b><?=$courseInfo["course_code"];?></b> : <?=$courseInfo["course_name"];?></h5>
    <hr>
    <div class="p-2">
        <div class="btn-group float-right" role="group">
            <?php if($canAdd == true):?>
                <?php
                $modalContent = '<input type="hidden" id="courseid" name="courseid" value="' . $courseInfo["id"] . '" />';
                $modalContent .= '<input type="hidden" id="intro" name="intro" value="Semeur" />';
                $modalContent .= '<input type="hidden" id="add_title" name="add_title" value="FlyingTitle" />';
                $modalContent .= '<input type="hidden" id="credits" name="credits" value="false" />';
                $modalContent .= '<input type="hidden" id="keepQuality" name="keepQuality" />';
                $modalContent .= '<input type="hidden" id="ratio" name="ratio" value="auto" />';
                $modalContent .= '<input type="hidden" id="downloadable" name="downloadable" value="1" />';

                $modalContent .= '<div class="modal-body form-horizontal">';

                $modalContent .= '<div class="form-group"><div class="row">';
                $modalContent .= '<label class="col-sm-3 control-label"><b>' . $this->lang["course"] . '</b></label>';
                $modalContent .= '<div class="col-sm-9 border-left"><p class="form-control-static"><b>' . $courseInfo["course_code"] . ' - ' . $courseInfo["course_name"] . '</b></p></div>';
                $modalContent .= '</div></div>';

                $modalContent .= '<div class="form-group mt-4"><div class="row">';
                $modalContent .= '<label class="col-sm-3 control-label"><b>' . $this->lang["title"] . '</b><p class="form-text text-muted">' . $this->lang["max_carac70"] . '</p></label>';
                $modalContent .= '<div class="col-sm-9 border-left"><input id="title" name="title" class="form-control" type="text" maxlength="70" not_required /></div>';
                $modalContent .= '</div></div>';

                $modalContent .= '<div class="form-group mt-4"><div class="row">';
                $modalContent .= '<label class="col-sm-3 control-label"><b>' . $this->lang["hidden"] . '</b><p class="form-text text-muted">' . $this->lang["optional"] . '</p></label>';
                $modalContent .= '<div class="col-sm-9 border-left"><div class="form-check form-switch">';
                $modalContent .= '<input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="private" value="1"><label class="form-check-label" for="flexSwitchCheckDefault">' . $this->lang["hidden_text"] . '</label>';
                $modalContent .= '</div></div></div></div>';

                $modalContent .= '<div class="form-group mt-4"><div class="row">';
                $modalContent .= '<label class="col-sm-3 control-label"><b>' . $this->lang["description"] . '</b><p class="form-text text-muted">' . $this->lang["optional"] . '</p></label>';
                $modalContent .= '<div class="col-sm-9 border-left"><textarea id="description" class="form-control" name="description" rows="4" style="resize: vertical;"></textarea></div>';
                $modalContent .= '</div></div>';

                $modalContent .= '<div class="form-group mt-4"><div class="row">';
                $modalContent .= '<label class="col-sm-3 control-label"><b>' . $this->lang["type"] . '</b><p class="form-text text-muted">' . $this->lang["type"] . ' ' . $this->lang["cam"] . '/' . $this->lang["slide"] . '</p></label>';
                $modalContent .= '<div class="col-sm-9 border-left">';
                $modalContent .= '<select class="form-select" name="type" id="mediaType" onchange="file_upload_type();">';
                $modalContent .= '<option selected="selected" value="cam">' . $this->lang["video"] . ' ' . $this->lang["cam"] . '</option>';
                $modalContent .= '<option value="slide">' . $this->lang["video"] . ' ' . $this->lang["slide"] . '</option>';
                $modalContent .= '<option value="camslide">' . $this->lang["video"] . ' ' . $this->lang["cam"] . ' - ' . $this->lang["video"] . ' ' . $this->lang["slide"] . '</option>';
                $modalContent .= '<option value="audio">' . $this->lang["audio_file"] . '</option>';
                $modalContent .= '</select>';
                $modalContent .= '</div></div></div>';

                $modalContent .= '<div class="form-group mt-4" id="submit_cam"><div class="row">';
                $modalContent .= '<label class="col-sm-3 control-label"><b>' . $this->lang["video"] . ' ' . $this->lang["cam"] . '</b><p class="form-text text-muted">' . $this->lang["max_2go"] . '</p></label>';
                $modalContent .= '<div class="col-sm-9 border-left"><input id="file_cam" type="file" name="fileupload[cam][]" accept="video/mp4,video/x-m4v,video/*" not_required /></div>';
                $modalContent .= '</div></div>';

                $modalContent .= '<div class="form-group mt-4" id="submit_slide" style="display: none;"><div class="row">';
                $modalContent .= '<label class="col-sm-3 control-label"><b>' . $this->lang["video"] . ' ' . $this->lang["slide"] . '</b><p class="form-text text-muted">' . $this->lang["max_2go"] . '</p></label>';
                $modalContent .= '<div class="col-sm-9 border-left"><input id="file_slide" type="file" name="fileupload[slide][]" accept="video/mp4,video/x-m4v,video/*" disabled not_required /></div>';
                $modalContent .= '</div></div>';

                $modalContent .= '<div class="form-group mt-4" id="submit_audio" style="display: none;"><div class="row">';
                $modalContent .= '<label class="col-sm-3 control-label"><b>' . $this->lang["audio_file"] . '</b><p class="form-text text-muted">' . $this->lang["max_2go"] . '</p></label>';
                $modalContent .= '<div class="col-sm-9 border-left"><input id="file_audio" type="file" name="fileupload[audio][]" accept="audio/*" disabled not_required /></div>';
                $modalContent .= '</div></div>';
                $modalContent .= '<div id="answer"></div>';

                $modalContent .= '</div>';

                $modalInfo = array(
                    "id" => "upload_video",
                    "class" => "modal-dialog modal-lg",
                    "form" =>
                        array(
                            "action" => "ajax/upload_content.php",
                            "method" => "POST",
                            "onsubmit" => "submitForm(); return false;",
                            "name" => "upload_video",
                            "id" => "submit_upload_video",
                            "enctype" => "multipart/form-data"
                        ),
                    "title" => $this->lang["upload_new_video"],
                    "content" => $modalContent,
                );
                $this->tmp->getModal($modalInfo);

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

                /*$editCourse = array(
                    "id" => "editCourse",
                    "class" => "modal-dialog",
                    "form" =>
                        array(
                            "action" => "ajax/records/copy.php",
                            "method" => "POST",
                            "onsubmit" => "recordCommand('{$auth->getSessionID()}'); return false;",
                            "name" => "move_record",
                            "id" => "submit_move_record",
                        ),
                    "title" => $this->lang["cours_options"],
                    "content" => '<div id="move_content"><div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div><span>' . $this->lang["please_wait"] . '</span></div><div id="move_ans"></div>'
                );
                $this->tmp->getModal($editCourse);*/
                ?>

                <a href="#" class="btn btn-secondary" role="button" data-remote="false" data-toggle="modal" data-target="#upload_video"><i class="fas fa-upload"></i> <?=$this->lang["add_content"];?></a>

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
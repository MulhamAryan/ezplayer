<script type="text/javascript" src="<?=$this->config->template["js"];?>/upload.js"></script>
<div id="upload_video">
    <form method="POST" action="ajax/upload_content.php" name="upload_video" id="submit_upload_video" enctype="multipart/form-data">
        <input type="hidden" id="courseid" name="courseid" value="<?=$courseInfo["id"];?>" />
        <input type="hidden" id="intro" name="intro" value="Semeur" />
        <input type="hidden" id="add_title" name="add_title" value="FlyingTitle" />
        <input type="hidden" id="credits" name="credits" value="false" />
        <input type="hidden" id="keepQuality" name="keepQuality" />
        <input type="hidden" id="ratio" name="ratio" value="auto" />
        <input type="hidden" id="downloadable" name="downloadable" value="1" />
        <div>
            <div class="form-group">
                <div class="row">
                    <label class="col-sm-2 control-label"><b><?=$this->lang["course"]["name"];?></b></label>
                    <div class="col-sm-10 border-left">
                        <p class="form-control-static"><b><?=$courseInfo["course_code"];?> - <?=$courseInfo["course_name"];?></b></p>
                    </div>
                </div>
            </div>
            <div class="form-group mt-4">
                <div class="row">
                    <label class="col-sm-2 control-label"><b><?=$this->lang["title"];?></b><p class="form-text text-muted"><?=$this->lang["max_carac70"];?></p></label>
                    <div class="col-sm-10 border-left"><input id="title" name="title" class="form-control" type="text" maxlength="70" required /></div>
                </div>
            </div>
            <div class="form-group mt-4">
                <div class="row">
                    <label class="col-sm-2 control-label">
                        <b>
                            <?=$this->lang["hidden"];?>
                        </b>
                        <p class="form-text text-muted">
                            <?=$this->lang["optional"];?>
                        </p>
                    </label>
                    <div class="col-sm-10 border-left">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="private" value="1">
                            <label class="form-check-label" for="flexSwitchCheckDefault">
                                <?=$this->lang["hidden_text"];?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mt-4">
                <div class="row">
                    <label class="col-sm-2 control-label">
                        <b>
                            <?=$this->lang["description"];?>
                        </b>
                        <p class="form-text text-muted">
                            <?=$this->lang["optional"];?>
                        </p>
                    </label>
                    <div class="col-sm-10 border-left">
                        <textarea id="description" class="form-control" name="description" rows="4" style="resize: vertical;"></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group mt-4">
                <div class="row">
                    <label class="col-sm-2 control-label">
                        <b>
                            <?=$this->lang["type"];?>
                        </b>
                        <p class="form-text text-muted">
                            <?=$this->lang["type"];?>
                            <?=$this->lang["cam"];?>/
                            <?=$this->lang["slide"];?>
                        </p>
                    </label>
                    <div class="col-sm-10 border-left">
                        <select class="form-select" name="type" id="mediaType">
                            <option value="cam">
                                <?=$this->lang["video"];?>
                                <?=$this->lang["cam"];?>
                            </option>
                            <option value="slide">
                                <?=$this->lang["video"];?>
                                <?=$this->lang["slide"];?>
                            </option>
                            <option value="camslide">
                                <?=$this->lang["video"];?>
                                <?=$this->lang["cam"];?> -
                                <?=$this->lang["video"];?>
                                <?=$this->lang["slide"];?>
                            </option>
                            <option value="audio">
                                <?=$this->lang["audio_file"];?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group mt-4" id="submit_cam">
                <div class="row">
                    <label class="col-sm-2 control-label">
                        <b>
                            <?=$this->lang["video"];?>
                            <?=$this->lang["cam"];?>
                        </b>
                        <p class="form-text text-muted">
                            <?=$this->lang["max_2go"];?>
                        </p>
                    </label>
                    <div class="col-sm-10 border-left">
                        <input id="file_cam" type="file" name="fileupload[cam][]" accept="video/mp4,video/x-m4v,video/*" class="form-control form-control-lg border border-success" required />
                    </div>
                </div>
            </div>
            <div class="form-group mt-4" id="submit_slide" style="display: none;">
                <div class="row">
                    <label class="col-sm-2 control-label">
                        <b>
                            <?=$this->lang["video"];?>
                            <?=$this->lang["slide"];?>
                        </b>
                        <p class="form-text text-muted">
                            <?=$this->lang["max_2go"];?>
                        </p>
                    </label>
                    <div class="col-sm-10 border-left">
                        <input id="file_slide" type="file" name="fileupload[slide][]" accept="video/mp4,video/x-m4v,video/*" class="form-control form-control-lg border border-success" disabled required />
                    </div>
                </div>
            </div>
            <div class="form-group mt-4" id="submit_audio" style="display: none;">
                <div class="row">
                    <label class="col-sm-2 control-label">
                        <b>
                            <?=$this->lang["audio_file"];?>
                        </b>
                        <p class="form-text text-muted">
                            <?=$this->lang["max_2go"];?>
                        </p>
                    </label>
                    <div class="col-sm-10 border-left">
                        <input id="file_audio" type="file" name="fileupload[audio][]" accept="audio/*" class="form-control form-control-lg border border-success" disabled required />
                    </div>
                </div>
            </div>
            <div id="answer"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="upload_video_close" data-dismiss="modal"><?=$this->lang["close"];?></button>
                <input type="submit" class="btn btn-primary" id="upload_video_submit" value="<?=$this->lang["submit"];?>">
            </div>
        </div>
    </form>
</div>
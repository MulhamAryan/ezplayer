<?php
    function record_list_html($records,$canAdd,$canEdit,$canDelete,$private){
        global $auth;

        foreach ($records as $record):
            if($record["private"] == $private && $record["status"] == "processed"):
                $eyeicon = ($record["private"] == 1) ? "fa-eye":"fa-eye-slash";
                $description = implode(' ', array_slice(explode(' ', $record["description"]), 0, 40));
                $duration = "01:30:25";
                ?>
                <?php if($canEdit == true):?>
                <div class="float-right btn-group-sm position-relative align-bottom mt-2 pr-2">
                    <button class="btn btn-dark" id="hideshow_<?=$record["id"];?>" onclick="recordOptions(<?=$record["id"];?>,'hideshow','<?=$auth->getSessionID();?>')"><i class="fas <?=$eyeicon;?>" id="visibility_<?=$record["id"];?>"></i></button>
                    <button class="btn btn-success" onclick="location.href='record/edit.php?id=<?=$record["id"]?>&session=<?=$auth->getSessionID();?>'" type="button"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-primary" onclick="recordOptions(<?=$record["id"];?>,'copy','<?=$auth->getSessionID();?>')" role="button" data-remote="false" data-toggle="modal" data-target="#copieRecord"><i class="fas fa-clone"></i></button>
                    <?php if($canDelete == true):?>
                        <button class="btn btn-warning" onclick="recordOptions(<?=$record["id"];?>,'move','<?=$auth->getSessionID();?>')" role="button" data-remote="false" data-toggle="modal" data-target="#moveRecord"><i class="fas fa-arrow-left"></i></button>
                        <button class="btn btn-danger" onclick="recordOptions(<?=$record["id"];?>,'delete','<?=$auth->getSessionID();?>')"><i class="fas fa-times"></i></button>
                    <?php endif;?>
                </div>
            <?php endif;?>
                <a href="<?=$auth->url(array("file" => System::fileRecord,"parameters" => array("id" => $record["id"])));?>" class="text-dark">
                    <div class="record border-bottom p-2" id="record_field_<?=$record["id"];?>">
                        <?php if($canEdit == true):?>
                            <input type="checkbox" name="recordlist" class="border-right float-left">
                        <?php endif;?>
                        <div class="image float-left mr-2 ml-2">
                            <span class="duration"><?=$duration;?></span>
                        </div>
                        <h5 class="align-top"><?=$record["title"];?> | 29/01/2020 - 13h05</h5>
                        <p><?=$description;?></p>
                        <div class="clearfix"></div>
                    </div>
                </a>
                <div class="clearfix"></div>

            <?php endif; ?>
        <?php endforeach; ?>
    <?php } ?>
    <?php if($canEdit == true): ?>
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
                <b><?=$this->lang["pubished_records"];?> <span class="text-danger">[<?=$countPublicRecord;?>]</span></b>
            </a>
            <a class="nav-item nav-link <?php if($countPrivateRecord == 0) echo 'disabled';?>" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
                <b><?=$this->lang["private_records"];?> <span class="text-danger">[<?=$countPrivateRecord;?>]</span></b>
            </a>
        </div>
    </nav>
    <?php endif;?>
    <div class="tab-content border-left border-right" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <?php record_list_html($records,$canAdd,$canEdit,$canDelete,0); ?>
        </div>
        <?php if($canEdit == true): ?>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <?php record_list_html($records,$canAdd,$canEdit,$canDelete,1); ?>
        </div>
        <?php endif;?>
    </div>

    <div style="clear: both"></div>

    <?php if(empty($records)): ?>
        <div class="text-center">
            <i class="far fa-folder-open fa-10x"></i><br><br>
            <h5><?=$this->lang["empty_course"];?></h5>
        </div>
    <?php else:?>

    <?php endif;?>
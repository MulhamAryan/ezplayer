<?php
    function pending_records($recordList){
        global $lang, $tmp; ?>
        <?php if(!empty($recordList["processing"])):?>
        <div class="card mb-2 shadow">
            <div class="card-header alert-success font-weight-bold"><?=$lang["course"]["processing"];?></div>
            <ul class="list-group list-group-flush">
                <?php foreach ($recordList["processing"] as $processing):?>
                    <li class="list-group-item">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <?=$processing["title"];?>
                        <span class="float-right"><?=$lang["since2"];?> <?=$tmp->convertTime($processing["addtime"]);?></span>

                    </li>
                <?php endforeach;?>
            </ul>
        </div>
        <?php endif;?>
        <?php if(!empty($recordList["scheduled"])):?>
            <div class="card mb-2 shadow">
                <div class="card-header alert-warning font-weight-bold"><?=$lang["course"]["scheduled"];?></div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($recordList["scheduled"] as $scheduled): ?>
                        <li class="list-group-item">
                            <i class="fa fa-pause-circle"></i>
                            <?=$scheduled["title"];?>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        <?php endif;?>
        <?php if(!empty($recordList["error"])):?>
            <div class="card shadow">
                <div class="card-header alert-danger font-weight-bold"><?=$lang["course"]["failed"];?></div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($recordList["error"] as $errors):?>
                        <li class="list-group-item">
                            <i class="fas fa-exclamation-triangle"></i>
                            <?=$errors["title"];?>
                            <span class="float-right"><?=$lang["since2"];?> <?=$tmp->convertTime($errors["addtime"]);?></span>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        <?php endif;?>
<?php }

    function record_list_html($records,$canAdd,$canEdit,$canDelete,$private){
        global $auth;

        foreach ($records as $record):
            if($record["private"] == $private && $record["status"] == "processed"):
                $description = implode(' ', array_slice(explode(' ', $record["description"]), 0, 40));
                $duration = gmdate("H:i:s",$record["duration"]);
                ?>
                <?php if($canEdit == true):?>
                <div class="float-right btn-group-sm position-relative align-bottom mt-2 pr-2">
                    <button class="btn btn-dark" id="hideshow_<?=$record["id"];?>" onclick="recordOptions(<?=$record["id"];?>,'hideshow','<?=$auth->getSessionID();?>')"><i class="fas <?=($record["private"] == 1) ? "fa-eye":"fa-eye-slash";?>" id="visibility_<?=$record["id"];?>"></i></button>
                    <button class="btn btn-success" onclick="location.href='<?=$auth->url(array("file" => System::fileRecord,"parameters" => array("id" => $record["id"], "do" => "edit", "session" => $auth->getSessionID())));?>'" type="button"><i class="fas fa-edit"></i></button>
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
                <a class="nav-item nav-link <?php if($countPrivateRecord == 0) echo 'disabled';?>" id="nav-private-tab" data-toggle="tab" href="#nav-private" role="tab" aria-controls="nav-private" aria-selected="false">
                    <b><?=$this->lang["private_records"];?> <span class="text-danger">[<?=$countPrivateRecord;?>]</span></b>
                </a>
                <?php if($countNotProcessed != 0): ?>
                    <a class="nav-item nav-link <?php if($countNotProcessed == 0) echo 'disabled';?>" id="nav-processing-tab" data-toggle="tab" href="#nav-processing" role="tab" aria-controls="nav-processing" aria-selected="false">
                        <b><?=$this->lang["records"]["processing_status"];?> <span class="text-danger">[<?=$countNotProcessed;?>]</span></b>
                    </a>
                <?php endif;?>
            </div>
        </nav>
    <?php endif;?>
    <div class="tab-content border-left border-right" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <?php record_list_html($records,$canAdd,$canEdit,$canDelete,0); ?>
        </div>
        <?php if($canEdit == true): ?>
        <div class="tab-pane fade" id="nav-private" role="tabpanel" aria-labelledby="nav-private-tab">
            <?php record_list_html($records,$canAdd,$canEdit,$canDelete,1); ?>
        </div>
        <div class="tab-pane fade p-3" id="nav-processing" role="tabpanel" aria-labelledby="nav-processing-tab">
            <?php pending_records($recordList);?>
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

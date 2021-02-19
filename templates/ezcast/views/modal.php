<div class="modal fade" id="<?=$modalInfo["id"];?>" tabindex="-1" aria-labelledby="<?=$modalInfo["id"];?>Label" aria-hidden="true">
    <div class="<?=$modalInfo["class"];?>">
        <?php if(!empty($modalInfo["form"])): ?>
        <?php
            foreach ($modalInfo["form"] as $paramKey => $paramValue){
                $formParam[] = "{$paramKey}=\"$paramValue\"";
            }
            $formParam = implode(" ",$formParam);
        ?>
        <form <?=$formParam;?> >
        <?php endif;?>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="<?=$modalInfo["id"];?>Label"><?=$modalInfo["title"];?></h5>
                <button type="button" class="btn-close" data-dismiss="modal" id="<?=$modalInfo["id"];?>_close_top" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?=$modalInfo["content"];?>
            </div>
            <?php if($modalInfo["form"] == true): ?>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="<?=$modalInfo["id"];?>_close" data-dismiss="modal"><?=$this->lang["close"];?></button>
                <input type="submit" class="btn btn-primary" id="<?=$modalInfo["id"];?>_submit" value="<?=$this->lang["submit"];?>">
            </div>
            <?php endif;?>
        </div>
            <?php if(!empty($modalInfo["form"])): ?></form><?php endif;?>
    </div>
</div>
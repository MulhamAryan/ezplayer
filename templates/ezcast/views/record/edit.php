<div class="p-3 mt-2 mb-2 bg-white text-dark border">
    <div class="container-fluid m-0 w-100 mw-100">
        <div class="row">
            <div class="">
                <span class="font-weight-bold">
                    <i class="fas fa-edit"></i><?=$this->lang["records"]["edit"];?> :
                </span>
                <?=$recordInfo["title"];?>
                <hr>
            </div>
        </div>
        <div class="row">
            <?=(!empty($error)) ? $this->tmp->getError($error) : "";?>
            <form method="post">
                <div class="form-group">
                    <label for="record_title" class="font-weight-bold"><?=$this->lang["records"]["title"];?></label>
                    <input type="text" class="form-control" id="record_title" aria-describedby="record_title" placeholder="<?=$this->lang["records"]["title"];?>" value="<?=$recordInfo["title"];?>" name="record_title">
                </div>
                <div class="form-group mt-2">
                    <label for="record_description" class="font-weight-bold"><?=$this->lang["records"]["description"];?></label>
                    <textarea class="form-control" id="record_description" aria-describedby="record_description" placeholder="<?=$this->lang["records"]["description"];?>" name="record_description"><?=$recordInfo["description"];?></textarea>
                </div>
                <div class="form-group mt-2">
                    <input type="submit" class="btn btn-primary float-right" name="save_modification" value="<?=$this->lang["save"];?>">
                </div>
            </form>
        </div>
    </div>
</div>
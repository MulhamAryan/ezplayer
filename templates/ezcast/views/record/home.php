<script src="<?=$this->config->template["js"];?>/bookmarks.js" crossorigin="anonymous"></script>
<div class="p-3 mt-2 mb-2 bg-white text-dark border">
    <div class="container-fluid m-0 w-100 mw-100">
        <div class="row">
            <div class="pl-3">
                <b><?=$recordInfo["course_code"] . " " . $recordInfo["course_name"];?></b>
                <hr>
            </div>
            <div class="col-lg-9 col-sm-9">
                <?php include $this->tmp->load("player/hls/player.php"); //TODO Add old video compability*/?>
            </div>
            <div class="col-lg-3 col-sm-3 border p-0">
                <div class="card-header">Bookmarks list <button type="button" class="btn btn-primary btn-sm" id="addnewbookmark"><i class="fas fa-plus-circle"></i> <?=$this->lang["add"];?></button></div>
                <div id="bookmarkErrorZone"></div>
                <div id="newbookmarkzone" style="display: none;">
                    <form class="form-control" id="bookmarkForm" action="ajax/record/add_bookmark.php">
                        <input type="hidden" value="<?=$recordInfo["record_id"];?>" name="record_id" id="record_id">
                        <input type="text" name="bookmark_title" class="form-control mb-1" placeholder="<?=$this->lang["title"];?>" id="bookmark_title" required>
                        <textarea name="bookmark_description" class="form-control mb-1" placeholder="<?=$this->lang["description"];?>" id="bookmark_description"></textarea>
                        <input type="time" name="bookmark_timecode" class="form-control mb-1" placeholder="" step="1" value="00:00:00" id="bookmark_timecode" required>
                        <?php if(in_array("edit",$permissions)):?>
                            <label><input type="checkbox" name="bookmark_public" value="checked" id="bookmark_public"> <b><?=$this->lang["public"];?></b> ?</label>
                        <?php endif;?>
                        <br><input type="submit" value="<?=$this->lang["save"];?>" class="btn btn-primary">
                    </form>
                </div>
                <div class="list-group" id="bookmarksZone">
                    <nav>
                        <div class="nav nav-pills m-2 float-left" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link" id="nav-public-tab" data-toggle="tab" href="#public-bookmark" role="tab" aria-controls="nav-home" aria-selected="true">
                                <b><?=$this->lang["public"];?></b>
                            </a>
                            <a class="nav-item nav-link active" id="nav-private-tab" data-toggle="tab" href="#private-bookmark" role="tab" aria-controls="nav-private" aria-selected="false">
                                <b><?=$this->lang["personal"];?></b>
                            </a>
                        </div>
                    </nav>
                    <div class="tab-content border-left border-right overflow-auto" id="nav-tabContent"  style="max-height: 400px;">
                        <div class="tab-pane fade show" id="public-bookmark" role="tabpanel" aria-labelledby="nav-home-tab"></div>
                        <div class="tab-pane fade show active" id="private-bookmark" role="tabpanel" aria-labelledby="nav-home-tab"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <b><?=$recordInfo["title"];?></b><br>
    <?=$recordInfo["description"];?><br>
    <?=$this->lang["published_on"];?> : <?=date("d/m/Y - h:i:s",$recordInfo["addtime"]);?><br>

    <hr>
    <div class="text-left profile">
        <span class="imgprofile">
            <i class="fas fa-user"></i>
        </span>
        <span>
            <b><?=$sys->getUserInfo(USER_FULLNAME,$recordInfo["user_id"]);?></b><br>
            <?=$sys->getUserInfo(USER_EMAIL,$recordInfo["user_id"]);?>
        </span>
    </div>
    <hr>
    <div class="container w-100 m-2 p-0 mw-100">
        <div class="row w-100">
            <form method="post" action="ajax/comment/addcomment.php" onsubmit="return addComment(this);">
                <input type="hidden" name="recordid" value="<?=$recordInfo["record_id"];?>">
                <input type="hidden" name="sessionid" value="<?=$auth->getSessionID();?>">
                <input type="hidden" name="hashid" value="<?=$auth->getSecHash($recordInfo["record_id"]);?>">
                <div class="row w-100">
                    <div class="col-1 p-0" style="width: 55px"><div class="commentprofile"><i class="fas fa-user"></i></div></div>
                    <div class="col p-0">
                        <h6 class="m-0"><?=$auth->getInfo(LOGIN_FULLNAME)?></h6>
                        <textarea class="form-control float-left mt-2" placeholder="Publier un commentaire ou une question ..." id="commentText"></textarea>
                    </div>
                    <div class="col-1 p-0 pt-4 pl-2" style="width: 65px"><input type="submit" class="btn btn-primary mt-3" value="Publié"></div>
                </div>
            </form>
            <div id="comment_error_zone"></div>
            <div id="noconnection" class="alert alert-danger mt-2" style="display: none"><?=$this->lang["nointernetconnection"];?></div>
            <hr class="mt-3">
            <div id="comments">Loading comment ...</div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=$this->config->template["js"];?>/comment.js"></script>
<script>
    var record_id = <?=$recordInfo["record_id"];?>;
    var hash      = '<?=$auth->getSecHash($recordInfo["record_id"]);?>';
    var lang = {
        "delete_bookmark" : "<?=$this->lang["admin"]["confirm_delete"];?>"
    };
    getComments(<?=$recordInfo["record_id"];?>,"<?=$auth->getSecHash($recordInfo["record_id"]);?>");
</script>

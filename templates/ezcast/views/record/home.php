<div class="p-3 mt-2 mb-2 bg-white text-dark border">
    <div class="container-fluid m-0 w-100 mw-100">
        <div class="row">
            <div class="pl-3">
                <b><?=$recordInfo["course_code"] . " " . $recordInfo["course_name"];?></b>
                <hr>
            </div>

            <div class="col-lg-9 col-sm-9">
                <?php include $this->tmp->load("player/hls/player.php"); //TODO Add old video compability?>
            </div>
            <div class="col-lg-3 col-sm-3 border">
                Bookmarks list
                <hr>
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
    getComments(<?=$recordInfo["record_id"];?>,"<?=$auth->getSecHash($recordInfo["record_id"]);?>");
</script>

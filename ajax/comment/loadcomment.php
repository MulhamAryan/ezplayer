<?php
    include "../../config.php";

    $sys  = new System();
    $auth = new Authentication();
    $log  = new Log();
    $tmp  = new Templates();

    $auth->requireLogin();
    $recordid = $sys->input("recordid",SET_INT);
    $hash     = $sys->input("hash",SET_STRING);
    if($auth->validateHash($hash,$recordid) == true):
    require_once $config->directory["library"] . "/comment.php";
    $comments = getComments($recordid);
    if($comments["error"] == false):
    foreach ($comments["msg"] as $cmt):
?>
        <div class="row w-100">
            <div class="col-1 p-0" style="width: 55px"><div class="commentprofile"><i class="fas fa-user"></i></div></div>
            <div class="col p-0">
                <h6 class="ml-0 mb-2"><?=$auth->getUserInfo(USER_FULLNAME,$cmt["userid"])?> <span style="font-weight: normal"> - <?=$lang["since"];?> <?=$tmp->convertTime($cmt["addtime"]);?></span></h6>
                <?=$cmt["comment"];?>
            </div>
            <div class="col-1 p-0 pt-4 pl-2" style="width: 65px"></div>
        </div>
        <hr>
<?php
    endforeach;
    endif;
    endif;
?>


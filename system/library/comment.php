<?php
    function checkAccess(int $recordid){
        global $sys;
        global $auth;
        $recordInfo  = $auth->instance(CHK_RECORD,$recordid);
        $canAccess   = $auth->getEnrollment(ENR_CAN_ACCESS,$recordInfo["course_id"]);
        return $canAccess;
    }

    function addComment(array $commentArray){
        global $sys;
        global $lang;
        $canAccess = checkAccess($commentArray["recordid"]);

        if($canAccess){
            $sqlComment = array(
                "userid"   => $commentArray["userid"],
                "recordid" => $commentArray["recordid"],
                "comment"  => $commentArray["comment"],
                "addtime"  => time()
            );
            $sys->insert(Databases::comments,$sqlComment);
            $ans = array(
                "error" => false,
                "msg" => $lang["commentadded"]
            );
        }
        else{
            $ans = array(
                "error" => true,
                "msg" => $lang["permission_denied"]
            );
        }
        return $ans;
    }

    function getComments($recordid){
        global $sys;
        global $lang;

        $canAccess = checkAccess($recordid);
        if($canAccess){
            $sqlComments = array(
                "table" => Databases::comments,
                "fields" => array(
                    "recordid" => $recordid
                )
            );
            $comments = $sys->fetch($sqlComments);

            $ans = array(
                "error" => false,
                "msg" => $comments
            );
        }
        else{
            $ans = array(
                "error" => true,
                "msg" => $lang["permission_denied"]
            );
        }
        return $ans;
    }
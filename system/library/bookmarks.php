<?php

    function addBookmark()
    {
        global $sys;
        global $lang;
        global $auth;

        $record_id = $sys->input("record_id", SET_INT);
        $hash      = $sys->input("hash",SET_STRING);

        $recordInfo = $auth->instance(CHK_RECORD, $record_id);
        $enrollment = $auth->getEnrollment(ENR_ACCESS_TYPE, $recordInfo["course_id"]);

        if (in_array("view", $enrollment)) {
            $title = $sys->input("bookmark_title", SET_STRING);
            $description = $sys->input("bookmark_description", SET_STRING);
            $timecode = $sys->input("bookmark_timecode", SET_STRING);
            $public = $sys->input("bookmark_public",SET_INT);
            $timecode = strtotime($timecode) - strtotime("TODAY");

            if (($public == 1 && in_array("edit", $enrollment)) || ($public == 0 && in_array("view", $enrollment))){
                $sqlBookmark = array(
                    "title" => $title,
                    "description" => $description,
                    "user_id" => $auth->getInfo(LOGIN_USER_ID),
                    "record_id" => $record_id,
                    "timecode" => $timecode,
                    "public" => $public,
                    "addtime" => time()
                );

                $addToBookmark = $sys->insert(Databases::records_bookmarks, $sqlBookmark);
                if ($addToBookmark == true) {
                    $ans = getBookmarks($record_id,$hash);
                } else {
                    $ans = array(
                        "error" => true,
                        "msg" => $lang["permission_denied"]
                    );
                }
            } else {
                $ans = array(
                    "error" => true,
                    "msg" => $lang["permission_denied"]
                );
            }
        }
        return $ans;
    }

    function getBookmarks(int $recordID = null, string $hash = null)
    {
        global $sys;
        global $auth;
        global $lang;
        if(is_null($recordID)){
            $recordID = $sys->input("record_id",SET_INT);
        }
        if(is_null($hash)){
            $hash     = $sys->input("hash",SET_STRING);
        }

        if($auth->validateHash($hash,$recordID) == true) {
            $recordInfo = $auth->instance(CHK_RECORD, $recordID);
            $enrollment = $auth->getEnrollment(ENR_ACCESS_TYPE, $recordInfo["course_id"]);
            if (in_array("view", $enrollment)) {
                $bookmarksSql = array(
                    "table" => Databases::records_bookmarks,
                    "fields" => array(
                        "record_id" => $recordID
                    )
                );
                $bookmarks = $sys->fetch($bookmarksSql);
                $isAdmin = ((in_array("edit", $enrollment)) ? true : false);
                foreach ($bookmarks as $bookmark) {
                    if ($isAdmin == true || $bookmark["user_id"] == $auth->getInfo(LOGIN_USER_ID)) {
                        $hashid = "{$bookmark["record_id"]}_{$bookmark["id"]}_{$bookmark["user_id"]}";
                        $bookmark["canedit"] = $auth->getSecHash($hashid);
                    } else {
                        $bookmark["canedit"] = false;
                    }
                    $newBookmarks[] = $bookmark;
                }
                return $newBookmarks;
            } else {
                return array(
                    "error" => true,
                    "msg" => $lang["permission_denied"]
                );
            }
        }
        else{
            return array(
                "error" => true,
                "msg" => $lang["permission_denied"]
            );
        }
    }

    function delete_bookmark(int $bookmarkid, string $hash)
    {
        global $sys;
        global $auth;
        global $lang;

        $bookmark = $sys->select(array("table" => Databases::records_bookmarks, "fields" => array("id" => $bookmarkid)));
        if ($bookmark != false){
            $generateHash = $auth->getSecHash("{$bookmark["record_id"]}_{$bookmark["id"]}_{$bookmark["user_id"]}");
            $canDelete = ($auth->getInfo(LOGIN_PERMISSIONS) == 1 ? true : ($generateHash == $hash) ? true : false);
            if ($canDelete == true) {
                $sqlDelete = $sys->delete(array("table" => Databases::records_bookmarks, "condition" => "id='{$bookmarkid}'"));

                if ($sqlDelete == true)
                    $ans = array("error" => false, "msg" => $lang["delete"]);
                else
                    $ans = array("error" => true, "msg" => $lang["permission_denied"]);
            } else {
                $ans = array("error" => true, "msg" => $lang["permission_denied"]);
            }
        }
        else{
            $ans = array("error" => true, "msg" => $lang["permission_denied"]);
        }
        return $ans;
    }
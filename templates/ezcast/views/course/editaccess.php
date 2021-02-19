<h6><?=$this->lang["access_management"];?> : <?=$courseInfo["course_name"];?></h6>
<?php

if ($courseInfo["anon_access"] == 2){
        $spanTitle = $this->lang["only_organization_student"];
        $spanHelp  = $this->lang["instruction_only_organization_student"];
    }
    elseif ($courseInfo["anon_access"] == 3){
        $spanTitle = $this->lang["shared_url"];
        $spanHelp  = $this->lang["instruction_shared_url"];
    }
    elseif ($courseInfo["anon_access"] == 4){
        $spanTitle = $this->lang["everybody"];
        $spanHelp  = $this->lang["instruction_everybody"];
    }
?>
<hr>
<form method="post">
    <div class="form-group bg-light p-3 border">
        <label for="accessmenu"><?=$this->lang["course_consultation"];?> : </label>
        <hr>
        <?php
        if(isset($saved) == true){
            echo $this->tmp->getSuccess($this->lang["pubished_records"]);
        }
        ?>
        <select id="accessmenu" class="form-control form-control-lg mb-2" name="accessmenu">
            <option value="0" <?=$this->tmp->selected($courseInfo["anon_access"],0);?>><?=$this->lang["only_registred"];?></option>
            <option value="2" <?=$this->tmp->selected($courseInfo["anon_access"],2);?>><?=$this->lang["only_organization_student"];?></option>
            <option value="3" <?=$this->tmp->selected($courseInfo["anon_access"],3);?>><?=$this->lang["shared_url"];?></option>
            <option value="4" <?=$this->tmp->selected($courseInfo["anon_access"],4);?>><?=$this->lang["everybody"];?></option>
            <option disabled><?=$this->lang["personalized"];?></option>
        </select>
        <div style="display: <?php if($courseInfo["anon_access"] != 0) echo "block"; else echo "none";?>" id="usingUrl">
        <hr>
            <div class="row">
                <div class="col">
                    <span id="urlTitle"><?=$spanTitle;?></span> - <a href="ajax/regenerattoken.php?type=course&id=<?=$courseInfo["id"];?>&hashid=<?=$auth->getSecHash($courseInfo["id"]);?>" id="regenerateLink"><?=$this->lang["regenerate_url_token"];?></a>
                    <div class="card">
                        <div class="card-body p-2" id="copyTxt" >
                            <?=$this->url(array("file" => System::fileCourse, "parameters" => array("id" => $courseInfo["id"],"token" => $courseInfo["token"])));?>
                        </div>
                    </div>
                    <span class="form-text text-muted" id="urlHelp"><?=$spanHelp;?></span>
                </div>
                <div class="col-2 p-0 pt-4 pl-2">
                    <button class="btn btn-primary" type="button" id="copyBtn"><?=$this->lang["copy_url"];?></button>
                </div>
            </div>
            <span id="answerTxt" class="text-white bg-dark p-2 mt-2" style="display: none"></span>
        </div>
        <br>
        <div class="float-right"><span class="text text-muted mr-1"><?=$this->lang["save_instruction"];?></span><input type="submit" class="btn btn-primary float-right" value="<?=$this->lang["save"];?>" name="saveModifications"></div>
        <div class="clearfix"></div>
    </div>
</form>
<script>
    let copyBtn = document.getElementById("copyBtn");
    let copyTxt = document.getElementById("copyTxt");
    let accessmenu = document.getElementById("accessmenu");
    let regenerateLink = document.getElementById("regenerateLink");
    let spanTitle = document.getElementById("urlTitle");
    let spanHelp  = document.getElementById("urlHelp");
    let usingUrl = document.getElementById("usingUrl");

    copyBtn.onclick = function () {
        if (document.selection) {
            let range = document.body.createTextRange();
            range.moveToElementText(copyTxt);
            range.select().createTextRange();
            document.execCommand("copy");
        } else if (window.getSelection) {
            let range = document.createRange();
            range.selectNode(copyTxt);
            window.getSelection().addRange(range);
            document.execCommand("copy");
        }
        document.getElementById("answerTxt").innerText = "Copié !";
        document.getElementById("answerTxt").style.display = "inline-block";
    };

    accessmenu.onchange = function () {
        if(this.value === "2"){
            spanTitle.innerText = "<?=$this->lang["only_organization_student"];?>";
            spanHelp.innerText  = "<?=$this->lang["instruction_only_organization_student"];?>";
            usingUrl.style.display = "block";
        }
        else if (this.value === "3"){
            spanTitle.innerText = "<?=$this->lang["shared_url"];?>";
            spanHelp.innerText  = "<?=$this->lang["instruction_shared_url"];?>";
            usingUrl.style.display = "block";
        }
        else if (this.value === "4"){
            spanTitle.innerText = "<?=$this->lang["everybody"];?>";
            spanHelp.innerText  = "<?=$this->lang["instruction_everybody"];?>";
            usingUrl.style.display = "block";
        }
        else{
            usingUrl.style.display = "none";
        }
    };

    regenerateLink.onclick = copyLink;

    function copyLink(event) {
        const xhr = new XMLHttpRequest();
        console.log(event.href);
        event.preventDefault();
        if(window.confirm("<?=$this->lang["alert_non_reversible"];?>")){
            const url = this.href;
            xhr.open("GET",url,false);
            xhr.send();
            copyTxt.innerText = xhr.responseText;
        }
        return xhr;
    }
</script>
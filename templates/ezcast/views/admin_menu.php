<nav class="bd-links">
<?php
    $adminMenu = $this->getAdminList();
    foreach ($adminMenu as $groupKey => $groupVal){
        foreach ($groupVal as $key => $val){
            $key = explode(":",$key);
            ?>
            <div class="list-group border-bottom" id="leftSideBarLink">
                <span class="p-2 border-bottom border-top bg-light"><i class="fas fa-<?=$key[1];?>"></i> <?=$this->lang["admin"][$key[0]];?></span>
                <?php
                    foreach ($val as $list){
                        $listUrl = $this->url(array("file" => System::folderAdmin . "/" . $list["link"]))
                        ?>
                        <a class="nav-link" href="<?=$listUrl;?>"><?=$this->lang["admin"][$list["title"]];?></a>
                    <?php } ?>
            </div>
        <?php
        }
    }
?>
    <div class="p-3 border-bottom font-weight-bolder"><?=$this->lang["my_courses"];?></div>

</nav>

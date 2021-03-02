<div class="mt-2 p-3 mb-2 bg-white text-dark border">
    <b><?=$lang["admin"]["users_list"];?></b>
    <hr>
    <form method="get">
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default"><?=$lang["users"]["search"];?></span>
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="<?=$lang["users"]["username"];?>, <?=$lang["users"]["name"];?>, <?=$lang["users"]["id"];?> ..." name="userSearch" value="<?=$search;?>">
            <span class="input-group-text" id="inputGroup-sizing-default">
                <input type="submit" value="Chercher" name="searchCourse" class="btn btn-primary">
            </span>
        </div>
    </form>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">«</a></li>
            <?php for($myPage = 1; $myPage <= $totalPages; $myPage++): ?>
                <li class="page-item <?= ($myPage == $page) ? "active":"";?>"><a class="page-link" href="<?=$_SERVER['REQUEST_URI'];?>&page=<?=$myPage;?>"><?=$myPage;?></a></li>
            <?php endfor; ?>

            <?php if(isset($lastMax)): ?>
                <li class="page-item"><a class="page-link" href="#"> ... </a></li>
                <?php for ($endPages = $lastMax - 2;$endPages <= $lastMax; $endPages++): ?>
                    <li class="page-item <?=($endPages == $page) ? "active":"";?>"><a class="page-link" href="<?=$_SERVER['REQUEST_URI'];?>&page=<?=$endPages;?>"><?=$endPages;?></a></li>
                <?php endfor;?>
            <?php endif;?>
            <li class="page-item"><a class="page-link" href="<?=$_SERVER['REQUEST_URI'];?>&page=<?=$page+1;?>">»</a></li>
        </ul>
    </nav>
    <?php
        if(!empty($success)){
            echo $tmp->getSuccess($success);
        }
        elseif (!empty($error))
            echo $tmp->getError($error);
    ?>
    <?php if (!empty($search)):?>
        <div class="text-danger font-weight-bold border-bottom">Environ <?=count($users);?> resultats trouvés</div>
    <?php endif;?>
    <table class="table table-striped">
        <thead class="font-weight-bold">
            <tr>
                <td scope="col">#</td>
                <td scope="col"><?=$lang["users"]["username"];?></td>
                <td scope="col"><?=$lang["users"]["fullname"];?></td>
                <td scope="col"><?=$lang["origin"];?></td>
                <td scope="col"><?=$lang["options"];?></td>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user) :?>
            <tr <?=($user["permissions"] == "deleted") ? 'class="btn-outline-danger"' : '';?>>
                <td><?=$user["id"];?></td>
                <td><?=$user["user_ID"];?></td>
                <td><a href="<?=$sys->url(array("file"=>System::fileCourse, "parameters" => array("id" => $user["id"])));?>" target="_blank" title="Voir le cours"><?=$user["forename"];?> <?=strtoupper($user["surname"]);?></a></td>
                <td><?=$user["origin"];?></td>
                <td>
                    <a href="<?=$sys->url(array("file"=>System::fileCourse, "parameters" => array("id" => $user["id"],"edit" => "course", "sessionid" => $auth->getSessionID())));?>" target="_blank" title="Modifier le cours"><i class="fas fa-edit"></i></a>
                    <?php if($user["permissions"] != "deleted"):?>
                        <a href="<?=$sys->url(array("file"=>System::fileUserConf, "parameters" => array("id" => $user["id"],"do" => "delete", "sessionid" => $auth->getSessionID())));?>" title="<?=$lang["delete"];?> <?=$user["user_ID"];?>" onclick="return confirm('<?=$lang["admin"]["confirm_delete"];?>')"><i class="fas fa-times"></i></a>
                    <?php else:?>
                        <a href="<?=$sys->url(array("file"=>System::fileUserConf, "parameters" => array("id" => $user["id"],"do" => "restore", "sessionid" => $auth->getSessionID())));?>" title="<?=$lang["users"]["restore"];?> <?=$user["user_ID"];?>"><i class="fas fa-trash-restore"></i></a>
                    <?php endif;?>

                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>

</div>
<div class="mt-2 p-3 mb-2 bg-white text-dark border">
    <b><?=$lang["admin"]["courses_list"];?></b>
    <hr>
    <form method="get">
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default"><?=$lang["course"]["search"];?></span>
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="<?=$lang["course"]["code"];?>, <?=$lang["course"]["title"];?> ..." name="courseName" value="<?=$search;?>">
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
    <?php if (!empty($search)):?>
        <div class="text-danger font-weight-bold border-bottom">Environ <?=count($courses);?> resultats trouvés</div>
    <?php endif;?>
    <table class="table table-striped">
        <thead class="font-weight-bold">
            <tr>
                <td scope="col">#</td>
                <td scope="col"><?=$lang["course"]["code"];?></td>
                <td scope="col"><?=$lang["course"]["title"];?></td>
                <td scope="col"><?=$lang["origin"];?></td>
                <td scope="col"><?=$lang["options"];?></td>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($courses as $cours) :?>
            <tr>
                <td><?=$cours["id"];?></td>
                <td><?=$cours["course_code"];?></td>
                <td><a href="<?=$sys->url(array("file"=>System::fileCourse, "parameters" => array("id" => $cours["id"])));?>" target="_blank" title="Voir le cours"><?=$cours["course_name"];?></a></td>
                <td><?=$cours["origin"];?></td>
                <td>
                    <a href="<?=$sys->url(array("file"=>System::fileCourse, "parameters" => array("id" => $cours["id"],"edit" => "course", "sessionid" => $auth->getSessionID())));?>" target="_blank" title="Modifier le cours"><i class="fas fa-edit"></i></a>
                    <a href="<?=$sys->url(array("file"=>System::fileCourse, "parameters" => array("id" => $cours["id"])));?>" target="_blank" title="Voir le cours"><i class="fas fa-external-link-alt"></i></a>
                    <a href="<?=$sys->url(array("file"=>System::fileCourse, "parameters" => array("id" => $cours["id"],"edit" => "enrolled", "sessionid" => $auth->getSessionID())));?>" target="_blank" title="Utilisateur inscrit"><i class="fas fa-users"></i></a>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>

</div>
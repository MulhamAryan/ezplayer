    <div id="homeLeftSideBar" class="col-10 col-md-3 col-xl-2 bd-sidebar shadow">
        <?php if($this->auth->getInfo(LOGIN_PERMISSIONS) == 1) include "admin_menu.php";?>
        <?php if(!empty($userCourses)): ?>
            <nav class="bd-links">
            <div class="list-group p-3 border-bottom d-md-none d-xl-none">
                <form method="post" action="search.php?do=search">
                    <div class="input-group search">
                        <input class="form-control pt-2 pb-2 border-right-0 rounded-0" type="text" placeholder="Chercher un cours ...">
                        <span class="input-group-append">
                        <button class="input-group-text bg-transparent border-left-0 rounded-0" type="submit"><i class="fas fa-search p-2" id="expandBarButton"></i></button>
                    </span>
                    </div>
                </form>
            </div>
            <div class="list-group" id="leftSideBarLink">
            <?php
                foreach ($userCourses as $userCours){ ?>
                <a href="<?=$this->url(array("file" => System::fileCourse, "parameters" => array("id" => $userCours["id"])));?>" class="p-3 border-bottom openCourse" rel="<?=$userCours["id"];?>" onclick='loadCourse("<?=$this->setTitle($userCours["course_code"]);?>",<?=$userCours["id"];?>); return false;'>
                    <span class="font-weight-bold">
                        <?=$userCours["course_code"];?>
                    </span>
                    <span class="d-nosne d-xl-block"> <?=$userCours["course_name"];?></span>
                </a>
                <?php } ?>
            </div>
        </nav>
        <?php endif; ?>
    </div>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PODCAST</title>
    <base href="<?=$this->config->url;?>">
    <link rel="stylesheet" href="<?=$this->config->template["css"];?>/bootstrap/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=$this->config->template["cssdir"];?>/fontawesome/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=$this->config->template["css"];?>/app.css" crossorigin="anonymous">
    <script src="<?=$this->config->template["js"];?>/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script language="JavaScript" type="text/javascript" src="<?=$this->config->template["js"];?>/app.js"></script>
</head>
<body>
    <?php if($this->auth->isLogged()){ ?>
    <header class="navbar navbar-expand navbar-grey flex-column flex-md-row bd-navbar pt-0 pb-0">
        <div class="container-fluid border-bottom">
            <div class="col-4 col-md-3 col-xl-2 border-right text-center">
                <div class="expand-bar d-md-none d-xl-nsone" id="leftSideBarButton">
                    <i class="fas fa-bars" id="expandBarButton"></i>
                </div>
                <div style="display: inline-block">
                    <a href="#" class="navbar-brand mr-3">
                        <img src="<?=$this->config->template["images"]; ?>ezplayerlogo.png" alt="EZplayer logo"/>
                    </a>
                </div>
                <div class="clear"></div>
            </div>
            <div class="float-left col-xl-6 col-md-6 d-none d-md-block">
                <form method="get" action="search.php?">
                    <div class="input-group search">
                        <input class="form-control pt-2 pb-2 border-right-0 rounded-0" type="text" placeholder="Chercher un cours ..." name="q" value="<?=$this->input("q",SET_STRING);?>">
                        <span class="input-group-append">
                        <button class="input-group-text bg-transparent border-left-0 rounded-0" type="submit"><i class="fas fa-search p-2" id="expandBarButton"></i></button>
                    </span>
                    </div>
                </form>
            </div>
            <div class="float-right col-lg-2 col-md-3 col-sm-5">
            <span class="float-right mr-4">
                <?=$this->auth->getInfo(LOGIN_FULLNAME, 0); ?><br>
                <a href="index.php?signout=true"><?=$this->lang["signout"]; ?></a>
            </span>
            </div>
        </div>
    </header>
    <div class="row full-height bg-light-more">
        <?php
        if ($array["requireLeftMenu"] == true)
            require_once "left_menu.php";
        }
        if($array["requireBody"] == true)
            require_once "body.php";
        ?>
<div class="container-fluid full-height width-control">
    <div class="row full-height">
        <div class="col bd-content bg-light-more"><div class="bg-white mx-auto p-4 mt-5 col-12 col-sm-9 col-md-6 col-lg-3 shadow">
                <div style="text-align: center">
                    <img src="<?php echo $this->config->template["images"];?>/ezplayerlogo.png" />
                </div>
                <div class="text-center">
                    <h5><?=$this->lang["connect"];?></h5>
                    <?=$this->lang["access_to_ezplayer"];?>
                </div>
                <br>
                <?php if(isset($error)) echo $error; ?>
                <form method="post" action="">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-user pt-2 pb-2"></i></span></div>
                        <input type="text" name="username" class="form-control pt-2 pb-2" placeholder="NetID" aria-label="Username" aria-describedby="basic-addon1" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-lock pt-2 pb-2"></i></span></div>
                        <input type="password" name="userpass" class="form-control pt-2 pb-2" placeholder="Mot de passe" aria-label="Userpss" aria-describedby="basic-addon1" required>
                    </div>
                    <hr>
                    <input class="btn btn-primary w-100 p-3" value="<?php echo $this->lang["signin"];?>" type="submit" name="signin"><br><br>
                    <div style="text-align: center;">
                    <?php
                        foreach ($this->config->activeLanguage as $lngKey => $lngVal){?>
                            <a href="lang.php?action=change&id=<?php echo $lngKey;?>"> | <?php echo $lngVal;?></a>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
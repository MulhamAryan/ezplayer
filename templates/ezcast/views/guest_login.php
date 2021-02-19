<div class="container-fluid full-height width-control">
    <div class="row full-height">
        <div class="col bd-content bg-light-more"><div class="bg-white mx-auto p-4 mt-5 col-12 col-sm-9 col-md-6 col-lg-3 shadow">
                <div style="text-align: center">
                    <img src="<?php echo $this->config->template["images"];?>/ezplayerlogo.png" />
                </div>
                <div class="text-center">
                    <h5><?=$this->lang["connect"];?></h5>
                    <?=$this->lang["access_to_ezplayer"];?>
                    <?=$this->lang["as_guest"];?>
                </div>
                <br>
                <?php if(isset($error)) echo $error; ?>
                <form method="post" action="">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-address-card pt-2 pb-2"></i></span></div>
                        <input type="text" name="fullname" class="form-control pt-2 pb-2" placeholder="<?=$this->lang["full_name"];?>" aria-label="fullname" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="captcha" class="form-control pt-2 pb-2" placeholder="<?=$this->lang["write_captcha"];?>" aria-label="captcha" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3 text-center">
                        <img src="public/captcha.php?time=<?=time();?>" class="text-center m-auto">
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
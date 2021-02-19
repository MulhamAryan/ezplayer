<div class="container-fluid full-height width-control">
    <div class="row full-height">
        <div class="col bd-content bg-light-more"><div class="bg-white mx-auto p-4 mt-5 col-12 col-sm-9 col-md-6 col-lg-4 shadow">
                <div style="text-align: center">
                    <img src="<?php echo $this->config->template["images"];?>/ezplayerlogo.png" />
                </div>
                <div class="text-center">
                    <h5><?=$this->lang["create_new_user"];?></h5>
                    <?=$this->lang["access_to"];?> EZplayer
                </div>
                <hr>
                <div class="text-center"><?=$this->lang["already_has_account"];?></div>
                <hr>
                <div id="answer"></div>
                <?php if(isset($error)) echo $error; ?>
                <div id="formSignup">
                    <form method="post" action="ajax/signup.php" id="signup">
                        <input type="hidden" value="<?=$courseInfo["id"];?>" name="id">
                        <input type="hidden" value="<?=$courseInfo["token"];?>" name="token">
                        <input type="hidden" value="<?=$courseInfo["type"];?>" name="type">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-user pt-2 pb-2"></i></span></div>
                            <input type="text" name="forname" id="forname" class="form-control pt-2 pb-2" placeholder="<?=$this->lang["forname"];?>" aria-label="forname" aria-describedby="basic-addon1" required>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-user pt-2 pb-2"></i></span></div>
                            <input type="text" name="surname" id="surname" class="form-control pt-2 pb-2" placeholder="<?=$this->lang["surname"];?>" aria-label="surname" aria-describedby="basic-addon1" required>
                        </div>
                        <div class="input-group mb-0">
                            <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-address-card pt-2 pb-2"></i></span></div>
                            <input type="text" name="username" id="username" class="form-control pt-2 pb-2" placeholder="<?=$this->lang["username"];?>" aria-label="Username" aria-describedby="basic-addon1" pattern="[a-zA-Z0-9-]+" required>
                        </div>
                        <div class="form-text text-muted border-top p-2">
                            <i class="far fa-question-circle"></i> ULBID : Doit être composé uniquement de caractères alphanumériques
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-envelope pt-2 pb-2"></i></span></div>
                            <input type="email" name="usermail" id="usermail" class="form-control pt-2 pb-2" placeholder="<?=$this->lang["email"];?>" aria-label="usermail" aria-describedby="basic-addon1" required>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-lock pt-2 pb-2"></i></span></div>
                            <input type="password" name="userpass" id="userpass" class="form-control pt-2 pb-2" placeholder="<?=$this->lang["password"];?>" aria-label="userpass" aria-describedby="basic-addon1" required>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-lock pt-2 pb-2"></i></span></div>
                            <input type="password" name="confirmpass" id="confirmpass" class="form-control pt-2 pb-2" placeholder="<?=$this->lang["confirm_password"];?>" aria-label="confirmepass" aria-describedby="basic-addon1" >
                        </div>
                        <div class="form-text text-muted border-top p-2" id="passwordConsign">
                            <div class="text-danger m-0 p-0 font-weight-bold"><i class="far fa-question-circle"></i> <?=$this->lang["password_consign"]["title"];?></div>
                            <?php foreach ($this->lang["password_consign"]["parameters"] as $parameter):?>
                                <span><?=$parameter;?></span><br>
                            <?php endforeach;?>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend w-100 text-center">
                                <img src="public/captcha.php?time=<?=time();?>" id="captcha"><a href="#" onclick="refreshCaptcha()"><i class="fas fa-sync-alt"></i></a>
                                <br>
                                <input type="text" name="captcha" id="captcha" class="form-control pt-2 pb-2" placeholder="<?=$this->lang["write_captcha"];?>">
                            </div>
                        </div>
                        <hr>
                        <input class="btn btn-primary w-100 p-3" value="<?=$this->lang["signup"];?>" type="submit" name="singup"><br><br>
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
</div>
<script>
    let signupForm  = document.getElementById("signup");
    let userpass    = document.getElementById("userpass");
    let confirmpass = document.getElementById("confirmpass");
    let params      = "";
    document.addEventListener('submit', function (e) {
        e.preventDefault();
        for (const[key, val] of Object.entries(signupForm)){
            if(val.value === ""){
                val.setAttribute("required","");
                return false;
            }
            params += val.name + "=" + val.value + "&";
        }
        if(confirmpass.value !== userpass.value){
            return false;
        }
        if(validatePassword(userpass.value) === false){
            document.getElementById("passwordConsign").classList.add("border");
            document.getElementById("passwordConsign").classList.add("border-danger");
            return false;
        }
        else{
            document.getElementById("passwordConsign").classList.remove("border");
            document.getElementById("passwordConsign").classList.remove("border-danger");
        }
        let post = {
            "url" : signupForm.action,
            "parmeters" : params
        };
        postData(post,"answer");
        refreshCaptcha();
        return false;
    });

    function refreshCaptcha() {
        let timeStamp = new Date().getTime();
        let img = document.getElementById("captcha");
        document.getElementById("captcha").value = "";
        img.src = "public/captcha.php?time=" + timeStamp;
        return false;
    }

    function postData(parameters,answerDiv){
        let answer = document.getElementById(answerDiv);
        let xhr = new XMLHttpRequest();
        let cssclass;
        xhr.open('POST', parameters["url"], true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                var resp = JSON.parse(this.responseText);
                if(resp.error === true){
                    cssclass = "alert-danger"
                }
                else{
                    cssclass = "alert-success";
                    document.getElementById("formSignup").innerHTML = "";
                    document.getElementById("formSignup").style.display = "none";
                }
                answer.innerHTML = "<div class=\"alert " + cssclass + "\">" + resp.msg + "</div>";
            }
        };

        xhr.send(parameters["parmeters"]);
    }

    function validatePassword(password) {
        return !!password.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/);
    }
</script>
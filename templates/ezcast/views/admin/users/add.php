<div class="mt-2 p-3 mb-2 bg-white text-dark border">
    <b><?=$lang["admin"]["add_user"];?></b>
    <hr>
    <?php
        if(isset($error))
            echo $tmp->getError($error);
    ?>
    <form method="post">
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default"><?=$lang["users"]["username"];?></span>
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="<?=$lang["users"]["username"];?>" name="username" value="<?=$username;?>" required>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default"><?=$lang["forname"];?></span>
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="<?=$lang["forname"];?>" name="forname" value="<?=$forname;?>" required>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default"><?=$lang["surname"];?></span>
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="<?=$lang["surname"];?>" name="surname" value="<?=$surname;?>" required>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default"><?=$lang["email"];?></span>
            <input type="email" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="<?=$lang["email"];?>" name="usermail" value="<?=$usermail;?>" required>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default"><?=$lang["password"];?></span>
            <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="<?=$lang["password"];?>" name="userpass" value="<?=$userpass;?>" required>
        </div>
        <div class="form-check form-switch font-weight-bold">
            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="permissions" value="1">
            <label class="form-check-label" for="flexSwitchCheckDefault">Administrateur ?</label>
        </div>
        <hr>
        <b><?=$lang["origin"];?></b>
        <div class="mb-3 form-check">
            <?php foreach ($loginMethod as $method): ?>
                <input class="form-check-input" type="radio" name="origin" id="radio1" value="<?=$method;?>" <?=($method == "internal") ? "checked" : ""?> required>
                <label class="form-check-label" for="radio1"><?=$method;?> </label><br>
            <?php endforeach;?>
        </div>

        <input type="submit" value="<?=$lang["save"];?>" name="submitCourse" class="btn btn-primary">
    </form>
</div>
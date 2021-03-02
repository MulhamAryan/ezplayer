<div class="mt-2 p-3 mb-2 bg-white text-dark border">
    <b><?=$lang["admin"]["add_course"];?></b>
    <hr>
    <?php
        if(!empty($error))
            echo $tmp->getError($error);
    ?>
    <form method="post">
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default"><?=$lang["course"]["code"];?></span>
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="<?=$lang["course"]["code"];?>" name="addCourseCode" value="<?=$courseCode;?>">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default"><?=$lang["course"]["title"];?></span>
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="<?=$lang["course"]["title"];?>" name="addCourseName" value="<?=$courseName;?>">
        </div>
        <b><?=$lang["origin"];?></b>
        <div class="mb-3 form-check">
            <input class="form-check-input" type="radio" name="origin" id="radio1" value="internal" checked>
            <label class="form-check-label" for="radio1"><?=$lang["internal"];?> </label><br>

            <input class="form-check-input" type="radio" name="origin" id="radio2" value="external">
            <label class="form-check-label" for="radio2"><?=$lang["external"];?> </label>
        </div>
        <input type="submit" value="<?=$lang["save"];?>" name="submitCourse" class="btn btn-primary">
    </form>
</div>
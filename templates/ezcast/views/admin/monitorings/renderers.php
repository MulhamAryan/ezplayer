<script>
    function checkServerStatus(serverid,username,server) {
        let xhrGetUsage = new XMLHttpRequest();
        xhrGetUsage.addEventListener("load", function (e) {
            let cpu = document.getElementById("cpu_" + serverid);
            let ram = document.getElementById("ram_" + serverid);
            let dsk = document.getElementById("dsk_" + serverid);

            let usageAns = JSON.parse(this.responseText);
            document.getElementById("loadingCpu_" + serverid).style.display = "none";
            document.getElementById("loadingRam_" + serverid).style.display = "none";
            document.getElementById("loadingDD_" + serverid).style.display = "none";
            if (usageAns["error"] === true) {
                document.getElementById("message_" + serverid).style.display = "";
                document.getElementById("message_" + serverid).innerHTML = usageAns["message"];
            } else {
                cpu.style.width = usageAns[0] + "%";
                cpu.innerText = usageAns[0] + "%";

                ram.style.width = usageAns[1] + "%";
                ram.innerHTML = usageAns[1] + "%";

                dsk.style.width = usageAns[2] + "%";
                dsk.innerHTML = usageAns[2] + "%";
            }
        });
        xhrGetUsage.open("GET", "admin/ajax/read_renderer_usage.php?username=" + username + "&hosturl=" + server + "&sessionid=<?=$auth->getSessionID();?>");
        xhrGetUsage.send();
    }
    
    function switchServer(id) {
        let xhr = new XMLHttpRequest();
        xhr.addEventListener("load", function (e) {
            if(xhr.status === 200){
                let xhrAns = JSON.parse(this.responseText);
                if(xhrAns["error"] === true){
                    alert(xhrAns["message"]);
                }
                else {
                    document.getElementById("status_" + id).style.color = (xhrAns["new_status"] === 1) ? "green" : "red";
                }
            }
            else{
                alert("please check your internet connection");
            }
        });
        xhr.open("GET", "admin/ajax/change_renderer_settings.php?id=" + id + "&sessionid=<?=$auth->getSessionID();?>");
        xhr.send();
    }
</script>

<div class="p-3 mt-2 mb-2 bg-white text-dark border">
    <b><?=$lang["admin"]["renderers_list"];?></b>
    <hr>
    <?php if(!empty($renderers)):?>
    <a href="<?=$sys->url(array("file" => System::fileRendering, "parameters" => array("do" => "add")));?>" class="btn btn-success"><?=$lang["admin"]["add_renderer"];?></a>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Nom d'hôte</th>
            <th scope="col">Program encoder</th>
            <th scope="col">CPU</th>
            <th scope="col">RAM</th>
            <th scope="col">DSK</th>
            <th scope="col">max job</th>
            <th scope="col">Activé</th>
            <th scope="col">Options</th>
        </tr>
        </thead>
        <tbody>
    <?php foreach ($renderers as $renderer) : ?>
        <tr>
            <td scope="row"><?=$renderer["id"]?></td>
            <td><?=$renderer["name"]?></td>
            <td><?=$renderer["hosturl"]?></td>
            <td><?=$renderer["encode_program"]?></td>
            <td>
                <div class="progress mb-1 border-top">
                    <div class="spinner-grow spinner-grow-sm bg-danger" role="status" id="loadingCpu_<?=$renderer["id"]?>"></div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="cpu_<?=$renderer["id"]?>"></div>
                </div>
            </td>
            <td>
                <div class="progress mb-1">
                    <div class="spinner-grow spinner-grow-sm bg-warning" role="status" id="loadingRam_<?=$renderer["id"]?>"></div>
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="ram_<?=$renderer["id"]?>"></div>
                </div>
            </td>
            <td>
                <div class="progress mb-1">
                    <div class="spinner-grow spinner-grow-sm bg-primary" role="status" id="loadingDD_<?=$renderer["id"]?>"></div>
                    <div class="progress-bar" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="dsk_<?=$renderer["id"]?>"></div>
                </div>
            </td>
            <td>
                <?=$renderer["maxjob"];?>
            </td>
            <td><i class="fas fa-circle" style="color: <?=($renderer["enabled"] == 1 ? "green":"red");?>" id="status_<?=$renderer["id"];?>"></i></td>
            <td>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?=($renderer["enabled"] == 1 ? "checked":"");?> onchange="switchServer(<?=$renderer["id"];?>)">
                    <a href="<?=$sys->url(array("file" => System::fileRendering,"parameters" => array("do" => "edit", "id" => $renderer["id"], "sessionid" => $auth->getSessionID())));?>" class="btn btn-success"><i class="fas fa-edit"></i></a>
                    <a href="<?=$sys->url(array("file" => System::fileRendering,"parameters" => array("do" => "delete", "id" => $renderer["id"], "hashid" => $auth->getSecHash($renderer["id"]))));?>" class="btn btn-danger" onclick="return confirm('<?=$lang["admin"]["confirm_delete"];?>')"><i class="fas fa-times"></i></a>
                </div>
            </td>
        </tr>
        <tr>
            <th colspan="10" id="message_<?=$renderer["id"]?>" style="display: none" class="alert-danger"></th>
        </tr>
        <script>
            checkServerStatus(<?=$renderer["id"];?>,'<?=$renderer["username"];?>','<?=$renderer["hosturl"];?>');
        </script>
    <?php endforeach; else:?>
        </tbody>
        </table>
    <div class="text-center">
        <i class="fa fa-server fa-6x"></i><br>
        <div class="text-danger p-3"><?=$lang["admin"]["no_renderer"];?></div>
        <a href="<?=$sys->url(array("file" => System::fileRendering, "parameters" => array("do" => "add")));?>"><button class="btn btn-primary"><?=$lang["admin"]["add_renderer"];?></button></a>
    </div>
    <?php endif;?>
</div>
window.onload = function () {
    const mediaType = document.getElementById("mediaType");
    const file_slide = document.getElementById("file_slide");
    const file_audio = document.getElementById("file_audio");
    const submit_slide = document.getElementById("submit_slide");
    const submit_audio = document.getElementById("submit_audio");
    const file_cam = document.getElementById("file_cam");
    const submit_cam = document.getElementById("submit_cam");
    const upload_video_submit = document.getElementById("submit_upload_video");

    mediaType.addEventListener("change", file_upload_type);
    upload_video_submit.addEventListener("submit",init_upload);

    function file_upload_type() {
        if(mediaType.value === "cam"){

            file_cam.disabled = false;
            file_slide.disabled = true;
            file_audio.disabled = true;

            file_slide.value = "";
            file_audio.value = "";

            submit_cam.style.display = "block";
            submit_slide.style.display = "none";
            submit_audio.style.display = "none";
        }
        else if(mediaType.value === "slide"){
            file_cam.disabled = true;
            file_slide.disabled = false;
            file_audio.disabled = true;

            file_cam.value = "";
            file_audio.value = "";

            submit_slide.style.display = "block";
            submit_cam.style.display = "none";
            submit_audio.style.display = "none";

        }
        else if(mediaType.value === "camslide"){
            file_cam.disabled = false;
            file_slide.disabled = false;
            file_audio.disabled = true;

            file_audio.value = "";

            submit_cam.style.display = "block";
            submit_slide.style.display = "block";
            submit_audio.style.display = "none";
        }
        else if(mediaType.value === "audio"){
            file_cam.disabled = true;
            file_slide.disabled = true;
            file_audio.disabled = false;

            file_slide.value = "";
            file_cam.value = "";

            submit_cam.style.display = "none";
            submit_slide.style.display = "none";
            submit_audio.style.display = "block";
        }
        else{
            file_cam.disabled = false;
            file_slide.disabled = true;
            file_audio.disabled = true;

            submit_cam.style.display = "block";
            submit_slide.style.display = "none";
            submit_audio.style.display = "none";
        }
    }

    function init_upload(event){
        event.preventDefault();
        var xhrAnswer;
        document.getElementById("upload_video_submit").disabled = true;
        document.getElementById("upload_video_close").disabled = true;
        var uploadFormData = new FormData(upload_video_submit);
        console.log(uploadFormData.getAll('courseid'));
        var answer = document.getElementById("answer");
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/upload_content.php", true);
        xhr.upload.onprogress = function(evt){
            if (evt.lengthComputable) {
                var percentComplete = evt.loaded / evt.total;
                var conversion = Math.round(percentComplete * 100);
                console.log("Upload: " + conversion + "% complete");
                answer.innerHTML = '<div class="border-top text-center mt-3 pt-2">Veuillez patienter un moment pendant le téléchargement ...<br><div class="progress" style="height: 20px;"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: ' + conversion + '%;" aria-valuenow="' + conversion + '" aria-valuemin="0" aria-valuemax="100">' + conversion + '%</div></div></div>';
            }
        };
        //TODO ADD STAT AFTER UPLOAD
        xhr.onload = function (evt) {
            document.getElementById("upload_video_submit").disabled = false;
            document.getElementById("upload_video_close").disabled = false;
            if (xhr.status === 200) {
                var objResponse = JSON.parse(xhr.responseText);
                if(objResponse.error === false){
                    xhrAnswer = '<div class="border-top text-center mt-3 pt-2"><div class="alert alert-success" role="alert"> ' + objResponse.msg + '</div></div>';
                }
                else{
                    xhrAnswer = '<div class="border-top text-center mt-3 pt-2"><hr><div class="alert alert-danger" role="alert"> ' + objResponse.msg + '</div></div>';
                }
            } else {
                xhrAnswer = '<div class="border-top text-center mt-3 pt-2"><hr><div class="alert alert-danger" role="alert"> Erreur " + xhr.status + " lors de la tentative d’envoi du fichier.</div></div>';
            }
            answer.innerHTML = xhrAnswer;
        };
        xhr.send(uploadFormData);
        return false;
    }
};
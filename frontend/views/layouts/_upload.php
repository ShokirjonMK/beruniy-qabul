<label for="upload" class="upload_file" id="upload_image">
    <span class="upload_icon">
       <i class="fa-solid fa-cloud-arrow-up"></i>
    </span>
    <div id="innerP"><?= Yii::t("app" , "a48") ?></div>
</label>


<?php
$js = <<<JS
fileInput2 = document.querySelector(".click_file");
fileText2 = document.querySelector("#innerP");

fileInput2.onchange = ({target}) => {
    let file2 = target.files[0];
    if(file2){
        let fileName2 = file2.name;
        if(fileName2.length >= 12){
            let splitName2 = fileName2.split('.');
            fileName2 = splitName2[0].substring(0, 13) + "... ." + splitName2[1];
        }
        fileText2.innerHTML = fileName2;
    }
}
JS;
$this->registerJs($js);
?>

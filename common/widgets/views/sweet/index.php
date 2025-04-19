
<?php



?>


<?php if ($type == 'error') : ?>

    <?php foreach ($message as  $key => $title) :  ?>

        <div id="liveToast<?= $i ?>" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-item error">
                <div class="toast-body d-flex align-items-center">
                    <div>
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <p><?= $title ?></p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                const toastLiveExample<?= $i ?> = document.getElementById('liveToast<?= $i ?>')
                const toastBootstrap<?= $i ?> = bootstrap.Toast.getOrCreateInstance(toastLiveExample<?= $i ?>)
                $(document).ready(function () {
                    toastBootstrap<?= $i ?>.show()
                });
            })
        </script>

    <?php  endforeach; ?>

<?php endif; ?>

<?php if ($type == 'success') : ?>

    <div id="liveToastSuc" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-item success">
            <div class="toast-body d-flex align-items-center">
                <div>
                    <i class="fa-regular fa-circle-check"></i>
                </div>
                <div>
                    <p>Ma'lumot muvaffaqiyatli saqlandi</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            const toastLiveExampleSuc = document.getElementById('liveToastSuc')
            const toastBootstrapSuc = bootstrap.Toast.getOrCreateInstance(toastLiveExampleSuc)
            $(document).ready(function () {
                toastBootstrapSuc.show()
            });
        })
    </script>






<?php endif; ?>








<?php
include_once('./server/Server.php');
$errors = Server::$errors;
?>
<script>
    $(document).ready(function() {
        $('.toast').toast('show');
    });
</script>
<?php if (count($errors) > 0) : ?>
    <div style="position: absolute; top: 0; right: 0; padding: 1rem;">

        <?php foreach ($errors as $error) : ?>

            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
                <div class="toast-header error">

                    <strong class="mr-auto">Error</strong>
                    <small class="text-muted"></small>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    <?php echo $error ?>
                </div>
            </div>
        <?php endforeach ?>

    </div>
<?php endif ?>
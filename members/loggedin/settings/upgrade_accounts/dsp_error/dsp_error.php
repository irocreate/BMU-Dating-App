<div class="box-border">
    <div class="box-pedding">
        <?php
        if (isset($_REQUEST['RESPMSG'])) {
            echo "<br>" . $_REQUEST['RESPMSG'];
        }
        echo "<br>" . language_code('DSP_YOUR_TANSACTION_NOT_COMPLETED_SUCCESSFULLY');
        ?>
    </div>
</div>

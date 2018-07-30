<?php

class Wpdating_Option_Powered_By_Settings
{
    public function __construct()
    {
        $this->powered_by_settings();
    }

    public function powered_by_settings()
    {

        if (isset($_REQUEST['action']) && 'enablepoweredby' == $_REQUEST['action']) {
            check_admin_referer('wpdating-enable-poweredby', '_wpnonce_enable-poweredby');
            $message = $this->powered_by();

            echo '<div id="message" class="updated notice is-dismissible"><p>' . $message . '</p></div>';
        }
        $Wpdating_Option_Config = Wpdating_Option_Config::getInstance();
        $powered_by             = $Wpdating_Option_Config->getValue('powered_by');


        ?>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div class="postbox-container">
                    <div id="advanced-sortables" class="meta-box-sortables ui-sortable">
                        <div class="postbox">

                            <h2 class="hndle">
                                <span><?php echo __('Powered By Settings'); ?></span>
                            </h2>
                            <div class="inside">
                                <form method="post" name="enablepoweredby" id="enablepoweredby">
                                    <?php wp_nonce_field('wpdating-enable-poweredby', '_wpnonce_enable-poweredby'); ?>

                                    <input name="action" type="hidden" value="enablepoweredby">
                                    <p>
                                        <input type="checkbox" name="enable_poweredby_checkbox"
                                               value="1"
                                            <?php checked('1', $powered_by); ?> > <?php echo __('enable Powered by',
                                            'wpdating'); ?>
                                        <br>
                                    </p>

                                    <p>
                                        <input type="submit" name="enablepoweredby" id="enablepoweredbysub"
                                               class="button-secondary edd_add_repeatable"
                                               value="<?php echo __('Submit', 'wpdating'); ?>">
                                    </p>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
        <?php
    }

    /**
     * Add debug to options
     */
    public function powered_by()
    {
        $powered_by = new stdClass;
        if (isset($_POST['enable_poweredby_checkbox'])) {
            $powered_by->enable_powered_by = '1';
        } else {
            $powered_by->enable_powered_by = '';
        }

        $Wpdating_Option_Config = Wpdating_Option_Config::getInstance();

        $Wpdating_Option_Config->setValue('powered_by', $powered_by->enable_powered_by);

        $Wpdating_Option_Config->saveConfig();

        $messages = __('Powered By saved.', 'wpdating');

        return $messages;
    }
}

new Wpdating_Option_Powered_By_Settings();
?>

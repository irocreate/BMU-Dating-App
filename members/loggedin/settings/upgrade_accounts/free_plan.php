<?php

class dsp_membership_plan
{
    private $membership_id = "";
    private $imagepath = '';
    private $image = '';
    private $name = '';
    private $desc = '';
    private $success = '';

    public function __construct($free_plan_values = array())
    {
        $this->membership_id = $free_plan_values['membership_id'];
        $this->imagepath = $free_plan_values['imagepath'];
        $this->image = $free_plan_values['image'];
        $this->name = $free_plan_values['name'];
        $this->desc = $free_plan_values['desc'];
        $this->handle_form();
        $this->html();
    }

    public function html()
    {
        ?>
        <div class="dspdp-col-sm-4 dsp-sm-4 dspdp-text-center">
            <div class="box-border dsp-upgrade-container">
                <div class="box-pedding">
                    <div class="setting-page__disable dsp-member-upgrade-page">
                        <ul class="dspdp-row dspdp-xs-text-center">
                            <li>
                                <?php if (isset($this->success) && !empty($this->success)) {
                                    ?>
                                    <div class=" dspdp-alert dspdp-alert-success" style="font-size:0.7em">
                                        <h5><?php echo $this->success;?></h5>
                                    </div>
                                    <?php
                                } ?>
                                <div class="dspdp-spacer "><img class="dspdp-img-responsive dspdp-block-center"
                                                                src='<?php echo $this->imagepath ?>/uploads/dsp_media/dsp_images/<?php echo $this->image; ?>'
                                                                title="<?php echo $this->name ?>"
                                                                alt="<?php echo $this->name ?>"/>
                                </div>
                            </li>

                            <li>
                                <div class="dspdp-spacer dspdp-upgrade-desc"><?php echo $this->desc; ?></div>
                            </li>

                            <li>
                                <form method="post">
                                    <input name="action" type="hidden" value="free_plan">
                                    <input type="hidden" name="membership_id" id="membership_id"
                                           value="<?php echo $this->membership_id ?>">
                                    <p>
                                        <input type="submit" value="<?php echo language_code('DSP_APPLY'); ?>"
                                               class="dsp_span_pointer dspdp-btn dspdp-btn-default"/>
                                    </p>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function handle_form()
    {
        if (!isset($_REQUEST['action'])) {
            return;
        }

        if ('free_plan' != $_REQUEST['action']) {
            return;
        }

        if ($this->membership_id != $_REQUEST['membership_id']) {
            return;
        }

        $this->enable_free_membership_plan();
    }

    public function enable_free_membership_plan()
    {
        global $wpdb;
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
        $dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;

        $check_already_user_exists = $wpdb->get_var("SELECT count(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
        $membership_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table where membership_id='$this->membership_id'");

        $pay_plan_id = $this->membership_id;
        $pay_plan_amount = 0;
        $pay_plan_days = $membership_plan->no_of_days;
        $pay_plan_name = $membership_plan->name;
        $payment_date = date("Y-m-d");
        $start_date = date("Y-m-d");

        $expiration_date = date_create(date("Y-m-d"));
        date_add($expiration_date, date_interval_create_from_date_string("'" . $pay_plan_days . " days'"));
        $expiration_date = date_format($expiration_date, 'Y-m-d');

        $payment_status = 1;

        if ($check_already_user_exists > 0) {

            $wpdb->update(
                $dsp_payments_table,
                array(
                    'pay_plan_id' => $this->membership_id,
                    'pay_plan_amount' => $pay_plan_amount,
                    'pay_plan_days' => $pay_plan_days,
                    'pay_plan_name' => $pay_plan_name,
                    'payment_date' => $payment_date,
                    'start_date' => $start_date,
                    'expiration_date' => $expiration_date,
                    'payment_status' => $payment_status
                ),
                array('pay_user_id' => $user_id)
            );
        } else {
            $wpdb->insert(
                $dsp_payments_table,
                array(
                    'pay_user_id' => $user_id,
                    'pay_plan_id' => $this->membership_id,
                    'pay_plan_amount' => $pay_plan_amount,
                    'pay_plan_days' => $pay_plan_days,
                    'pay_plan_name' => $pay_plan_name,
                    'payment_date' => $payment_date,
                    'start_date' => $start_date,
                    'expiration_date' => $expiration_date,
                    'payment_status' => $payment_status
                )
            );
        }
        $this->success = $pay_plan_name . ' ' . language_code('DSP_PLAN') . ' ' . language_code('DSP_IS_ACTIVE') ;
    }
}


<?php
global $wpdb;
$dsp_report_user = $wpdb->prefix . 'dsp_reported_user';
$dsp_user_profiles = $wpdb->prefix . 'dsp_user_profiles';
$report_id = isset($_POST['report_id']) ? $_POST['report_id'] : '';
$reported_to = isset($_POST['reported_to']) ? $_POST['reported_to'] : '';

if (isset($_POST['ignore_report'])) {
    $ignore = $wpdb->query(
        $wpdb->prepare("DELETE FROM $dsp_report_user WHERE  id= %d ", $report_id)
    );
}

if (isset($_POST['delete_profile'])) {
    $delete_report = $wpdb->query("UPDATE $dsp_user_profiles SET status_id=3 WHERE user_id= " . $reported_to);
    $ignore = $wpdb->query(
        $wpdb->prepare("DELETE FROM $dsp_report_user WHERE  id= %d ", $report_id)
    );
}
?>
    <table class="wp-list-table widefat fixed striped users">
        <thead>
        <tr>
            <th scope="col" id="Reported_by" class="manage-column">
                <span><?php echo language_code('DSP_REPORTED_BY'); ?></span>
            </th>

            <th scope="col" id="Reported_to" class="manage-column">
                <span><?php echo language_code('DSP_REPORTED_TO'); ?></span>

            </th>
            <th scope="col" id="reason" class="manage-column">
                <span><?php echo language_code('DSP_REASONS'); ?></span>
            </th>
            <th scope="col" id="action" class="manage-column">
                <span><?php echo language_code('DSP_Action'); ?></span>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $report_user = $wpdb->get_results(" SELECT * FROM $dsp_report_user");

        foreach ($report_user as $report) { ?>
            <tr>
                <td>
                    <a href="<?php echo site_url() . '/members/' . dsp_get_user($report->reported_by) ?>">
                        <?php echo dsp_get_user($report->reported_by); ?></a>

                </td>

                <td>
                    <a href="<?php echo site_url() . '/members/' . dsp_get_user($report->reported_to) ?>">
                        <?php echo dsp_get_user($report->reported_to); ?></a>
                </td>

                <td>
                    <?php echo wp_unslash($report->reason); ?>
                </td>

                <td>
                    <form method="post">
                        <input type="hidden" name="report_id" value="<?php echo $report->id; ?>">
                        <input type="submit" onclick="return confirm('Are you sure you want to Ignore?')"
                               id="ignore_report" name="ignore_report"
                               class="comment_button ignore_comment_button action"
                               value="<?php echo language_code('DSP_IGNORE'); ?>">

                        <input type="hidden" name="reported_to" value="<?php echo $report->reported_to; ?>">
                        <input type="submit"
                               onclick="return confirm('Are you sure you want to Delete reported profile?')"
                               id="delete_profile" name="delete_profile"
                               class="comment_button ignore_comment_button action"
                               value="Delete Profile">
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php
/**
 * Get the user name from the user id
 * @param $user_id
 * @return mixed
 */
function dsp_get_user($user_id)
{
    global $wpdb;
    $get_user = $wpdb->get_var("SELECT user_login FROM {$wpdb->prefix}users where id = {$user_id}");
    return $get_user;
}

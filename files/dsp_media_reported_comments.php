<style>
    .delete_comment_button:hover {
        color: #fff;
        background-color: #c9302c;
        border-color: #ac2925;
    }

    .delete_comment_button {
        color: #fff;
        background-color: #d9534f;
        border-color: #d43f3a;
    }

    .ignore_comment_button {
        color: #fff;
        background-color: #337ab7;
        border-color: #2e6da4;
    }

    .ignore_comment_button:hover {
        color: #fff;
        background-color: #286090;
        border-color: #204d74;
    }

    .comment_button {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
</style>

<?php

global $wpdb;
$table_name = $wpdb->prefix.'dsp_reported_comments';
if (isset($_POST['ignore_comment'])) {

    $wpdb->query(
        $wpdb->prepare(
            "
            DELETE FROM $table_name
		    WHERE comments_id = %d
		",
            $_POST['comment_id']
        )
    );


} else if (isset($_POST['delete_comment'])) {
    $table_name_comment = $wpdb->prefix.'dsp_user_comments';
    $wpdb->query(
        $wpdb->prepare(
            "
            DELETE FROM $table_name_comment
		    WHERE comments_id = %d
		",
            $_POST['comment_id']
        )
    );

    $wpdb->query(
        $wpdb->prepare(
            "
            DELETE FROM $table_name
		    WHERE comments_id = %d
		",
            $_POST['comment_id']
        )
    );



}
$reports = array();

$limit = 3;

if (isset($_GET['offset']))
    $unit_offset = $_GET['offset'];
else
    $unit_offset = 0;

if ($unit_offset < 2)
    $offset = 0;
else
    $offset = ($unit_offset - 1) * $limit;


$total_comments = $wpdb->get_results(
    "
        SELECT DISTINCT comments_id
        FROM $table_name
    "
);

$total_comments = count($total_comments);

$comments = $wpdb->get_results(
    "
        SELECT DISTINCT  comments_id, member_id
        FROM $table_name
        ORDER BY comments_id
    "
    . "LIMIT " . $limit
    . " OFFSET " . $offset
);

foreach ($comments as $key => $value) {

    $reports[$key]['comments_id'] = $value->comments_id;
    $table_name_comment = $wpdb->prefix.'dsp_user_comments';
    $comments_name = $wpdb->get_row(
        "
        SELECT comments
        FROM $table_name_comment
        WHERE comments_id = $value->comments_id
        "
    );

    $reports[$key]['comment_name'] = $comments_name->comments;
    $reports[$key]['member_name'] = get_username($value->member_id);

    $reported_details = $wpdb->get_results(
        "
        SELECT *
        FROM $table_name
        WHERE comments_id = $value->comments_id
        "
    );

    foreach ($reported_details as $reported_user_name_key => $reported_user_name_value) {

        $report_user_name = $wpdb->get_row(
            "
        SELECT user_nicename
        FROM wp_users
        WHERE ID = $reported_user_name_value->reported_user_id
        "
        );

        $reported_details[$reported_user_name_key]->user_nicename = $report_user_name->user_nicename;
        $reported_details[$reported_user_name_key]->user_id = $reported_user_name_value->reported_user_id;
        $reported_details[$reported_user_name_key]->reason = $reported_user_name_value->reason;

    }
    $reports[$key]['report'] = $reported_details;
}

?>
<table class="wp-list-table widefat fixed striped users">
    <thead>
    <tr>
        <th scope="col" id="comment" class="manage-column">
            <span><?php echo language_code('DSP_COMMENT'); ?></span>
            </a>
        </th>
        <th scope="col" id="Reported_by" class="manage-column">
            <span><?php echo language_code('DSP_REPORTED_BY'); ?></span>
        </th>
        <th scope="col" id="reason" class="manage-column">
            <span><?php echo language_code('DSP_REASONS'); ?></span>
            </a>
        </th>
        <th scope="col" id="action" class="manage-column">
            <span><?php echo language_code('DSP_Action'); ?></span>
        </th>
    </tr>
    </thead>

    <tbody>

    <?php foreach ($reports as $report => $value) { ?>
        <tr>
            <td>
                <a href="<?php echo $root_link.'/members/'.$value['member_name'];?>" ><?php echo $value['comment_name']; ?></a>
            </td>

            <td>
                <?php
                foreach ($value['report'] as $k => $v) {
                    echo $v->user_nicename . '<br>';
                }
                ?>
            </td>

            <td>
                <?php
                foreach ($value['report'] as $k => $v) {
                    echo $v->reason . '<br>';
                }
                ?>
            </td>

            <td>
                <form method="post">
                    <input type="hidden" name="comment_id" value="<?php echo $value['comments_id']; ?> ">
                    <input type="submit" onclick="return confirm('Are you sure you want to Ignore?');" id="ignore_comment" name="ignore_comment"
                           class="comment_button ignore_comment_button action"
                           value="<?php echo language_code('DSP_IGNORE'); ?>">

                    <input type="submit" onclick="return confirm('Are you sure you want to Delete?');" id="delete_comment" name="delete_comment" class="comment_button delete_comment_button action"
                           value="<?php echo language_code('DSP_DELETE'); ?>">
                </form>
            </td>
        </tr>
    <?php
    }
    ?>
    </tbody>
    </tr>
    </tbody>
</table>
<br><br>
<?php
if ($total_comments > $limit) {
    dsp_create_pagination_links($total_comments, $limit, $unit_offset);
}

function dsp_create_pagination_links($total_stripe_payments, $limit, $unit_offset)
{
    $current_url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

    echo '<style> .pagination-links > li { display: inline-block; margin: 7px; } </style>';
    echo '<br><ul class="pagination-links">';

    $quotient = $total_stripe_payments / $limit;
    $no_of_loops = ceil($quotient);

    if ($no_of_loops < 11) {
        for ($i = 0; $i < $no_of_loops; $i++) {
            if ($unit_offset == $i + 1)
                echo '<li>' . ($i + 1) . '</li>';
            else
                echo '<li><a href="' . add_query_arg('offset', $i + 1, $current_url) . '">' . ($i + 1) . '</a></li>';
        }
    } else    // too many links to display
    {
        echo '<li><a href="' . add_query_arg('offset', 1, $current_url) . '">' . 1 . '</a></li>';

        echo '<li>.....</li>';
        if ($unit_offset > 4 && $unit_offset < ($no_of_loops - 4)) {
            echo '<li><a href="' . add_query_arg('offset', $unit_offset - 1, $current_url) . '">' . $unit_offset - 1 . '</a></li>';
            echo '<li>' . $unit_offset . '</li>';
            echo '<li><a href="' . add_query_arg('offset', $unit_offset + 1, $current_url) . '">' . $unit_offset + 1 . '</a></li>';
        } else {
            if ($unit_offset > 1) {
                echo '<li><a href="' . add_query_arg('offset', $unit_offset - 1, $current_url) . '"> Previous </a></li>';
            }
            if ($unit_offset < $no_of_loops)   // for next
            {
                echo '<li><a href="' . add_query_arg('offset', $unit_offset + 1, $current_url) . '"> Next </a></li>';
            }
        }
        echo '<li>.....</li>';

        echo '<li><a href="' . add_query_arg('offset', $no_of_loops, $current_url) . '">' . $no_of_loops . '</a></li>';
    }

    echo '</ul><br>';
}

?>
<?php
/*
Plugin Name: Invoice Shortcode WP User Frontend Pro
Plugin URI : https://github.com/masoudv/invoice-shortcode-wp-user-frontend
Description: This simple plugin, give access you for display WPUF Pro Invoice in every where
Author : Masoud Vatankhah
Version: 0.0.1
Author URI: https://github.com/masoudv/
*/
// Invoice page

function wpuf_display_invoice()
{
    echo '<div class="table-responsive" id="my-invoices">
    <table class="table table-hover table-sm" cellpadding="0" cellspacing="0">
        <thead>
            <tr class="items-list-header">';
    echo '<th>'.__('Transaction ID', 'wpuf-pro').'</th>';
    //  echo '<th>'.__('Invoice Date', 'wpuf-pro').'</th>';
    echo '<th>'.__('Download Invoice', 'wpuf-pro').'</th>';
    echo '</tr>
        </thead>
        <tbody>
            <tr>';
    global $wpdb;
    $user_id = get_current_user_id();
    $sql = $wpdb->prepare('SELECT transaction_id
                FROM '.$wpdb->prefix.'wpuf_transaction
                WHERE user_id = %s', $user_id);

    $results = $wpdb->get_results($sql);
    if (!empty($results)) {
        foreach ($results as $result) {
            $t_id = (array) $result;
            echo
                    '<td>
                        <h6>';
            echo $t_id['transaction_id'];
            echo '</h6>
                    </td>
                    <td>';
            $var = get_user_meta($user_id, '_invoice_link'.$t_id['transaction_id'], true);
            echo '<a href="';
            echo $var;
            echo'" style="font-size: 18px;">';
            _e('<i class="fas fa-file-download"></i> Download', 'wpuf-pro');
            echo '</a>

                    </td>
                    </tr>';
        }
    }
    echo '</tbody></table></div>';
}
add_shortcode('myinvoice', 'wpuf_display_invoice');

function add_invoice_page()
{
    // Create post object
    $my_post = [
      'post_title' => wp_strip_all_tags('My Invoice'),
      'post_content' => '[myinvoice]',
      'post_status' => 'publish',
      'post_author' => 1,
      'post_type' => 'page',
    ];

    // Insert the post into the database
    wp_insert_post($my_post);
}

register_activation_hook(__FILE__, 'add_invoice_page');

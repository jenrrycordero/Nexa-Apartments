<?php

function theme_get_page_content()
{

    $response = array(
        'status' => 1,
        'pages' => 1,
        'html' => ''
    );

    if (!isset($_REQUEST['post'])) {
        $response['status'] = -1;
        echo json_encode($response);
    }

    $postId = $_REQUEST['post'];

    if ($postId < 0 && isset($_REQUEST['slug'])) {
        $slug = $_REQUEST['slug'];
        $post = get_page_by_path($slug);
    }
    else
        $post = get_post($postId);

    $layouts = get_field('layouts', $post->ID);

    $html = renderPageContentByPostID($layouts, $post);

    $response['html'] = $html;
    $response['pages'] = count($layouts);

    echo json_encode($response);
    wp_die();
}
add_action('wp_ajax_get_page_content', 'theme_get_page_content'); // logged in users
add_action('wp_ajax_nopriv_get_page_content', 'theme_get_page_content'); // for non-logged in users


// filter the Gravity Forms button type (****INPUT REQUIRED)
add_filter( 'gform_submit_button', 'form_submit_button', 10, 2 );
function form_submit_button( $button, $form ) {
    return '<button id="gform_submit_button_' . $form['id'] . '" class="form-submit"><div class="primary-btn btn-arrow">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
                </div>
            </button>';
}
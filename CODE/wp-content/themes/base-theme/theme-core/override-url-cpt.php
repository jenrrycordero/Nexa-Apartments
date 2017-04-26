<?php

function f_get_post_types_to_override()
{
	return array(
			'cpt-post-type'   => TRUE,
		);
}

add_filter( 'post_type_link', 'f_remove_cpt_slug', 10, 3 );
/**
 * Function to remove the slug of the CPT.
 *
 * @param string $post_link default link to the post.
 * @param object $post post object.
 * @param string $leavename
 *
 * @return string Return the new link.
 */
function f_remove_cpt_slug( $post_link, $post, $leavename )
{
	$array_post_type_to_override = f_get_post_types_to_override();

	if ( $post->post_status != 'publish' || ! isset( $array_post_type_to_override[ $post->post_type ] ) ) {
		return $post_link;
	}

	$post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );

	return $post_link;
}


add_action( 'pre_get_posts', 'f_parse_request_trick' );
/**
 * Have WordPress match postname to any of our public post types (post, page, race)
 * All of our public post types can have /post-name/ as the slug, so they better be unique across all posts
 * By default, core only accounts for posts and pages where the slug is /post-name/
 */
function f_parse_request_trick( $query )
{
	// Only noop the main query
	if ( ! $query->is_main_query() ) return;

	// Only noop our very specific rewrite rule match
	if ( count( $query->query ) != 2 || ! isset( $query->query['page'] ) ) return;

	$array_post_type_to_override = f_get_post_types_to_override();

	// 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
	if ( ! empty( $query->query['name'] ) )
	{
		$override_post_types = array_merge(
			$array_post_type_to_override,
			array(
				'post',
				'page'
			)
		);

		$query->set( 'post_type', $override_post_types );
	}
}
<?php
get_header();

$postId = get_option('page_on_front');

if (get_field('layouts', $postId)) {
	$layoutIndex = 0;
	while (has_sub_field('layouts', $postId)) :
		$layoutIndex++;
		include theme_get_template_part("layout/" . get_row_layout(), "layout/404");
	endwhile;
}
?>

<?php
get_footer();
<?php

$blockId = get_sub_field("block_id");
$blockClass = get_sub_field("block_classes");

$title        = get_sub_field( 'block_title' );
$description  = get_sub_field( 'block_description' );
$blockImage   = get_sub_field( "imagen_background" );
$imageUrl     = $blockImage['url'];
$blockBgColor = get_sub_field('color_background');

$blockContained = get_sub_field("section_contained");
$blockPadding = get_sub_field("section_padding");

$list = get_sub_field('list');
$useAccordion = get_sub_field('use_an_accordion_behavior');

if ( $blockContained ) $columnsClassWrapper .= "container no-padding ";

if ( !$imageUrl ) $imageOpacity = 0.6;
?>
<style>
	.bg-image-overlay-opacity:before{
		opacity: <?php echo $imageOpacity;?>;"
	}
</style>
<div id="<?php echo $blockId;?>" class="row block relative text-black <?php echo $blockClass;?>">
	<div class="columns-wrapper <?php echo $columnsClassWrapper;?>">
		<div class="col-single col-element" style="background-color: <?php echo $blockBgColorLeft;?>; padding-top: <?php echo $blockPadding;?>px;padding-bottom: <?php echo $blockPadding;?>px;">
			<?php if ( $imageUrl): ?>
				<div class="bg-image bg-image-overlay bg-image-overlay-opacity" style="background-image: url('<?php echo $imageUrl?>');"></div>
			<?php endif; ?>

			<?php if ( $title || $description ) : ?>
			<div class="text-wrapper text-block z-index-1 relative">
				<div class='text-block-title title'><?php echo $title;?></div>
				<div class="text-block-description description"><?php echo $description;?></div>
			</div>
			<?php endif; ?>

			<?php if ( $useAccordion ) :
				wp_enqueue_script('jquery-ui-accordion');
			?>
				<script>
					jQuery(document).ready(function($){


						$(".accordion-wrapper").accordion({
							collapsible: true,
							heightStyle: "content"
						});
					})
				</script>
				<div class="accordion-wrapper">
					<?php
					foreach ( $list as $listElement ) :
						if ( $listElement['new_block'] ) echo '</div><div class="accordion-wrapper">' ?>

						<div class="accordion-title">
							<i class="fa fa-plus show-on-close"></i><i class="fa fa-minus show-on-open"></i>
							<span class="accordion-element-title"> <?php echo $listElement['title']; ?></span>
						</div>
						<div class="list-desc"><?php echo $listElement['descripcion']; ?></div>
					<?php
					endforeach;
					?>
				</div>
			<?php
			else :
			?>
			<div class="list-wrapper">
				<ul class="generic-list no-style">
					<?php
					foreach ($list as $listElement) :
						if ( $listElement['new_block'])  echo '</ul><ul class="generic-list no-style">'; ?>
					<li class="list-element">
						<div class="list-title">
							<i class="fa fa-plus"></i> <?php echo $listElement['title'];?>
						</div>
						<div class="list-desc"><?php echo $listElement['descripcion'];?></div>
					</li>
					<?php
					endforeach;
					?>
				</ul>
			</div>
			<?php
			endif;
			?>
		</div>
	</div>
</div>
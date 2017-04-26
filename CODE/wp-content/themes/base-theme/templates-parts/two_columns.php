<?php
/**
 * Created by PhpStorm.
 * User: aliannieves
 * Date: 9/19/16
 * Time: 6:58 PM
 */


$blockId = get_sub_field("block_id");
$blockClass = get_sub_field("block_classes");

$titleLeft = get_sub_field('block_title_left');
$descriptionLeft = get_sub_field('block_description_left');
$blockImageLeft = get_sub_field("imagen_background_left");
$imageUrlLeft = $blockImageLeft['url'];
$blockBgColorLeft = get_sub_field('color_background_left');

$titleRight = get_sub_field('block_title_right');
$descriptionRight = get_sub_field('block_description_right');
$blockImageRight = get_sub_field("imagen_background_right");
$imageUrlRight = $blockImageRight['url'];
$blockBgColorRight = get_sub_field('color_background_right');

$blockContained = get_sub_field("section_contained");
$blockPadding = get_sub_field("section_padding");

$columnsClassWrapper = "";
if ( $blockContained ) $columnsClassWrapper .= "container no-padding ";

$imageOpacity = 0.6;
?>
<style>
	#<?php echo $blockId;?> .bg-image-overlay-opacity:before{
		opacity: <?php echo $imageOpacity;?>;"
	}
</style>
<div id="<?php echo $blockId;?>" class="row block relative block-two-columns text-white <?php echo $blockClass;?>">
	<div class="columns-wrapper <?php echo $columnsClassWrapper;?>">
		<div class="col-left col-element" style="background-color: <?php echo $blockBgColorLeft;?>; padding-top: <?php echo $blockPadding;?>px;padding-bottom: <?php echo $blockPadding;?>px;">
			<?php if ( $imageUrlLeft): ?>
			<div class="bg-image bg-image-overlay bg-image-overlay-opacity" style="background-image: url('<?php echo $imageUrlLeft?>');"></div>
			<?php endif; ?>

			<div class="text-wrapper text-block z-index-1 relative">
				<div class='text-block-title title'><?php echo $titleLeft;?></div>
				<div class="text-block-description description"><?php echo $descriptionLeft;?></div>
			</div>
		</div><!--

	--><div class="col-right col-element" style="background-color: <?php echo $blockBgColorRight;?>; padding-top: <?php echo $blockPadding;?>px;padding-bottom: <?php echo $blockPadding;?>px;">
			<?php if ( $imageUrlRight): ?>
			<div class="bg-image bg-image-overlay bg-image-overlay-opacity" style="background-image: url('<?php echo $imageUrlRight?>');"></div>
			<?php endif; ?>

			<div class="text-wrapper text-block z-index-1 relative">
				<div class='text-block-title title'><?php echo $titleRight;?></div>
				<div class="text-block-description description"><?php echo $descriptionRight;?></div>
			</div>
		</div>
	</div>
</div>

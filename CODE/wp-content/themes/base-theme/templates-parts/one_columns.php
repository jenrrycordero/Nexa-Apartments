<?php

$blockId = get_sub_field("block_id");
$blockClass = get_sub_field("block_classes");

$title        = get_sub_field('block_title');
$description  = get_sub_field( 'block_description');
$blockImage   = get_sub_field( "imagen_background");
$imageUrl     = $blockImage['url'];
$blockBgColor = get_sub_field('color_background');

$blockContained = get_sub_field("section_contained");
$blockPadding = get_sub_field("section_padding");

if ( $blockContained ) $columnsClassWrapper .= "container no-padding ";

if ( !$imageUrl ) $imageOpacity = 0.7;
?>
<style>
	#<?php echo $blockId;?> .bg-image-overlay-opacity:before{
		opacity: <?php echo $imageOpacity;?>;"
	}
</style>
<div id="<?php echo $blockId;?>" class="row block relative block-one-columns <?php echo $blockClass;?>">
	<div class="columns-wrapper <?php echo $columnsClassWrapper;?>">
		<div class="col-single col-element" style="background-color: <?php echo $blockBgColorLeft;?>; padding-top: <?php echo $blockPadding;?>px;padding-bottom: <?php echo $blockPadding;?>px;">
			<?php if ( $imageUrl): ?>
			<div class="bg-image bg-image-overlay bg-image-overlay-opacity" style="background-image: url('<?php echo $imageUrl?>');"></div>
			<?php endif; ?>

			<div class="text-wrapper text-block z-index-1 relative">
				<div class='text-block-title title'><?php echo $title;?></div>
				<div class="text-block-description description"><?php echo $description;?></div>
			</div>
		</div>
	</div>
</div>

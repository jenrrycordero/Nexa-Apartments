<?php


$blockId = get_sub_field("block_id");
$blockClass = get_sub_field("block_classes");
$titleTag = get_sub_field('title_relevante');
$title = get_sub_field('block_title');
$description = get_sub_field('block_description');
$blockImage = get_sub_field("imagen");
$blockTextAlignClass = get_sub_field('text_alingment');
$imageOpacity = get_sub_field('overlay_opacity');
$blockSize = get_sub_field('block_size');

$imageUrl = $blockImage['url'];

if ( $blockSize ) $blockSizeStyle = "height: $blockSize" . "vh";
?>
<style>
	#<?php echo $blockId;?> .bg-image-overlay-opacity:before{
		opacity: <?php echo $imageOpacity;?>;"
	}
</style>
<div id="<?php echo $blockId;?>" class="row block relative block-full-height-image <?php echo $blockClass;?>" style="<?php echo $blockSizeStyle;?>">

	<div class="bg-image bg-image-overlay bg-image-overlay-opacity" style="background-image: url('<?php echo $imageUrl?>');"></div>

	<div class="text-wrapper text-block z-index-1 relative <?php echo $blockTextAlignClass;?>">
		<?php echo "<$titleTag class='text-block-title title'>$title</$titleTag>";?>
		<div class="text-block-description description"><?php echo $description;?></div>
	</div>
</div>

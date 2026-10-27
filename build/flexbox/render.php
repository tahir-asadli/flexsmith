<?php
/**
 * Flexsmith
 *
 * @package flexsmith
 */



if (!defined('ABSPATH')) {
	exit;
}
$flexsmith = new \Flexsmith\Flexbox_Block($attributes);

?>
<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<div <?php echo get_block_wrapper_attributes($flexsmith->wrapper_attributes()); ?>>
	<?php
	if (!empty($block->inner_blocks)) {
		foreach ($block->inner_blocks as $flexsmith_inner_block) {
			echo $flexsmith_inner_block->render();
		}
	}

	?>
</div>
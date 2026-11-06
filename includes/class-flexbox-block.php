<?php
/**
 * Flexsmith
 *
 * @package flexsmith
 */

namespace Flexsmith;


if (!defined('ABSPATH')) {
	exit;
}


final class Flexbox_Block
{

	private $id = '';
	private $attributes = [];

	private const CLASS_PROPS = [
		'justifyContent' => 'justify',
		'alignItems' => 'align',
		'direction' => 'direction',
		'wrap' => 'wrap',
		'display' => 'display',
		'grow' => 'grow',
		'shrink' => 'shrink',
		'position' => 'position',
	];

	private const STYLE_PROPS = array(
		'width' => 'width',
		'columnGap' => 'column-gap',
		'rowGap' => 'row-gap',
		'top' => 'top',
		'right' => 'right',
		'bottom' => 'bottom',
		'left' => 'left',
	);

	private const BREAKPOINTS = array(
		'' => 'only screen',
		'tablet' => '(min-width: 481px) and (max-width: 782px)',
		'mobile' => '(max-width: 480px)',
	);

	private function attribute_key(string $breakpoint, string $name): string
	{
		return '' === $breakpoint ? $name : $breakpoint . ucfirst($name);
	}

	public function __construct($attributes = [])
	{
		$this->id = wp_unique_id('taufb-');
		$this->attributes = $attributes;
		$this->add_inline_styles();
	}

	public function wrapper_attributes()
	{
		return array(
			'class' => implode(' ', $this->get_classes()),
			'id' => $this->id,
		);
	}

	public function get_classes()
	{
		$classes = array('taufb');

		foreach (self::CLASS_PROPS as $slug => $name) {

			foreach (array_keys(self::BREAKPOINTS) as $breakpoint) {

				$key = $this->attribute_key($breakpoint, $slug);

				if (empty($this->attributes[$key])) {
					continue;
				}

				$classes[] = sanitize_html_class(
					'' === $breakpoint
					? "taufb--{$name}-{$this->attributes[$key]}"
					: "taufb--{$name}-{$breakpoint}-{$this->attributes[$key]}"
				);

			}

		}
		return $classes;

	}

	public function add_inline_styles()
	{
		$positions = ['top', 'right', 'bottom', 'left'];
		$css = '';
		foreach (self::BREAKPOINTS as $breakpoint => $media) {
			$rules = '';

			foreach (self::STYLE_PROPS as $name => $property) {
				$key = $this->attribute_key($breakpoint, $name);
				debug_log('key: ' . $key);
				if (empty($this->attributes[$key])) {
					continue;
				}

				if (in_array($key, $positions)) {
					if ($this->attributes['position'] == '' || $this->attributes['position'] == 'static') {
						continue;
					}
				}

				$declaration = safecss_filter_attr($property . ':' . $this->attributes[$key]);

				if ($declaration) {
					$rules .= $declaration . ';';
				}
			}
			if ($rules) {
				$css .= sprintf('@media %s{#%s{%s}}', $media, $this->id, $rules);
			}
		}
		if (!empty($css)) {
			wp_register_style('flexsmith', false, [], '0.1.1');
			wp_enqueue_style('flexsmith');
			wp_add_inline_style(
				'flexsmith',
				$css
			);
		}

	}
}
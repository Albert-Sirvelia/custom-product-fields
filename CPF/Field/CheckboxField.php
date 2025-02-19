<?php

namespace CPF\Field;

class CheckboxField
{

	public function __construct(string $type, string $slug, string $name, bool $save_individual = true)
	{
		$this->type = $type;
		$this->slug = $slug;
		$this->name = $name;
		$this->default_value = '0';
		$this->save_individual = $save_individual;
		add_action('woocommerce_process_product_meta', [$this, 'save']);
	}


	public static function create(string $type, string $slug, string $name)
	{
		return (new self($type, $slug, $name));
	}

	public function display()
	{
		$input = '';
		$checked = get_post_meta(get_the_ID(), '_' . $this->slug, true);
		if ($checked == '') $checked = $this->default_value;
		if ($this->type == 'checkbox') {
			ob_start(); ?>
			<p class="form-field _<?= $this->type ?>_field ">
				<label for="_<?= $this->slug ?>"><?= $this->name ?></label>
				<input type="checkbox" <?= $checked === '1' ? 'checked' : '' ?> name="_<?= $this->slug ?>" id="_<?= $this->slug ?>" value="1">
			</p>
			<?php $input = ob_get_clean();
		}
		echo $input;
	}

	public function display_complex() {
		$input = '';
		if ($this->type === 'checkbox') {
			ob_start(); ?>
			<p class="form-field _<?= $this->type ?>_field">
				<label for="_<?= $this->slug ?>"><?= $this->name ?></label>
				<input x-cloak type="checkbox" :checked="entries[tab] ? (entries[tab]['<?= $this->slug ?>'] == '1' ? true : false) : ('<?= $this->default_value ?>' == '1' ? true : false)" name="_<?= $this->slug . '[]' ?>" id="_<?= $this->slug ?>" value="1">
			</p>
			<?php $input = ob_get_clean();
		}
		echo $input;
	}

	public function default_value($default_value) {
		$this->default_value = $default_value;

		return $this;
	}

	public function save($product_id)
	{
		if (!$this->save_individual) return;
		
		$key = '_' . $this->slug;
		if (isset($_POST[$key])) { // phpcs:ignore
			update_post_meta($product_id, $key, '1'); // phpcs:ignore
		}
		else {
			update_post_meta($product_id, $key, '0'); // phpcs:ignore
		}
	}
}

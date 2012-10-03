<div class="settings form">
	<h2><?php echo $title_for_layout; ?></h2>

	<?php
		echo $this->Form->create('Setting', array(
			'url' => array(
				'controller' => 'settings',
				'action' => 'prefix',
				$prefix,
			),
		));
	?>
	<fieldset>
	<?php
		$i = 0;
		foreach ($settings as $setting) {
			$key = $setting['Setting']['key'];
			$keyE = explode('.', $key);
			$keyTitle = Inflector::humanize($keyE['1']);

			$label = $keyTitle;
			if ($setting['Setting']['title'] != null) {
				$label = $setting['Setting']['title'];
			}

			$inputType = 'text';
			if ($setting['Setting']['input_type'] != null) {
				$inputType = $setting['Setting']['input_type'];
			}

			echo '<div class="setting">';
				echo $this->Form->input("Setting.$i.id", array('value' => $setting['Setting']['id']));
				echo $this->Form->input("Setting.$i.key", array('type' => 'hidden', 'value' => $key));

				if ($setting['Setting']['input_type'] == 'multiple') {
					$multiple = true;
					if (isset($setting['Params']['multiple'])) {
						$multiple = $setting['Params']['multiple'];
					};
					$selected = json_decode($setting['Setting']['value']);
					$options = json_decode($setting['Params']['options'], true);
					echo $this->Form->input("Setting.$i.values", array(
						'label' => $label,
						'multiple' => $multiple,
						'options' => $options,
						'selected' => $selected,
						));

				} elseif ($setting['Setting']['input_type'] == 'checkbox') {
					if ($setting['Setting']['value'] == 1) {
						echo $this->Form->input("Setting.$i.value", array(
							'label' => $label,
							'type' => $setting['Setting']['input_type'],
							'checked' => 'checked',
							'div' => array(
								'title' => $setting['Setting']['description'],
							),
						));
					} else {
						echo $this->Form->input("Setting.$i.value", array(
							'label' => $label,
							'type' => $setting['Setting']['input_type'],
							'div' => array(
								'title' => $setting['Setting']['description'],
							),
						));
					}
				} else {
					echo $this->Form->input("Setting.$i.value", array(
						'label' => $label,
						'type' => $inputType,
						'value' => $setting['Setting']['value'],
						'rel' => $setting['Setting']['description'],
					));
				}
			echo "</div>";
			$i++;
		}
	?>
	</fieldset>

	<div class="buttons">
	<?php
		echo $this->Form->end(__('Save'));
	?>
	</div>
</div>
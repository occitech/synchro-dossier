<script>
	$(document).ready(function(){
		$('.<?php echo $class ?>').chosen({
			no_results_text : "<?= Configure::read('sd.chosen.no_results_text');?>"
		});
		$(".<?php echo $class ?>-deselect").chosen({
			allow_single_deselect:true
		});
	});
</script>

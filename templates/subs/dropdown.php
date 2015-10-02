<?php
	global $context;
	if(!($context instanceof Arguments)){
		$context = new Arguments($context);
	}
?>
<select name="<?=$context->name?>">
	<?php
		foreach($context->options as $id => $desc){
			echo "<option value=\"{$id}\"";
			if(intval($id) == intval($context->value)){
				echo " selected=\"selected\"";
			}
			echo ">{$desc}</option>";
		}
	?>
</select>
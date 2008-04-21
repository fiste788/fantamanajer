<div>
	<table>
	<?php foreach ($this->mail as $key => $val): ?>
		<tr>
			<td>
				<?php if($val[0] == 0): ?>
					<img src="<?php echo IMGSURL.'ok.png'; ?>" alt="ok" />
				<?php else: ?>
					<img src="<?php echo IMGSURL.'attention.png'; ?>" alt="error" />
				<?php endif; ?>
			</td>
			<td>
				<?php echo $val[1]; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
</div>

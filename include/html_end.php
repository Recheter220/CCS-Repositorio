	</body>
<?php if (isset($_SESSION['modal'])) { ?>
	<script>
		swal({
			title: "<?= $_SESSION['modal']['titulo']; ?>", 
			html:  "<?= $_SESSION['modal']['corpo']; ?>", 
			type:  "<?= $_SESSION['modal']['tipo']; ?>"
		});
	</script>
<?php } ?>
<?php unset($_SESSION['modal']); ?>
</html>
<?php echo $this->fetch("/head.php", []);
echo (json_encode($employees));
?>

<h1>Employees</h1>
<?php foreach ($employees as $e) { ?>
    <p><a href="<?=$e["link"]?>"><?=$e["name"]?></a></p>
<?php } ?>

<?php echo $this->fetch("/foot.php", []);?>
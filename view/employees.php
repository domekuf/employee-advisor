<?php
echo $this->fetch("/head.php");
echo $this->fetch("/partial/nav.php", ["nav"=>$nav]);
?>

<h1>Employees</h1>
<?php foreach ($employees as $e) { ?>
    <p><a href="<?=$e["link"]?>"><?=$e["name"]?></a></p>
<?php } ?>

<?php echo $this->fetch("/foot.php", []);?>

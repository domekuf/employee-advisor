<?php
echo $this->fetch("/head.php", []);
echo $this->fetch("/partial/nav.php", ["nav"=>$nav]);
?>

<h1>Congratulations</h1>
<p>You've successfully reviewed <?=$name?></p>
<p><?=$content?></p>
<?php for ($i = 0; $i < $rate; $i ++) { ?>
    <i class="fa fa-star"></i>
<?php } ?>

<?php echo $this->fetch("/foot.php", []);?>

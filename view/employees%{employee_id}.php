<?php echo $this->fetch("/head.php", []);?>
<h1><?= $name ?></h1>
<?php echo $this->fetch("/partial/review-list.php", ["reviews" => $reviews]);?>
<?php echo $this->fetch("/foot.php", []);?>

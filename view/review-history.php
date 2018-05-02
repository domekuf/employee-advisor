<?php
echo $this->fetch("/head.php");
echo $this->fetch("/partial/nav.php", ["nav"=>$nav]);
?>

<h1>Reviews</h1>
<?php echo $this->fetch("/partial/review-list.php", ["reviews" => $reviews]);?>
<?php echo $this->fetch("/foot.php", []);?>

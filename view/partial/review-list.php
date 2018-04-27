<?php 
foreach ($reviews as $r) {
?>
    <p>
<?php
    for ($i = 0; $i < $r["rate"]; $i ++) {
?>
    <i class="fa fa-star"></i>
<?php 
    }
?>
<?=$r["content"]?>
    </p>
<?php
}
?>


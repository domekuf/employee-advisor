<?php
echo $this->fetch("/head.php");
echo $this->fetch("/partial/nav.php", ["nav"=>$nav]);
?>
<link rel="stylesheet" href="<?=RT?>css/review.css">
<h1>Review an employee</h1>
<form method="POST" action="submit">
    <input type="hidden" name="user-id" value="<?= $user_id ?>">
    <div class="form-group">
        <label for="employee-id">Choose the employee to review:</label>
        <select id="employee-id" name="employee-id" class="form-control">';
<?php foreach ($employees as $e) { ?>
                <option value="<?=$e["id"]?>"><?=$e["name"]?></option>';
<?php } ?>
        </select>
    </div>
    <div class="form-group">
        <label for="review-text">Write your comment here:</label>
        <textarea name="review-text" class="form-control" id="review-text" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label>Rate:</label>
        <input id="review-rate" type="hidden" name="review-rate" value="0"/>
        <div id="star-rate">
            <i role="button" class="far fa-star"></i>
            <i role="button" class="far fa-star"></i>
            <i role="button" class="far fa-star"></i>
            <i role="button" class="far fa-star"></i>
            <i role="button" class="far fa-star"></i>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<script defer src="<?=RT?>js/review.js"></script>

<?php echo $this->fetch("/foot.php", []);?>

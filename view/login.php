<?php echo $this->fetch("/head.php", ["hide_nav" => true]);?>
<h1>Please login</h1>
<form method="POST" action="review">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="email" class="form-control" id="username" name="username" placeholder="user@company.com">
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>
<?php echo $this->fetch("/foot.php", ["flash" => $flash]);


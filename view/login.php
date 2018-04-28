<?php echo $this->fetch("/head.php", []);?>

<form method="POST" action="review">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="email" class="form-control" id="username" name="username" placeholder="user@company.com">
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<?php echo $this->fetch("/foot.php", []);
if (isset($flash)) {
?>
        <script defer>
            $("#flash-title").text('<?=$flash["title"]?>');
            $("#flash-content").text('<?=$flash["content"]?>');
            $("#flash-modal").modal();
        </script>
<?php
}
?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav mr-auto">
<?php
foreach ($nav as $n) {
?>
            <li class="nav-item active">
            <a class="nav-link" href="<?=$n["link"]?>"><?=$n["label"]?></a>
            </li>
<?php
}
?>
        </ul>
    </div>
</nav>

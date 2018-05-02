<table class="table">
    <thead>
        <tr>
<?php
if (isset($reviews[0]["name"])) {
?>
            <th scope="col">Name</th>
<?php
}
?>
            <th scope="col">Rate</th>
            <th scope="col">Content</th>
        </tr>
    </thead>
    <tbody>
<?php 
foreach ($reviews as $r) {
?>
    <tr>
<?php
        if (isset($r["name"])) {
?>
        <td>
            <?=$r["name"]?>
        </td>
<?php
        }
?>
        <td>
<?php
        for ($i = 0; $i < $r["rate"]; $i ++) {
?>
            <i class="fa fa-star"></i>
<?php 
        }
?>
        </td>
        <td>
            <?=$r["content"]?>
        </td>
    </tr>
<?php
}
?>
    </tbody>
</table>

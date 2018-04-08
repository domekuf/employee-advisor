<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "/api.php/employees");
$data = curl_exec($ch);
curl_close();
echo $data;
?>

<h1>Review an employee</h1>
<p>Write something here!</p>
<form>
    <div class="callback" api="/employees">
        <div class="template" hidden>
            <option>[name]</option>
        </div>
        <select class="form-control target" id="employees">
        </select>
    </div>
</form>

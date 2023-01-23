<?php
echo $_POST['max'];

for($n="A";;$n++){
    if (isset($_POST[strval($n) . '1'])) {
        echo $_POST[strval($n) . '1'];
    } else
        break;
}

?>
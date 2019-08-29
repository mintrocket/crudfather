<?php
if ($form['enum']){
    $dataEnum = $form['enum'];
    $result = [];
    foreach (explode(';', $value) as $i => $v) {

            $result[] = $dataEnum[$v];
    }
    $result = implode(', ', $result);
} else {
    $result = str_replace(";", ", ", $value);
}
?>
{!! $result !!}
<?php

$title = $this->pageName . ' ' . $error['code'];
$msg = $title . '<br/>';
$msg .= (isset($error['message']) && !empty($error['message'])) ? $error['message'] : Yii::t('error', $error['code']);
$msg .= '<br/><br/>Type: ' . $error['type'] . '<br/>';
$msg .= 'File: ' . $error['file'] . '<br/>';
$msg .= 'Line: ' . $error['line'] . '<br/>';
Yii::app()->tpl->alert('warning', $msg, false);
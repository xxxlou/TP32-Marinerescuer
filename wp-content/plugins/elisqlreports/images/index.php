<?php
//This file prevents directory indexing and displays my default plugin logo
header("Content-type: image/gif");
$img_src = 'ELISQLREPORTS-16x16.gif';
if (!(file_exists($img_src) && $img_bin = @file_get_contents($img_src)))
	$img_bin = base64_decode('R0lGODlhEAAQAIABAAAAAP///yH5BAEAAAEALAAAAAAQABAAAAIshB0Qm+eo2HuJNWdrjlFm3S2hKB7kViKaxZmr98YgSo/jzH6tiU0974MADwUAOw==');
die($img_bin);
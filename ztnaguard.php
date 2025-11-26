<?php
if (strpos($_SERVER['REMOTE_ADDR'], '10.0.0.') !== 0) {
    http_response_code(403);
    exit("Access Denied: ZTNA Required");
}
?>

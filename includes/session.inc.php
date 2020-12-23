<?php
if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
    if(session_status() == PHP_SESSION_NONE) {
        session_start(array(
            'cache_limiter' => 'private',
            'read_and_close' => false,
        ));
    }
} else if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
} else {
    if(session_id() == '') {
        session_start();
    }
}
?>
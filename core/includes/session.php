<?php
checkAccessToken();

// set session
function setSession($key, $value)
{
    if (!empty(session_id())) {
        $_SESSION[$key] = $value;
        return true;
    }

    return false;
}

// get session
function getSession($key = '')
{
    if (empty($key)) {
        return $_SESSION;
    }

    if (isset($_SESSION[$key])) {
        return $_SESSION[$key];
    }

    return false;
}

// Xoá session
function removeSession($key = '')
{
    if (empty($key)) {
        session_destroy();
        return true;
    }

    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
        return true;
    }

    return false;
}


// set session flash
function setSessionFlash($key, $value)
{
    $key = $key . 'Flash';

    $rel = setSession($key, $value);

    return $rel;
}

// get session flash
function getSessionFlash($key)
{
    $key = $key . 'Flash';

    $rel = getSession($key);

    removeSession($key);

    return $rel;
}
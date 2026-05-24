<?php

function csrf_token()
{
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }

    return $_SESSION["csrf_token"];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' .
        htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') .
        '">';
}

function csrf_is_valid()
{
    $token = $_POST["csrf_token"] ?? "";

    return isset($_SESSION["csrf_token"]) && hash_equals($_SESSION["csrf_token"], $token);
}

function rate_limit_key($name)
{
    return "rate_limit_" . preg_replace("/[^a-zA-Z0-9_]/", "_", $name);
}

function rate_limit_remaining_seconds($name, $maxAttempts, $windowSeconds)
{
    $key = rate_limit_key($name);
    $now = time();

    if (
        empty($_SESSION[$key]) ||
        !isset($_SESSION[$key]["started_at"], $_SESSION[$key]["attempts"]) ||
        ($now - $_SESSION[$key]["started_at"]) >= $windowSeconds
    ) {
        $_SESSION[$key] = [
            "attempts" => 0,
            "started_at" => $now,
        ];
    }

    if ($_SESSION[$key]["attempts"] < $maxAttempts) {
        return 0;
    }

    return $windowSeconds - ($now - $_SESSION[$key]["started_at"]);
}

function rate_limit_hit($name)
{
    $key = rate_limit_key($name);

    if (empty($_SESSION[$key])) {
        $_SESSION[$key] = [
            "attempts" => 0,
            "started_at" => time(),
        ];
    }

    $_SESSION[$key]["attempts"]++;
}

function rate_limit_reset($name)
{
    unset($_SESSION[rate_limit_key($name)]);
}

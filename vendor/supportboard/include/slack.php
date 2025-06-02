<?php

/*
 * ==========================================================
 * SLACK.PHP
 * ==========================================================
 *
 * Slack response listener. This file receive the Slack messages of the agents forwarded by board.support. This file require the Slack App.
 *
 */

if (file_exists('../apps/slack/functions.php')) {
    require_once('functions.php');
    require_once('../apps/slack/functions.php');
    $response = json_decode(file_get_contents('php://input'), true);
    sb_slack_listener($response);
}

?>
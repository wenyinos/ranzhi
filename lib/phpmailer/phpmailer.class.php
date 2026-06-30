<?php
/**
 * PHPMailer compatibility bridge.
 * Loads PHPMailer from Composer vendor and aliases to global namespace.
 */
require_once dirname(dirname(dirname(__FILE__))) . '/vendor/autoload.php';

class_alias('PHPMailer\\PHPMailer\\PHPMailer', 'PHPMailer');
class_alias('PHPMailer\\PHPMailer\\SMTP', 'SMTP');
class_alias('PHPMailer\\PHPMailer\\Exception', 'phpmailerException');

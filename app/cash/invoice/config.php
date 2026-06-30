<?php
$config->invoice->require = new stdclass();
$config->invoice->require->create = 'code, type, amount';
$config->invoice->require->edit   = 'code, type, amount';

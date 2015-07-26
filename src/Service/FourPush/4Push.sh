#!/usr/local/bin/php
<?php

require_once 'CoreAutoloader.php';
\CoreAutoloader::init();

global $argv;
\Service\FourPush\FourPush::notify($argv[1], $argv[2]);

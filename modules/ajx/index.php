<?php

require "../../core/config.php";
require "../../core/init.php";

Session::put('error', 'Directory access prohibited.');
header('Location: ../../');
exit;

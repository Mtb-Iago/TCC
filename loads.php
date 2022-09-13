<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once __DIR__.'/app/config/config.php';

require_once __DIR__.'/app/interfaces/user.interface.php';
require_once __DIR__.'/app/interfaces/posts.interface.php';
require_once __DIR__.'/app/interfaces/category.interface.php';

require_once __DIR__.'/app/model/user.model.php';
require_once __DIR__.'/app/controller/user.controller.php';

require_once __DIR__.'/app/model/posts.model.php';
require_once __DIR__.'/app/controller/posts.controller.php';

require_once __DIR__.'/app/model/category.model.php';
require_once __DIR__.'/app/controller/category.controller.php';
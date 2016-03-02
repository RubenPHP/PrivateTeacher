<?php
new yii\web\Application(require(dirname(dirname(__DIR__)) . '/config/frontend/acceptance.php'));

\Codeception\Util\Autoload::registerSuffix('Steps', __DIR__.DIRECTORY_SEPARATOR.'_steps');
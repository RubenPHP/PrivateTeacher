<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'uploadDirectoryForURL' => 'uploads/',
    'uploadDirectory' => ['frontend' => Yii::getAlias('@frontend').'/web/uploads/',
                          'backend' => Yii::getAlias('@backend').'/web/uploads/'],
    'URL' => ['frontend' => 'overwrite on params-local.php. i.e. http://heavycms.com/',
              'backend' => 'overwrite on params-local.php. i.e. http://backend.heavycms.com/'],
];

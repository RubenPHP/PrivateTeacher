<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator app\giiTemplates\crud\Generator */

echo $form->field($generator, 'db');
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'searchModelClass');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'viewPath');
echo $form->field($generator, 'baseControllerClass');
echo $form->field($generator, 'indexWidgetType')->dropDownList([
    'grid' => 'GridView',
    'list' => 'ListView',
]);
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'enableRBACAdminAccess')->checkbox();
echo $form->field($generator, 'generateView')->checkbox();
echo $form->field($generator, 'useSummernoteOnTextFields')->checkbox();
echo $form->field($generator, 'useSelect2ForHasManyRelations')->checkbox();
echo $form->field($generator, 'messageCategory');

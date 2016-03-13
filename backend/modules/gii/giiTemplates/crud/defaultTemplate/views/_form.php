<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator backend\modules\gii\giiTemplates\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass;
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

$dontGenerateAttributes = ['created_by', 'created_at', 'updated_by', 'updated_at', 'slug'];
foreach ($safeAttributes as $i => $attribute) {
    if (in_array($attribute, $dontGenerateAttributes)) {
        unset($safeAttributes[$i]);
    }
}

$modelVariable = Inflector::variablize(StringHelper::basename($generator->modelClass));

/*TODO: (ruben) insert "use ClassName" to use allow the use of getMappedArray*/

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
<?php if ($generator->useSummernoteOnTextFields && $generator->hasTextFields()): ?>
use Zelenin\yii\widgets\Summernote\Summernote;
<?php endif; ?>
<?php if ($generator->useSelect2ForHasManyRelations): ?>
use \kartik\select2\Select2;
<?php endif; ?>

<?php if ($generator->hasImageFields()): ?>
use kartik\file\FileInput;
<?php endif; ?>

/* @var $this yii\web\View */
/* @var $<?= $modelVariable ?> <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?php if ($generator->hasFileFields()): ?>
        <?= "<?php " ?>$form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data'],]); ?>
    <?php else: ?>
        <?= "<?php " ?>$form = ActiveForm::begin(); ?>
    <?php endif; ?>


    <?php foreach ($generator->getColumnNames() as $attribute) {
        if (in_array($attribute, $safeAttributes)) {
            echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
        }
    } ?>

    <?php if ($generator->useSelect2ForHasManyRelations): ?>
        <?php foreach ($generator->getAllJunctionRelations() as $multipleRelation): ?>
            <?php
            $class = $generator->modelClass;
            $modelVariable = $generator->variablize($class);
            $relationName = $multipleRelation['name'];
            $pkRelatedTable = $multipleRelation['pkRelatedTable'];
            $nameRelatedTable = $multipleRelation['nameRelatedTable'];
            $relatedClassAndNamespace = $generator->getNameSpace() . '\\' . $nameRelatedTable;
            ?>
            <div class="form-group">
            <label class="control-label" for="<?= $modelVariable ?>-<?= $relationName ?>">

                <?= "<?= \${$modelVariable}->getAttributeLabel('{$relationName}') ?>" ?>

            </label>
            <?= " <?= " ?>
                Select2::widget([
                    'name' => '<?= $relationName ?>',
                    'data' => <?= $relatedClassAndNamespace ?>::getMappedArray(),
                    'value' => $<?= $modelVariable ?>->getColumnFromRelation('<?= $pkRelatedTable ?>', '<?= $relationName ?>'),
                    'options' => [
                        'placeholder' => 'Select a <?= $nameRelatedTable ?> ...',
                        'multiple' => true,
                    ],

                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [','],
                    ],
                ]);
            <?= " ?> " ?>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>


    <div class="form-group">
        <?= "<?= " ?>Html::submitButton($<?= $modelVariable ?>->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>, ['class' => $<?= $modelVariable ?>->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

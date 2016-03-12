<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\modules\gii\giiTemplates\crud\Generator */

$urlParams = $generator->generateUrlParams();
$modelVariable = Inflector::variablize(StringHelper::basename($generator->modelClass));

echo "<?php\n";
?>

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $<?= $modelVariable ?> <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString('Update {modelClass}: ', ['modelClass' => Inflector::camel2words(StringHelper::basename($generator->modelClass))]) ?> . ' ' . $<?= $modelVariable ?>-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
<?php if ($generator->generateView): ?>
    $this->params['breadcrumbs'][] = ['label' => $<?= $modelVariable ?>-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
<?php endif; ?>
$this->params['breadcrumbs'][] = <?= $generator->generateString('Update') ?>;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <?= "<?= " ?>$this->render('_form', [
        '<?= $modelVariable ?>' => $<?= $modelVariable ?>,
    ]) ?>

</div>

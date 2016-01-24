<?php
/**
 * This is the template for generating the base model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>\base;

use Yii;

<?php if ($hasIncludes): ?>
    <?php foreach ($includes as $include): ?>
        <?= $include . ";\n"  ?>
    <?php endforeach; ?>
<?php endif; ?>

/**
 * This is the base model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{

<?php if ($hasAttributes): ?>
    <?php foreach ($attributes as $attribute): ?>
        <?= $attribute . ";\n"  ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($hasInit): ?>
    public function init()
    {
        parent::init();
    <?php foreach ($initList as $init): ?>
        <?= $init . "\n"  ?>
    <?php endforeach; ?>
    }
<?php endif; ?>


<?php if ($hasBeforeSave): ?>
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
    <?php foreach ($beforeSaveList as $beforeSave): ?>
        <?= $beforeSave . "\n"  ?>
    <?php endforeach; ?>

        return true;
    }
<?php endif; ?>
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

<?php if ($hasBehaviours): ?>
    /**
    * @inheritdoc
    */
    public function behaviors()
    {
    return [
    <?php foreach ($behaviours as $behaviourName => $behaviourCode): ?>
        <?= $behaviourCode . ",\n"  ?>
    <?php endforeach; ?>
    ];
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . "\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * @inheritdoc
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>
}

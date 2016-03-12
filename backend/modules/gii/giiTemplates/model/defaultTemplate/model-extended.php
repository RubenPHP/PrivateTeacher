<?php
/**
 * This is the template for generating the extended model class of a specified table.
 *
 * @var yii\web\View $this
 * @var yii\gii\generators\model\Generator $generator
 * @var string $tableName full table name
 * @var string $className class name
 * @var yii\db\TableSchema $tableSchema
 * @var string[] $labels list of attribute labels (name => label)
 * @var string[] $rules list of validation rules
 * @var array $relations list of relations (name => relation declaration)
 */

/*TODO (ruben): Generate different classes for Backend, common, and frontend*/

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;

use \<?= $generator->ns ?>\base\<?= $className ?> as Base<?= $className ?>;

/**
* This is the model class for table "<?= $tableName ?>".
*/
class <?= $className ?> extends Base<?= $className . "\n" ?>
{

}
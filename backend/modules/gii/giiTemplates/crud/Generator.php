<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\gii\giiTemplates\crud;

use Yii;
use yii\base\ErrorException;
use yii\base\UnknownMethodException;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use app\modules\gii\GeneratorTrait;

/**
 * Generates CRUD
 *
 * @property array $columnNames Model column names. This property is read-only.
 * @property string $controllerID The controller ID (without the module ID prefix). This property is
 * read-only.
 * @property array $searchAttributes Searchable attributes. This property is read-only.
 * @property boolean|\yii\db\TableSchema $tableSchema This property is read-only.
 * @property string $viewPath The controller view path. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\generators\crud\Generator
{
    use GeneratorTrait;

    public $enableRBACAdminAccess = false;
    public $generateView = false;
    public $useSummernoteOnTextFields = true;
    public $useSelect2ForHasManyRelations = true;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'My CRUD Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [

            [['enableRBACAdminAccess'], 'boolean'],
            [['generateView'], 'boolean'],
            [['useSummernoteOnTextFields'], 'boolean'],
            [['useSelect2ForHasManyRelations'], 'boolean'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'generateView' => 'Generate code related with model\'s View',
            'useSummernoteOnTextFields' => 'Use Summernote text editor on "TEXT" fields',
            'useSelect2ForHasManyRelations' => 'Use Select2 on form to select multiples items from a Many Relation'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'enableRBACAdminAccess' => 'This enable the Access Control to action\'s controller (index, create, update, delete).<br/ >
                Only Admin role could access to that actions if this option is enabled.<br/ >
                Enable it if you are using RBAC on your project, and you have created an admin role.',
            'generateView' => 'This generate the code related with the View of the model (actionView, view.php).<br />
                Many times we don\'t need the view of the model. So by default, it will not be generated.',
            'useSummernoteOnTextFields' => 'If checked, the default editor for text fields will be Summernote editor.',
            'useSelect2ForHasManyRelations' => 'If checked, the form will display a select2 input to select multiple items
            from the related table',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');

        $files = [
            new CodeFile($controllerFile, $this->render('controller.php')),
        ];

        if (!empty($this->searchModelClass)) {
            $searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));
            $files[] = new CodeFile($searchModel, $this->render('search.php'));
        }

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (empty($this->searchModelClass) && $file === '_search.php') {
                continue;
            }
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }

        return $files;
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute)
    {
        $class = $this->modelClass;
        $modelVariable = $this->variablize($class);
        $nameSpace = $this->getNameSpace();

        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return "\$form->field(\${$modelVariable}, '$attribute')->passwordInput()";
            } else {
                return "\$form->field(\${$modelVariable}, '$attribute')";
            }
        }
        $column = $tableSchema->columns[$attribute];

        if (strpos($column->name, 'image') !== false) {
            return "
                \$form->field(\${$modelVariable}->imageManager, 'uploadedImage',
                    ['options'=>['class'=>'form-group image-file-upload']])->widget(
                    FileInput::classname(),[
                    'options' => ['multiple' => false, 'accept' => 'image/*'],
                    'pluginOptions' => [
                        'defaultPreviewContent' => Html::img(\${$modelVariable}->imageManager->getFullUrlImage()),
                        'overwriteInitial' => true,
                        'showCaption' => false,
                        'showRemove' => true,
                        'showUpload' => false,
                        'showClose' => false,
                        'browseLabel' => '',
                        'removeLabel' => '',
                        'removeIcon' => '<i class=\"glyphicon glyphicon-remove\"></i>',
                        'layoutTemplates' => ['main2' => '{preview} {browse} {remove}'],
                        'allowedFileExtensions' => [\"jpg\", \"png\", \"gif\"]
                    ]
                ]);
            ";
        } elseif ($column->phpType === 'boolean' || $column->dbType === 'int(1)' || $column->dbType === 'tinyint(1)') {
            return "\$form->field(\${$modelVariable}, '$attribute')->checkbox()";
        } elseif ($column->type === 'text' && $this->useSummernoteOnTextFields) {
            return "\$form->field(\${$modelVariable}, '$attribute')->widget(Summernote::className(), [
                    'clientOptions' => [
                        'toolbar' => [
                                        ['style', ['bold', 'italic', 'underline', 'clear']],
                                        ['font', ['strikethrough', 'superscript', 'subscript']],
                                        ['para', ['ul', 'ol', 'paragraph']],
                                        ['misc', ['codeview']],
                                     ],
                    ],
                    'options' => [
                        'class' => '{$attribute}-summernote form-control',
                    ]
                ])";
        } elseif ($column->type === 'text') {
            return "\$form->field(\${$modelVariable}, '$attribute')->textarea(['rows' => 6])";
        } else {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                $input = 'passwordInput';
            } else {
                $input = 'textInput';
            }
            if (is_array($column->enumValues) && count($column->enumValues) > 0) {
                $dropDownOptions = [];
                foreach ($column->enumValues as $enumValue) {
                    $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
                }
                return "\$form->field(\${$modelVariable}, '$attribute')->dropDownList("
                . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)) . ", ['prompt' => ''])";
            } elseif ($this->isForeignKey($tableSchema, $attribute)) {
                $classNameForeignKey = $nameSpace . "\\" . $this->getClassNameOfForeignKey($tableSchema, $attribute);
                return "\$form->field(\${$modelVariable}, '$attribute')->dropDownList($classNameForeignKey::getMappedArray())";
            } elseif ($column->phpType !== 'string' || $column->size === null) {
                return "\$form->field(\${$modelVariable}, '$attribute')->$input()";
            } else {
                return "\$form->field(\${$modelVariable}, '$attribute')->$input(['maxlength' => true])";
            }
        }
    }

    public function generateUrlParams(){
        $urlParams = parent::generateUrlParams();
        $class = $this->modelClass;
        $modelVariable = $this->variablize($class);

        $urlParams = str_replace('$model', "\${$modelVariable}", $urlParams);
        return $urlParams;

    }

    /**
     * @param array $multipleRelation
     * 'name' => string 'jobTags'
     * 'method' => string 'getJobTags'
     * 'isMultiple' => boolean true
     * 'pkRelatedTable' => string 'tag_id'
     * 'nameRelatedTable' => string 'Tag'
     */
    public function generateActiveFieldForMultipleRelation($multipleRelation)
    {
        $class = $this->modelClass;
        $modelVariable = $this->variablize($class);
        $relationName = $multipleRelation['name'];
        $pkRelatedTable = $multipleRelation['pkRelatedTable'];
        $nameRelatedTable = $multipleRelation['nameRelatedTable'];
        $relatedClassAndNamespace = $this->getNameSpace() . '\\' . $nameRelatedTable;

        return "
        Select2::widget([
            'name' => '$relationName',
            'data' => {$relatedClassAndNamespace}::getMappedArray(),
            'value' => \${$modelVariable}->getColumnFromRelation('$pkRelatedTable', '$relationName'),
            'options' => [
                'placeholder' => 'Select a $nameRelatedTable ...',
                'multiple' => true,
            ],

            'pluginOptions' => [
                'tags' => true,
                'tokenSeparators' => [','],
            ],
        ]);
        ";
    }


    private function isForeignKey($table, $columnName)
    {
        $foreignKeys = [];
        foreach ($table->foreignKeys as $foreignKey) {
            foreach ($foreignKey as $field => $name) {
                if (!is_numeric($field)) {
                    $foreignKeys[] = $field;
                }
            }
        }

        return in_array($columnName, $foreignKeys);
    }

    private function getClassNameOfForeignKey($table, $foreignKey)
    {
        foreach ($table->foreignKeys as $fk) {
            $className = '';
            foreach ($fk as $field => $name) {
                if (is_numeric($field)) {
                    $className = $fk[0];
                } else {
                    if ($field == $foreignKey) {
                        return Inflector::camelize($className);
                    }
                }
            }
        }
        return null;
    }

    public function hasTextFields()
    {
        $table = $this->getTableSchema();
        $hasTextFields = false;
        foreach ($table->columns as $column) {
            if ($column->dbType === 'text') {
                $hasTextFields = true;
            }
        }
        return $hasTextFields;
    }
}

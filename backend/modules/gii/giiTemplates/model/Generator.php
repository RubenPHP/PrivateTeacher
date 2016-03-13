<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\modules\gii\giiTemplates\model;

use Yii;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\base\NotSupportedException;
use backend\modules\gii\GeneratorTrait;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\generators\model\Generator
{
    use GeneratorTrait;

    public $generateExtendedModelClass = false;
    public $generateNMRelatedFunction = false;
    private $_includesToGenerate = [];
    private $_behavioursToGenerate = [];
    private $_attributesToGenerate = [];
    private $_initToGenerate = [];
    private $_beforeSaveToGenerate = [];
    private $_notInsertOnRequired = ['created_by', 'updated_by', 'created_at', 'updated_at'];
    /*TODO (ruben): autodetect sluggable field*/
    private $_allBehaviors = [
        'blameable' => [
            'includes' => ['use yii\behaviors\BlameableBehavior'],
            'behaviours' => ["'blameable' => ['class' => BlameableBehavior::className(),]"],
        ],
        'timestamp' => [
            'includes' => ['use yii\behaviors\TimestampBehavior'],
            'behaviours' => ["'timestamp' => ['class' => TimestampBehavior::className(),]"]
        ],
        'sluggable' => [
            'includes' => ['use yii\behaviors\SluggableBehavior'],
            'behaviours' => ["'sluggable' => ['class' => SluggableBehavior::className(),'attribute' => 'anchor',]"],
        ],
    ];
    /*TODO (ruben): Create automatically the file \common\models\helpers\ImageManager*/
    private $_allSpecialFields = [
        'image' => [
            'includes' => [
                'use yii\web\UploadedFile',
                'use common\models\collaborators\ImageManager'
            ],
            'attributes' => ['public $imageManager'],
            'init' => '$this->imageManager = new ImageManager($this, $imageDirectory = \'images/\', $imageFieldName = \'image\', $defaultImage = \'default_image.jpg\');',
            'beforeSave' => '$this->imageManager->uploadedImage = UploadedFile::getInstance($this->imageManager, \'uploadedImage\');
                            $this->imageManager->saveImageToDisk();
                            if (isset($this->imageManager->uploadedImage)&&!$this->imageManager->isImageSavedToDiskOk) {
                                return false;
                            }',
        ],
        'avatar' => [
            'includes' => [
                'use yii\web\UploadedFile',
                'use common\models\collaborators\ImageManager'
            ],
            'attributes' => ['public $avatarManager'],
            'init' => '$this->avatarManager = new ImageManager($this, $imageDirectory = \'avatars/\', $imageFieldName = \'avatar\', $defaultImage = \'default_avatar.jpg\');',
            'beforeSave' => '$this->avatarManager->uploadedImage = UploadedFile::getInstance($this->avatarManager, \'uploadedImage\');
                            $this->avatarManager->saveImageToDisk();
                            if (isset($this->avatarManager->uploadedImage)&&!$this->avatarManager->isImageSavedToDiskOk) {
                                return false;
                            }',
        ],
    ];

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'My Model Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates an ActiveRecord class for the specified database table.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['generateNMRelatedFunction'], 'boolean'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'generateExtendedModelClass' => 'Generate Extended Model Class',
            'generateNMRelatedFunction' => 'Generate getColumnFromRelation function',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'generateExtendedModelClass' => 'This indicates whether the generator should generate the Extended Model Class',
            'generateNMRelatedFunction' => 'If checked the function <code>getColumnFromRelation</code> will be generated
                This function is used to select a column of a NM table. This function must be created on the class that
                is the primary on a relation. I.e.: On <code>Job</code> table, we can check this option to retrieve all
                the <code>Tags</code> from job_tag table.',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();

        foreach ($this->getTableNames() as $tableName) {
            // model :
            $modelClassName = $this->generateClassName($tableName);
            $queryClassName = ($this->generateQuery) ? $this->generateQueryClassName($modelClassName) : false;
            $tableSchema = $db->getTableSchema($tableName);

            $this->generateBehaviours($tableSchema);
            $this->generateSpecialFields($tableSchema);

            $params = [
                'tableName' => $tableName,
                'className' => $modelClassName,
                'queryClassName' => $queryClassName,
                'tableSchema' => $tableSchema,
                'labels' => $this->generateLabels($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => isset($relations[$tableName]) ? $relations[$tableName] : [],
                'includes' => array_unique($this->_includesToGenerate),
                'hasIncludes' => !empty($this->_includesToGenerate),
                'attributes' => array_unique($this->_attributesToGenerate),
                'hasAttributes' => !empty($this->_attributesToGenerate),
                'behaviours' => array_unique($this->_behavioursToGenerate),
                'hasBehaviours' => !empty($this->_behavioursToGenerate),
                'initList' => array_unique($this->_initToGenerate),
                'hasInit' => !empty($this->_initToGenerate),
                'beforeSaveList' => array_unique($this->_beforeSaveToGenerate),
                'hasBeforeSave' => !empty($this->_beforeSaveToGenerate),
                'fieldsForMappedArray' => $this->generateFieldsForMappedArray($tableSchema),
            ];
            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/base/' . $modelClassName . '.php',
                $this->render('model.php', $params)
            );

            // Generate extendedModelClass
            $extendedModelClassFile = Yii::getAlias('@' . str_replace('\\', '/',
                        $this->ns)) . '/' . $modelClassName . '.php';
            if ($this->generateExtendedModelClass || !is_file($extendedModelClassFile)) {
                $files[] = new CodeFile(
                    $extendedModelClassFile,
                    $this->render('model-extended.php', $params)
                );
            }

            // query :
            if ($queryClassName) {
                $params = [
                    'className' => $queryClassName,
                    'modelClassName' => $modelClassName,
                ];
                $files[] = new CodeFile(
                    Yii::getAlias('@' . str_replace('\\', '/', $this->queryNs)) . '/' . $queryClassName . '.php',
                    $this->render('query.php', $params)
                );
            }
        }

        return $files;
    }

    // https://github.com/schmunk42/yii2-giiant/blob/master/src/generators/model/Generator.php#L305
    protected function generateRelations()
    {
        $relations = parent::generateRelations();
        // inject namespace
        $ns = "\\{$this->ns}\\";
        foreach ($relations AS $model => $relInfo) {
            foreach ($relInfo AS $relName => $relData) {
                $relations[$model][$relName][0] = preg_replace(
                    '/(has[A-Za-z0-9]+\()([a-zA-Z0-9]+::)/',
                    '$1__NS__$2',
                    $relations[$model][$relName][0]
                );
                $relations[$model][$relName][0] = str_replace('__NS__', $ns, $relations[$model][$relName][0]);
            }
        }
        return $relations;
    }

    /**
     * Generate the behaviours for the specified table.
     * @param $tableSchema
     * @return array with the generated behaviours ([blameable, timestamp, etc])
     */
    public function generateBehaviours($tableSchema)
    {
        foreach ($tableSchema->columns as $column) {
            if (strcasecmp($column->name, 'created_by') == 0 || strcasecmp($column->name, 'updated_by') == 0) {
                $this->_behavioursToGenerate = array_merge($this->_behavioursToGenerate,
                    $this->_allBehaviors['blameable']['behaviours']);
                $this->_includesToGenerate = array_merge($this->_includesToGenerate,
                    $this->_allBehaviors['blameable']['includes']);
            } elseif (strcasecmp($column->name, 'created_at') == 0 || strcasecmp($column->name, 'updated_at') == 0) {
                $this->_behavioursToGenerate = array_merge($this->_behavioursToGenerate,
                    $this->_allBehaviors['timestamp']['behaviours']);
                $this->_includesToGenerate = array_merge($this->_includesToGenerate,
                    $this->_allBehaviors['timestamp']['includes']);
            } elseif (strcasecmp($column->name, 'slug') == 0) {
                $this->_behavioursToGenerate = array_merge($this->_behavioursToGenerate,
                    $this->_allBehaviors['sluggable']['behaviours']);
                $this->_includesToGenerate = array_merge($this->_includesToGenerate,
                    $this->_allBehaviors['sluggable']['includes']);
            }
        }
    }

    /**
     * Generate the list of special fields for the specified table.
     * A special field is the one that makes the class to include a specific Trait
     * i.e.: field 'image' makes the classes to include ImageManager Trait, that
     * facilitate the management of an image (save to directory, rename, getUrl, default image, etc.
     * @param $tableSchema
     * @return array with the generated fields ([image, avatar, etc])
     */
    public function generateSpecialFields($tableSchema)
    {
        foreach ($tableSchema->columns as $column) {
            if (strpos($column->name, 'image') !== false) {
                $this->_includesToGenerate = array_merge($this->_includesToGenerate,
                    $this->_allSpecialFields['image']['includes']);
                $this->_attributesToGenerate = array_merge($this->_attributesToGenerate,
                    $this->_allSpecialFields['image']['attributes']);
                array_push($this->_initToGenerate, $this->_allSpecialFields['image']['init']);
                array_push($this->_beforeSaveToGenerate, $this->_allSpecialFields['image']['beforeSave']);
            } elseif (strcasecmp($column->name, 'avatar') == 0) {
                $this->_includesToGenerate = array_merge($this->_includesToGenerate,
                    $this->_allSpecialFields['avatar']['includes']);
                $this->_attributesToGenerate = array_merge($this->_attributesToGenerate,
                    $this->_allSpecialFields['avatar']['attributes']);
                array_push($this->_initToGenerate, $this->_allSpecialFields['avatar']['init']);
                array_push($this->_beforeSaveToGenerate, $this->_allSpecialFields['avatar']['beforeSave']);
            }
        }
    }

    /**
     * Generates validation rules for the specified table.
     * @param \yii\db\TableSchema $table the table schema
     * @return array the generated fields used on getMappedArray static function
     */
    public function generateFieldsForMappedArray($table)
    {
        $foreignKeys = [];
        $fieldsForMappedArray = [
            'idName' => 'id',
            'fieldName' => 'name'
        ];
        $primaryKey = $table->primaryKey[0];

        // get all foreign keys names
        foreach ($table->foreignKeys as $foreignKey) {
            foreach ($foreignKey as $field => $name) {
                if (!is_numeric($field)) {
                    $foreignKeys[] = $field;
                }
            }
        }
        // get the first field of the table that is not the id field, or a foreign key field.
        foreach ($table->columnNames as $columnName) {
            if (!in_array($columnName, $foreignKeys) && $columnName != $primaryKey) {
                $fieldsForMappedArray = [
                    'idName' => $primaryKey,
                    'fieldName' => $columnName
                ];
                break;
            }
        }
        return $fieldsForMappedArray;
    }

    /**
     * Generates validation rules for the specified table.
     * @param \yii\db\TableSchema $table the table schema
     * @return array the generated validation rules
     */
    public function generateRules($table)
    {
        $types = [];
        $lengths = [];
        foreach ($table->columns as $column) {
            if ($column->autoIncrement) {
                continue;
            }
            if (!$column->allowNull && $column->defaultValue === null && !in_array($column->name,
                    $this->_notInsertOnRequired)
            ) {
                $types['required'][] = $column->name;
            }
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case 'double': // Schema::TYPE_DOUBLE, which is available since Yii 2.0.3
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $types['safe'][] = $column->name;
                    break;
                default: // strings
                    if ($column->size > 0) {
                        $lengths[$column->size][] = $column->name;
                    } else {
                        $types['string'][] = $column->name;
                    }
            }
            /*TODO: (ruben) enhance the url string detection with regex (only when 'url' is the only word, or have an _ before or after the word.*/
            if (strstr($column->name, 'url')) {
                $types['url'][] = $column->name;
            }
        }
        $rules = [];
        foreach ($types as $type => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }
        foreach ($lengths as $length => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], 'string', 'max' => $length]";
        }

        // Unique indexes rules
        try {
            $db = $this->getDbConnection();
            $uniqueIndexes = $db->getSchema()->findUniqueIndexes($table);
            foreach ($uniqueIndexes as $uniqueColumns) {
                // Avoid validating auto incremental columns
                if (!$this->isColumnAutoIncremental($table, $uniqueColumns)) {
                    $attributesCount = count($uniqueColumns);

                    if ($attributesCount == 1) {
                        $rules[] = "[['" . $uniqueColumns[0] . "'], 'unique']";
                    } elseif ($attributesCount > 1) {
                        $labels = array_intersect_key($this->generateLabels($table), array_flip($uniqueColumns));
                        $lastLabel = array_pop($labels);
                        $columnsList = implode("', '", $uniqueColumns);
                        $rules[] = "[['" . $columnsList . "'], 'unique', 'targetAttribute' => ['" . $columnsList . "'], 'message' => 'The combination of " . implode(', ',
                                $labels) . " and " . $lastLabel . " has already been taken.']";
                    }
                }
            }
        } catch (NotSupportedException $e) {
            // doesn't support unique indexes information...do nothing
        }

        return $rules;
    }


}

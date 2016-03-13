<?php

namespace backend\modules\gii;


use yii\base\ErrorException;
use yii\base\NotSupportedException;
use yii\base\UnknownMethodException;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

trait GeneratorTrait
{
    public function getHasAnyJunctionRelation()
    {
        return $this->getAllJunctionRelations() > 0;
    }

    public function getAllJunctionRelations()
    {
        $allJunctionRelations = [];
        foreach ($this->getModelRelations() as $modelRelation) {
            if ($modelRelation['isJunctionTable']) {
                $allJunctionRelations[] = $modelRelation;
            }
        }
        return $allJunctionRelations;
    }

    /**
     * https://github.com/yiisoft/yii2/issues/1282#issuecomment-29071134
     * https://github.com/yiisoft/yii2/issues/1282#issuecomment-29072322
     * https://github.com/yiisoft/yii2/issues/1282#issuecomment-124382968
     * @return array
     */
    public function getModelRelations()
    {
        $db = $this->getDbConnection();
        $modelClass = $this->modelClass;
        if ($this->isModelGenerator()) {
            $modelClass = $this->ns . '\\' . $this->modelClass;
        }
        $reflector = new \ReflectionClass($modelClass);
        $model = new $modelClass;
        $className = StringHelper::basename($modelClass);
        $stack = [];
        $baseClassMethods = get_class_methods('yii\db\ActiveRecord');
        foreach ($reflector->getMethods() as $i => $method) {
            if (in_array($method->name, $baseClassMethods)) {
                continue;
            }
            try {
                $relation = call_user_func([$model, $method->name]);

                if ($relation instanceof \yii\db\ActiveQuery) {
                    $relationNameUppercase = str_replace('get', '', $method->name);
                    $stack[$i]['name'] = $this->variablize($relationNameUppercase);
                    $stack[$i]['nameUppercase'] = $relationNameUppercase;
                    $stack[$i]['method'] = $method->name;
                    $stack[$i]['isMultiple'] = $relation->multiple;
                    $tableSchema = $db->getTableSchema($model::tableName());
                    $stack[$i]['isJunctionTable'] = $this->checkJunctionTable($tableSchema);
                    //$stack[$i]['isJunctionTable'] = false;
                    if ($relation->multiple) {
                        /*TODO: (ruben) Make the detection of the NM table using ReflectionClass, not parsing text*/
                        $nameRelatedTablePlural = str_replace($className, '',
                            str_replace('get', '', $method->name));
                        $nameRelatedTable = Inflector::singularize($nameRelatedTablePlural);
                        $pkRelatedTable = $this->variablize($nameRelatedTable) . '_id';
                        $classNMName = Inflector::singularize($relationNameUppercase);
                        $stack[$i]['pkRelatedTable'] = $pkRelatedTable;
                        $stack[$i]['nameRelatedTable'] = $nameRelatedTable;
                        $stack[$i]['variableRelatedTable'] = $this->variablize($nameRelatedTable);
                        $stack[$i]['nameRelatedTablePlural'] = $nameRelatedTablePlural;
                        $stack[$i]['nameRelatedTablePluralLowercase'] = $this->variablize($nameRelatedTablePlural);
                        $stack[$i]['classNMName'] = $classNMName;
                        /*TODO: change the way junction table is detected*/
                        //$stack[$i]['isJunctionTable'] = strlen(preg_replace('![^A-Z]+!', '', $classNMName)) > 1;

                    }
                }
            } catch (UnknownMethodException $e) {
                continue;
            } catch (ErrorException $e) {
                continue;
            }
        }
        return $stack;
    }

    public function variablize($classPath)
    {
        $className = StringHelper::basename($classPath);
        return Inflector::variablize($className);
    }

    public function getNameSpace(){
        $class = $this->modelClass;
        return StringHelper::dirname($class);
    }

    public function isCrudGenerator(){
        return !isset($this->tableName);
    }

    public function isModelGenerator(){
        return isset($this->tableName);
    }

    /**
     * Checks if the given table is a junction table, that is it has at least one pair of unique foreign keys.
     * @param \yii\db\TableSchema the table being checked
     * @return array|boolean all unique foreign key pairs if the table is a junction table,
     * or false if the table is not a junction table.
     */
    protected function checkJunctionTable($table)
    {
        if (count($table->foreignKeys) < 2) {
            return false;
        }
        $uniqueKeys = [$table->primaryKey];
        try {
            $uniqueKeys = array_merge($uniqueKeys, $this->getDbConnection()->getSchema()->findUniqueIndexes($table));
        } catch (NotSupportedException $e) {
            // ignore
        }
        $result = [];
        // find all foreign key pairs that have all columns in an unique constraint
        $foreignKeys = array_values($table->foreignKeys);
        for ($i = 0; $i < count($foreignKeys); $i++) {
            $firstColumns = $foreignKeys[$i];
            unset($firstColumns[0]);

            for ($j = $i + 1; $j < count($foreignKeys); $j++) {
                $secondColumns = $foreignKeys[$j];
                unset($secondColumns[0]);

                $fks = array_merge(array_keys($firstColumns), array_keys($secondColumns));
                foreach ($uniqueKeys as $uniqueKey) {
                    if (count(array_diff(array_merge($uniqueKey, $fks), array_intersect($uniqueKey, $fks))) === 0) {
                        // save the foreign key pair
                        $result[] = [$foreignKeys[$i], $foreignKeys[$j]];
                        break;
                    }
                }
            }
        }
        return empty($result) ? false : $result;
    }

}
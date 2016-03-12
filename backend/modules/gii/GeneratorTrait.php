<?php

namespace app\modules\gii;


use yii\base\ErrorException;
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
        $modelClass = $this->ns . '\\' . $this->modelClass;
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
                    $stack[$i]['isJunctionTable'] = false;
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
                        $stack[$i]['isJunctionTable'] = strlen(preg_replace('![^A-Z]+!', '', $classNMName)) > 1;

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
}
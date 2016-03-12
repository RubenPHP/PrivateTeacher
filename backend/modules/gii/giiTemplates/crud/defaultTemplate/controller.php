<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;


/* @var $this yii\web\View */
/* @var $generator app\modules\gii\giiTemplates\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$modelVariable = Inflector::variablize($modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();
$viewAction = $generator->generateView ? ", 'view'" : '';
echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
<?php if ($generator->enableRBACAdminAccess): ?>
    use yii\filters\AccessControl;
<?php endif; ?>
use yii\filters\VerbFilter;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    public function behaviors()
    {
        return [
        <?php if ($generator->enableRBACAdminAccess): ?>
            'access' => [
                'class' => AccessControl::className(),
                    'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete'<?= $viewAction ?>],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        <?php endif; ?>
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $<?= $modelVariable ?> = new <?= $modelClass ?>();
        $post = Yii::$app->request->post();

        if ($<?= $modelVariable ?>->load($post) && $<?= $modelVariable ?>->save()) {
            <?php if ($generator->useSelect2ForHasManyRelations): ?>

                <?php foreach ($generator->getAllMultipleRelations() as $multipleRelation): ?>
                    <?php
                    $relationName = $multipleRelation['name'];
                    $relationNameUppercase = $multipleRelation['nameUppercase'];
                    ?>

                    $<?= $relationName ?>POST = !empty($post['<?= $relationName ?>']) ? $post['<?= $relationName ?>'] : [];
                    $<?= $modelVariable ?>->create<?= $relationNameUppercase ?>Items($<?= $relationName ?>POST);
                <?php endforeach; ?>
            <?php endif; ?>
            return $this->redirect(['index']);
        }

        return $this->render('create', compact('<?= $modelVariable ?>'));
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $<?= $modelVariable ?> = $this->find<?= $modelClass ?>Model(<?= $actionParams ?>);
        $post = Yii::$app->request->post();

        if ($<?= $modelVariable ?>->load($post) && $<?= $modelVariable ?>->save()) {
            <?php if ($generator->useSelect2ForHasManyRelations): ?>

                <?php foreach ($generator->getAllMultipleRelations() as $multipleRelation): ?>
                    <?php
                    $relationName = $multipleRelation['name'];
                    $relationNameUppercase = $multipleRelation['nameUppercase'];
                    ?>

                    $<?= $relationName ?>POST = !empty($post['<?= $relationName ?>']) ? $post['<?= $relationName ?>'] : [];
                    $<?= $modelVariable ?>->update<?= $relationNameUppercase ?>Items($<?= $relationName ?>POST);
                <?php endforeach; ?>
            <?php endif; ?>
            return $this->redirect(['index']);
        }

        return $this->render('update', compact('<?= $modelVariable ?>'));
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        $this->find<?= $modelClass ?>Model(<?= $actionParams ?>)->delete();

        return $this->redirect(['index']);
    }

    <?php if ($generator->generateView): ?>

    /**
    * Displays a single <?= $modelClass ?> model.
    * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
    * @return mixed
    */
    public function actionView(<?= $actionParams ?>)
    {
        return $this->render('view', [
            '<?= $modelVariable ?>' => $this->find<?= $modelClass ?>Model(<?= $actionParams ?>),
        ]);
    }
    <?php endif; ?>

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function find<?= $modelClass ?>Model(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        $<?= $modelVariable ?> = <?= $modelClass ?>::findOne(<?= $condition ?>);
        if (!isset($<?= $modelVariable ?>)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $<?= $modelVariable ?>;
    }
}

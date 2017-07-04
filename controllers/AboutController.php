<?php

namespace app\controllers;

use app\models\node\About;
use app\models\node\AboutHistory;
use app\models\node\Career;
use app\models\node\Procurement;
use app\models\node\Team;
use app\models\node\AboutFirst;
use Yii;
use app\models\node\Node;
use app\models\category\Category;
use app\models\forms\AboutResumeForm;


class AboutController extends \app\components\Controller
{
    public $layout = 'about';

    public function actionContacts()
    {
        $comp_cr = Yii::$app->request->get('company', 0);

        /** @var \app\models\node\Type $type */
        $type = About::getNodeType();
        $model = About::find()->byType($type->id)->byAlias('about_contact')->byCompany($comp_cr)->active()->one();

        return $this->render('contacts', compact('model'));
    }

    public function actionCareers()
    {

        $comp_cr = Yii::$app->request->get('company', 0);

        /** @var \app\models\node\Type $type */
        $type = Career::getNodeType();

        $intro  = Career::find()->byType($type->id)->byAlias('about_career_intro')->byCompany($comp_cr)->active()->one();

        $models_f = Career::find()->byType($type->id)->orderBy(['sort' => SORT_ASC])->byCompany($comp_cr)->active();
        if ($intro) {
            $models_f->andWhere('id !=' . $intro->id);
        }
        $models = $models_f->all();

        return $this->render('career', compact('models', 'intro'));
    }

    public function actionTeam()
    {
        $comp_cr = Yii::$app->request->get('company', 0);

        /** @var \app\models\node\Type $type */
        $type = Team::getNodeType();
        $models = Team::find()->byType($type->id)->byCompany($comp_cr)->active()->all();

        return $this->render('team', compact('models'));

    }

    public function actionHistory()
    {
        $comp_cr = Yii::$app->request->get('company', 0);

        /** @var \app\models\node\Type $type */
        $type = AboutHistory::getNodeType();
        $models = AboutHistory::find()->byType($type->id)->byCompany($comp_cr)->active()->all();

        return $this->render('history', compact('models'));

    }

    public function actionIndex()
    {
        $comp_cr = Yii::$app->request->get('company', 0);

        /** @var \app\models\node\Type $type */
        $type = AboutFirst::getNodeType();
        $model = AboutFirst::find()->byType($type->id)->byAlias('about_first')->byCompany($comp_cr)->one();

        return $this->render('about', compact('model'));
    }

    public function actionInfo()
    {
        $comp_cr = Yii::$app->request->get('company', 0);

        /** @var \app\models\node\Type $type */
        $type = FinancialMetrics::getNodeType();
        $models = FinancialMetrics::find()->byType($type->id)->byCompany($comp_cr)->addOrderBy(['title' => SORT_DESC])->active()->all();

        /** @var \app\models\node\Type $type */
        $type = InsuranceDocumentation::getNodeType();
        $ins_doc_models = InsuranceDocumentation::find()->byType($type->id)->byCompany($comp_cr)->addOrderBy(['title' => SORT_DESC])->active()->all();

        /** @var \app\models\node\Type $type */
        $type = PolicyActions::getNodeType();
        $policy_actions_models = PolicyActions::find()->byType($type->id)->byCompany($comp_cr)->addOrderBy(['title' => SORT_DESC])->active()->all();

        $category = Category::find()->sorted()->all();

        return $this->render('disclosure', compact('models', 'ins_doc_models', 'policy_actions_models', 'category'));

    }

//    public function actionReestr()
//    {
//        $comp_cr = Yii::$app->request->get('company', 0);
//
//        /** @var \app\models\node\Type $type */
//        $type = AboutFirst::getNodeType();
//
//        $models = AboutFirst::find()->byType($type->id)->byAlias('about_reestr')->all();
//
//        $company = Node::getCompany();
//
//        return $this->render('reestr', compact('models', 'company', 'comp_cr'));
//
//    }

    public function actionProcurement()
    {
        $comp_cr = Yii::$app->request->get('company', 0);

        /** @var \app\models\node\Type $type */
        $type = Procurement::getNodeType();
        $models = Procurement::find()->byType($type->id)->byCompany($comp_cr)->addOrderBy(['title' => SORT_DESC])->active()->all();

        /** @var \app\models\node\Type $type */
        $type = UsefulInformation::getNodeType();
        $useful_inf_models = UsefulInformation::find()->byType($type->id)->byCompany($comp_cr)->addOrderBy(['title' => SORT_DESC])->active()->all();

        /** @var \app\models\node\Type $type */
        $type = InsuranceRegistry::getNodeType();
        $ins_registry_models = InsuranceRegistry::find()->byType($type->id)->byCompany($comp_cr)->addOrderBy(['title' => SORT_DESC])->active()->all();

        return $this->render('procurement', compact('models', 'useful_inf_models', 'ins_registry_models'));
    }





}
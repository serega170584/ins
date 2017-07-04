<?php
/** @var \app\models\forms\OrderForm $form */
use yii\helpers\Html;

?>
    <h3>11111111</h3>

<?php if($form->policy_title): ?><strong>Название продукта: </strong> <?php echo Html::encode($form->policy_title); ?><br/><br/><?php endif; ?>
<?php if($form->name): ?><strong>Мое имя: </strong> <?php echo Html::encode($form->name); ?><br/><br/><?php endif; ?>
<?php if($form->phone): ?><strong>Телефон: </strong> <?php echo Html::encode($form->phone); ?><br/><br/><?php endif; ?>

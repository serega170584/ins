<?php
/** @var \app\models\forms\EventForm $form */
use yii\helpers\Html;

?>
<h3>Сообщение 123</h3>

<?php if($form->policy_title): ?><strong>Продукт: </strong> <?php echo Html::encode($form->policy_title); ?><br/><br/><?php endif; ?>
<?php if($form->message): ?><strong>Что случилось: </strong> <?php echo Html::encode($form->message); ?><br/><br/><?php endif; ?>
<?php if($form->name): ?><strong>Мое имя: </strong> <?php echo Html::encode($form->name); ?><br/><br/><?php endif; ?>
<?php if($form->number): ?><strong>Номер полиса: </strong> <?php echo Html::encode($form->number); ?><br/><br/><?php endif; ?>
<?php if($form->email): ?><strong>Почта: </strong> <?php echo Html::encode($form->email); ?><br/><br/><?php endif; ?>
<?php if($form->phone): ?><strong>Телефон: </strong> <?php echo Html::encode($form->phone); ?><br/><br/><?php endif; ?>


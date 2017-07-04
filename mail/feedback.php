<?php
/** @var \app\models\forms\FeedbackForm $form */
use yii\helpers\Html;

?>
<h3>ooooo</h3>

<?php if($form->name): ?><strong>Мое имя: </strong> <?php echo Html::encode($form->name); ?><br/><br/><?php endif; ?>
<?php if($form->phone): ?><strong>Мой телефон: </strong> <?php echo Html::encode($form->phone); ?><br/><br/><?php endif; ?>
<?php if($form->time): ?><strong>Удобное время звонка: </strong> <?php echo Html::encode($form->time); ?><br/><br/><?php endif; ?>
<?php if($form->email): ?><strong>Email: </strong> <?php echo Html::encode($form->email); ?><br/><br/><?php endif; ?>
<?php
$preferred_text = '';
if (trim($form->preferred) == 'phone') {
    $preferred_text = 'Телефон';
}
if (trim($form->preferred) == 'email') {
    $preferred_text = 'Почта';
}
?>
<?php if($preferred_text): ?><strong>Предпочитаемый вид связи: </strong> <?php echo $preferred_text ?><br/><br/><?php endif; ?>
<?php if($form->region): ?><strong>Регион: </strong> <?php echo Html::encode($form->region); ?><br/><br/><?php endif; ?>
<?php if($form->title): ?><strong>Тема обращения: </strong> <?php echo Html::encode($form->title); ?><br/><br/><?php endif; ?>
<?php if($form->message): ?><strong>Мое сообщение: </strong> <?php echo Html::encode($form->message); ?><br/><br/><?php endif; ?>

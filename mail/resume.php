<?php
/** @var \app\models\forms\AboutResumeForm $form */
use yii\helpers\Html;

?>
    <h3>Резюме</h3>
<?php if($form->title): ?><strong>Название вакансии: </strong> <?php echo Html::encode($form->title); ?><br/><br/><?php endif; ?>
<?php if($form->name): ?><strong>Мое имя: </strong> <?php echo Html::encode($form->name); ?><br/><br/><?php endif; ?>
<?php if($form->email): ?><strong>Email: </strong> <?php echo Html::encode($form->email); ?><br/><br/><?php endif; ?>
<?php if($form->message): ?><strong>Сообщение: </strong> <?php echo Html::encode($form->message); ?><br/><br/><?php endif; ?>

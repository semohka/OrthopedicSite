<?php
/* @var $this ModelController */
/* @var $model Models */
?>

<h1>Просмотр модели</h1>

<?php
$this->widget('ext.fancybox.EFancyBox', [
    'target' => '#'.$model->id,
    'config' => [
        'enableEscapeButton' => true,
    ],
]);

$this->widget('bootstrap.widgets.TbDetailView', [
    'data' => $model,
    'attributes' => [
        'name',
        'description',
        'date_created',
        'date_modified',
        'picture' => [
            'name' => 'picture',
            'type' => 'raw',
            'value' => TbHtml::imagePolaroid(Yii::app()->baseUrl . '/upload/OrthopedicGallery/' . $model->picture, $model->name, [
                    'width' => '350px',
                    'id' => $model->id,
                    'href' => Yii::app()->baseUrl . '/upload/OrthopedicGallery/' . $model->picture,
                ]),
        ],
    ],
]);

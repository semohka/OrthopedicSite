<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />

	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<!-- <meta name="viewport" content="initial-scale=1.0, width=device-width" /> -->
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>images/shoes.png" type="image/png">
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.fancybox.css" type="text/css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php 
	Yii::app()->clientScript->registerScript('display',"
		$('#center').hide().fadeIn(800);

		//перемотка вверх
	    $('#back-top').hide();
	    	// fade in #back-top
	      	$(function () {
		  	$(window).scroll(function () {
		    	if ($(this).scrollTop() >= 400) {
			  		$('#back-top').fadeIn();
		      	} else {
			  		$('#back-top').fadeOut();
		      	}
		  });
		  // scroll body to 0px on click
		  $('#back-top a').click(function () {
		      $('body,html').animate({
			  scrollTop: 0
		      }, 700);
		      return false;
		  });
	      });//конец перемотка вверх
	", CClientScript::POS_READY);
	?>
</head>

<body id="top">
<code><font size="2" style="position:relative; left:130px; top:0px;text-shadow:1px 1px 1px rgba(255,255,255,1.0)">Version 0.2</font></code>
<a href="https://github.com/DmitrySoloviyev/OrthopedicSite">
    <img style="position: absolute; top: 0; left: 0; border: 0; float:left" src=<?php echo Yii::app()->request->baseUrl; ?>"/images/forkme_left_green.png" alt="Fork me on GitHub" />
</a>

<div class="container" id="page" >

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="navigation">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Главная', 'url'=>array('/site/index')),
				array('label'=>'Новый заказ', 'url'=>array('/site/new')),
				array('label'=>'Все заказы', 'url'=>array('/site/view')),
				array('label'=>'Поиск', 'url'=>array('site/search')),
			//	array('label'=>'Статистика', 'url'=>array('site/statistics')),
				array('label'=>'Администрирование БД', 'url'=>array('/site/admin'), 'visible'=>!Yii::app()->user->isGuest),
			//	array('label'=>'Контакты', 'url'=>array('/site/contact')),
				array('label'=>'О сайте', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Войти', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Выйти ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->
	
	<div id="center"><?php echo $content; ?></div>

	<div class="clear"></div>

	<div id="footer"><hr/>
		Copyright &copy; <?php echo date('Y'); ?> by <?php echo CHtml::mailto('Dmitry Soloviyev', 'sd_dima@mail.ru')?>.<br/>
		г. Москва. All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

<p id="back-top"><a href="#top"><img src="../../images/arrow_up.png"></img></a></p>
</body>
</html>
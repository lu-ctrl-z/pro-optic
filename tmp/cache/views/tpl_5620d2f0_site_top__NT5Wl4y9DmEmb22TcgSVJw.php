<?php 
function tpl_5620d2f0_site_top__NT5Wl4y9DmEmb22TcgSVJw(PHPTAL $tpl, PHPTAL_Context $ctx) {
	$_thistpl = $tpl ;
	/* tag "documentElement" from line 1 */ ;
	$ctx->setDocType('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',false) ;
?>

<?php 	/* tag "html" from line 2 */; ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja" dir="ltr">
<?php 	/* tag "head" from line 3 */; ?>
<head>
    <?php 	/* tag "meta" from line 4 */; ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <?php 	/* tag "meta" from line 5 */; ?>
<meta http-equiv="Content-Style-Type" content="text/css"/>
    <?php 	/* tag "meta" from line 6 */; ?>
<meta http-equiv="Content-Script-Type" content="text/javascript"/>

    <?php 
	/* tag "tal:block" from line 8 */ ;
	if (phptal_true(!empty($ctx->title_layout))):  ;
?>

        <?php 		/* tag "title" from line 9 */; ?>
<title><?php echo phptal_escape($ctx->title_layout, 'UTF-8') ?>
</title>
    <?php 	endif; ?>

    <?php 
	/* tag "tal:block" from line 11 */ ;
	if (phptal_true(!empty($ctx->description_layout))):  ;
?>

        <?php 		/* tag "meta" from line 12 */; ?>
<meta name="description" content="<?php echo phptal_escape($ctx->description_layout, 'UTF-8') ?>
"/>
    <?php 	endif; ?>

    <?php 
	/* tag "tal:block" from line 14 */ ;
	if (phptal_true(!empty($ctx->keywords_layout))):  ;
?>

        <?php 		/* tag "meta" from line 15 */; ?>
<meta name="keywords" content="<?php echo phptal_escape($ctx->keywords_layout, 'UTF-8') ?>
"/>
    <?php 	endif; ?>

    <?php 
	/* tag "tal:block" from line 17 */ ;
	if (phptal_true(empty($ctx->content_noindex))):  ;
?>

        <?php 		/* tag "meta" from line 18 */; ?>
<meta name="robots" content="index,follow"/>
    <?php 	endif; ?>

    <?php 
	/* tag "tal:block" from line 20 */ ;
	if (phptal_true(!empty($ctx->content_noindex))):  ;
?>

        <?php 		/* tag "meta" from line 21 */; ?>
<meta name="robots" content="noindex"/>
    <?php 	endif; ?>

    <?php 	/* tag "meta" from line 23 */; ?>
<meta name="author" content="SMS"/>
    <?php 	/* tag "meta" from line 24 */; ?>
<meta property="og:image" content="http://pharma-navi.net/top_20151009.png"/>

    <?php 
	/* tag "link" from line 27 */ ;
	if (null !== ($_tmp_1 = ($ctx->FULL_BASE_URL . '/img/pc/favicon.ico'))):  ;
		$_tmp_1 = ' href="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<link rel="shortcut icon"<?php echo $_tmp_1 ?>
/>
    <?php 
	/* tag "link" from line 29 */ ;
	if (null !== ($_tmp_1 = ($ctx->FULL_BASE_URL . '/img/sp/apple-touch-icon.png'))):  ;
		$_tmp_1 = ' href="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<link rel="apple-touch-icon"<?php echo $_tmp_1 ?>
/>
    <!-- #90 Start Luvina Modify -->
    <?php 
	/* tag "link" from line 32 */ ;
	if (null !== ($_tmp_1 = ($ctx->FULL_BASE_URL . '/css/pc/style.css?102'))):  ;
		$_tmp_1 = ' href="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<link rel="stylesheet" type="text/css" media="screen,print"<?php echo $_tmp_1 ?>
/>
    <!-- #90 End Luvina Modify -->
    <?php 
	/* tag "link" from line 36 */ ;
	if (phptal_true($ctx->view->params->controller!='home' || ($ctx->view->params->controller=='home' && $ctx->view->params->action!='index'))):  ;
		if (null !== ($_tmp_1 = ($ctx->FULL_BASE_URL . '/css/pc/content.css?102'))):  ;
			$_tmp_1 = ' href="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
		else:  ;
			$_tmp_1 = '' ;
		endif ;
?>
<link rel="stylesheet" type="text/css" media="screen,print"<?php echo $_tmp_1 ?>
/><?php 	endif; ?>

    <?php 
	/* tag "script" from line 37 */ ;
	if (null !== ($_tmp_1 = ($ctx->FULL_BASE_URL . '/js/jquery-1.11.1.min.js'))):  ;
		$_tmp_1 = ' src="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<script type="text/javascript"<?php echo $_tmp_1 ?>
></script>
    <!-- css3-mediaqueries.js for IE less than 9 -->
    <!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
    
    <!-- html5.js for IE less than 9 -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <?php 
	/* tag "span" from line 47 */ ;
	/* #102 Start Luvina Modify */ ;
?>

    <?php 
	/* tag "script" from line 48 */ ;
	if (null !== ($_tmp_1 = ($ctx->FULL_BASE_URL . '/js/script.js'))):  ;
		$_tmp_1 = ' src="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<script type="text/javascript"<?php echo $_tmp_1 ?>
></script>
    <?php 
	/* tag "span" from line 49 */ ;
	/* #102 End Luvina Modify */ ;
?>

</head>
<?php 	/* tag "body" from line 51 */; ?>
<body>
    <?php 	/* tag "div" from line 52 */; ?>
<div id="top"></div>
    <!-- header -->
    <?php 	/* tag "div" from line 54 */; ?>
<div id="header">
        <?php 	/* tag "div" from line 55 */; ?>
<div class="header_wrapper">
            <?php 
	/* tag "tal:block" from line 56 */ ;
	if (phptal_true($ctx->view->params->controller=='home' && $ctx->view->params->action=='index')):  ;
?>

                <?php 		/* tag "h1" from line 57 */; ?>
<h1 class="index_h1">在宅・訪問薬局をお探しなら訪問薬局ナビ</h1>
            <?php 	endif; ?>

            <?php 
	/* tag "tal:block" from line 59 */ ;
	if (phptal_true($ctx->view->params->controller!='home' || ($ctx->view->params->controller=='home' && $ctx->view->params->action!='index'))):  ;
?>

                <?php 		/* tag "div" from line 60 */; ?>
<div id="description">在宅・訪問薬局をお探しなら訪問薬局ナビ</div>
            <?php 	endif; ?>

        </div>
        <?php 	/* tag "div" from line 63 */; ?>
<div class="wrapper">
            <?php 	/* tag "div" from line 64 */; ?>
<div id="logo">
                <?php 
	/* tag "a" from line 65 */ ;
	if (null !== ($_tmp_1 = ($ctx->view->Html->url('/')))):  ;
		$_tmp_1 = ' href="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<a<?php echo $_tmp_1 ?>
>
                <?php 	/* tag "img" from line 66 */; ?>
<img src="/img/pc/logo.jpg" alt=""/></a>
            </div>
            <!-- #90 Start Luvina Modify -->
            <?php 	/* tag "div" from line 69 */; ?>
<div id="navi" class="search-new">
                <?php 	/* tag "ul" from line 70 */; ?>
<ul>
                    <?php 	/* tag "li" from line 71 */; ?>
<li><?php 
	/* tag "a" from line 71 */ ;
	if (null !== ($_tmp_1 = ($ctx->view->Html->url('/column/column/about/')))):  ;
		$_tmp_1 = ' href="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<a<?php echo $_tmp_1 ?>
>訪問薬局ナビとは</a></li>
                    <?php 	/* tag "li" from line 72 */; ?>
<li><?php 
	/* tag "a" from line 72 */ ;
	if (null !== ($_tmp_1 = ($ctx->view->Html->url('/column/column/contact/')))):  ;
		$_tmp_1 = ' href="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<a<?php echo $_tmp_1 ?>
>薬局情報掲載のお問い合わせ</a></li>
                </ul>
                <?php 	/* tag "script" from line 74 */; ?>
<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false&amp;language=ja"></script>
                <?php 
	/* tag "span" from line 75 */ ;
	/* #103 Start Luvina Modify */ ;
?>

                <?php 
	/* tag "script" from line 76 */ ;
	if (null !== ($_tmp_1 = ($ctx->FULL_BASE_URL . '/js/until_new.js'))):  ;
		$_tmp_1 = ' src="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<script type="text/javascript"<?php echo $_tmp_1 ?>
></script>
                <?php 	/* tag "div" from line 77 */; ?>
<div style="clear: both;"></div>
                 <?php 
	/* tag "span" from line 78 */ ;
	/* #90 End Luvina Fix Bug 239 */ ;
?>

            </div>
            <!-- #90 End Luvina Modify -->
        </div>
    </div>
    <!-- /header -->

    <!-- contents -->
    <?php 	/* tag "div" from line 86 */; ?>
<div id="contents">
        <!-- #89 Start Luvina Modify -->
        <?php 
	/* tag "tal:block" from line 88 */ ;
	if (phptal_true(!empty($ctx->topicPathList))):  ;
?>

            <?php 		/* tag "ul" from line 89 */; ?>
<ul id="pkz" class="wrapper">
                <?php 
		/* tag "tal:block" from line 90 */ ;
		$_tmp_1 = $ctx->repeat ;
		$_tmp_1->topicPath = new PHPTAL_RepeatController($ctx->topicPathList)
 ;
		$ctx = $tpl->pushContext() ;
		foreach ($_tmp_1->topicPath as $ctx->topicPath): ;
?>

                    <?php 			/* tag "li" from line 91 */; ?>
<li>
                        <?php 
			/* tag "tal:block" from line 92 */ ;
			if (phptal_true(!empty($ctx->topicPath['url']))):  ;
?>

                        <?php 				/* tag "a" from line 93 */; ?>
<a href="<?php echo phptal_escape($ctx->topicPath['url'], 'UTF-8') ?>
"><?php echo phptal_escape($ctx->topicPath['value'], 'UTF-8') ?>
</a>
                        <?php 			endif; ?>

                        <?php 
			/* tag "tal:block" from line 95 */ ;
			if (phptal_true(empty($ctx->topicPath['url']))):  ;
?>

                            <?php 				/* tag "span" from line 96 */; ?>
<span><?php 				echo phptal_escape($ctx->topicPath['value'], 'UTF-8'); ?>
</span>
                        <?php 			endif; ?>

                        <?php 
			/* tag "tal:block" from line 98 */ ;
			if (phptal_true($ctx->repeat->topicPath->number < $ctx->repeat->topicPath->length)):  ;
?>

                            <?php 				/* tag "span" from line 99 */; ?>
<span class="lt">＞</span>
                        <?php 			endif; ?>

                    </li>
                <?php 
		endforeach ;
		$ctx = $tpl->popContext() ;
?>

            </ul>
        <?php 	endif; ?>

        <!-- #89 End Luvina Modify -->
        <?php 
	/* tag "tal:block" from line 106 */ ;
	$ctx->noThrow(true) ;
	if (!phptal_isempty($_tmp_1 = $ctx->path($ctx, 'content_for_layout', true))):  ;
		echo phptal_tostring($_tmp_1) ;
	else:  ;
	endif ;
	$ctx->noThrow(false) ;
?>

    </div>
    <!-- /contents -->

    <?php 	/* tag "div" from line 110 */; ?>
<div style="clear: both;"></div>

    <!-- footer -->
    <?php 	/* tag "div" from line 113 */; ?>
<div id="footer">
        <?php 	/* tag "div" from line 114 */; ?>
<div class="wrapper">
            <?php 	/* tag "ul" from line 115 */; ?>
<ul>
            <?php 	/* tag "li" from line 116 */; ?>
<li><?php 
	/* tag "a" from line 116 */ ;
	if (null !== ($_tmp_1 = ($ctx->view->Html->url('/')))):  ;
		$_tmp_1 = ' href="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<a<?php echo $_tmp_1 ?>
>トップ</a></li>
            <?php 	/* tag "li" from line 117 */; ?>
<li><?php 
	/* tag "a" from line 117 */ ;
	if (null !== ($_tmp_1 = ($ctx->view->Html->url('/column/column/contact')))):  ;
		$_tmp_1 = ' href="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<a<?php echo $_tmp_1 ?>
>薬局情報掲載のお問い合わせ</a></li>
            <?php 	/* tag "li" from line 118 */; ?>
<li><?php 
	/* tag "a" from line 118 */ ;
	if (null !== ($_tmp_1 = ($ctx->view->Html->url('/rule')))):  ;
		$_tmp_1 = ' href="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<a<?php echo $_tmp_1 ?>
>利用規約</a></li>
            <?php 	/* tag "li" from line 119 */; ?>
<li><?php 
	/* tag "a" from line 119 */ ;
	if (null !== ($_tmp_1 = ($ctx->view->Html->url('/privacy')))):  ;
		$_tmp_1 = ' href="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<a<?php echo $_tmp_1 ?>
>個人情報について</a></li>
            <?php 	/* tag "li" from line 120 */; ?>
<li><?php 
	/* tag "a" from line 121 */ ;
	if (null !== ($_tmp_1 = ($ctx->view->Html->url('http://www.bm-sms.co.jp/')))):  ;
		$_tmp_1 = ' href="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<a target="_blank"<?php echo $_tmp_1 ?>
>運営会社</a></li>
            <?php 	/* tag "li" from line 122 */; ?>
<li><?php 
	/* tag "a" from line 123 */ ;
	if (null !== ($_tmp_1 = ($ctx->view->Html->url('http://cocoyaku.jp/')))):  ;
		$_tmp_1 = ' href="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<a target="_blank"<?php echo $_tmp_1 ?>
>ココヤク</a></li>
            </ul>
            <?php 	/* tag "p" from line 125 */; ?>
<p class="copy">
            Copyright &copy; SMS Co.,Ltd. All Rights Reserved.<?php 	/* tag "br" from line 126 */; ?>
<br/>
            掲載の記事・写真・イラストなど、すべてのコンテンツの無断複写・転載・公衆送信を禁じます。
            </p>
        </div>
    </div>
    <!-- /footer -->

</body>
</html><?php 
	/* end */ ;

}

?>
<?php /* 
*** DO NOT EDIT THIS FILE ***

Generated by PHPTAL from D:\prjs_yk\30_implementation\33_source\station-navi\app\View\Layouts\Pc\site_top.html (edit that file instead) */; ?>
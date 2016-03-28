<?php 
function tpl_556831c3_form_search__V9P2Aof_AZtzfbH3RFKRoA(PHPTAL $tpl, PHPTAL_Context $ctx) {
	$_thistpl = $tpl ;
	/* tag "documentElement" from line 1 */ ;
	/* tag "div" from line 1 */ ;
?>
<div id="accordion">
    <?php 	/* tag "div" from line 2 */; ?>
<div class="heading <?php echo phptal_escape($ctx->class, 'UTF-8') ?>
">薬局内容から絞り込む</div>
    <?php 
	/* tag "div" from line 4 */ ;
	if (null !== ($_tmp_1 = (!empty($ctx->class)? 'display: block;' : 'display: none'))):  ;
		$_tmp_1 = ' style="'.phptal_escape($_tmp_1, 'UTF-8').'"' ;
	else:  ;
		$_tmp_1 = '' ;
	endif ;
?>
<div class="box <?php echo phptal_escape($ctx->class, 'UTF-8') ?>
"<?php echo $_tmp_1 ?>
>
        <?php 	/* tag "form" from line 5 */; ?>
<form action="<?php echo phptal_escape($ctx->action, 'UTF-8') ?>
" name="search" method="get">
            <?php 	/* tag "ul" from line 6 */; ?>
<ul class="serviceList">
                <?php 	/* tag "li" from line 7 */; ?>
<li>
                    <?php 
	/* tag "input" from line 8 */ ;
	if (!empty($ctx->pal_care) ? true : false):  ;
		$_tmp_2 = ' checked="checked"' ;
	else:  ;
		$_tmp_2 = '' ;
	endif ;
?>
<input type="checkbox" name="pal_care" id="pal_care" value="1"<?php echo $_tmp_2 ?>
/>
                    <?php 	/* tag "label" from line 9 */; ?>
<label for="pal_care" class="checkbox">
                        <?php 
	/* tag "span" from line 10 */ ;
	echo phptal_tostring(nl2br($ctx->aryPalCareSearch['name'])) ;
?>

                    </label>
                </li>
                <?php 	/* tag "li" from line 13 */; ?>
<li>
                    <?php 
	/* tag "input" from line 14 */ ;
	if (!empty($ctx->aseptic_handle) ? true : false):  ;
		$_tmp_2 = ' checked="checked"' ;
	else:  ;
		$_tmp_2 = '' ;
	endif ;
?>
<input type="checkbox" name="aseptic_handle" id="aseptic_handle" value="1"<?php echo $_tmp_2 ?>
/>
                    <?php 	/* tag "label" from line 15 */; ?>
<label for="aseptic_handle" class="checkbox">
                        <?php 
	/* tag "span" from line 16 */ ;
	echo phptal_tostring(nl2br($ctx->aryAepticHandleSearch['name'])) ;
?>

                    </label>
                </li>
                <?php 	/* tag "li" from line 19 */; ?>
<li>
                    <?php 
	/* tag "input" from line 20 */ ;
	if (!empty($ctx->child_spt) ? true : false):  ;
		$_tmp_2 = ' checked="checked"' ;
	else:  ;
		$_tmp_2 = '' ;
	endif ;
?>
<input type="checkbox" name="child_spt" id="child_spt" value="1"<?php echo $_tmp_2 ?>
/>
                    <?php 	/* tag "label" from line 21 */; ?>
<label for="child_spt" class="checkbox">
                        <?php 
	/* tag "span" from line 22 */ ;
	echo phptal_tostring(nl2br($ctx->aryChildSptSearch['name'])) ;
?>

                    </label>
                </li>
                <?php 	/* tag "li" from line 25 */; ?>
<li>
                    <?php 
	/* tag "input" from line 26 */ ;
	if (!empty($ctx->hour_aday) ? true : false):  ;
		$_tmp_2 = ' checked="checked"' ;
	else:  ;
		$_tmp_2 = '' ;
	endif ;
?>
<input type="checkbox" name="24_hour_aday" id="24_hour_aday" value="1"<?php echo $_tmp_2 ?>
/>
                    <?php 	/* tag "label" from line 27 */; ?>
<label for="24_hour_aday" class="checkbox">
                        <?php 
	/* tag "span" from line 28 */ ;
	echo phptal_tostring(nl2br($ctx->ary24HourAdaySearch['name'])) ;
?>

                    </label>
                </li>
                <?php 	/* tag "li" from line 31 */; ?>
<li>
                    <?php 
	/* tag "input" from line 32 */ ;
	if (!empty($ctx->dementia_spt) ? true : false):  ;
		$_tmp_2 = ' checked="checked"' ;
	else:  ;
		$_tmp_2 = '' ;
	endif ;
?>
<input type="checkbox" name="dementia_spt" id="dementia_spt" value="1"<?php echo $_tmp_2 ?>
/>
                    <?php 	/* tag "label" from line 33 */; ?>
<label for="dementia_spt" class="checkbox">
                        <?php 
	/* tag "span" from line 34 */ ;
	echo phptal_tostring(nl2br($ctx->aryDementiaSptSearch['name'])) ;
?>

                    </label>
                </li>
                <?php 	/* tag "li" from line 37 */; ?>
<li>
                    <?php 
	/* tag "input" from line 38 */ ;
	if (!empty($ctx->drug_storage) ? true : false):  ;
		$_tmp_2 = ' checked="checked"' ;
	else:  ;
		$_tmp_2 = '' ;
	endif ;
?>
<input type="checkbox" name="drug_storage" id="drug_storage" value="1"<?php echo $_tmp_2 ?>
/>
                    <?php 	/* tag "label" from line 39 */; ?>
<label for="drug_storage" class="checkbox">
                        <?php 
	/* tag "span" from line 40 */ ;
	echo phptal_tostring(nl2br($ctx->aryDrugStorageSearch['name'])) ;
?>

                    </label>
                </li>
            </ul>
            <?php 	/* tag "div" from line 44 */; ?>
<div class="reset">
                <?php 	/* tag "input" from line 45 */; ?>
<input type="reset" value="条件をリセット"/>
            </div>
            <?php 	/* tag "div" from line 47 */; ?>
<div class="submit">
                <?php 	/* tag "input" from line 48 */; ?>
<input type="submit" value="この条件で検索する"/>
            </div>
        </form>
    </div>
</div>
<?php 	/* tag "script" from line 53 */; ?>
<script>
$("input[type='reset']").click(function(){
    $("input[type='checkbox']").attr('checked', false);
    return false;
});
</script><?php 
	/* end */ ;

}

?>
<?php /* 
*** DO NOT EDIT THIS FILE ***

Generated by PHPTAL from D:\prjs_yk\30_implementation\33_source\station-navi\app\View\Elements\form_search.html (edit that file instead) */; ?>
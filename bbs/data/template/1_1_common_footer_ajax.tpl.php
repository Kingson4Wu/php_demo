<? if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<?php $s = ob_get_contents();
ob_end_clean();
$s = preg_replace("/([\\x01-\\x08\\x0b-\\x0c\\x0e-\\x1f])+/", ' ', $s);
$s = str_replace(array(chr(0), ']]>'), array(' ', ']]&gt;'), $s); ?><?=$s?>
<? if(isset($prompts['newbietask']) && $prompts['newbietask'] && $_G['setting']['newbietasks']) { include template('forum/task_newbie_js'); } ?>
]]></root><?php exit; ?>
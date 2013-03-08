<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

?>

<div class="subcategories"><?php echo JText::_('COM_SEMINARMAN_SUBCATEGORIES'); ?></div>

<?php

$n = count($this->categories);
$i = 0;

?>

<div class="subcategorieslist">
<?php
foreach ($this->categories as $sub):
?>
		<strong><a href="<?php echo JRoute::_('index.php?view=category&cid=' . $sub->slug); ?>"><?php echo $this->escape($sub->title); ?></a></strong>
		(<?php echo $sub->assignedseminarmans != null ? $sub->assignedseminarmans : 0; ?>)

<?php
    $i++;
    if ($i != $n) echo ',';
endforeach;
?>
</div>
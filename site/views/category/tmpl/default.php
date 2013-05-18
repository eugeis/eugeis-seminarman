<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

?>
<div id="seminarman" class="seminarman">

<p class="buttons"><?php echo seminarman_html::favouritesbutton($this->params) . seminarman_html::mailbutton('category', $this->params, $this->category->slug); ?></p>

<?php if ($this->params->get('show_page_title', 1) && $this->params->get('page_title') != $this->category->title): ?>
    <h1 class="componentheading"><?php echo $this->params->get('page_title'); ?></h1>
<?php endif; ?>

<?php
if ($this->category->id > 0)
	echo $this->loadTemplate('category');

if (count($this->categories) && $this->category->id > 0)
    echo $this->loadTemplate('subcategories');
?>

<script type="text/javascript">
function tableOrdering( order, dir, task ) {

	if (task == 'il') {
		var form = document.getElementById('adminForm2');
		form.filter_order2.value = order;
		form.filter_order_Dir2.value = dir;
		document.getElementById('adminForm2').submit( task );
	}
	else {
		var form = document.getElementById('adminForm');
		form.filter_order.value = order;
		form.filter_order_Dir.value	= dir;
		document.getElementById('adminForm').submit( task );
	}
}
</script>

<?php
jimport('joomla.html.pane');

//$tabs = &JPane::getInstance('tabs', array('startOffset'=>0));
//echo $tabs->startPane('mytabs');
//echo $tabs->startPanel(JText::_('COM_SEMINARMAN_DATES'), 0);
//echo '<div>' . $this->loadTemplate('courses') . '</div>';
echo $this->loadTemplate('courses');
// echo $tabs->endPanel();
// if ($this->params->get('enable_salesprospects', 0) == 1)
// {
// 	echo $tabs->startPanel(JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS'), 0);
// 	echo '<div>' . $this->loadTemplate('templates') . '</div>';
// 	echo $tabs->endPanel();
// }
// echo $tabs->endPane();
?>
</div>
<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

?>

<div class="floattext">
    
	<h2 class="seminarman cat<?php echo $this->category->id; ?>"><?php echo $this->escape($this->category->title); ?></h2>

<?php

if (!empty($this->category->image)):
?>
	<div class="catimg">
<?php
// echo JHTML::_('image.site', $this->category->image, 'images/', null, null,
//		$this->escape($this->category->title));
  echo JHTML::_('image', 'images/' . $this->category->image, $this->escape($this->category->title));
?>
	</div>
<?php
endif;
?>
	
	<div class="catdescription"><?php echo $this->category->text; ?></div>
</div>
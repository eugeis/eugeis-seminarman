<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 2 of the License, or
* any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/


defined('_JEXEC') or die('Restricted access');

class seminarman_html
{
    static function printbutton($print_link, $params)
    {
        if ($params->get('show_print_icon'))
        {

            JHTML::_('behavior.tooltip');

            $status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

            if ($params->get('show_icons'))
            {
                // $image = JHTML::_('image.site', 'printButton.png', 'media/system/images/', null, null,
                //    JText::_('COM_SEMINARMAN_PRINT'));
            	$image = JHTML::_('image', 'media/system/images/printButton.png', JText::_('COM_SEMINARMAN_PRINT'));
            } else
            {
                $image = JText::_('COM_SEMINARMAN_ICON_SEPARATOR') . '&nbsp;' . JText::_('COM_SEMINARMAN_PRINT') . '&nbsp;' . JText::
                    _('COM_SEMINARMAN_ICON_SEPARATOR');
            }

            if (JRequest::getInt('pop'))
            {

                $output = '<a href="#" onclick="window.print();return false;">' . $image .
                    '</a>';
            } else
            {

                $overlib = JText::_('COM_SEMINARMAN_PRINT_TIP');
                $text = JText::_('COM_SEMINARMAN_PRINT');

                $output = '<a href="' . JRoute::_($print_link) .
                    '" class="editlinktip hasTip" onclick="window.open(this.href,\'win2\',\'' . $status .
                    '\'); return false;" title="' . $text . '::' . $overlib . '">' . $image . '</a>';
            }

            return $output;
        }
        return;
    }

    static function mailbutton($view, $params, $slug = null, $courseslug = null)
    {
        if ($params->get('show_email_icon'))
        {

            JHTML::_('behavior.tooltip');
            $uri = JURI::getInstance();
            $base = $uri->toString(array('scheme', 'host', 'port'));

            if ($view == 'category')
            {
                $link = $base . JRoute::_('index.php?option=com_seminarman&view=' . $view . '&cid=' . $slug, false);
            } elseif ($view == 'courses')
            {
                $link = $base . JRoute::_('index.php?option=com_seminarman&view=' . $view . '&cid=' . $slug . '&id=' .
                    $courseslug, false);
            } elseif ($view == 'tags')
            {
                $link = $base . JRoute::_('index.php?option=com_seminarman&view=' . $view . '&id=' . $slug, false);
            } else
            {
                $link = $base . JRoute::_('index.php?option=com_seminarman&view=' . $view, false);
            }
            $url = 'index.php?option=com_mailto&tmpl=component&link=' . base64_encode($link);
            $status = 'width=400,height=300,menubar=yes,resizable=yes';

            if ($params->get('show_icons'))
            {
                // $image = JHTML::_('image.site', 'emailButton.png', 'media/system/images/', null, null,
                //    JText::_('COM_SEMINARMAN_EMAIL'));
            	$image = JHTML::_('image', 'media/system/images/emailButton.png', JText::_('COM_SEMINARMAN_EMAIL'));
            } else
            {
                $image = JText::_('COM_SEMINARMAN_ICON_SEPARATOR') . '&nbsp;' . JText::_('COM_SEMINARMAN_EMAIL'). '&nbsp;' . JText::
                    _('COM_SEMINARMAN_ICON_SEPARATOR');
            }

            $overlib = JText::_('COM_SEMINARMAN_EMAIL_TIP');
            $text = JText::_('COM_SEMINARMAN_EMAIL');

            $output = '<a href="' . JRoute::_($url) .
                '" class="editlinktip hasTip" onclick="window.open(this.href,\'win2\',\'' . $status .
                '\'); return false;" title="' . $text . '::' . $overlib . '">' . $image . '</a>';

            return $output;
        }
        return;
    }


    function saveContentPrep(&$row)
    {

        $text = JRequest::getVar('text', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $certificate_text = JRequest::getVar('certificate_text', '', 'post', 'string', JREQUEST_ALLOWRAW);

        $text = str_replace('<br>', '<br />', $text);
        $certificate_text = str_replace('<br>', '<br />', $certificate_text);

        $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
        $tagPos = preg_match($pattern, $text);

        if ($tagPos == 0)
        {
            $row->introtext = $text;
        } else
        {
            list($row->introtext, $row->fulltext) = preg_split($pattern, $text, 2);
        }
        $row->certificate_text = $certificate_text;

        jimport('joomla.application.component.helper');
        $config = JComponentHelper::getParams('com_content');
        $user = JFactory::getUser();
        $gid = $user->get('gid');

        $filterGroups = $config->get('filter_groups');

        if (is_array($filterGroups) && in_array($gid, $filterGroups))
        {
            $filterType = $config->get('filter_type');
            $filterTags = preg_split('#[,\s]+#', trim($config->get('filter_tags')));
            $filterAttrs = preg_split('#[,\s]+#', trim($config->get('filter_attritbutes')));
            switch ($filterType)
            {
                case 'NH':
                    $filter = new JFilterInput();
                    break;
                case 'WL':
                    $filter = new JFilterInput($filterTags, $filterAttrs, 0, 0, 0);

                    break;
                case 'BL':
                default:
                    $filter = new JFilterInput($filterTags, $filterAttrs, 1, 1);
                    break;
            }
            $row->introtext = $filter->clean($row->introtext);
            $row->fulltext = $filter->clean($row->fulltext);
            $row->certificate_text = $filter->clean($row->certificate_text);
        } elseif (empty($filterGroups) && $gid != '25')
        {
            $filter = new JFilterInput(array(), array(), 1, 1);
            $row->introtext = $filter->clean($row->introtext);
            $row->fulltext = $filter->clean($row->fulltext);
            $row->certificate_text = $filter->clean($row->certificate_text);
        }

        return true;
    }

    static function favoure($course, $params, $favoured)
    {
        $user = JFactory::getUser();

        JHTML::_('behavior.tooltip');

        if ($user->id && $favoured)
        {
            if ($params->get('show_icons'))
            {
                // $image = JHTML::_('image.site', 'heart_delete.png',
                //    'components/com_seminarman/assets/images/', null, null, JText::_('COM_SEMINARMAN_REMOVE_FAVOURITE'));
            	$image = JHTML::_('image', 'components/com_seminarman/assets/images/heart_delete.png', JText::_('COM_SEMINARMAN_REMOVE_FAVOURITE'));
            } else
            {
                $image = JText::_('COM_SEMINARMAN_ICON_SEPARATOR') . '&nbsp;' . JText::_('COM_SEMINARMAN_REMOVE_FAVOURITE') .
                    '&nbsp;' . JText::_('COM_SEMINARMAN_ICON_SEPARATOR');
            }
            $overlib = JText::_('COM_SEMINARMAN_REMOVE_FAVOURITE_TIP');
            $text = JText::_('COM_SEMINARMAN_REMOVE_FAVOURITE');

            $link = 'index.php?option=com_seminarman&task=removefavourite&cid=' . $course->categoryslug . '&id=' . $course->
                slug;
            $output = '<a href="' . JRoute::_($link) .
                '" class="editlinktip hasTip" title="' . $text . '::' . $overlib . '">' . $image .
                '</a>';
        } elseif ($user->id)
        {
            if ($params->get('show_icons'))
            {
                // $image = JHTML::_('image.site', 'heart_add.png',
                //    'components/com_seminarman/assets/images/', null, null, JText::_('COM_SEMINARMAN_FAVOURE'));
            	$image = JHTML::_('image', 'components/com_seminarman/assets/images/heart_add.png', JText::_('COM_SEMINARMAN_FAVOURITE'));
            } else
            {
                $image = JText::_('COM_SEMINARMAN_ICON_SEPARATOR') . '&nbsp;' . JText::_('COM_SEMINARMAN_FAVOURE') . '&nbsp;' .
                    JText::_('COM_SEMINARMAN_ICON_SEPARATOR');
            }
            $overlib = JText::_('COM_SEMINARMAN_FAVOURE_TIP');
            $text = JText::_('COM_SEMINARMAN_FAVOURE');

            $link = 'index.php?option=com_seminarman&task=addfavourite&cid=' . $course->categoryslug . '&id=' . $course->
                slug;
            $output = '<a href="' . JRoute::_($link) .
                '" class="editlinktip hasTip" title="' . $text . '::' . $overlib . '">' . $image .
                '</a>';
        } else
        {

            $overlib = JText::_('COM_SEMINARMAN_FAVOURE_LOGIN_TIP');
            $text = JText::_('COM_SEMINARMAN_FAVOURE');

            if ($params->get('show_icons'))
            {
                // $image = JHTML::_('image.site', 'heart_login.png',
                //    'components/com_seminarman/assets/images/', null, null, $text,
                //    'class="editlinktip hasTip" title="' . $text . '::' . $overlib . '"');
            	$image = JHTML::_('image', 'components/com_seminarman/assets/images/heart_login.png', $text, 'class="editlinktip hasTip" title="' . $text . '::' . $overlib . '"');
            } else
            {
                $image = JText::_('COM_SEMINARMAN_ICON_SEPARATOR') . '&nbsp;' .
                    '<span class="editlinktip hasTip" title="' . $text . '::' . $overlib . '">' . $text .
                    '</span>';
            }

            $output = $image;
        }

        return $output;
    }

    static function favouritesbutton($params)
    {
        if ($params->get('show_favourites'))
        {
            JHTML::_('behavior.tooltip');

            if ($params->get('show_icons'))
            {
                // $image = JHTML::_('image.site', 'heart.png',
                //    'components/com_seminarman/assets/images/', null, null, JText::_('COM_SEMINARMAN_FAVOURITES'));
            	$image = JHTML::_('image', 'components/com_seminarman/assets/images/heart.png', JText::_('COM_SEMINARMAN_FAVOURITES'));
            } else
            {
                $image = JText::_('COM_SEMINARMAN_ICON_SEPARATOR') . '&nbsp;' . JText::_('COM_SEMINARMAN_FAVOURITES') . '&nbsp;' .
                    JText::_('COM_SEMINARMAN_ICON_SEPARATOR');
            }
            $overlib = JText::_('COM_SEMINARMAN_FAVOURITES_TIP');
            $text = JText::_('COM_SEMINARMAN_FAVOURITES');

            $link = 'index.php?option=com_seminarman&view=favourites';
            $output = '<a href="' . JRoute::_($link) .
                '" class="editlinktip hasTip" title="' . $text . '::' . $overlib . '">' . $image .
                '</a>';

            return $output;
        }
        return;
    }

    function removefavbutton(&$params, $course)
    {
        $user = JFactory::getUser();
        JHTML::_('behavior.tooltip');

        if ($user->id)
        {
            if ($params->get('show_favourites'))
            {
                JHTML::_('behavior.tooltip');

                if ($params->get('show_icons'))
                {
                    // $image = JHTML::_('image.site', 'delete.png',
                    //    'components/com_seminarman/assets/images/', null, null, JText::_('COM_SEMINARMAN_REMOVE_FAVOURITE'));
                	$image = JHTML::_('image', 'components/com_seminarman/assets/images/delete.png', JText::_('COM_SEMINARMAN_REMOVE_FAVOURITE'));
                } else
                {
                    $image = JText::_('COM_SEMINARMAN_ICON_SEPARATOR') . '&nbsp;' . JText::_('COM_SEMINARMAN_FAVOURITES') . '&nbsp;' .
                        JText::_('COM_SEMINARMAN_ICON_SEPARATOR');
                }
                $overlib = JText::_('COM_SEMINARMAN_REMOVE_FAVOURITE_TIP');
                $text = JText::_('COM_SEMINARMAN_REMOVE_FAVOURITE');

                $link = 'index.php?option=com_seminarman&task=removefavourite&id=' . $course->id;
                $output = '<a href="' . JRoute::_($link) .
                    '" class="editlinktip hasTip" title="' . $text . '::' . $overlib . '">' . $image .
                    '</a>';

                return $output;
            }
        }
        return;
    }
}

class seminarman_upload
{
    static function check($file, &$err)
    {
        $params = JComponentHelper::getParams('com_seminarman');

        if (empty($file['name']))
        {
            $err = 'Please input a file for upload';
            return false;
        }

        jimport('joomla.filesystem.file');
        if ($file['name'] !== JFile::makesafe($file['name']))
        {
            $err = 'COM_SEMINARMAN_WARNFILENAME';
            return false;
        }

        $format = strtolower(JFile::getExt($file['name']));

        $allowable = explode(',', $params->get('upload_extensions'));
        $ignored = explode(',', $params->get('ignore_extensions'));
        if (!in_array($format, $allowable) && !in_array($format, $ignored))
        {
            $err = JText::_('COM_SEMINARMAN_WARNFILETYPE');
            return false;
        }

        $maxSize = (int)$params->get('upload_maxsize', 0);
        if ($maxSize > 0 && (int)$file['size'] > $maxSize)
        {
            $err = 'COM_SEMINARMAN_WARNFILETOOLARGE';
            return false;
        }

        $imginfo = null;

        $images = explode(',', $params->get('image_extensions'));

        if ($params->get('restrict_uploads', 1))
        {

            if (in_array($format, $images))
            {

                if (($imginfo = getimagesize($file['tmp_name'])) === false)
                {
                    $err = 'COM_SEMINARMAN_WARNINVALIDIMG';
                    return false;
                }

            } else
                if (!in_array($format, $ignored))
                {

                    $allowed_mime = explode(',', $params->get('upload_mime'));
                    $illegal_mime = explode(',', $params->get('upload_mime_illegal'));

                    if (function_exists('finfo_open') && $params->get('check_mime', 1))
                    {

                        $finfo = finfo_open(FILEINFO_MIME);
                        $type = finfo_file($finfo, $file['tmp_name']);
                        if (strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime))
                        {
                            $err = 'COM_SEMINARMAN_WARNINVALIDMIME';
                            return false;
                        }
                        finfo_close($finfo);

                    } else
                        if (function_exists('mime_content_type') && $params->get('check_mime', 1))
                        {

                            $type = mime_content_type($file['tmp_name']);

                            if (strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime))
                            {
                                $err = 'COM_SEMINARMAN_WARNINVALIDMIME';
                                return false;
                            }

                        }
                }
        }
        $xss_check = JFile::read($file['tmp_name'], false, 256);
        $html_tags = array('abbr', 'acronym', 'address', 'applet', 'area', 'audioscope',
            'base', 'basefont', 'bdo', 'bgsound', 'big', 'blackface', 'blink', 'blockquote',
            'body', 'bq', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col',
            'colgroup', 'comment', 'custom', 'dd', 'del', 'dfn', 'dir', 'div', 'dl', 'dt',
            'em', 'embed', 'fieldset', 'fn', 'font', 'form', 'frame', 'frameset', 'h1', 'h2',
            'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html', 'iframe', 'ilayer', 'img', 'input',
            'ins', 'isindex', 'keygen', 'kbd', 'label', 'layer', 'legend', 'li', 'limittext',
            'link', 'listing', 'map', 'marquee', 'menu', 'meta', 'multicol', 'nobr',
            'noembed', 'noframes', 'noscript', 'nosmartquotes', 'object', 'ol', 'optgroup',
            'option', 'param', 'plaintext', 'pre', 'rt', 'ruby', 's', 'samp', 'script',
            'select', 'server', 'shadow', 'sidebar', 'small', 'spacer', 'span', 'strike',
            'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot',
            'th', 'thead', 'title', 'tr', 'tt', 'ul', 'var', 'wbr', 'xml', 'xmp', '!DOCTYPE',
            '!--');
        foreach ($html_tags as $tag)
        {

            if (stristr($xss_check, '<' . $tag . ' ') || stristr($xss_check, '<' . $tag .
                '>'))
            {
                $err = 'WARNIEXSS';
                return false;
            }
        }

        return true;
    }

    static function sanitize($base_Dir, $filename)
    {
        jimport('joomla.filesystem.file');

        $filename = preg_replace("/^[.]*/", '', $filename);
        $filename = preg_replace("/[.]*$/", '', $filename);


        $lastdotpos = strrpos($filename, '.');

        $chars = '[^0-9a-zA-Z()_-]';
        $filename = strtolower(preg_replace("/$chars/", '_', $filename));

        $beforedot = substr($filename, 0, $lastdotpos);
        $afterdot = substr($filename, $lastdotpos + 1);


        $now = time();

        while (JFile::exists($base_Dir . $beforedot . '_' . $now . '.' . $afterdot))
        {
            $now++;
        }

        $filename = $beforedot . '_' . $now . '.' . $afterdot;

        return $filename;
    }
}

class seminarman_images
{
    static function BuildIcons($rows)
    {
        jimport('joomla.filesystem.file');

        $basePath = COM_SEMINARMAN_FILEPATH;

        for ($i = 0, $n = count($rows); $i < $n; $i++)
        {

            if (is_file($basePath . DS . $rows[$i]->filename))
            {
                $path = str_replace(DS, '/', JPath::clean($basePath . DS . $rows[$i]->filename));

                $size = filesize($path);

                if ($size < 1024)
                {
                    $rows[$i]->size = $size . ' bytes';
                } else
                {
                    if ($size >= 1024 && $size < 1024 * 1024)
                    {
                        $rows[$i]->size = sprintf('%01.2f', $size / 1024.0) . ' Kb';
                    } else
                    {
                        $rows[$i]->size = sprintf('%01.2f', $size / (1024.0 * 1024)) . ' Mb';
                    }
                }

                $ext = strtolower(JFile::getExt($rows[$i]->filename));
                switch ($ext)
                {

                    case 'jpg':
                    case 'png':
                    case 'gif':
                    case 'xcf':
                    case 'odg':
                    case 'bmp':
                    case 'jpeg':
                        $rows[$i]->icon = 'components/com_seminarman/assets/images/mime-icon-16/image.png';
                        break;

                    default:
                        $icon = JPATH_SITE . DS . 'components' . DS . 'com_seminarman' . DS . 'assets' . DS .
                            'images' . DS . 'mime-icon-16' . DS . $ext . '.png';
                        if (file_exists($icon))
                        {
                            $rows[$i]->icon = 'components/com_seminarman/assets/images/mime-icon-16/' . $ext .
                                '.png';
                        } else
                        {
                            $rows[$i]->icon = 'components/com_seminarman/assets/images/mime-icon-16/unknown.png';
                        }
                        break;
                }
            }
            else
            {
            	$rows[$i]->icon = 'components/com_seminarman/assets/images/mime-icon-16/unknown.png';
            	$rows[$i]->size = '';
            }

        }

        return $rows;
    }
}

class CMFactory
{
/**
 * Return the view object, responsible for all db manipulation. Singleton
 *
 * @param	string		type	libraries/helper
 * @param	string		name 	class prefix
 */
static function load( $type, $name )
{
	//include_once(JPATH_ROOT.DS.'components'.DS.'com_seminarman'.DS.'libraries'.DS.'error.php');

	include_once(JPATH_ROOT.DS.'components'.DS.'com_seminarman'.DS.$type.DS. strtolower($name) .'.php');

	// If it is a library, we call the object and call the 'load' method
	if( $type == 'libraries' )
	{
		$classname = 'C'.$name ;
		if(	class_exists($classname) ) {
			// @todo
		}
	}
}
}

/**
 * Templating system for seminarman
 */
class CMTemplate {
	var $vars; /// Holds all the template variables

	function escape( $text )
	{
		CMFactory::load( 'helpers' , 'string' );

		return cEscape( $text );
	}
	function renderModules($position, $attribs = array())
	{
		jimport( 'joomla.application.module.helper' );

		$modules 	= JModuleHelper::getModules( $position );
		$modulehtml = '';

		foreach($modules as $module)
		{
			// If style attributes are not given or set, we enforce it to use the xhtml style
			// so the title will display correctly.
			if( !isset($attribs['style'] ) )
				$attribs['style']	= 'xhtml';

			$modulehtml .= JModuleHelper::renderModule($module, $attribs);
		}

		echo $modulehtml;
	}

	/**
	 * Constructor
	 *
	 * @param $file string the file name you want to load
	 */
	function CMTemplate($file = null) {
		$this->file = $file;
		@ini_set('short_open_tag', 'On');
		$this->set('dummy', true);
	}


	/**
	 * Get the template full path name, given a templaet name code
	 */
	function _getTemplateFullpath($file)
	{
		$cfg	= CMFactory::getConfig();
		if(!JString::strpos($file, '.php'))
		{
			$filename	= $file;

			// Test if template override exists in joomla's template folder
			$mainframe		= JFactory::getApplication();

			$overridePath	= JPATH_ROOT . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'html';
			$overrideExists	= JFolder::exists( $overridePath . DS . 'com_seminarman' );
			$template		= SEMINARMAN_COM_PATH . DS . 'templates' . DS . $cfg->get('template') . DS . $filename . '.php';

			// Test override path first
			if( JFile::exists( $overridePath . DS . 'com_seminarman' . DS . $filename . '.php') )
			{
				// Load the override template.
				$file	= $overridePath . DS . 'com_seminarman' . DS . $filename . '.php';
			}
			else if( JFile::exists( $template ) && !$overrideExists )
			{
				// If override fails try the template set in config
				$file	= $template;
			}
			else
			{
				// We assume to use the default template
				$file	= SEMINARMAN_COM_PATH . DS . 'templates' . DS . 'default' . DS . $filename . '.php';
			}
		}

		return $file;
	}

	/**
	 * Set a template variable.
	 */
	function set($name, $value) {
		$this->vars[$name] = $value; //is_object($value) ? $value->fetch() : $value;
	}

	/**
	 * Set a template variable by reference
	 */
	function setRef($name, &$value) {
		$this->vars[$name] =& $value; //is_object($value) ? $value->fetch() : $value;
	}

	function addStylesheet( $file )
	{
		$mainframe	= JFactory::getApplication();
		$cfg		= CMFactory::getConfig();

		if(!JString::strpos($file, '.css'))
		{
			$filename	= $file;

			jimport( 'joomla.filesystem.file' );
			jimport( 'joomla.filesystem.folder' );

			// Test if template override exists in joomla's template folder
			$overridePath	= JPATH_ROOT . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'html';
			$overrideExists	= JFolder::exists( $overridePath . DS . 'com_community' );
			$template		= SEMINARMAN_COM_PATH . DS . 'templates' . DS . $cfg->get('template') . DS . 'css' . DS . $filename . '.css';

			// Test override path first
			if( JFile::exists( $overridePath . DS . 'com_community' . DS . 'css' . DS . $filename . '.css') )
			{
				// Load the override template.
				$file	= '/templates/' . $mainframe->getTemplate() . '/html/com_community/css/' . $filename . '.css';
			}
			else if( JFile::exists( $template ) && !$overrideExists )
			{
				// If override fails try the template set in config
				$file	=  '/components/com_community/templates/' . $cfg->get('template') . '/css/' . $filename . '.css';
			}
			else
			{
				// We assume to use the default template
				$file	= '/components/com_community/templates/default/css/' . $filename . '.css';
			}
		}

		CAssets::attach( $file , 'css' , rtrim( JURI::root() , '/' ) );
	}

	/***
	 * Allow a template to include other template and inherit all the variable
	 */
	function load($file)
	{
		if($this->vars)
			extract($this->vars, EXTR_REFS);

		$file = $this->_getTemplateFullpath($file);
		include($file);
		return $this;
	}


	/**
	 * Open, parse, and return the template file.
	 *
	 * @param $file string the template file name
	 */
	function fetch($file = null)
	{

		if( JRequest::getVar('format') == 'iphone' )
		{
			$file	.= '.iphone';
		}

		$file = $this->_getTemplateFullpath( $file );

		if(!$file) $file = $this->file;

		if((JRequest::getVar('format') == 'iphone') && (!JFile::exists($file)))
		{
			//if we detected the format was iphone and the template file was not there, return empty content.
			return '';
		}

		// @rule: always add seminarman config object in the template scope so we don't really need
		// to always set it.
		if( !isset( $this->vars['config'] ) && empty($this->vars['config']) )
		{
			$this->vars['config']	= CMFactory::getConfig();
		}

		if($this->vars)
			extract($this->vars, EXTR_REFS);          // Extract the vars to local namespace

		ob_start();                    // Start output buffering
		require($file);                // Include the file
		$contents = ob_get_contents(); // Get the contents of the buffer
		ob_end_clean();                // End buffering and discard
		return $contents;              // Return the contents
	}

	function object_to_array($obj) {
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
		$arr = array();
		foreach ($_arr as $key => $val) {
			$val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
			$arr[$key] = $val;
		}
		return $arr;
	}
}

//
// class CCachedTemplate extends CMTemplate {
//     var $cache_id;
//     var $expire;
//     var $cached;
//     var $file;
//
//     /**
//      * Constructor.
//      *
//      * @param $cache_id string unique cache identifier
//      * @param $expire int number of seconds the cache will live
//      */
//     function CCachedTemplate($cache_id = "", $cache_timeout = 10000) {
//         $this->CMTemplate();
//         $this->cache_id = AZ_CACHE_PATH . "/cache__". md5($cache_id);
//         $this->cached = false;
//         $this->expire = $cache_timeout;
//     }
//
//     /**
//      * Test to see whether the currently loaded cache_id has a valid
//      * corrosponding cache file.
//      */
//     function is_cached() {
//     	//return false;
//         if($this->cached) return true;
//
//         // Passed a cache_id?
//         if(!$this->cache_id) return false;
//
//         // Cache file exists?
//         if(!file_exists($this->cache_id)) return false;
//
//         // Can get the time of the file?
//         if(!($mtime = filemtime($this->cache_id))) return false;
//
//         // Cache expired?
//         // Implemented as 'never-expires' cache, so, the data need to change
//         // for the cache to be modified
//         if(($mtime + $this->expire) < time()) {
//             @unlink($this->cache_id);
//             return false;
//         }
//
//         else {
//             /**
//              * Cache the results of this is_cached() call.  Why?  So
//              * we don't have to double the overhead for each template.
//              * If we didn't cache, it would be hitting the file system
//              * twice as much (file_exists() & filemtime() [twice each]).
//              */
//             $this->cached = true;
//             return true;
//         }
//     }
//
//     /**
//      * This function returns a cached copy of a template (if it exists),
//      * otherwise, it parses it as normal and caches the content.
//      *
//      * @param $file string the template file
//      */
//     function fetch_cache($file, $processFunc = null) {
//     	// Get the configuration object.
// 		$config	= CMFactory::getConfig();
//
//     	$contents	= "";
// 		$file = SEMINARMAN_COM_PATH .DS. 'templates'.DS.$config->get('template').DS.$file . '.php';
//
//         if($this->is_cached()) {
//             $fp = @fopen($this->cache_id, 'r');
//             if($fp){
//             	$filesize = filesize($this->cache_id);
//             	if($filesize > 0){
//             		$contents = fread($fp, $filesize);
//             	}
//             	fclose($fp);
//             } else {
//             	$contents = $this->fetch($file);
// 			}
//         }
//         else {
//             $contents = $this->fetch($file);
//
//             // Check if caller wants to process contents with another function
// 			if($processFunc)
//                 $contents = $processFunc($contents);
//
// 			if(!empty($contents)){
//
// 	            // Write the cache, only if there is some data
// 	            if($fp = @fopen($this->cache_id, 'w')) {
// 	                fwrite($fp, $contents);
// 	                fclose($fp);
// 	            }
// 	            else {
// 	                //die('Unable to write cache.');
// 	            }
//             }
//
//
//         }
//
//          return $contents;
//     }
// }

?>

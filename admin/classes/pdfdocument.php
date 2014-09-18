<?php
/**
* @Copyright Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
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

defined( '_JEXEC' ) or die( 'Restricted access' );

if (!class_exists('TCPDF')) {
   require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_seminarman'.DS.'classes'.DS.'tcpdf'.DS.'config'.DS.'lang'.DS.'ger.php';
   require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_seminarman'.DS.'classes'.DS.'tcpdf'.DS.'tcpdf.php';
}
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_seminarman'.DS.'classes'.DS.'fpdi'.DS.'fpdi.php';
// require_once JPATH_ROOT.DS.'libraries'.DS.'tcpdf'.DS.'config'.DS.'lang'.DS.'ger.php';
// require_once JPATH_ROOT.DS.'libraries'.DS.'tcpdf'.DS.'tcpdf.php';
// require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_seminarman'.DS.'classes'.DS.'fpdi'.DS.'fpdi.php';

class PdfDocument extends FPDI
{
	public function __construct($template)
	{
		global $l;
		parent::__construct($template->orientation,'mm', $template->paperformat);
		
		$this->SetCreator(PDF_CREATOR);
		$this->setPrintHeader(false);
		$this->setPrintFooter(false);
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$this->setMargins($template->margin_left, $template->margin_top, $template->margin_right);
		$this->setPageOrientation($template->orientation, true, $template->margin_bottom);
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$this->setLanguageArray($l);
		$this->SetFont('FreeSans', '', 10);
		$this->AddPage();
		$this->lastPage();
		
		
		if (!empty($template->srcpdf) && is_file(COM_SEMINARMAN_FILEPATH.DS.$template->srcpdf))
		{
			$this->setSourceFile(COM_SEMINARMAN_FILEPATH.DS.$template->srcpdf);
			$tplidx = $this->importPage(1);
			$this->useTemplate($tplidx);
			$this->setPageMark();
		}
	}
	
	public function addHTMLBox($html)
	{
		$margins = $this->getMargins();
		$this->writeHTMLCell(0, 0, $margins['left'], $margins['top'], $html);
	}
	
	protected function replace($text, $data)
	{
		foreach ($data as $field => $value) {
			if ($field == 'TITLE' && !empty($value))
				$value .= ' ';
			$text = str_replace('{'. $field .'}', $value, $text);
		}
		return $text;
	}
}

class PdfInvoice extends PdfDocument
{
	private $_file = '';
	
	public function __construct($template, $data)
	{
		parent::__construct($template);
		
		$text = $this->replace($template->html, $data);
		$this->addHTMLBox($text);
	}

	public function store($name)
	{
		if (!empty($this->_file)) return;
		$this->_file = $name;
		$this->Output($name, 'F');
	}
	
	public function getFile()
	{
		return $this->_file;
	}
}

class PdfAttList extends PdfDocument
{
	private $_file = '';
	
	public function __construct($template, $data, $attendees)
	{
		parent::__construct($template);
		
		include_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_seminarman'.DS.'classes'.DS.'simple_html_dom.php';
		
		$html = str_get_html($template->html);
		
		$loopElem = $html->find('[class="{LOOP}"]');
		if (count($loopElem) > 0) {
			$loopElem = $loopElem[0];
			$loopElem->removeAttribute('class');
			
			$loopHtml = $loopElem->outertext;
			$loopElem->outertext = '';
			foreach ($attendees as $record) {
				$loopElem->outertext .= $this->replace($loopHtml, array_merge($data, $record));
			}
			$html = $html->save();
		} else {
			$html = $template->html;
		}
		
		$html = $this->replace($html, $data);	
		
		$this->addHTMLBox($html);
	}
	
	public function store($name)
	{
		if (!empty($this->_file)) return;
		$this->_file = $name;
		$this->Output($name, 'F');
	}
	
	public function getFile()
	{
		return $this->_file;
	}
}

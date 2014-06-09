<?php

// no direct access
defined('_JEXEC') or die('Restricted access');
/*******************************************************************************
 *                      PHP Paypal IPN Integration Class
*******************************************************************************
*      Author:     Micah Carrick
*      Email:      email@micahcarrick.com
*      Website:    http://www.micahcarrick.com
*
*      File:       paypal.class.php
*      Version:    1.3.0
*      Copyright:  (c) 2005 - Micah Carrick
*                  You are free to use, distribute, and modify this software
*                  under the terms of the GNU General Public License.
*
*******************************************************************************
*  VERION HISTORY:
*      v1.3.0 [10.10.2005] - Fixed it so that single quotes are handled the
*                            right way rather than simple stripping them.  This
*                            was needed because the user could still put in
*                            quotes.
*
*      v1.2.1 [06.05.2005] - Fixed typo from previous fix :)
*
*      v1.2.0 [05.31.2005] - Added the optional ability to remove all quotes
*                            from the paypal posts.  The IPN will come back
*                            invalid sometimes when quotes are used in certian
*                            fields.
*
*      v1.1.0 [05.15.2005] - Revised the form output in the submit_paypal_post
*                            method to allow non-javascript capable browsers
*                            to provide a means of manual form submission.
*
*      v1.0.0 [04.16.2005] - Initial Version
*
*******************************************************************************
*  DESCRIPTION:
*
*      NOTE: See www.micahcarrick.com for the most recent version of this class
*            along with any applicable sample files and other documentaion.
*
*******************************************************************************
*/

class paypal_class {

	var $last_error;                 // holds the last error encountered

	var $ipn_log;                    // bool: log IPN results to text file?

	var $ipn_log_file;               // filename of the IPN log
	var $ipn_response;               // holds the IPN response from paypal
	var $ipn_data = array();         // array contains the POST values for IPN

	var $fields = array();           // array holds the fields to submit to paypal


	function paypal_class() {
			
		// initialization constructor.  Called when class is created.

		$this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
		//$this->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url

		$this->last_error = '';

		$this->ipn_log_file = 'ipn_results.log';
		$this->ipn_log = false;
		$this->ipn_response = '';

		// populate $fields array with a few default values.  See the paypal
		// documentation for a list of fields and their data types. These defaul
		// values can be overwritten by the calling script.

		$this->add_field('rm','2');           // Return method = POST
		$this->add_field('cmd','_xclick');

	}

	function add_field($field, $value) {

		// adds a key=>value pair to the fields array, which is what will be
		// sent to paypal as POST variables.  If the value is already in the
		// array, it will be overwritten.
		$this->fields["$field"] = $value;
	}


	function validate_ipn() {

		// parse the paypal URL
		$url_parsed=parse_url($this->paypal_url);

		// generate the post string from the _POST vars aswell as load the
		// _POST vars into an arry so we can play with them from the calling
		// script.
		$post_string = '';
		foreach ($_POST as $field=>$value) {
			$this->ipn_data["$field"] = $value;
			$post_string .= $field.'='.urlencode(stripslashes($value)).'&';
		}
		$post_string.="cmd=_notify-validate"; // append ipn command

		// open the connection to paypal
		$fp = fsockopen($url_parsed['host'],"80",$err_num,$err_str,30);
		if(!$fp) {

			// could not open the connection.  If loggin is on, the error message
			// will be in the log.
			$this->last_error = "fsockopen error no. $errnum: $errstr";
			$this->log_ipn_results(false);
			return false;

		} else {

			// Post the data back to paypal
			fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n");
			fputs($fp, "Host: $url_parsed[host]\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ".strlen($post_string)."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $post_string . "\r\n\r\n");

			// loop through the response from the server and append to variable
			while(!feof($fp)) {
				$this->ipn_response .= fgets($fp, 1024);
			}

			fclose($fp); // close connection

		}

		if (eregi("VERIFIED",$this->ipn_response)) {

			// Valid IPN transaction.
			$this->log_ipn_results(true);

			//do some backend updates

			return true;

		} else {

			// Invalid IPN transaction.  Check the log for details.
			$this->last_error = 'IPN Validation Failed.';
			$this->log_ipn_results(false);
			return false;

		}

	}

	function log_ipn_results($success) {
			
		if (!$this->ipn_log) return;  // is logging turned off?

		// Timestamp
		$text = '['.date('m/d/Y g:i A').'] - ';

		// Success or failure being logged?
		if ($success) $text .= "SUCCESS!\n";
		else $text .= 'FAIL: '.$this->last_error."\n";

		// Log the POST variables
		$text .= "IPN POST Vars from Paypal:\n";
		foreach ($this->ipn_data as $key=>$value) {
			$text .= "$key=$value, ";
		}

		// Log the response from the paypal server
		$text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;

		// Write to log
		$fp=fopen($this->ipn_log_file,'a');
		fwrite($fp, $text . "\n\n");

		fclose($fp);  // close file
	}

	function get_submit_paypal_html()
	{
		$html = '<form method="post" name="paypal_form" action="'.$this->paypal_url.'">';
		$html .= '<input type="hidden" name="charset" value="utf-8">';
		foreach ($this->fields as $name => $value)
			$html .= '	<input type="hidden" name="'.$name.'" value="'.$value.'" />';
		$html .= '	<input type="submit" id="submitpaypalbtn" value="'.JText::_('COM_SEMINARMAN_PAY_WITH_PAYPAL').'" onclick="javascript:transfer_paypal()" />';
		$html .= '</form>';
		return $html;
	}
}

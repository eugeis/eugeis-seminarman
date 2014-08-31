<?php defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">
function transfer_paypal()
{
	var btn = document.getElementById("submitpaypalbtn");
	btn.disabled=false;
	btn.value = "<?php echo JText::_('COM_SEMINARMAN_PAYPAL_TRANSFER'); ?>";
}
window.onload=function(){
	var btn = document.getElementById("submitpaypalbtn");
	btn.disabled=false;
	btn.value = "<?php echo JText::_('COM_SEMINARMAN_PAY_WITH_PAYPAL'); ?>";
}
</script>

<?php 

/*  PHP Paypal IPN  PROFINVENT
 *  Based on 4.16.2005 - Micah Carrick, email@micahcarrick.com
*/
// Setup class
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'paypal.class.php');  // include the class file
$p = new paypal_class;             // initiate an instance of the class

// Get the page/component configuration
$mainframe = JFactory::getApplication();
$params = $mainframe->getParams('com_seminarman');

if ($params->get('enable_paypal') == 2) {
  $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
} else {
  $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
}

$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

for($i = 0; $i < count($this->fields); $i++)
	$p->add_field($this->fields[$i]['paypalcode'], $this->fields[$i]['value']);

$p->add_field('last_name', $this->bookingDetails->last_name);
$p->add_field('first_name', $this->bookingDetails->first_name);
$p->add_field('payer_email', $this->bookingDetails->email);
if ($params->get('enable_paypal') == 2) {
    $p->add_field('business', $params->get('paypal_sandbox'));
} else {
	$p->add_field('business', $params->get('paypal_email'));
}
$p->add_field('item_number',$this->bookingDetails->code . '#' . $this->bookingDetails->bookingid);
$p->add_field('return', $this_script.'?option=com_seminarman&controller=paypal&bookingid='.$this->bookingDetails->bookingid.'&task=success');
$p->add_field('cancel_return', $this_script.'?option=com_seminarman&controller=paypal&bookingid='.$this->bookingDetails->bookingid.'&task=cancel');
$p->add_field('notify_url', $this_script.'?option=com_seminarman&controller=paypal&task=ipn');
$p->add_field('item_name', $this->bookingDetails->title);
$p->add_field('item_number', $this->bookingDetails->code . '#' . $this->bookingDetails->bookingid);
$p->add_field('quantity', $this->bookingDetails->attendees);
$p->add_field('amount', $this->amount);
$p->add_field('currency_code', $params->get('currency'));
?>

<div align="center" class="componentheading">
<?php if ($params->get('enable_paypal')) echo $p->get_submit_paypal_html(); ?>
</div>

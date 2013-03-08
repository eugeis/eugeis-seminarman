function copyBillingDetails()
{
	var form = document.adminForm;
	form.bill_addr.value = form.street.value;
	form.bill_city.value = form.city.value;
	form.bill_state.value = form.state.value;
	form.bill_zip.value = form.zip.value;
	form.bill_id_country.selectedIndex = form.id_country.selectedIndex;
	form.bill_phone.value = form.primary_phone.value;
}

function copyCourseTitle()
{
	var form = document.adminForm;
	var w = form.courseid.selectedIndex;
	form.title.value = form.courseid.options[w].text;
	form.alias.value = form.courseid.options[w].text;

}

function copyStartTime()
{
	var form = document.adminForm;
	form.finish_time.value = form.start_time.value;
}
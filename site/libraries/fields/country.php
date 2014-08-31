<?php
/**
 * @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
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

// no direct access
defined('_JEXEC') or die('Restricted access');

class CFieldsCountry
{
	/**
	 * Method to format the specified value for text type
	 **/
	function getFieldData( $value )
	{
		if( empty( $value ) )
			return $value;

		return $value;
	}

	function getFieldHTML( $field , $required )
	{
		// If maximum is not set, we define it to a default
		$field->max	= empty( $field->max ) ? 200 : $field->max;

		$class	= ($field->required == 1) ? ' required' : '';
		CMFactory::load( 'helpers' , 'string' );
		ob_start();
?>
	<select id="field<?php echo $field->id;?>" name="field<?php echo $field->id;?>" class="hasTip tipRight select validate-country<?php echo $class;?> inputbox" title="<?php echo $field->name;?>::<?php echo cEscape( $field->tips );?>">
		<option value="selectcountry"<?php echo empty($field->value) ? ' selected="selected"' : ''; ?>><?php echo JText::_('SELECT A COUNTRY');?></option>
		<option value="Afghanistan"<?php echo ($field->value == "Afghanistan") ? ' selected="selected"' : '';?>><?php echo JText::_('Afghanistan');?></option>
		<option value="Albania"<?php echo ($field->value == "Albania") ? ' selected="selected"' : '';?>><?php echo JText::_('Albania');?></option>
		<option value="Algeria"<?php echo ($field->value == "Algeria") ? ' selected="selected"' : '';?>><?php echo JText::_('Algeria');?></option>
		<option value="American Samoa"<?php echo ($field->value == "American Samoa") ? ' selected="selected"' : '';?>><?php echo JText::_('American Samoa');?></option>
		<option value="Andorra"<?php echo ($field->value == "Andorra") ? ' selected="selected"' : '';?>><?php echo JText::_('Andorra');?></option>
		<option value="Angola"<?php echo ($field->value == "Angola") ? ' selected="selected"' : '';?>><?php echo JText::_('Angola');?></option>
		<option value="Anguilla"<?php echo ($field->value == "Anguilla") ? ' selected="selected"' : '';?>><?php echo JText::_('Anguilla');?></option>
		<option value="Antarctica"<?php echo ($field->value == "Antarctica") ? ' selected="selected"' : '';?>><?php echo JText::_('Antarctica');?></option>
		<option value="Antigua and Barbuda"<?php echo ($field->value == "Antigua and Barbuda") ? ' selected="selected"' : '';?>><?php echo JText::_('Antigua and Barbuda');?></option>
		<option value="Argentina"<?php echo ($field->value == "Argentina") ? ' selected="selected"' : '';?>><?php echo JText::_('Argentina');?></option>
		<option value="Armenia"<?php echo ($field->value == "Armenia") ? ' selected="selected"' : '';?>><?php echo JText::_('Armenia');?></option>
		<option value="Aruba"<?php echo ($field->value == "Aruba") ? ' selected="selected"' : '';?>><?php echo JText::_('Aruba');?></option>
		<option value="Australia"<?php echo ($field->value == "Australia") ? ' selected="selected"' : '';?>><?php echo JText::_('Australia');?></option>
		<option value="Austria"<?php echo ($field->value == "Austria") ? ' selected="selected"' : '';?>><?php echo JText::_('Austria');?></option>
		<option value="Azerbaijan"<?php echo ($field->value == "Azerbaijan") ? ' selected="selected"' : '';?>><?php echo JText::_('Azerbaijan');?></option>
		<option value="Bahamas"<?php echo ($field->value == "Bahamas") ? ' selected="selected"' : '';?>><?php echo JText::_('Bahamas');?></option>
		<option value="Bahrain"<?php echo ($field->value == "Bahrain") ? ' selected="selected"' : '';?>><?php echo JText::_('Bahrain');?></option>
		<option value="Bangladesh"<?php echo ($field->value == "Bangladesh") ? ' selected="selected"' : '';?>><?php echo JText::_('Bangladesh');?></option>
		<option value="Barbados"<?php echo ($field->value == "Barbados") ? ' selected="selected"' : '';?>><?php echo JText::_('Barbados');?></option>
		<option value="Belarus"<?php echo ($field->value == "Belarus") ? ' selected="selected"' : '';?>><?php echo JText::_('Belarus');?></option>
		<option value="Belgium"<?php echo ($field->value == "Belgium") ? ' selected="selected"' : '';?>><?php echo JText::_('Belgium');?></option>
		<option value="Belize"<?php echo ($field->value == "Belize") ? ' selected="selected"' : '';?>><?php echo JText::_('Belize');?></option>
		<option value="Benin"<?php echo ($field->value == "Benin") ? ' selected="selected"' : '';?>><?php echo JText::_('Benin');?></option>
		<option value="Bermuda"<?php echo ($field->value == "Bermuda") ? ' selected="selected"' : '';?>><?php echo JText::_('Bermuda');?></option>
		<option value="Bhutan"<?php echo ($field->value == "Bhutan") ? ' selected="selected"' : '';?>><?php echo JText::_('Bhutan');?></option>
		<option value="Bolivia"<?php echo ($field->value == "Bolivia") ? ' selected="selected"' : '';?>><?php echo JText::_('Bolivia');?></option>
		<option value="Bosnia and Herzegovina"<?php echo ($field->value == "Bosnia and Herzegovina") ? ' selected="selected"' : '';?>><?php echo JText::_('Bosnia and Herzegovina');?></option>
		<option value="Botswana"<?php echo ($field->value == "Botswana") ? ' selected="selected"' : '';?>><?php echo JText::_('Botswana');?></option>
		<option value="Bouvet Island"<?php echo ($field->value == "Bouvet Island") ? ' selected="selected"' : '';?>><?php echo JText::_('Bouvet Island');?></option>
		<option value="Brazil"<?php echo ($field->value == "Brazil") ? ' selected="selected"' : '';?>><?php echo JText::_('Brazil');?></option>
		<option value="British Indian Ocean Territory"<?php echo ($field->value == "British Indian Ocean Territory") ? ' selected="selected"' : '';?>><?php echo JText::_('British Indian Ocean Territory');?></option>
		<option value="Brunei Darussalam"<?php echo ($field->value == "Brunei Darussalam") ? ' selected="selected"' : '';?>><?php echo JText::_('Brunei Darussalam');?></option>
		<option value="Bulgaria"<?php echo ($field->value == "Bulgaria") ? ' selected="selected"' : '';?>><?php echo JText::_('Bulgaria');?></option>
		<option value="Burkina Faso"<?php echo ($field->value == "Burkina Faso") ? ' selected="selected"' : '';?>><?php echo JText::_('Burkina Faso');?></option>
		<option value="Burundi"<?php echo ($field->value == "Burundi") ? ' selected="selected"' : '';?>><?php echo JText::_('Burundi');?></option>
		<option value="Cambodia"<?php echo ($field->value == "Cambodia") ? ' selected="selected"' : '';?>><?php echo JText::_('Cambodia');?></option>
		<option value="Cameroon"<?php echo ($field->value == "Cameroon") ? ' selected="selected"' : '';?>><?php echo JText::_('Cameroon');?></option>
		<option value="Canada"<?php echo ($field->value == "Canada") ? ' selected="selected"' : '';?>><?php echo JText::_('Canada');?></option>
		<option value="Cape Verde"<?php echo ($field->value == "Cape Verde") ? ' selected="selected"' : '';?>><?php echo JText::_('Cape Verde');?></option>
		<option value="Cayman Islands"<?php echo ($field->value == "Cayman Islands") ? ' selected="selected"' : '';?>><?php echo JText::_('Cayman Islands');?></option>
		<option value="Central African Republic"<?php echo ($field->value == "Central African Republic") ? ' selected="selected"' : '';?>><?php echo JText::_('Central African Republic');?></option>
		<option value="Chad"<?php echo ($field->value == "Chad") ? ' selected="selected"' : '';?>><?php echo JText::_('Chad');?></option>
		<option value="Chile"<?php echo ($field->value == "Chile") ? ' selected="selected"' : '';?>><?php echo JText::_('Chile');?></option>
		<option value="China"<?php echo ($field->value == "China") ? ' selected="selected"' : '';?>><?php echo JText::_('China');?></option>
		<option value="Christmas Island"<?php echo ($field->value == "Christmas Island") ? ' selected="selected"' : '';?>><?php echo JText::_('Christmas Island');?></option>
		<option value="Cocos (Keeling) Islands"<?php echo ($field->value == "Cocos (Keeling) Islands") ? ' selected="selected"' : '';?>><?php echo JText::_('Cocos (Keeling) Islands');?></option>
		<option value="Colombia"<?php echo ($field->value == "Colombia") ? ' selected="selected"' : '';?>><?php echo JText::_('Colombia');?></option>
		<option value="Comoros"<?php echo ($field->value == "Comoros") ? ' selected="selected"' : '';?>><?php echo JText::_('Comoros');?></option>
		<option value="Congo"<?php echo ($field->value == "Congo") ? ' selected="selected"' : '';?>><?php echo JText::_('Congo');?></option>
		<option value="Cook Islands"<?php echo ($field->value == "Cook Islands") ? ' selected="selected"' : '';?>><?php echo JText::_('Cook Islands');?></option>
		<option value="Costa Rica"<?php echo ($field->value == "Costa Rica") ? ' selected="selected"' : '';?>><?php echo JText::_('Costa Rica');?></option>
		<option value="Cote D'Ivoire (Ivory Coast)"<?php echo ($field->value == "Cote D'Ivoire (Ivory Coast)") ? ' selected="selected"' : '';?>><?php echo JText::_('Cote D\'Ivoire (Ivory Coast)');?></option>
		<option value="Croatia (Hrvatska)"<?php echo ($field->value == "Croatia (Hrvatska)") ? ' selected="selected"' : '';?>><?php echo JText::_('Croatia (Hrvatska)');?></option>
		<option value="Cuba"<?php echo ($field->value == "Cuba") ? ' selected="selected"' : '';?>><?php echo JText::_('Cuba');?></option>
		<option value="Cyprus"<?php echo ($field->value == "Cyprus") ? ' selected="selected"' : '';?>><?php echo JText::_('Cyprus');?></option>
		<option value="Czechoslovakia (former)"<?php echo ($field->value == "Czechoslovakia (former)") ? ' selected="selected"' : '';?>><?php echo JText::_('Czechoslovakia (former)');?></option>
		<option value="Czech Republic"<?php echo ($field->value == "Czech Republic") ? ' selected="selected"' : '';?>><?php echo JText::_('Czech Republic');?></option>
		<option value="Denmark"<?php echo ($field->value == "Denmark") ? ' selected="selected"' : '';?>><?php echo JText::_('Denmark');?></option>
		<option value="Djibouti"<?php echo ($field->value == "Djibouti") ? ' selected="selected"' : '';?>><?php echo JText::_('Djibouti');?></option>
		<option value="Dominica"<?php echo ($field->value == "Dominica") ? ' selected="selected"' : '';?>><?php echo JText::_('Dominica');?></option>
		<option value="Dominican Republic"<?php echo ($field->value == "Dominican Republic") ? ' selected="selected"' : '';?>><?php echo JText::_('Dominican Republic');?></option>
		<option value="East Timor"<?php echo ($field->value == "East Timor") ? ' selected="selected"' : '';?>><?php echo JText::_('East Timor');?></option>
		<option value="Ecuador"<?php echo ($field->value == "Ecuador") ? ' selected="selected"' : '';?>><?php echo JText::_('Ecuador');?></option>
		<option value="Egypt"<?php echo ($field->value == "Egypt") ? ' selected="selected"' : '';?>><?php echo JText::_('Egypt');?></option>
		<option value="El Salvador"<?php echo ($field->value == "El Salvador") ? ' selected="selected"' : '';?>><?php echo JText::_('El Salvador');?></option>
		<option value="Equatorial Guinea"<?php echo ($field->value == "Equatorial Guinea") ? ' selected="selected"' : '';?>><?php echo JText::_('Equatorial Guinea');?></option>
		<option value="Eritrea"<?php echo ($field->value == "Eritrea") ? ' selected="selected"' : '';?>><?php echo JText::_('Eritrea');?></option>
		<option value="Estonia"<?php echo ($field->value == "Estonia") ? ' selected="selected"' : '';?>><?php echo JText::_('Estonia');?></option>
		<option value="Ethiopia"<?php echo ($field->value == "Ethiopia") ? ' selected="selected"' : '';?>><?php echo JText::_('Ethiopia');?></option>
		<option value="Falkland Islands (Malvinas)"<?php echo ($field->value == "Falkland Islands (Malvinas)") ? ' selected="selected"' : '';?>><?php echo JText::_('Falkland Islands (Malvinas)');?></option>
		<option value="Faroe Islands"<?php echo ($field->value == "Faroe Islands") ? ' selected="selected"' : '';?>><?php echo JText::_('Faroe Islands');?></option>
		<option value="Fiji"<?php echo ($field->value == "Fiji") ? ' selected="selected"' : '';?>><?php echo JText::_('Fiji');?></option>
		<option value="Finland"<?php echo ($field->value == "Finland") ? ' selected="selected"' : '';?>><?php echo JText::_('Finland');?></option>
		<option value="France"<?php echo ($field->value == "France") ? ' selected="selected"' : '';?>><?php echo JText::_('France');?></option>
		<option value="France, Metropolitan"<?php echo ($field->value == "France, Metropolitan") ? ' selected="selected"' : '';?>><?php echo JText::_('France, Metropolitan');?></option>
		<option value="French Guiana"<?php echo ($field->value == "French Guiana") ? ' selected="selected"' : '';?>><?php echo JText::_('French Guiana');?></option>
		<option value="French Polynesia"<?php echo ($field->value == "French Polynesia") ? ' selected="selected"' : '';?>><?php echo JText::_('French Polynesia');?></option>
		<option value="French Southern Territories"<?php echo ($field->value == "French Southern Territories") ? ' selected="selected"' : '';?>><?php echo JText::_('French Southern Territories');?></option>
		<option value="Gabon"<?php echo ($field->value == "Gabon") ? ' selected="selected"' : '';?>><?php echo JText::_('Gabon');?></option>
		<option value="Gambia"<?php echo ($field->value == "Gambia") ? ' selected="selected"' : '';?>><?php echo JText::_('Gambia');?></option>
		<option value="Georgia"<?php echo ($field->value == "Georgia") ? ' selected="selected"' : '';?>><?php echo JText::_('Georgia');?></option>
		<option value="Germany"<?php echo ($field->value == "Germany") ? ' selected="selected"' : '';?>><?php echo JText::_('Germany');?></option>
		<option value="Ghana"<?php echo ($field->value == "Ghana") ? ' selected="selected"' : '';?>><?php echo JText::_('Ghana');?></option>
		<option value="Gibraltar"<?php echo ($field->value == "Gibraltar") ? ' selected="selected"' : '';?>><?php echo JText::_('Gibraltar');?></option>
		<option value="Great Britain (UK)"<?php echo ($field->value == "Great Britain (UK)") ? ' selected="selected"' : '';?>><?php echo JText::_('Great Britain (UK)');?></option>
		<option value="Greece"<?php echo ($field->value == "Greece") ? ' selected="selected"' : '';?>><?php echo JText::_('Greece');?></option>
		<option value="Greenland"<?php echo ($field->value == "Greenland") ? ' selected="selected"' : '';?>><?php echo JText::_('Greenland');?></option>
		<option value="Grenada"<?php echo ($field->value == "Grenada") ? ' selected="selected"' : '';?>><?php echo JText::_('Grenada');?></option>
		<option value="Guadeloupe"<?php echo ($field->value == "Guadeloupe") ? ' selected="selected"' : '';?>><?php echo JText::_('Guadeloupe');?></option>
		<option value="Guam"<?php echo ($field->value == "Guam") ? ' selected="selected"' : '';?>><?php echo JText::_('Guam');?></option>
		<option value="Guatemala"<?php echo ($field->value == "Guatemala") ? ' selected="selected"' : '';?>><?php echo JText::_('Guatemala');?></option>
		<option value="Guinea"<?php echo ($field->value == "Guinea") ? ' selected="selected"' : '';?>><?php echo JText::_('Guinea');?></option>
		<option value="Guinea-Bissau"<?php echo ($field->value == "Guinea-Bissau") ? ' selected="selected"' : '';?>><?php echo JText::_('Guinea-Bissau');?></option>
		<option value="Guyana"<?php echo ($field->value == "Guyana") ? ' selected="selected"' : '';?>><?php echo JText::_('Guyana');?></option>
		<option value="Haiti"<?php echo ($field->value == "Haiti") ? ' selected="selected"' : '';?>><?php echo JText::_('Haiti');?></option>
		<option value="Heard and McDonald Islands"<?php echo ($field->value == "Heard and McDonald Islands") ? ' selected="selected"' : '';?>><?php echo JText::_('Heard and McDonald Islands');?></option>
		<option value="Honduras"<?php echo ($field->value == "Honduras") ? ' selected="selected"' : '';?>><?php echo JText::_('Honduras');?></option>
		<option value="Hong Kong"<?php echo ($field->value == "Hong Kong") ? ' selected="selected"' : '';?>><?php echo JText::_('Hong Kong');?></option>
		<option value="Hungary"<?php echo ($field->value == "Hungary") ? ' selected="selected"' : '';?>><?php echo JText::_('Hungary');?></option>
		<option value="Iceland"<?php echo ($field->value == "Iceland") ? ' selected="selected"' : '';?>><?php echo JText::_('Iceland');?></option>
		<option value="India"<?php echo ($field->value == "India") ? ' selected="selected"' : '';?>><?php echo JText::_('India');?></option>
		<option value="Indonesia"<?php echo ($field->value == "Indonesia") ? ' selected="selected"' : '';?>><?php echo JText::_('Indonesia');?></option>
		<option value="Iran"<?php echo ($field->value == "Iran") ? ' selected="selected"' : '';?>><?php echo JText::_('Iran');?></option>
		<option value="Iraq"<?php echo ($field->value == "Iraq") ? ' selected="selected"' : '';?>><?php echo JText::_('Iraq');?></option>
		<option value="Ireland"<?php echo ($field->value == "Ireland") ? ' selected="selected"' : '';?>><?php echo JText::_('Ireland');?></option>
		<option value="Israel"<?php echo ($field->value == "Israel") ? ' selected="selected"' : '';?>><?php echo JText::_('Israel');?></option>
		<option value="Italy"<?php echo ($field->value == "Italy") ? ' selected="selected"' : '';?>><?php echo JText::_('Italy');?></option>
		<option value="Jamaica"<?php echo ($field->value == "Jamaica") ? ' selected="selected"' : '';?>><?php echo JText::_('Jamaica');?></option>
		<option value="Japan"<?php echo ($field->value == "Japan") ? ' selected="selected"' : '';?>><?php echo JText::_('Japan');?></option>
		<option value="Jordan"<?php echo ($field->value == "Jordan") ? ' selected="selected"' : '';?>><?php echo JText::_('Jordan');?></option>
		<option value="Kazakhstan"<?php echo ($field->value == "Kazakhstan") ? ' selected="selected"' : '';?>><?php echo JText::_('Kazakhstan');?></option>
		<option value="Kenya"<?php echo ($field->value == "Kenya") ? ' selected="selected"' : '';?>><?php echo JText::_('Kenya');?></option>
		<option value="Kiribati"<?php echo ($field->value == "Kiribati") ? ' selected="selected"' : '';?>><?php echo JText::_('Kiribati');?></option>
		<option value="Korea, North"<?php echo ($field->value == "Korea, North") ? ' selected="selected"' : '';?>><?php echo JText::_('Korea, North');?></option>
		<option value="South Korea"<?php echo ($field->value == "South Korea") ? ' selected="selected"' : '';?>><?php echo JText::_('South Korea');?></option>
		<option value="Kuwait"<?php echo ($field->value == "Kuwait") ? ' selected="selected"' : '';?>><?php echo JText::_('Kuwait');?></option>
		<option value="Kyrgyzstan"<?php echo ($field->value == "Kyrgyzstan") ? ' selected="selected"' : '';?>><?php echo JText::_('Kyrgyzstan');?></option>
		<option value="Laos"<?php echo ($field->value == "Laos") ? ' selected="selected"' : '';?>><?php echo JText::_('Laos');?></option>
		<option value="Latvia"<?php echo ($field->value == "Latvia") ? ' selected="selected"' : '';?>><?php echo JText::_('Latvia');?></option>
		<option value="Lebanon"<?php echo ($field->value == "Lebanon") ? ' selected="selected"' : '';?>><?php echo JText::_('Lebanon');?></option>
		<option value="Lesotho"<?php echo ($field->value == "Lesotho") ? ' selected="selected"' : '';?>><?php echo JText::_('Lesotho');?></option>
		<option value="Liberia"<?php echo ($field->value == "Liberia") ? ' selected="selected"' : '';?>><?php echo JText::_('Liberia');?></option>
		<option value="Libya"<?php echo ($field->value == "Libya") ? ' selected="selected"' : '';?>><?php echo JText::_('Libya');?></option>
		<option value="Liechtenstein"<?php echo ($field->value == "Liechtenstein") ? ' selected="selected"' : '';?>><?php echo JText::_('Liechtenstein');?></option>
		<option value="Lithuania"<?php echo ($field->value == "Lithuania") ? ' selected="selected"' : '';?>><?php echo JText::_('Lithuania');?></option>
		<option value="Luxembourg"<?php echo ($field->value == "Luxembourg") ? ' selected="selected"' : '';?>><?php echo JText::_('Luxembourg');?></option>
		<option value="Macau"<?php echo ($field->value == "Macau") ? ' selected="selected"' : '';?>><?php echo JText::_('Macau');?></option>
		<option value="Macedonia"<?php echo ($field->value == "Macedonia") ? ' selected="selected"' : '';?>><?php echo JText::_('Macedonia');?></option>
		<option value="Madagascar"<?php echo ($field->value == "Madagascar") ? ' selected="selected"' : '';?>><?php echo JText::_('Madagascar');?></option>
		<option value="Malawi"<?php echo ($field->value == "Malawi") ? ' selected="selected"' : '';?>><?php echo JText::_('Malawi');?></option>
		<option value="Malaysia"<?php echo ($field->value == "Malaysia") ? ' selected="selected"' : '';?>><?php echo JText::_('Malaysia');?></option>
		<option value="Maldives"<?php echo ($field->value == "Maldives") ? ' selected="selected"' : '';?>><?php echo JText::_('Maldives');?></option>
		<option value="Mali"<?php echo ($field->value == "Mali") ? ' selected="selected"' : '';?>><?php echo JText::_('Mali');?></option>
		<option value="Malta"<?php echo ($field->value == "Malta") ? ' selected="selected"' : '';?>><?php echo JText::_('Malta');?></option>
		<option value="Marshall Islands"<?php echo ($field->value == "Marshall Islands") ? ' selected="selected"' : '';?>><?php echo JText::_('Marshall Islands');?></option>
		<option value="Martinique"<?php echo ($field->value == "Martinique") ? ' selected="selected"' : '';?>><?php echo JText::_('Martinique');?></option>
		<option value="Mauritania"<?php echo ($field->value == "Mauritania") ? ' selected="selected"' : '';?>><?php echo JText::_('Mauritania');?></option>
		<option value="Mauritius"<?php echo ($field->value == "Mauritius") ? ' selected="selected"' : '';?>><?php echo JText::_('Mauritius');?></option>
		<option value="Mayotte"<?php echo ($field->value == "Mayotte") ? ' selected="selected"' : '';?>><?php echo JText::_('Mayotte');?></option>
		<option value="Mexico"<?php echo ($field->value == "Mexico") ? ' selected="selected"' : '';?>><?php echo JText::_('Mexico');?></option>
		<option value="Micronesia"<?php echo ($field->value == "Micronesia") ? ' selected="selected"' : '';?>><?php echo JText::_('Micronesia');?></option>
		<option value="Moldova"<?php echo ($field->value == "Moldova") ? ' selected="selected"' : '';?>><?php echo JText::_('Moldova');?></option>
		<option value="Monaco"<?php echo ($field->value == "Monaco") ? ' selected="selected"' : '';?>><?php echo JText::_('Monaco');?></option>
		<option value="Mongolia"<?php echo ($field->value == "Mongolia") ? ' selected="selected"' : '';?>><?php echo JText::_('Mongolia');?></option>
		<option value="Montserrat"<?php echo ($field->value == "Montserrat") ? ' selected="selected"' : '';?>><?php echo JText::_('Montserrat');?></option>
		<option value="Morocco"<?php echo ($field->value == "Morocco") ? ' selected="selected"' : '';?>><?php echo JText::_('Morocco');?></option>
		<option value="Mozambique"<?php echo ($field->value == "Mozambique") ? ' selected="selected"' : '';?>><?php echo JText::_('Mozambique');?></option>
		<option value="Myanmar"<?php echo ($field->value == "Myanmar") ? ' selected="selected"' : '';?>><?php echo JText::_('Myanmar');?></option>
		<option value="Namibia"<?php echo ($field->value == "Namibia") ? ' selected="selected"' : '';?>><?php echo JText::_('Namibia');?></option>
		<option value="Nauru"<?php echo ($field->value == "Nauru") ? ' selected="selected"' : '';?>><?php echo JText::_('Nauru');?></option>
		<option value="Nepal"<?php echo ($field->value == "Nepal") ? ' selected="selected"' : '';?>><?php echo JText::_('Nepal');?></option>
		<option value="Netherlands"<?php echo ($field->value == "Netherlands") ? ' selected="selected"' : '';?>><?php echo JText::_('Netherlands');?></option>
		<option value="Netherlands Antilles"<?php echo ($field->value == "Netherlands Antilles") ? ' selected="selected"' : '';?>><?php echo JText::_('Netherlands Antilles');?></option>
		<option value="Neutral Zone"<?php echo ($field->value == "Neutral Zone") ? ' selected="selected"' : '';?>><?php echo JText::_('Neutral Zone');?></option>
		<option value="New Caledonia"<?php echo ($field->value == "New Caledonia") ? ' selected="selected"' : '';?>><?php echo JText::_('New Caledonia');?></option>
		<option value="New Zealand"<?php echo ($field->value == "New Zealand") ? ' selected="selected"' : '';?>><?php echo JText::_('New Zealand');?></option>
		<option value="Nicaragua"<?php echo ($field->value == "Nicaragua") ? ' selected="selected"' : '';?>><?php echo JText::_('Nicaragua');?></option>
		<option value="Niger"<?php echo ($field->value == "Niger") ? ' selected="selected"' : '';?>><?php echo JText::_('Niger');?></option>
		<option value="Nigeria"<?php echo ($field->value == "Nigeria") ? ' selected="selected"' : '';?>><?php echo JText::_('Nigeria');?></option>
		<option value="Niue"<?php echo ($field->value == "Niue") ? ' selected="selected"' : '';?>><?php echo JText::_('Niue');?></option>
		<option value="Norfolk Island"<?php echo ($field->value == "Norfolk Island") ? ' selected="selected"' : '';?>><?php echo JText::_('Norfolk Island');?></option>
		<option value="Northern Mariana Islands"<?php echo ($field->value == "Northern Mariana Islands") ? ' selected="selected"' : '';?>><?php echo JText::_('Northern Mariana Islands');?></option>
		<option value="Norway"<?php echo ($field->value == "Norway") ? ' selected="selected"' : '';?>><?php echo JText::_('Norway');?></option>
		<option value="Oman"<?php echo ($field->value == "Oman") ? ' selected="selected"' : '';?>><?php echo JText::_('Oman');?></option>
		<option value="Pakistan"<?php echo ($field->value == "Pakistan") ? ' selected="selected"' : '';?>><?php echo JText::_('Pakistan');?></option>
		<option value="Palau"<?php echo ($field->value == "Palau") ? ' selected="selected"' : '';?>><?php echo JText::_('Palau');?></option>
		<option value="Panama"<?php echo ($field->value == "Panama") ? ' selected="selected"' : '';?>><?php echo JText::_('Panama');?></option>
		<option value="Papua New Guinea"<?php echo ($field->value == "Papua New Guinea") ? ' selected="selected"' : '';?>><?php echo JText::_('Papua New Guinea');?></option>
		<option value="Paraguay"<?php echo ($field->value == "Paraguay") ? ' selected="selected"' : '';?>><?php echo JText::_('Paraguay');?></option>
		<option value="Peru"<?php echo ($field->value == "Peru") ? ' selected="selected"' : '';?>><?php echo JText::_('Peru');?></option>
		<option value="Philippines"<?php echo ($field->value == "Philippines") ? ' selected="selected"' : '';?>><?php echo JText::_('Philippines');?></option>
		<option value="Pitcairn"<?php echo ($field->value == "Pitcairn") ? ' selected="selected"' : '';?>><?php echo JText::_('Pitcairn');?></option>
		<option value="Poland"<?php echo ($field->value == "Poland") ? ' selected="selected"' : '';?>><?php echo JText::_('Poland');?></option>
		<option value="Portugal"<?php echo ($field->value == "Portugal") ? ' selected="selected"' : '';?>><?php echo JText::_('Portugal');?></option>
		<option value="Puerto Rico"<?php echo ($field->value == "Puerto Rico") ? ' selected="selected"' : '';?>><?php echo JText::_('Puerto Rico');?></option>
		<option value="Qatar"<?php echo ($field->value == "Qatar") ? ' selected="selected"' : '';?>><?php echo JText::_('Qatar');?></option>
		<option value="Reunion"<?php echo ($field->value == "Reunion") ? ' selected="selected"' : '';?>><?php echo JText::_('Reunion');?></option>
		<option value="Romania"<?php echo ($field->value == "Romania") ? ' selected="selected"' : '';?>><?php echo JText::_('Romania');?></option>
		<option value="Russian Federation"<?php echo ($field->value == "Russian Federation") ? ' selected="selected"' : '';?>><?php echo JText::_('Russian Federation');?></option>
		<option value="Rwanda"<?php echo ($field->value == "Rwanda") ? ' selected="selected"' : '';?>><?php echo JText::_('Rwanda');?></option>
		<option value="Saint Kitts and Nevis"<?php echo ($field->value == "Saint Kitts and Nevis") ? ' selected="selected"' : '';?>><?php echo JText::_('Saint Kitts and Nevis');?></option>
		<option value="Saint Lucia"<?php echo ($field->value == "Saint Lucia") ? ' selected="selected"' : '';?>><?php echo JText::_('Saint Lucia');?></option>
		<option value="Saint Vincent and the Grenadines"<?php echo ($field->value == "Saint Vincent and the Grenadines") ? ' selected="selected"' : '';?>><?php echo JText::_('Saint Vincent and the Grenadines');?></option>
		<option value="Samoa"<?php echo ($field->value == "Samoa") ? ' selected="selected"' : '';?>><?php echo JText::_('Samoa');?></option>
		<option value="San Marino"<?php echo ($field->value == "San Marino") ? ' selected="selected"' : '';?>><?php echo JText::_('San Marino');?></option>
		<option value="Sao Tome and Principe"<?php echo ($field->value == "Sao Tome and Principe") ? ' selected="selected"' : '';?>><?php echo JText::_('Sao Tome and Principe');?></option>
		<option value="Saudi Arabia"<?php echo ($field->value == "Saudi Arabia") ? ' selected="selected"' : '';?>><?php echo JText::_('Saudi Arabia');?></option>
		<option value="Senegal"<?php echo ($field->value == "Senegal") ? ' selected="selected"' : '';?>><?php echo JText::_('Senegal');?></option>
		<option value="Seychelles"<?php echo ($field->value == "Seychelles") ? ' selected="selected"' : '';?>><?php echo JText::_('Seychelles');?></option>
		<option value="S. Georgia and S. Sandwich Isls."<?php echo ($field->value == "S. Georgia and S. Sandwich Isls.") ? ' selected="selected"' : '';?>><?php echo JText::_('S. Georgia and S. Sandwich Isls.');?></option>
		<option value="Sierra Leone"<?php echo ($field->value == "Sierra Leone") ? ' selected="selected"' : '';?>><?php echo JText::_('Sierra Leone');?></option>
		<option value="Singapore"<?php echo ($field->value == "Singapore") ? ' selected="selected"' : '';?>><?php echo JText::_('Singapore');?></option>
		<option value="Slovak Republic"<?php echo ($field->value == "Slovak Republic") ? ' selected="selected"' : '';?>><?php echo JText::_('Slovak Republic');?></option>
		<option value="Slovenia"<?php echo ($field->value == "Slovenia") ? ' selected="selected"' : '';?>><?php echo JText::_('Slovenia');?></option>
		<option value="Solomon Islands"<?php echo ($field->value == "Solomon Islands") ? ' selected="selected"' : '';?>><?php echo JText::_('Solomon Islands');?></option>
		<option value="Somalia"<?php echo ($field->value == "Somalia") ? ' selected="selected"' : '';?>><?php echo JText::_('Somalia');?></option>
		<option value="South Africa"<?php echo ($field->value == "South Africa") ? ' selected="selected"' : '';?>><?php echo JText::_('South Africa');?></option>
		<option value="Spain"<?php echo ($field->value == "Spain") ? ' selected="selected"' : '';?>><?php echo JText::_('Spain');?></option>
		<option value="Sri Lanka"<?php echo ($field->value == "Sri Lanka") ? ' selected="selected"' : '';?>><?php echo JText::_('Sri Lanka');?></option>
		<option value="St. Helena"<?php echo ($field->value == "St. Helena") ? ' selected="selected"' : '';?>><?php echo JText::_('St. Helena');?></option>
		<option value="St. Pierre and Miquelon"<?php echo ($field->value == "St. Pierre and Miquelon") ? ' selected="selected"' : '';?>><?php echo JText::_('St. Pierre and Miquelon');?></option>
		<option value="Sudan"<?php echo ($field->value == "Sudan") ? ' selected="selected"' : '';?>><?php echo JText::_('Sudan');?></option>
		<option value="Suriname"<?php echo ($field->value == "Suriname") ? ' selected="selected"' : '';?>><?php echo JText::_('Suriname');?></option>
		<option value="Svalbard and Jan Mayen Islands"<?php echo ($field->value == "Svalbard and Jan Mayen Islands") ? ' selected="selected"' : '';?>><?php echo JText::_('Svalbard and Jan Mayen Islands');?></option>
		<option value="Swaziland"<?php echo ($field->value == "Swaziland") ? ' selected="selected"' : '';?>><?php echo JText::_('Swaziland');?></option>
		<option value="Sweden"<?php echo ($field->value == "Sweden") ? ' selected="selected"' : '';?>><?php echo JText::_('Sweden');?></option>
		<option value="Switzerland"<?php echo ($field->value == "Switzerland") ? ' selected="selected"' : '';?>><?php echo JText::_('Switzerland');?></option>
		<option value="Syria"<?php echo ($field->value == "Syria") ? ' selected="selected"' : '';?>><?php echo JText::_('Syria');?></option>
		<option value="Taiwan"<?php echo ($field->value == "Taiwan") ? ' selected="selected"' : '';?>><?php echo JText::_('Taiwan');?></option>
		<option value="Tajikistan"<?php echo ($field->value == "Tajikistan") ? ' selected="selected"' : '';?>><?php echo JText::_('Tajikistan');?></option>
		<option value="Tanzania"<?php echo ($field->value == "Tanzania") ? ' selected="selected"' : '';?>><?php echo JText::_('Tanzania');?></option>
		<option value="Thailand"<?php echo ($field->value == "Thailand") ? ' selected="selected"' : '';?>><?php echo JText::_('Thailand');?></option>
		<option value="Togo"<?php echo ($field->value == "Togo") ? ' selected="selected"' : '';?>><?php echo JText::_('Togo');?></option>
		<option value="Tokelau"<?php echo ($field->value == "Tokelau") ? ' selected="selected"' : '';?>><?php echo JText::_('Tokelau');?></option>
		<option value="Tonga"<?php echo ($field->value == "Tonga") ? ' selected="selected"' : '';?>><?php echo JText::_('Tonga');?></option>
		<option value="Trinidad and Tobago"<?php echo ($field->value == "Trinidad and Tobago") ? ' selected="selected"' : '';?>><?php echo JText::_('Trinidad and Tobago');?></option>
		<option value="Tunisia"<?php echo ($field->value == "Tunisia") ? ' selected="selected"' : '';?>><?php echo JText::_('Tunisia');?></option>
		<option value="Turkey"<?php echo ($field->value == "Turkey") ? ' selected="selected"' : '';?>><?php echo JText::_('Turkey');?></option>
		<option value="Turkmenistan"<?php echo ($field->value == "Turkmenistan") ? ' selected="selected"' : '';?>><?php echo JText::_('Turkmenistan');?></option>
		<option value="Turks and Caicos Islands"<?php echo ($field->value == "Turks and Caicos Islands") ? ' selected="selected"' : '';?>><?php echo JText::_('Turks and Caicos Islands');?></option>
		<option value="Tuvalu"<?php echo ($field->value == "Tuvalu") ? ' selected="selected"' : '';?>><?php echo JText::_('Tuvalu');?></option>
		<option value="Uganda"<?php echo ($field->value == "Uganda") ? ' selected="selected"' : '';?>><?php echo JText::_('Uganda');?></option>
		<option value="Ukraine"<?php echo ($field->value == "Ukraine") ? ' selected="selected"' : '';?>><?php echo JText::_('Ukraine');?></option>
		<option value="United Arab Emirates"<?php echo ($field->value == "United Arab Emirates") ? ' selected="selected"' : '';?>><?php echo JText::_('United Arab Emirates');?></option>
		<option value="United Kingdom"<?php echo ($field->value == "United Kingdom") ? ' selected="selected"' : '';?>><?php echo JText::_('United Kingdom');?></option>
		<option value="United States"<?php echo ($field->value == "United States") ? ' selected="selected"' : '';?>><?php echo JText::_('United States');?></option>
		<option value="Uruguay"<?php echo ($field->value == "Uruguay") ? ' selected="selected"' : '';?>><?php echo JText::_('Uruguay');?></option>
		<option value="US Minor Outlying Islands"<?php echo ($field->value == "US Minor Outlying Islands") ? ' selected="selected"' : '';?>><?php echo JText::_('US Minor Outlying Islands');?></option>
		<option value="USSR (former)"<?php echo ($field->value == "USSR (former)") ? ' selected="selected"' : '';?>><?php echo JText::_('USSR (former)');?></option>
		<option value="Uzbekistan"<?php echo ($field->value == "Uzbekistan") ? ' selected="selected"' : '';?>><?php echo JText::_('Uzbekistan');?></option>
		<option value="Vanuatu"<?php echo ($field->value == "Vanuatu") ? ' selected="selected"' : '';?>><?php echo JText::_('Vanuatu');?></option>
		<option value="Vatican City State (Holy Sea)"<?php echo ($field->value == "Vatican City State (Holy Sea)") ? ' selected="selected"' : '';?>><?php echo JText::_('Vatican City State (Holy Sea)');?></option>
		<option value="Venezuela"<?php echo ($field->value == "Venezuela") ? ' selected="selected"' : '';?>><?php echo JText::_('Venezuela');?></option>
		<option value="Viet Nam"<?php echo ($field->value == "Viet Nam") ? ' selected="selected"' : '';?>><?php echo JText::_('Viet Nam');?></option>
		<option value="Virgin Islands (British)"<?php echo ($field->value == "Virgin Islands (British)") ? ' selected="selected"' : '';?>><?php echo JText::_('Virgin Islands (British)');?></option>
		<option value="Virgin Islands (U.S.)"<?php echo ($field->value == "Virgin Islands (U.S.)") ? ' selected="selected"' : '';?>><?php echo JText::_('Virgin Islands (U.S.)');?></option>
		<option value="Wallis and Futuna Islands"<?php echo ($field->value == "Wallis and Futuna Islands") ? ' selected="selected"' : '';?>><?php echo JText::_('Wallis and Futuna Islands');?></option>
		<option value="Western Sahara"<?php echo ($field->value == "Western Sahara") ? ' selected="selected"' : '';?>><?php echo JText::_('Western Sahara');?></option>
		<option value="Yemen"<?php echo ($field->value == "Yemen") ? ' selected="selected"' : '';?>><?php echo JText::_('Yemen');?></option>
		<option value="Yugoslavia"<?php echo ($field->value == "Yugoslavia") ? ' selected="selected"' : '';?>><?php echo JText::_('Yugoslavia');?></option>
		<option value="Zaire"<?php echo ($field->value == "Zaire") ? ' selected="selected"' : '';?>><?php echo JText::_('Zaire');?></option>
		<option value="Zambia"<?php echo ($field->value == "Zambia") ? ' selected="selected"' : '';?>><?php echo JText::_('Zambia');?></option>
		<option value="Zimbabwe"<?php echo ($field->value == "Zimbabwe") ? ' selected="selected"' : '';?>><?php echo JText::_('Zimbabwe');?></option>
	</select>
	<span id="errfield<?php echo $field->id;?>msg" style="display:none;">&nbsp;</span>
<?php
		$html	= ob_get_contents();
		ob_end_clean();

		return $html;
	}

	function isValid( $value , $required )
	{
		if( $value === 'selectcountry' && $required )
			return false;

		return true;
	}

}
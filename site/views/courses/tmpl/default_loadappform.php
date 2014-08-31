    <!--application form-->
<script type="text/javascript">
function setVisibility() {
    document.getElementById('course_appform').style.display = 'block';
}
function unsetVisibility() {
        document.getElementById('course_appform').style.display = 'none';
}
</script>

<a onclick="setVisibility();" href="<?php echo JURI::getInstance()->toString(); ?>#appform" id="appform"><h2 class="booking componentheading<?php echo $this->params->get('pageclass_sfx'); ?>"><?php echo JText::_('COM_SEMINARMAN_BOOK_COURSE') . ": " . $this->course->title; ?></h2></a>

    <div class="course_applicationform" id="course_appform">
        <?php if ($params->get('enable_loginform') == 1) echo '<h2>' . JText::_('COM_SEMINARMAN_LOGIN_PLEASE') . '</h2>'; ?>
    <?php
    $module = JModuleHelper::getModule('mod_login','OSG Login');
    if ((!(is_null($module))) && ($params->get('enable_loginform') == 1)) echo JModuleHelper::renderModule($module);
        switch ($params->get('enable_bookings')) {
                case 3:
                        echo $this->loadTemplate('applicationform');
                        break;
                case 2:
                        echo $this->loadTemplate('applicationform');
                        break;
                case 1:
                        if ($this->user->get('guest'))
                                echo JText::_('COM_SEMINARMAN_PLEASE_LOGIN_FIRST') .'.';
                        else
                                    echo  $this->loadTemplate('applicationform');
                        break;
                default:
                        echo JText::_('COM_SEMINARMAN_BOOKINGS_DISABLED') .'.';
        }
        ?>
        </div>

<?php
if (!( isset($_GET['buchung']) && $_GET['buchung'] == 1 )) {
  echo '<script type="text/javascript">unsetVisibility();</script>';
}
?>
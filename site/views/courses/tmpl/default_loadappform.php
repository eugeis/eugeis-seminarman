    <!--application form-->
<script type="text/javascript">
function setVisibility() {
    document.getElementById('course_appform').style.display = 'block';
}
function unsetVisibility() {
        document.getElementById('course_appform').style.display = 'none';
}
</script>

<?php
if (!( isset($_GET['buchung']) && $_GET['buchung'] == 1 )) {
  echo '<script type="text/javascript">unsetVisibility();</script>';
}
?>
<br><center>
<table><tr><td align="center"><br>
<a href="http://www.osg-gmbh.de" target="_blank"><img src="components/com_seminarman/assets/images/smlogo.png" width=90 height=90 alt="OSG Seminar Manager" title="OSG Seminar Manager"></a>
 </td>
 <td><b><br><br><br> OSG Seminar Manager <?php 
		require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'liveupdate'.DS.'classes'.DS.'xmlslurp.php';
		$xmlslurp = new LiveUpdateXMLSlurp();
		$data = $xmlslurp->getInfo('com_seminarman', 'seminarman.xml');
		$version = $data['version'];
		echo $version;
		?></b><br><br></td>
 </tr>
 </table><br><br>
 <a href="http://www.osg-gmbh.de" target="_blank"><img src="components/com_seminarman/assets/images/logo.png" width=90 alt="Open Source Group Logo" title="Open Source Group Gmbh"></a>
<br><br><a href="http://www.osg-gmbh.de" target="_blank" title="Open Source Group Gmbh">Open Source Group GmbH</a><br><br><br>
<div align="center">Joomla! extension <b>OSG Seminar Manager</b> at Joomlacode:<br>
<a href="http://joomlacode.org/gf/project/com_seminarman/frs" target="_blank">http://joomlacode.org/gf/project/com_seminarman/frs</a>
<br><br>No phone support for our Joomla! extension OSG Seminar Manager.<br>Please use the <a href="http://joomlacode.org/gf/project/com_seminarman/forum" target="_blank">forum</a>
 at <i>Joomlacode.org</i>.<br></div>
<br><br></center>
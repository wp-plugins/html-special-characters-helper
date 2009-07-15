<?php
include_once('../../../wp-blog-header.php');
if (!$HTMLSpecialCharactersHelper) exit();
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>HTML Special Characters</title>
	<script language="javascript" type="text/javascript" src="../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="../../../wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="../../../wp-includes/js/prototype.js"></script>
	<?php do_action('htmlspecialcharacters_head'); ?>
	<base target="_self" />
</head>
<body id="image" onload="document.body.style.display='';" style="display: none">
	<div class="tabs">
		<ul>
			<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="return false;">HTML Special Characters</a></span></li>
		</ul>
	</div>

	<div class="panel_wrapper">
		<div id="general_panel" class="panel current">
     <table border="0" cellpadding="4" cellspacing="0">
		<!--tr><td>Certain character have special meaning in HTML (&amp;, &lt;, &rt;, &quot;, among others) which, if used in text and intended to
			be displayed, should be replaced with a special entity code representing that character.  In addition, other characters generally have
			no keyboard mapped representation (&copy;, &reg;, etc), which requires the use of special entity codes to be displayed.</td></tr-->
		<tr><td>Click to insert character into post.  Mouse-over character for more info.</td></tr>

        <tr>
		  <td>
			<?php $HTMLSpecialCharactersHelper->show_html_specialcharacters('rte'); ?>
		  </td>
		</tr>
	 </table>
	
	 <p><a href="#" onclick="tinyMCEPopup.close();">Close</a></p>
	</div>
</body>
</html>
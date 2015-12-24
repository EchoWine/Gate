<?php include 'header.php'; ?>
<div id='container'>
	<?php include 'container-nav.php'; ?>
	<?php if($pageCredential){ ?><?php include 'credential.page.php'; ?><?php }else if($pageSystemInfo){ ?><?php include 'SystemInfo.page.php'; ?><?php }else{ ?><?php include 'content.php'; ?><?php } ?>
</div>
<?php 
  @require_once("../../../wp-config.php");
  if(!isset($_GET['type']))
	exit(0);
  else $type=$_GET['type'];
?>
<html>
  <head>
	<title>Upload Iframe</title>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri()?>/css/all.css.php">
  </head>
<body>
  <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/js/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/js/all.js.php"></script>
  <script type="text/javascript" src="<?php echo get_template_directory_uri()?>/js/jquery.scrollTo-1.4.2-min.js"></script>
  <style type="text/css">
	.cosmo_uploader_interface { width: 85% !important}
	body { background:none !important }
  </style>
<?php
	_core::method('_CosmoUploader','print_form',"Attached ".$type."s",$type,($type=="image" || $type=="video")?true:false, ($type=="video")?true:false);
	_core::method('_CosmoUploader','init',$type);?>
<script type="text/javascript">
  jQuery(function(){
  var uploader=window.<?php echo $type?>_uploader;
  uploader.inh_upload_finished_with_success=uploader.upload_finished_with_success;
  uploader.upload_finished_with_success=function(params)
	{
	  this.inh_upload_finished_with_success(params);
	  update();
	}
  uploader.inh_remove=uploader.remove;
  uploader.remove=function(id)
	{
	  this.inh_remove(id);
	  update();
	}
  function update()
	{
	  if(uploader.video_urls){
			window.parent.update_hidden_inputs(uploader.attachments,"<?php echo $type?>",uploader.video_urls,uploader.featured);
		}
	  else window.parent.update_hidden_inputs(uploader.attachments,"<?php echo $type?>");
	}
  uploader.inh_set_featured=uploader.set_featured;
  uploader.set_featured=function(id)
	{
	  this.inh_set_featured(id);
	  update();
	}
	window.<?php echo $type?>_uploader=uploader;
  <?php if(isset($_GET['post'])){
			if(@get_post_format($_GET['post'])==$type){
				echo "update();";
			}
		}?>
});
</script>
	
  </body>
</html>
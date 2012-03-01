var Cosmo_Uploader=
	{
		senders:new Array(),
		process_error:function(receiver,error)
			{
				this.senders[receiver].show_error(error);
			},
		upload_finished:function(receiver,params)
			{
				this.senders[receiver].upload_finished_with_success(params);
			},
		init:function()
		  {
			window.Cosmo_Uploader=this;
		  },
		Basic_Functionality:function(interface_id)
			{
				var object=new Object();
				object.interface_id=interface_id;
				object.attachments=new Array();
				object.thumbnail_ids=new Array();
				object.next_thumbnail_id=0;
				object.files_input_element=document.getElementById(object.interface_id).getElementsByTagName("input")[0];
				Cosmo_Uploader.senders[object.interface_id]=object;
				
				jQuery("#"+object.interface_id).ready(function(){
					jQuery("#"+object.interface_id+" .cui_spinner_container").hide();
				});
				
				jQuery(object.files_input_element).change(function()
				{
					object.show_spinner();
					object.start_upload();
				});
				
				var multiple_files_upload=function()
					{
						var l=this.files_input_element.files.length;
						this.files_processed=0;
						this.files_total=l;
						jQuery("#"+this.interface_id+" .cui_spinner_container p").html(__("Uploading")+" "+l+" "+__("file")+(l==1?'':'s')+". "+__("This may take a while")+".");
						jQuery("#"+this.interface_id+" input[name*=\"method\"]").val("form");
						jQuery("#"+this.interface_id+" input[name*=\"action\"]").val("upload");
						jQuery("#"+this.interface_id+" input[name*=\"sender\"]").val(this.interface_id);
						jQuery("#"+this.interface_id+" form").submit();
						document.getElementById(this.interface_id).getElementsByTagName("form")[0].reset();
					}
				var single_file_upload=function()
					{
						jQuery("#"+this.interface_id+" .cui_spinner_container p").html(__("Uploading... Please wait."));
						jQuery("#"+this.interface_id+" input[name*=\"action\"]").val("upload");
						jQuery("#"+this.interface_id+" input[name*=\"sender\"]").val(this.interface_id);
						jQuery("#"+this.interface_id+" form").submit();
						document.getElementById(this.interface_id).getElementsByTagName("form")[0].reset();
					}
				if(object.files_input_element.files)
					object.start_upload=multiple_files_upload;
				else object.start_upload=single_file_upload;
				
				object.show_spinner=function()
					{
						jQuery("#"+object.interface_id+" .cui_error_container").html("");
						jQuery("#"+object.interface_id+" .cui_add_button").hide();
						jQuery("#"+object.interface_id+" .cui_spinner_container").slideDown();
					}
				object.hide_spinner=function()
					{
						jQuery("#"+object.interface_id+" .cui_add_button").show();
						jQuery("#"+object.interface_id+" .cui_spinner_container").slideUp();
					}
				object.show_error=function(error)
					{
						object.hide_spinner();
						jQuery("#"+object.interface_id+" .cui_error_container").append(error+"<br>");
					}
				object.remove=function(id)
					{
						if(!confirm(__("Are you sure?"))) return;
						var attach_id=this.thumbnail_ids[id];
						var thumbnail_id="thumbnail_"+id;
						var idx=jQuery.inArray(attach_id,this.attachments);
						if(idx!=-1)
						  {
							this.attachments.splice(idx,1);
						  }
						idx=jQuery.inArray(id,this.thumbnail_ids);
						if(idx!=-1)
						  {
							this.thumbnail_ids.splice(idx,1);
						  }
					  	var uri=Cosmo_Uploader.template_directory_uri;
						jQuery.ajax({
							url:uri+"/upload-server.php",
							type:"post",
							data:"action=delete&attach_id="+attach_id
						});
						jQuery("#"+this.interface_id+" #"+thumbnail_id).remove();
					}
				object.upload_finished_with_success=function(params)
					{
						this.hide_spinner();
						this.attachments.push(params["attach_id"]);
						var thumbnail_id_to_return=this.next_thumbnail_id;
						var thumbnail_id="thumbnail_"+this.next_thumbnail_id;
						this.thumbnail_ids[this.next_thumbnail_id]=params["attach_id"];
						this.next_thumbnail_id++;
					    var diff=50-params["h"];
						var append="<div class=\"cui_thumbnail\" id=\""+thumbnail_id+"\">";
						append+=params["fn_excerpt"];
						append+="<a href=\"javascript:void(0)\" class=\"feat_ref\" title=\""+params["filename"]+" "+__('Click to set as featured')+".\">"
						append+="<img src=\""+params["url"]+"\" witdh=\""+params['w']+"\" height=\""+params['h']+"\" alt=\""+params["filename"]+". "+__('Click to set as featured')+"\" style=\"margin-top:"+diff+"px\">";
						append+="</a>";
						append+="<br/>";
						append+="<a href=\"javascript:void(0)\" class=\"remove_ref\">"+__('Remove')+"</a>";
						append+="</div>";
						jQuery("#"+this.interface_id+" .cui_thumbnail_container").append(append);
						var jthis=this;
						
						jQuery("#"+this.interface_id+" #"+thumbnail_id+" .remove_ref").click(function()
							{
							  jthis.remove(thumbnail_id_to_return);
							});
						return thumbnail_id_to_return;
					}
				object.serialize=function()
					{
						var querydata="";
						var id;
						for(id=0;id<this.attachments.length;id++)
							{
								querydata+="&attachments[]="+encodeURIComponent(this.attachments[id]);
							}
						 return querydata;
					}
				return object;
			},
			
		URL_Functionality:function(object,url_id)
			{
				object.url_id=url_id;
				jQuery("#"+object.interface_id+" .cui_add_url_button_container").click(function(){
					jQuery("#"+object.url_id).slideDown();
					jQuery.scrollTo(jQuery("#"+object.url_id).offset().top-300,400);
				});
				jQuery("#"+object.url_id).ready(function(){
					jQuery("#"+object.url_id).hide();
				});
				jQuery("#"+object.interface_id+" .cui_upload_button_container").click(function(){
					jQuery("#"+object.url_id).hide();
				});
				jQuery("#"+object.url_id+ " .add_url_link").click(function()
					{
					  jQuery("#"+object.url_id).slideUp();
					  object.add_url(jQuery("#"+object.url_id+" .add_url").val());
					  jQuery("#"+object.url_id+" .add_url").val("");
					});
				object.add_url=function(url)
					{
					  var uri=Cosmo_Uploader.template_directory_uri;
					  this.show_spinner();
					  jQuery("#"+this.interface_id+" .cui_spinner_container p").html(__("Downloading. Please wait."));
					  var jthis=this;
					  jQuery.ajax({
						url:uri+"/upload-server.php",
						type:"post",
						data:"action=add_url&type="+jQuery("#"+this.interface_id+" input[name*=\"type\"]").val()+"&url="+encodeURIComponent(url)+"&sender="+encodeURIComponent(this.interface_id),
						success:function(msg)
						  {
							jthis.hide_spinner();
							eval(msg);
						  }
					  });
					}
				return object;
			},
		 Featured_Functionality:function(object)
			  {
				object.inherited_upload_finished_with_success=object.upload_finished_with_success;
				object.upload_finished_with_success=function(params)
					{
						var tid=this.inherited_upload_finished_with_success(params);
						var thumbnail_id="thumbnail_"+tid;
						var jthis=this;
						if(jQuery("#"+this.interface_id+" .cui_thumbnail").length==1)
							{
							  jthis.set_featured(tid);
							}
						jQuery("#"+this.interface_id+" #"+thumbnail_id+" .feat_ref").click(function()
							{
							  jthis.set_featured(tid);
							});
					}
				object.set_featured=function(id)
					{
						this.featured=this.thumbnail_ids[id];
						var thumbnail_id="thumbnail_"+id;
					    jQuery("#"+this.interface_id+" .cui_thumbnail").css("border-color","white");
					    jQuery("#"+this.interface_id+" #"+thumbnail_id).css("border-color","gray");
						
					}
				object.inherited_remove=object.remove;
				object.remove=function(id)
				{
					this.inherited_remove(id);
					if(this.featured==this.thumbnail_ids[id])
						{
						  var i;
						  for(i=0;i<this.attachments.length;i++)
							{
							  if(this.attachments[i])
								{
								  var thumbnail_id=jQuery.inArray(this.attachments[i],this.thumbnail_ids);
								  this.set_featured(thumbnail_id);
								  break;
								}
							}
						}
				}
				object.inherited_serialize=object.serialize;
				object.serialize=function()
				  {
					return this.inherited_serialize()+"&featured="+(this.featured?this.featured:'');
				  }
				return object;
			  },
		Video_Functionality:function(object)
		  {
			object.video_urls=new Array();
			object.inherited_serialize=function()
			{
			  var querydata="";
			  var id;
			  for(id=0;id<this.attachments.length;id++)
				{
					querydata+="&attachments[]="+encodeURIComponent(this.attachments[id]);
					if(this.video_urls[this.attachments[id]])
					  querydata+="&video_urls["+object.attachments[id]+"]="+encodeURIComponent(this.video_urls[this.attachments[id]]);
				}
			  return querydata;
			}
			object.inherited_inherited_upload_finished_with_success=object.upload_finished_with_success;
			object.upload_finished_with_success=function(params)
			{
			  this.inherited_inherited_upload_finished_with_success(params);
			  if(params["video_url"])
				object.video_urls[params["attach_id"]]=params["video_url"];
			}
			object.inherited_inherited_remove=object.remove;
			object.remove=function(id)
			{
			  this.inherited_inherited_remove(id);
			  var attach_id=this.thumbnail_ids[id];
			  var idx=jQuery.inArray(attach_id,this.video_urls);
			  if(idx!=-1)
				{
				  this.video_urls.splice(idx,1);
				}
			}
		  },
		Degenerate_Into_Featured_Image_Uploader:function(object)
		{
		  object.inherited_inherited_upload_finished_with_success=object.upload_finished_with_success;
		  object.upload_finished_with_success=function(params)
			{
			  var i;
			  for(i=0;i<this.thumbnail_ids.length;i++)
			  {
				this.remove(i);
			  }
			  object.inherited_inherited_upload_finished_with_success(params);
			}
		  object.remove=function(id)
		  {
			var attach_id=this.featured;
			var uri=Cosmo_Uploader.template_directory_uri;
			this.attachments=new Array();
			this.thumbnail_ids=new Array();
		  	
			jQuery.ajax({
				url:uri+"/upload-server.php",
				type:"post",
				data:"action=delete&attach_id="+attach_id
			});
			jQuery("#"+this.interface_id+" .cui_thumbnail").remove();
		  }
		},
		Get_Floating_Uploader:function(image_selector,hidden_input)
		{
			var j_image_selector=image_selector;
			var j_hidden_input_selector=hidden_input;
			jQuery(image_selector).mouseenter(function()
				{
					jQuery("#floating_uploader").css("top",jQuery(j_image_selector).position().top+"px");
					jQuery("#floating_uploader").css("left",jQuery(j_image_selector).position().left+"px");
					jQuery(j_image_selector).css('opacity',0.1);
					jQuery("#floating_uploader").removeClass("hidden");
					window.floating_uploader.upload_finished_with_success=function(params)
					{
						jQuery(j_image_selector).attr("src",params["url"]);
						jQuery(j_hidden_input_selector).val(params["attach_id"]);
						this.hide_spinner();
					}
				}
			);
			jQuery("#floating_uploader").mouseleave(function()
				{
					jQuery("#floating_uploader").addClass("hidden");
					jQuery(j_image_selector).css('opacity',1);
				}
			);
		}
	}
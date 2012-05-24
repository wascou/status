
WSE_ST={
		status: null,

		/* Dialogs - Common*/
		paramsDialogMessage:{
			height: "50",
			position: "top",
			show: "blind",
			hide: "blind",
			dialogClass: "status-dialog-message"
		},

		/* Dialogs - Specific to profile */
		paramsDialogSendMessage:function(){
			b_ok = $("#OKButtonOnSend").val();
			b_cancel = $("#CancelButtonOnSend").val();
			options =
			{
					height: "auto",
					position: "center",
					modal: true,
					buttons:
						[
							 {
								 text: b_ok,
								 click: function(){
								 	p_id = $("#dialog-sendmessage #dialog-sendmessage-receiver").val();
				        	 		url = $("#BaseURL").val()+"/sendmessage";
				        	 		message = encodeURIComponent($("#dialog-sendmessage #dialog-sendmessage-message").val());

				        	 		//TODO Add the not empty check

				        	 		$.getJSON(
				        	 				url,
				        	 				{PersonID: p_id, Message: message},
				        	 				function(d,t){
				        	 					if (d.success){
				        	 						WSE_ST.displayDialogMessage($("#SuccessMessageOnSend").val());
				        	 						WSE_ST.inc("status-profile-status-count");
				        	 						WSE_ST.inc("status-profile-messages-count");
				        	 					}else{
				        	 						WSE_ST.displayDialogMessage($("#ErrorMessageOnSend").val());
				        	 					}
				        	 				}
				        	 		);
				        	 		$(this).dialog("close");
							 	 }
							 },
							 {
								 text: b_cancel,
								 click: function(){$(this).dialog("close");}
							 }
						 ]
			};
			return options;
		},

		/* Dialog - Status*/
		paramsDialogSpread:function(){
			b_ok = $("#OKButtonOnSpread").val();
			b_cancel = $("#CancelButtonOnSpread").val();
			options =
			{
					height: "auto",
					position: "center",
					modal: true,
					buttons:
						[
						 	{
						 		text: b_ok,
						 		click: function(){
							 		s=WSE_ST.status;
				        	 		WSE_ST.status=null;
				        	 		s_id = $(s).find("#StatusID").val();
				        	 		url = $("#BaseURL").val()+"/spread";

				        	 		$.getJSON(
				        	 				url,
				        	 				{StatusID: s_id},
				        	 				function(d,t){
				        	 					if (d.success){
				        	 						$("#StatusID[value="+s_id+"]").parent().find(".ui-icon-transferthick-e-w.status-already").show();
				        	 						WSE_ST.displayDialogMessage($("#SuccessMessageOnSpread").val());
				        	 						WSE_ST.inc("status-profile-status-count");
				        	 					}else{
				        	 						WSE_ST.displayDialogMessage($("#ErrorMessageOnSpread").val());
				        	 					}
				        	 				}
				        	 		);
				        	 		$(this).dialog("close");
						 		}
						 	},
						 	{
						 		text: b_cancel,
						 		click: function(){$(this).dialog("close");}
						 	}
						]

			};
			return options;
		},
		paramsDialogReply:function(){
			b_ok = $("#OKButtonOnReply").val();
			b_cancel = $("#CancelButtonOnReply").val();
			options =
			{
					height: "auto",
					position: "center",
					modal: true,
					buttons:
						[
							 {
								 text: b_ok,
								 click: function(){
								 	s=WSE_ST.status;
								 	WSE_ST.status=null;
								 	s_id = $(s).find("#StatusID").val();
				        	 		url = $("#BaseURL").val()+"/reply";
				        	 		message = encodeURIComponent($("#dialog-reply #dialog-reply-message").val());

				        	 		//TODO Add the not empty check

				        	 		$.getJSON(
				        	 				url,
				        	 				{StatusID: s_id, Message: message},
				        	 				function(d,t){
				        	 					if (d.success){
				        	 						WSE_ST.displayDialogMessage($("#SuccessMessageOnReply").val());
				        	 						$("#StatusID[value="+s_id+"]").parent().find("#StatusDiscussion").val(d.discussion_id);
				        	 						$("#StatusID[value="+s_id+"]").parent().find("#StatusViewDiscussionAction").removeClass("hidden");
				        	 						WSE_ST.inc("status-profile-status-count");
				        	 					}else{
				        	 						WSE_ST.displayDialogMessage($("#ErrorMessageOnReply").val());
				        	 					}
				        	 				}
				        	 		);
				        	 		$(this).dialog("close");
							 	 }
							 },
							 {
								 text: b_cancel,
								 click: function(){$(this).dialog("close");}
							 }
						 ]
			};
			return options;
		},
		paramsDialogDelete:function(){
			b_ok = $("#OKButtonOnDelete").val();
			b_cancel = $("#CancelButtonOnDelete").val();
			options =
			{
				height: "auto",
				position: "center",
				modal: true,
				buttons:
					[
					 	{
					 		text: b_ok,
					 		click: function() {
			        	 		s=WSE_ST.status;
			        	 		WSE_ST.status=null;
			        	 		s_id = $(s).find("#StatusID").val();
			        	 		url = $("#BaseURL").val()+"/delete_status";
			        	 		$.getJSON(
			        	 				url,
			        	 				{StatusID: s_id},
			        	 				function(d,t){
			        	 					if (d.success){
			        	 						$("#StatusID[value="+s_id+"]").parent().hide('blind');
			        	 						WSE_ST.displayDialogMessage($("#SuccessMessageOnDelete").val());
			        	 						WSE_ST.dec("status-profile-status-count");
			        	 					}else{
			        	 						WSE_ST.displayDialogMessage($("#ErrorMessageOnDelete").val());
			        	 					}
			        	 				}
			        	 		);
			        	 		$(this).dialog("close");
			         		}
					 	},
					 	{
					 		text : $("#CancelButtonOnDelete").val(),
					 		click: function(){$(this).dialog("close");}
					 	}
					 ],
				dialogClass: "status-dialog-message"
			};
			return options;
		},
		/* Actions - Only on profile */
		actionFollowPerson: function(){
			pid = $("#PersonID").val();
			url = $("#BaseURL").val()+"/follow";
			$.getJSON(url, {PersonID: pid},
			function(d,t){
				if (d.success){
					$("#FollowButton").addClass("hidden");
					$("#UnfollowButton").removeClass("hidden");
					$("#SendButton").removeClass("hidden");
					$("#status-currently-following").empty().append($("#SuccessMessageOnFollow").val());
					WSE_ST.displayDialogMessage($("#SuccessMessageOnFollow").val());
				}else{
					WSE_ST.displayDialogMessage($("#ErrorMessageOnFollow").val());
				}
			});
		},
		actionUnfollowPerson: function(){
			pid = $("#PersonID").val();
			url = $("#BaseURL").val()+"/unfollow";
			$.getJSON(url, {PersonID: pid},
			function(d,t){
				if (d.success){
					$("#FollowButton").removeClass("hidden");
					$("#UnfollowButton").addClass("hidden");
					$("#SendButton").addClass("hidden");
					$("#status-currently-following").empty().append($("#SuccessMessageOnUnfollow").val());
					WSE_ST.displayDialogMessage($("#SuccessMessageOnUnfollow").val());
				}else{
					WSE_ST.displayDialogMessage($("#SuccessMessageOnUnfollow").val());
				}
			});
		},
		actionSendMessage:function(){
			WSE_ST.displayDialogSendMessage(event);
		},
		/* Actions - Status toolbar */
		actionAddFavorite: function(event){
			s=$(event.target).parent().parent().parent();
			s_id = $(s).find("#StatusID").val();
			o_id = $("#LoggedPersonID").val();
			url = $("#BaseURL").val()+"/add_favorite";

			$.getJSON(url, {StatusID: s_id, StatusOwnerID: o_id},
			function(d,t){
				if (d.success){
					$(s).find(".status-header .ui-icon-star").removeClass("hidden");
					$(s).find("#StatusIsFavorite").attr("value","1");
					WSE_ST.inc("status-profile-favorites-count");
					WSE_ST.configureToolBar(s);
				}
			});
		},
		actionRemoveFavorite: function(event){
			s=$(event.target).parent().parent().parent();
			s_id = $(s).find("#StatusID").val();
			o_id = $("#LoggedPersonID").val();
			url = $("#BaseURL").val()+"/remove_favorite";

			$.getJSON(url, {StatusID: s_id, StatusOwnerID: o_id},
			function(d,t){
				if (d.success){
					$(s).find(".status-header .ui-icon-star").addClass("hidden");
					$(s).find("#StatusIsFavorite").attr("value","0");
					WSE_ST.dec("status-profile-favorites-count");
					WSE_ST.configureToolBar(s);
				}
			});
		},
		actionRemoveStatus: function(event){
			WSE_ST.displayDialogDelete(event);
		},
		actionSpreadStatus: function(event){
			WSE_ST.displayDialogSpread(event);
		},
		actionUnspreadStatus: function(event){
			s=$(event.target).parent().parent().parent();
			s_id = $(s).find("#StatusID").val();
			url = $("#BaseURL").val()+"/unspread";

			$.getJSON(url, {StatusID: s_id},
			function(d,t){
				if (d.success){
					$(s).hide('blind');
					WSE_ST.configureToolBar(s);
				}
			});
		},
		actionReplyStatus: function(event){
			WSE_ST.displayDialogReply(event);
		},
		actionViewDiscussion: function(event){
			s=$(event.target).parent().parent().parent();
			d_id = $(s).find("#StatusDiscussion").val();
			url = $("#ModuleURL").val()+"/discussion/";
			document.location=url+d_id;
		},
		actionHelp: function(event){
			console.log('ok ici');
			document.location=$("#ModuleURL").val()+"/help";
		},
		configureToolBar: function(status){
			isf = $(status).find("#StatusIsFavorite").val();
			if (isf == 1){
				$("#StatusFavoriteAction").addClass("hidden");
				$("#StatusUnfavoriteAction").removeClass("hidden");
			}else{
				$("#StatusUnfavoriteAction").addClass("hidden");
				$("#StatusFavoriteAction").removeClass("hidden");
			}

			is = $(status).find("#StatusIsSpread").val();
			if (is == 1){
				$("#StatusSpreadAction").addClass("hidden");
				$("#StatusUnspreadAction").removeClass("hidden");
			}else{
				$("#StatusUnspreadAction").addClass("hidden");
				$("#StatusSpreadAction").removeClass("hidden");
			}

			io = $(status).find("#StatusIsOwned").val();
			if (io != 0){
				$("#StatusDeleteAction").removeClass("hidden");
			}else{
				$("#StatusDeleteAction").addClass("hidden");
			}

			id = $(status).find("#StatusDiscussion").val();
			if (id != 0){
				$("#StatusViewDiscussionAction").removeClass("hidden");
			}else{
				$("#StatusViewDiscussionAction").addClass("hidden");
			}


		},
		/* Dialogs - Common */
		displayDialogMessage: function(message){
			if (message != ""){
				$("#dialog-message").empty().append(message).dialog(WSE_ST.paramsDialogMessage);
				setTimeout("$('#dialog-message').dialog('close')", 3000);
			}
		},
		/* Dialogs - Specific  */
		displayDialogSendMessage: function(event){
			$("#dialog-sendmessage").dialog(WSE_ST.paramsDialogSendMessage());
			$("#dialog-sendmessage #dialog-sendmessage-message").unbind("keyup").keyup(function(event){
				WSE_ST.countCharacters($(event.target).val(), $("#dialog-sendmessage"));
			}).keyup();
		},
		/* Dialogs - Status */
		displayDialogDelete: function(event){
			WSE_ST.status=$(event.target).parent().parent().parent();
			$("#dialog-delete").dialog(WSE_ST.paramsDialogDelete());
		},
		displayDialogSpread: function(event){
			WSE_ST.status=$(event.target).parent().parent().parent();
			$("#dialog-spread").dialog(WSE_ST.paramsDialogSpread());
		},
		displayDialogReply: function(event){
			WSE_ST.status=$(event.target).parent().parent().parent();
			$("#dialog-reply").dialog(WSE_ST.paramsDialogReply()).find("#dialog-reply-message")[0].innerHTML=$(WSE_ST.status).find("#StatusQuotedPersons").val();
			$("#dialog-reply #dialog-reply-message").unbind("keyup").keyup(function(event){
				WSE_ST.countCharacters($(event.target).val(), $("#dialog-reply"));
			}).keyup();
		},
		/* Misc functions */
		countCharacters: function(message, element){
			c = $(element).find("#dialog-counter");
			v = $("#StatusLength").val()-message.length;
			if(v>=0 && $(c).hasClass("negative")){
				$(c).removeClass("negative");
			}else if (v < 0 && !$(c).hasClass("negative")){
				$(c).addClass("negative");
			}
			$(c)[0].innerHTML = v;
		},
		inc: function(id){
			$("#"+id)[0].innerHTML = parseInt( $("#"+id)[0].innerHTML) + 1;
		},
		dec: function(id){
			$("#"+id)[0].innerHTML = parseInt( $("#"+id)[0].innerHTML) - 1;
		}
};

$(document).ready(function(){

	/* GENERIC */
	$(".text").click(function(){
		$(this).val('');
		$(this).addClass("text_typed");
	});

	if ($("#status-form-add-message textarea").size()){
		$("#status-form-add-message textarea").click(function(){
			$(this).addClass("text_typed").switchClass('','high');
		}).keyup(function(event){
			WSE_ST.countCharacters($(event.target).val(), $("#status-form-add-message"));
		});
	};

	/* PROFILE TOOLBAR BINDS */
	if ($("#block-info").size()){
		$("#FollowButton").click(WSE_ST.actionFollowPerson);
		$("#UnfollowButton").click(WSE_ST.actionUnfollowPerson);
		$("#SendButton").click(WSE_ST.actionSendMessage);
		$("#HelpButton").click(WSE_ST.actionHelp);
	}

	/* DIRECT MESSAGES SPECIFIC */
	if ($("#status-messages").size()){
		$("#NewMessageButton").click(WSE_ST.actionSendMessage);
	}

	/* STATUS TOOLBAR SPECIFIC */
	if ($("#status-toolbar").size()){
		/* TOOLBAR BIND */
		$("#status-toolbar").mouseenter(function(){
			$(this).addClass("ui-state-hover");
		}).mouseleave(function(){
			$(this).removeClass("ui-state-hover");
		});

		/* ICONS GENERIC BINDS*/
		$("#status-toolbar .ui-icon").mouseenter(function(){
			$(this).addClass("ui-state-active");
		}).mouseleave(function(){
			$(this).removeClass("ui-state-active");
		});

		/* BOX BIND */
		$(".status-box").mouseenter(function(){
			if (tb){
				tb.appendTo($(this).find(".status-toolbar-placeholder")).show();
				tb=null;
				$(this).addClass("status-colored");
				WSE_ST.configureToolBar(this);
			}
		}).mouseleave(function(){
			tb = $("#status-toolbar").hide().detach();
			$(this).removeClass("status-colored");
		});

		/* STATUS TOOLBAR BIND */
		$("#StatusFavoriteAction").click(WSE_ST.actionAddFavorite);
		$("#StatusUnfavoriteAction").click(WSE_ST.actionRemoveFavorite);
		$("#StatusDeleteAction").click(WSE_ST.actionRemoveStatus);
		$("#StatusSpreadAction").click(WSE_ST.actionSpreadStatus);
		$("#StatusUnspreadAction").click(WSE_ST.actionUnspreadStatus);
		$("#StatusReplyAction").click(WSE_ST.actionReplyStatus);
		$("#StatusViewDiscussionAction").click(WSE_ST.actionViewDiscussion);

		tb = $("#status-toolbar").detach();
	}

	/* STATUS PROFILE ACTIONS SPECIFIC */
	if($("#status-profile-actions").size()){
		$("#status-profile-actions").mouseenter(function(){
			$(this).addClass("ui-state-hover");
		}).mouseleave(function(){
			$(this).removeClass("ui-state-hover");
		});
		$("#status-profile-actions .ui-icon").mouseenter(function(){
			$(this).addClass("ui-state-active");
		}).mouseleave(function(){
			$(this).removeClass("ui-state-active");
		});
	}

});
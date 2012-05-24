{debug-accumulator id="status-shared-dialog-reply" name="Dialog reply"}
<input type="hidden" id="OKButtonOnReply" value="{'OK'|i18n('design/status/dialog')}"/>
<input type="hidden" id="CancelButtonOnReply" value="{'Cancel'|i18n('design/status/dialog')}"/>

<input type="hidden" id="SuccessMessageOnReply" value="{'The reply has been sent.'|i18n('design/status/dialog')}"/>
<input type="hidden" id="ErrorMessageOnReply" value="{'An error occured : the reply has not been sent.'|i18n('design/status/dialog')}"/>

<div id="dialog-reply" class="hidden status-dialog">
	<h2>{'Add a reply'|i18n('design/status/dialog')}</h2>

	<label for="dialog-sendmessage-message">{'Message :'|i18n('design/status/dialog')}</label>
	<textarea id="dialog-reply-message" rows="4"></textarea>

	<div>
		<span id="dialog-counter">{ezini('GlobalSettings', 'StatusLength', 'status.ini')}</span> {'characters left.'|i18n('design/status/dialog')}
	</div>
</div>
{/debug-accumulator}
<div id="status-form-add-message">
	<form action="{concat('status/', $origin)|ezurl('no')}" method="post">
		<textarea name="Message" class="text ui-corner-all text_to_add">{'Type it in here...'|i18n('design/status/mine')}</textarea>
		<input type="submit" name="AddButton" class="button" value="{'Send'|i18n('design/status/mine')}"/>
		<div id="status-form-counter"><span id="dialog-counter">{ezini('GlobalSettings', 'StatusLength', 'status.ini')}</span> {'characters left.'|i18n('design/status/dialog')}</div>
	</form>
</div>
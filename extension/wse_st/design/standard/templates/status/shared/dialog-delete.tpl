{debug-accumulator id="status-shared-dialog-delete" name="Dialog delete"}
<input type="hidden" id="OKButtonOnDelete" value="{'OK'|i18n('design/status/dialog')}"/>
<input type="hidden" id="CancelButtonOnDelete" value="{'Cancel'|i18n('design/status/dialog')}"/>

<input type="hidden" id="SuccessMessageOnDelete" value="{'The status has been removed.'|i18n('design/status/dialog')}"/>
<input type="hidden" id="ErrorMessageOnDelete" value="{'An error occured and the status has not been removed.'|i18n('design/status/dialog')}"/>

<div id="dialog-delete" class="hidden status-dialog">
	{'Are you sure you want to delete this status ?'|i18n('design/status/dialog')}
</div>
{/debug-accumulator}
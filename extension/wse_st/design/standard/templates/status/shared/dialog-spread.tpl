{debug-accumulator id="status-shared-dialog-spread" name="Dialog spread"}
<input type="hidden" id="OKButtonOnSpread" value="{'OK'|i18n('design/status/dialog')}"/>
<input type="hidden" id="CancelButtonOnSpread" value="{'Cancel'|i18n('design/status/dialog')}"/>

<input type="hidden" id="SuccessMessageOnSpread" value="{'The status has been spread.'|i18n('design/status/dialog')}"/>
<input type="hidden" id="ErrorMessageOnSpread" value="{'An error occured : the status has not been spread.'|i18n('design/status/dialog')}"/>

<div id="dialog-spread" class="hidden status-dialog">
	{'Are you sure you want to spread this status ?'|i18n('design/status/dialog')}
</div>
{/debug-accumulator}
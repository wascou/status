{debug-accumulator id="status-shared-dialog-sendmessage" name="Dialog send message"}
{def $receivers_count=fetch('status', 'mutually_following_count', hash('person_id', $person.id) )
	 $length = ezini("GlobalSettings", "StatusLength", "status.ini")
}

<input type="hidden" id="OKButtonOnSend" value="{'OK'|i18n('design/status/dialog')}"/>
<input type="hidden" id="CancelButtonOnSend" value="{'Cancel'|i18n('design/status/dialog')}"/>

<input type="hidden" id="SuccessMessageOnSend" value="{'The message has been sent.'|i18n('design/status/dialog')}"/>
<input type="hidden" id="ErrorMessageOnSend" value="{'An error occured : the message has not been sent.'|i18n('design/status/dialog')}"/>

<div id="dialog-sendmessage" class="hidden status-dialog">
	<h2>{'Send a message'|i18n('design/status/dialog')}</h2>

    {if and( is_set($mutual), $mutual )}
        <label for="dialog-sendmessage-receiver">{'To :'|i18n('design/status/dialog')}</label>

    	<input type="hidden" id="dialog-sendmessage-receiver" value="{$person.id}">
    	<span>{$person.ezuser.login|wash()} - {$person.ezuser.contentobject.name|wash()}</span>

    	<label for="dialog-sendmessage-message">{'Message :'|i18n('design/status/dialog')}</label>
    	<textarea id="dialog-sendmessage-message" rows="4"></textarea>

    	<div>
    		<span id="dialog-counter">{$length}</span> {'characters left.'|i18n('design/status/dialog')}
    	</div>

	{elseif $receivers_count|gt(0)}
		{def $receivers=fetch('status', 'mutually_following', hash('person_id', $person.id) )}
        	<label for="dialog-sendmessage-receiver">{'To :'|i18n('design/status/dialog')}</label>
        	<select id="dialog-sendmessage-receiver">
        		{foreach $receivers as $k => $r}
        			<option value="{$r.id}">{$r.ezuser.login|wash()} - {$r.ezuser.contentobject.name|wash()}</option>
        		{/foreach}
        	</select>

        	<label for="dialog-sendmessage-message">{'Message :'|i18n('design/status/dialog')}</label>
        	<textarea id="dialog-sendmessage-message" rows="4"></textarea>

        	<div>
        		<span id="dialog-counter">{$length}</span> {'characters left.'|i18n('design/status/dialog')}
        	</div>
        {undef $receivers}
    {else}
    	{"You must have one mutual follower to send a message."|i18n('design/status/dialog')}
    {/if}
</div>
{/debug-accumulator}
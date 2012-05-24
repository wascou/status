{def $limit = ezini("GlobalSettings", "StatusLimit", "status.ini")
	 $offset = 0
	 $keys = array_keys($status)
}

{if is_set($view_parameters['offset'])}
	{set $offset=$view_parameters['offset']}
{/if}

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<div class="content-view-full">
	<div class="class-article" id="status-home">

		{include uri="design:status/shared/discussion-actors.tpl" first_status=$status[$keys[0]] actors=$actors}

	    <div id="status-container">

	    	<input type="hidden" id="PersonID" value="{$person.id}"/>
	    	<input type="hidden" id="LoggedPersonID" value="{$person.id}"/>
	    	<input type="hidden" id="BaseURL" value="{'/status/ajax/'|ezurl('no')}"/>
	    	<input type="hidden" id="ModuleURL" value="{'/status/'|ezurl('no')}"/>
	    	<input type="hidden" id="StatusLength" value="{ezini('GlobalSettings','StatusLength','status.ini')}"/>

	    	<h1><img src="{'status-logo.png'|ezimage('no')}" alt="{'WSE - Status'|i18n('design/status')}" title="{'WSE - Status'|i18n('design/status')}"/> {'Discussion'|i18n('design/status/discussion')} #<a href="{concat('/status/discussion/', $discussion_id)|ezurl('no')}">{$discussion_id}</a></h1>

			{include uri="design:status/shared/status-toolbar.tpl"}

			{include uri="design:status/shared/dialog-message.tpl"}
			{include uri="design:status/shared/dialog-delete.tpl"}
			{include uri="design:status/shared/dialog-spread.tpl"}
			{include uri="design:status/shared/dialog-reply.tpl"}

			{* TODO Put some cache here ? *}
			{foreach $status as $k => $s}
				{include uri="design:status/shared/status-box.tpl" s=$s logged_person=$person}
			{/foreach}

	    </div>

	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>

{undef $limit $offset}
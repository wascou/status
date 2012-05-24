{def $limit = ezini("GlobalSettings", "StatusLimit", "status.ini")
	 $offset = 0
}

{if is_set($view_parameters['offset'])}
	{set $offset=$view_parameters['offset']}
{/if}

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<div class="content-view-full">
	<div class="class-article" id="status-messages">

		<div id="block-info">
           	{include uri="design:status/shared/person-info.tpl" person=$person logged_person=$person}
           	{include uri="design:status/shared/search-box.tpl"}
		</div>


	    <div id="status-container">

	    	<input type="hidden" id="PersonID" value="{$person.id}"/>
	    	<input type="hidden" id="LoggedPersonID" value="{$person.id}"/>
	    	<input type="hidden" id="BaseURL" value="{'/status/ajax/'|ezurl('no')}"/>
	    	<input type="hidden" id="ModuleURL" value="{'/status/'|ezurl('no')}"/>
	    	<input type="hidden" id="StatusLength" value="{ezini('GlobalSettings','StatusLength','status.ini')}"/>

	    	<input type="hidden" id="MessagePrivacy" value="0"/>

	    	<h1><img src="{'status-logo.png'|ezimage('no')}" alt="{'WSE - Status'|i18n('design/status')}" title="{'WSE - Status'|i18n('design/status')}"/>{'Direct messages'|i18n('design/status/messages')}</h1>
	    	<input type="submit" id="NewMessageButton" class="button" value="{'New message'|i18n('design/status/messages')}"/>

			{include uri="design:status/shared/dialog-message.tpl"}
			{include uri="design:status/shared/dialog-sendmessage.tpl" person=$person}

			{if $status|count()|eq(0)}
	        	<div>{'You do not have any messages.'|i18n('design/status/messages')}</div>
			{else}
				{* TODO Put some cache here ?*}
				{include uri="design:status/shared/status-toolbar.tpl"}

				{include uri="design:status/shared/dialog-delete.tpl"}
				{include uri="design:status/shared/dialog-spread.tpl"}


				{foreach $status as $k => $s}
					{include uri="design:status/shared/status-box.tpl" s=$s logged_person=$person}
				{/foreach}
			{/if}

	    </div>

	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>

{undef $limit $offset}
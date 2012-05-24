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
	<div class="class-article" id="status-home">

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
	    	<input type="hidden" id="MessagePrivacy" value="1"/>

	    	<h1><img src="{'status-logo.png'|ezimage('no')}" alt="{'WSE - Status'|i18n('design/status')}" title="{'WSE - Status'|i18n('design/status')}"/> {'Something is happening ?'|i18n('design/status/mine')}</h1>

	    	{include uri='design:status/shared/status-form-add_message.tpl' origin='mine'}

			{def $status_count = fetch('status', 'status_count', hash('person_id', $person.id, 'scope', 'own' ))}
    			{if $status_count|eq(0)}
    				{'You have no status yet, try to follow someone or to express yourself !'|i18n('design/status/home')}
    			{else}
    	 				{def $status = fetch('status', 'status', hash('person_id', $person.id, 'scope', 'own', 'limit', $limit, 'offset', $offset) )}
    	 					{cache-block keys=array($person.id, $status_count, $limit, $offset, $person.last_update)}
        						{include uri="design:status/shared/status-toolbar.tpl"}

                				{include uri="design:status/shared/dialog-message.tpl"}
                				{include uri="design:status/shared/dialog-delete.tpl"}
                				{include uri="design:status/shared/dialog-spread.tpl"}
                				{include uri="design:status/shared/dialog-reply.tpl"}

                				{foreach $status as $k => $s}
                					{include uri="design:status/shared/status-box.tpl" s=$s person=$person logged_person=$person}
                				{/foreach}

                				{include uri='design:navigator/google.tpl'
                			         page_uri="/status/mine"
                			         item_count=$status_count
                			         view_parameters=$view_parameters
                			         item_limit=$limit
                			    }
                			{/cache-block}
						{undef $status}
    			    {undef $status_count}
    			{/if}
    		{undef $status_count}

	    </div>

	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>

{undef $limit $offset $status $status_count}
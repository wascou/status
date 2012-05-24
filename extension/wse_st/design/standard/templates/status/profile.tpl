{debug-accumulator id="status-profile" name="Profile page"}

{debug-accumulator id="status-profile-init" name="Profile page init"}
{def $limit = ezini("GlobalSettings", "StatusLimit", "status.ini")
	 $offset = 0
}

{if is_set($view_parameters['offset'])}
	{set $offset = $view_parameters['offset']}
{/if}

{/debug-accumulator}

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<div class="content-view-full">
	<div class="class-article">
		{def $status_count = fetch('status', 'status_count', hash('person_id', $person.id, 'scope', 'own' ))}

			<div id="block-info">
            	{include uri="design:status/shared/person-info.tpl" person=$person logged_person=$logged_person status_count=$status_count}
            	{include uri="design:status/shared/search-box.tpl"}
            </div>

	    	<div id="status-container">

    			<input type="hidden" id="PersonID" value="{$person.id}"/>
    	    	<input type="hidden" id="LoggedPersonID" value="{$logged_person.id}"/>
    	    	<input type="hidden" id="BaseURL" value="{'/status/ajax/'|ezurl('no')}"/>
    	    	<input type="hidden" id="ModuleURL" value="{'/status/'|ezurl('no')}"/>
    	    	<input type="hidden" id="StatusLength" value="{ezini('GlobalSettings','StatusLength','status.ini')}"/>
    	    	<input type="hidden" id="MessagePrivacy" value="1"/>

    			{debug-accumulator id="status-profile-status-list" name="Profile status list"}
					{cache-block keys=array($person.id, $status_count, $limit, $offset, $person.last_update)}
            			{if $status_count|eq(0)}
            				<h1><img src="{'status-logo.png'|ezimage('no')}"/> @{$person.ezuser.login} {'has no status yet !'|i18n('design/status/profile')}</h1>
            			{else}
            				{def $status = fetch('status', 'status', hash('person_id', $person.id, 'scope', 'own', 'limit', $limit, 'offset', $offset) )
    							 $status_keys = array_keys($status)
            					 $first_status = $status[$status_keys[0]]
            				}
            					<h2 class="status-header"><img src="{'status-logo.png'|ezimage('no')}"  alt="{'WSE - Status'|i18n('design/status')}" title="{'WSE - Status'|i18n('design/status')}" /> {$first_status.person.ezuser.login|wash()} <span class="status-user-name"> - {$first_status.person.ezuser.contentobject.name|wash()}</span></h2>
            					<h1>{$first_status.message|parse_status()}</h1>


                				{include uri="design:status/shared/status-toolbar.tpl" person=$person logged_person=$logged_person}

                				{include uri="design:status/shared/dialog-message.tpl"}
                				{include uri="design:status/shared/dialog-delete.tpl"}
                				{include uri="design:status/shared/dialog-spread.tpl"}
                				{include uri="design:status/shared/dialog-reply.tpl"}

                				{foreach $status as $k => $s}
                					{if $k|ne($status_keys[0])}
                						{include uri="design:status/shared/status-box.tpl" s=$s}
                					{/if}
                				{/foreach}

                				{include uri='design:navigator/google.tpl'
                			         page_uri=concat("/status/profile/",$person.ezuser.login)
                			         item_count=$status_count
                			         view_parameters=$view_parameters
                			         item_limit=$limit
                			    }
    						{undef $status $status_keys $first_status}
            			{/if}
					{/cache-block}
    			{/debug-accumulator}

	    	</div>
	    {undef $status_count}
	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>

{undef $limit $offset}
{/debug-accumulator}
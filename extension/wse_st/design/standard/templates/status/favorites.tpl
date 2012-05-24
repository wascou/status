{debug-accumulator id="status-favorite" name="Favorites page"}
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

	    	<h1><img src="{'status-logo.png'|ezimage('no')}" alt="{'WSE - Status'|i18n('design/status')}" title="{'WSE - Status'|i18n('design/status')}"/> {'Your favorite status'|i18n('design/status/favorites')}</h1>

			{debug-accumulator id="status-favorites-status-list" name="Favorites status list"}
				{def $status_count=fetch('status', 'favorites_count', hash( 'person_id', $person.id ))}
					{cache-block keys=array($person.id, $status_count, $limit, $offset, $person.last_update)}
            			{if $status_count|eq(0)}
            	        	{'You have no favorites yet.'|i18n('design/status/favorites')}
            			{else}
            				{def $status = fetch('status', 'favorites', hash('person_id', $person.id, 'limit', $limit, 'offset', $offset ) )}

                				{include uri="design:status/shared/status-toolbar.tpl"}

                				{include uri="design:status/shared/dialog-message.tpl"}
                				{include uri="design:status/shared/dialog-delete.tpl"}
                				{include uri="design:status/shared/dialog-spread.tpl"}
                				{include uri="design:status/shared/dialog-reply.tpl"}

                				{foreach $status as $k => $s}
                					{include uri="design:status/shared/status-box.tpl" s=$s logged_person=$person}
                				{/foreach}

                				{include uri='design:navigator/google.tpl'
                			         page_uri=concat("/status/favorites/",$person.ezuser.login)
                			         item_count=$status_count
                			         view_parameters=$view_parameters
                			         item_limit=$limit
                			    }
							{undef $status}
            			{/if}
            		{/cache-block}
            	{undef $status_count}
            {/debug-accumulator}

	    </div>

	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>

{undef $offset $limit}
{/debug-accumulator}
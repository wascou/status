{debug-accumulator id="status-leaders" name="Leaders page"}
{debug-accumulator id="status-leaders-init" name="Leaders init"}

{def $bio=ezini('GlobalSettings', 'BioAttribute', 'status.ini')
	 $limit = ezini('GlobalSettings', 'FollowersLimitByPage', 'status.ini')
	 $offset = 0
}

{if is_set($view_parameters['offset'])}
	{set $offset=$view_parameters['offset']}
{/if}

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

{/debug-accumulator}

<div class="content-view-full">
	<div class="class-article" id="status-home">

		<div id="block-info">
           	{include uri="design:status/shared/person-info.tpl" person=$person logged_person=$person}
           	{include uri="design:status/shared/search-box.tpl"}
		</div>

	    <div id="status-container">

	    	<h1><img src="{'status-logo.png'|ezimage('no')}" alt="{'WSE - Status'|i18n('design/status')}" title="{'WSE - Status'|i18n('design/status')}"/> {'Leaders of'|i18n('design/status/leaders')} @{$person.ezuser.login|wash()}</h1>

	    	{debug-accumulator id="status-leaders-list" name="List of leaders"}
	    		{def $leaders_count=fetch('status','leaders_count', hash( 'person_id', $person.id) )}
	    			{cache-block keys=array($person.id, $followers_count)}
	    				{if $leaders_count|eq(0)}
        	    			{'This person does not follow someone yet.'|i18n('design/status/leaders')}
        	    		{else}
    	    				{def $leaders=fetch('status','leaders', hash( 'person_id', $person.id, 'limit', $limit, 'offset', $offset ) )}
                    	    	{foreach $leaders as $p_id => $f}
                    	    		{include uri="design:status/shared/person-box.tpl" f=$f.ezuser}
                    	    	{/foreach}
                    	    {undef $leaders}

                    	    {include uri='design:navigator/google.tpl'
            			         page_uri="/status/leaders"
            			         item_count=$leaders_count
            			         view_parameters=$view_parameters
            			         item_limit=$limit
            			    }
						{/if}
            	    {/cache-block}
				{undef $leaders_count}
	    	{/debug-accumulator}
	    </div>

	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
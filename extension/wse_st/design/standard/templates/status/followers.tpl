{debug-accumulator id="status-followers" name="Followers page"}
{debug-accumulator id="status-followers-init" name="Followers init"}

{def $bio=ezini('GlobalSettings', 'BioAttribute', 'status.ini')
	 $limit = ezini('GlobalSettings', 'FollowersLimitByPage', 'status.ini')
	 $offset = 0
}

{if is_set($view_parameters['offset'])}
	{set $offset=$view_parameters['offset']}
{/if}

{/debug-accumulator}

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

	    	<h1><img src="{'status-logo.png'|ezimage('no')}" alt="{'WSE - Status'|i18n('design/status')}" title="{'WSE - Status'|i18n('design/status')}"/> {'Followers of'|i18n('design/status/followers')} @{$person.ezuser.login|wash()}</h1>

			{debug-accumulator id="status-followers-list" name="List of followers"}
    			{def $followers_count=fetch('status','followers_count', hash( 'person_id', $person.id) )}
        	    	{cache-block keys=array($person.id, $followers_count)}
        	    		{if $followers_count|eq(0)}
        	    			{'This person does not have followers yet.'|i18n('design/status/followers')}
        	    		{else}
            	    		{def $followers=fetch('status','followers', hash( 'person_id', $person.id, 'limit', $limit, 'offset', $offset ) )}
                    	    	{foreach $followers as $p_id => $f}
                    	    		{include uri="design:status/shared/person-box.tpl" f=$f.ezuser}
                    	    	{/foreach}
                    	    {undef $followers}

                    	    {include uri='design:navigator/google.tpl'
            			         page_uri="/status/followers"
            			         item_count=$followers_count
            			         view_parameters=$view_parameters
            			         item_limit=$limit
            			    }
						{/if}

            	    {/cache-block}
            	{undef $followers_count}
    	    {/debug-accumulator}
	    </div>


	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
{undef $bio}
{/debug-accumulator}
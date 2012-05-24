{debug-accumulator id="status-shared-personinfo" name="Person info"}
{def $my_status_count=fetch('status', 'status_count', hash('person_id', $person.id, 'scope', 'own' ))
	 $followers_count=fetch('status', 'followers_count', hash('person_id', $person.id) )
	 $leaders_count=fetch('status', 'leaders_count', hash('person_id', $person.id) )
	 $favorites_count=fetch('status', 'favorites_count', hash('person_id', $person.id))
	 $messages_count=fetch('status', 'messages_count', hash('person_id', $person.id))

	 $as_follower=fetch('status', 'is_follower', hash('follower_id', $logged_person.id, 'leader_id', $person.id) )
	 $as_mutual_follower=fetch('status', 'is_mutual_follower', hash('person_id', $logged_person.id, 'person_id2', $person.id) )
	 $as_me=$person.id|eq($logged_person.id)
}

{cache-block keys=array($person.id, $my_status_count, $followers_count, $leaders_count, $favorites_count, $messages_count, $as_follower, $as_me, $as_mutual_followers)}

	<div id="person-picture" class="ui-corner-all box">
		<h2>{$person.ezuser.contentobject.name|wash()}</h2>

        {if $person.ezuser.contentobject.current.data_map.image.has_content}
        	{attribute_view_gui attribute=$person.ezuser.contentobject.current.data_map.image image_class="status_avatar_medium"}
        {else}
			<img src="{'p-avatar.png'|ezimage('no')}"/>
        {/if}


        <h2><a href="{concat('status/profile/', $person.ezuser.login)|ezurl('no')}">@{$person.ezuser.login|wash()}</a></h2>


		{if $as_me|not()}
			<p id="status-currently-following">{if $as_follower}{'You are following this person'|i18n('design/status/shared/person-info', 'Static')}{else}{'You are not following this person'|i18n('design/status/shared/person-info', 'Static')}{/if}</p>
	    {/if}

	    <div id="status-profile-actions" class="ui-state-default ui-corner-all">
        	<input type="hidden" id="SuccessMessageOnFollow" value="{'You are now following this person.'|i18n('design/status/shared/person-info', 'Message')}"/>
        	<input type="hidden" id="ErrorMessageOnFollow" value="{'An error occured : you are not following this person.'|i18n('design/status/shared/person-info', 'Message')}"/>

        	<input type="hidden" id="SuccessMessageOnUnfollow" value="{'You are now not following this person.'|i18n('design/status/shared/person-info', 'Message')}"/>
        	<input type="hidden" id="ErrorMessageOnUnfollow" value="{'An error occured : you are still following this person.'|i18n('design/status/shared/person-info', 'Message')}"/>

			{if $as_mutual_follower}
        		{include uri="design:status/shared/dialog-sendmessage.tpl" person=$person mutual=true()}
        	{/if}

			<span id="FollowButton" class="ui-icon ui-corner-all ui-icon-bookmark {if or($as_follower, $as_me )}hidden{/if}" title="{'Follow'|i18n('design/status/shared/person-info', 'Button')}"></span>
			<span id="UnfollowButton" class="ui-icon ui-corner-all ui-icon-cancel {if or($as_follower|not(), $as_me )}hidden{/if}" title="{'Unfollow'|i18n('design/status/shared/person-info', 'Button')}"></span>
			{if $as_mutual_follower}<span id="SendButton" class="ui-icon ui-corner-all ui-icon-mail-closed {if or($as_follower|not(), $as_me )}hidden{/if}" title="{'Send a message'|i18n('design/status/shared/person-info', 'Button')}"></span>{/if}
			<span id="HelpButton" class="ui-icon ui-corner-all ui-icon-help" title="{'Help'|i18n('design/status/shared/person-info', 'Help')}"></span>
		</div>

		<div>
			{if $as_me}
				<h3>{'Status'|i18n('design/status/shared/person-info')} <a href="{'/status/mine/'|ezurl('no')}" id="status-profile-status-count">{$my_status_count}</a></h3>
			{else}
				<h3>{'Status'|i18n('design/status/shared/person-info')} <a href="{concat('/status/profile/', $person.ezuser.login)|ezurl('no')}" id="status-profile-status-count">{$my_status_count}</a></h3>
			{/if}
		</div>

		{if $as_me}
    		<div>
    			<h3>{'Favorites'|i18n('design/status/shared/person-info')} <a href="{concat('/status/favorites/',$person.ezuser.login)|ezurl('no')}" id="status-profile-favorites-count">{$favorites_count}</a></h3>
    		</div>

    		<div>
    			<h3>{'Messages'|i18n('design/status/shared/person-info')} <a href="{'/status/messages'|ezurl('no')}" id="status-profile-messages-count">{$messages_count}</a></h3>
    		</div>
    	{/if}

		<div>
			<h3>{'Followers'|i18n('design/status/shared/person-info')} <a href="{concat('/status/followers/',$person.ezuser.login)|ezurl('no')}">{$followers_count}</a></h3>
			{if $followers_count|gt(0)}
				{def $followers=fetch('status', 'followers', hash('person_id', $person.id, 'limit', ezini('GlobalSettings', 'FollowersLimitInPersonalBox', 'status.ini'), 'offset', 0 ) )}
    				{foreach $followers as $f}
    					{if $f.ezuser.contentobject.data_map.image.has_content}
    						<a href="{concat('/status/profile/', $f.ezuser.login)|ezurl('no')}">{attribute_view_gui attribute=$f.ezuser.contentobject.data_map.image image_class=status_avatar_tiny}</a>
    					{else}
    						<a href="{concat('/status/profile/', $f.ezuser.login)|ezurl('no')}"><img src="{'p-avatar-24.png'|ezimage('no')}" alt="{$f.ezuser.contentobject.name|wash()}" title="{$f.ezuser.contentobject.name|wash()}"/></a>
    					{/if}
    				{/foreach}
    			{undef $followers}
			{/if}
		</div>


		<div>
			<h3>{'Leaders'|i18n('design/status/shared/person-info')} <a href="{concat('/status/leaders/',$person.ezuser.login)|ezurl('no')}">{$leaders_count}</a></h3>
			{if $leaders_count|gt(0)}
				{def $leaders=fetch('status', 'leaders', hash('person_id', $person.id, 'limit', ezini('GlobalSettings', 'LeadersLimitInPersonalBox', 'status.ini'), 'offset', 0 ) )}
    				{foreach $leaders as $f}

    					{if $f.ezuser.contentobject.data_map.image.has_content}
    						<a href="{concat('/status/profile/', $f.ezuser.login)|ezurl('no')}">{attribute_view_gui attribute=$f.ezuser.contentobject.data_map.image image_class=status_avatar_tiny}</a>
    					{else}
    						<a href="{concat('/status/profile/', $f.ezuser.login)|ezurl('no')}"><img src="{'p-avatar-24.png'|ezimage('no')}" alt="{$f.ezuser.contentobject.name|wash()}" title="{$f.ezuser.contentobject.name|wash()}"/></a>
    					{/if}
    				{/foreach}
    			{undef $leaders}
			{/if}
		</div>

	</div>

{/cache-block}

{undef $my_status_count $favorites_count $messages_count $as_follower}
{/debug-accumulator}
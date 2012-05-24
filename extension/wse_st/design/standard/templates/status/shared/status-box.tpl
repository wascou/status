<div class="status-box">

	<input type="hidden" id="StatusID" value="{$s.id}"/>
	<input type="hidden" id="StatusOwnerID" value="{$s.person_id}"/>
	<input type="hidden" id="StatusIsFavorite" value="{$s.is_favorite}"/>
	<input type="hidden" id="StatusIsSpread" value="{$s.is_spread}"/>
	<input type="hidden" id="StatusIsOwned" value="{if $s.person.id|eq($logged_person.id)}1{else}0{/if}"/>
	<input type="hidden" id="StatusQuotedPersons" value="{extract_persons($s.message, $s.person.ezuser.login)|implode(' ')}"/>
	<input type="hidden" id="StatusDiscussion" value="{$s.discussion_id}"/>

	<div class="status-toolbar-placeholder"></div>
	<div class="status-image">
		<a href="{concat('/status/profile/', $s.spread_data.ezuser.login)|ezurl('no')}">
    		{if $s.person.ezuser.contentobject.current.data_map.image.has_content}
    			{attribute_view_gui attribute=$s.person.ezuser.contentobject.current.data_map.image image_class="status_avatar_small"}
    		{else}
    			<img src="{'p-avatar-50.png'|ezimage('no')}" alt="{$s.person.ezuser.contentobject.name|wash()}" title="{$s.person.ezuser.contentobject.name|wash()}"/>
    		{/if}
		</a>
	</div>

	<h2 class="status-header">
		<a href="{concat('/status/profile/', $s.person.ezuser.login)|ezurl('no')}">{$s.person.ezuser.login|wash()}</a> <span class="status-user-name"> - {$s.person.ezuser.contentobject.name|wash()}</span>
		{if $s.is_spread}<span class="status-user-via"> {'by'|i18n('design/status/shared/status-box')} <a href="{concat('/status/profile/', $s.spread_data.ezuser.login)|ezurl('no')}">{$s.spread_data.ezuser.contentobject.name|wash()}</a></span>{/if}
		<span class="ui-icon ui-icon-transferthick-e-w status-already {if $s.is_spread|not()}hidden{/if}" title="{'Spread'|i18n('design/status/shared/status-box')}"></span>
		<span class="ui-icon ui-icon-star status-already {if $s.is_favorite|not()}hidden{/if}" title="{'Favorite'|i18n('design/status/shared/status-box')}"></span>
		<span class="ui-icon ui-icon-script status-already {if $s.discussion_id|not()}hidden{/if}" title="{'Discussion'|i18n('design/status/shared/status-box')}"></span>
	</h2>
	<div class="status-message">{$s.message|parse_status()}</div>

	{include uri="design:status/shared/status-elapsed.tpl" s=$s}

</div>
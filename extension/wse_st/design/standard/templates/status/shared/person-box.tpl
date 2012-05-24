<div class="person-summary">
    <div class="status-image">
    	{if $f.contentobject.current.data_map.image.has_content}
			<a href="{concat('/status/profile/',$f.login|wash())|ezurl('no')}">{attribute_view_gui attribute=$f.contentobject.current.data_map.image image_class="status_avatar_small"}</a>
		{else}
			<a href="{concat('/status/profile/',$f.login|wash())|ezurl('no')}"><img src="{'p-avatar-50.png'|ezimage('no')}" alt="{$f.contentobject.name|wash()}" title="{$f.contentobject.name|wash()}"/></a>
		{/if}
	</div>
	<h2><a href="{concat('/status/profile/',$f.login|wash())|ezurl('no')}">@{$f.login|wash()}</a></h2>
	{if and(
		is_set($f.contentobject.data_map.bio),
		$f.contentobject.data_map.bio.has_content
	)} 
		<div class="status-message">{$f.contentobject.data_map[$bio].content|shorten(140)}</div>
	{/if}
</div>
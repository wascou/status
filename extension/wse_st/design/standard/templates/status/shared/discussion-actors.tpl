<div id="block-info" class="ui-corner-all">
  {foreach $actors as $k => $a}
      <div id="person-picture">
      	<h2>{$a.ezuser.contentobject.name|wash()}</h2>
        {if $a.ezuser.contentobject.current.data_map.image.has_content}
              <a href="{concat('/status/profile/', $a.ezuser.login)|ezurl('no')}">{attribute_view_gui attribute=$a.ezuser.contentobject.current.data_map.image image_class="status_avatar_medium"}</a>
            {else}
          <a href="{concat('/status/profile/', $a.ezuser.login)|ezurl('no')}"><img src="{'p-avatar.png'|ezimage('no')}"/></a>
            {/if}
            <h3><a href="{concat('/status/profile/', $a.ezuser.login)|ezurl('no')}">@{$a.ezuser.login}</a></h3>

      </div>
    {/foreach}

</div>

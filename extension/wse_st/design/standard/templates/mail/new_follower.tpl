<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{'You have a new follower'|i18n('design/status/mail')}</title>
</head>
<body>
	<h1>{'You have a new follower :'|i18n('design/status/mail')} {$sender.login}</h1>

{'Click here to learn more about'|i18n('design/status/mail')} <a href="{ezsys( 'hostname' )}/{concat('/status/profile/', $sender.login)|ezurl('no')}">{$sender.login}</a>

</body>
</html>
<?php
require_once(WPATH."globals.php");
require_once(WPATH."assets".DS."chat".DS."src".DS."phpfreechat.class.php");
$params = array();
$params["title"] = "AVega Bros International Shipping Corp";
$params["nick"] = $_SESSION['abas_login']['username'];  // setup the intitial nickname
$params['firstisadmin'] = false;
//$params["isadmin"] = true; // makes everybody admin: do not use it on production servers ;)
$params["serverid"] = md5(__FILE__); // calculate a unique id for this chat
$params["debug"] = true;
$chat = new phpFreeChat( $params );

?>
<link rel="stylesheet" type="text/css" href="<?php echo LINK; ?>assets/chat/client/themes/carbon/jquery.phpfreechat.min.css" />
<script src="<?php echo LINK; ?>assets/chat/client/jquery.phpfreechat.min.js" type="text/javascript"></script>
<style>
#pfc_words { color:#FFFFFF !important; background-color:#000000 !important; text-align:left; }
#pfc_container { background: none; }
</style>

<div style="display:none;"><a href="http://www.phpfreechat.net">phpFreeChat: simple Web chat</a></div>
<?php
$chat->printChat();
?>
<div class="cl"></div>
<script type="text/javascript">
	$('.mychat').phpfreechat({ serverUrl: '<?php echo HTTP_PATH; ?>home/chat',refresh_delay: 1000 });
</script>

<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html><!--[if IE 8]><html lang="en" class="ie8 no-js"><![endif]--><!--[if IE 9]><html lang="en" class="ie9 no-js"><![endif]--><!--[if !IE]><!--><html lang="en" class="no-js"><!--<![endif]--><!-- BEGIN HEAD --><head><meta charset="utf-8" /><title><?php echo ($webtitle); ?>－管理后台</title><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta content="width=device-width, initial-scale=1.0" name="viewport" /><meta content="" name="description" /><meta content="" name="author" /><meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- Bootstrap --><!-- BEGIN GLOBAL MANDATORY STYLES --><link href="__ROOT__/Public/css/bootstrap.min.css" rel="stylesheet" type="text/css"/><link href="__ROOT__/Public/css/admin.css" rel="stylesheet" type="text/css"/><link href="__ROOT__/Public/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/><!-- END GLOBAL MANDATORY STYLES --><!-- BEGIN THEME STYLES --><!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries --><!-- WARNING: Respond.js doesn't work if you view the page via file:// --><!--[if lt IE 9]><script src="__ROOT__/Public/js/html5shiv.min.js"></script><script src="__ROOT__/Public/js/respond.min.js"></script><![endif]--><!-- END THEME STYLES --></head><body style="margin-bottom:10px; background:url(Public/images/bg.jpg) center center;background-size:cover;height:100%;width:100%;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='Public/images/bg.jpg', sizingMethod='scale');min-height:750px;;min-width: 1004px;"><!-- begin container --><div class=" container " style="margin-top:60px"><br><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">管理员登录</h3></div><div class="panel-body"><h1 style="color:red"><span class="glyphicon glyphicon-remove"></span><small><?php echo ($emg); ?></small></h1>			 页面自动 跳转<a id="href" href="<?php echo U('Public/login');?>">跳转</a> 等待时间： <b id="wait">1</b><span class="help-block"></span><script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script></div></div></div><div class="col-md-4"></div></div></div><!-- end container --><!-- /.modal --><!-- END FOOTER --><!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) --><!-- BEGIN CORE PLUGINS --><script src="__ROOT__/Public/js/jquery.js"></script><script src="__ROOT__/Public/js/bootstrap.min.js"></script><script >	$('#myModal').on('hidden.bs.modal', function (e) {
	location.reload()

})
$('#ajax').on('hidden.bs.modal', function (e) {
	location.reload()

})


function delcfm() {
if (!confirm("确认要删除？")) {
window.event.returnValue = false;
}
}




</script><!-- END CORE PLUGINS --><!-- END JAVASCRIPTS --></body><!-- END BODY --></html>
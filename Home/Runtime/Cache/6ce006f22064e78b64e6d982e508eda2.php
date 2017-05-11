<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	    <link rel="stylesheet" href="__PUBLIC__/bootstrap-3.3.7-dist/css/bootstrap-theme.css" />
	    <link rel="stylesheet" href="__PUBLIC__/bootstrap-3.3.7-dist/css/bootstrap.css" />
		
		<script language="javascript" src="__PUBLIC__/bootstrap-3.3.7-dist/js/jquery-1.12.2.min.js"></script>
		<script language="javascript" src="__PUBLIC__/bootstrap-3.3.7-dist/js/bootstrap.js"></script>
		
		
		<title>
			
				TCOJ
			
		</title>
	</head>	
	<body>
		
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<div class="row">
							<div class="col-md-12">
								<nav class="navbar navbar-default">
								  <div class="container-fluid">
									<!-- Brand and toggle get grouped for better mobile display -->
									<div class="navbar-header">
									  <a class="navbar-brand" href="__APP__/Index/index">TCOJ</a>
									</div>
									<!-- Collect the nav links, forms, and other content for toggling -->
									<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
									  <ul class="nav navbar-nav">
										<!--<li class="active">
											<a href="__APP__/Index/index">主页
												<span class="sr-only">(current)</span>
											</a>
										</li>-->
										<li><a href="__APP__/Problem/showProblemList">题目</a></li>
										<li><a href="__APP__/User/showAllUserRank">排名</a></li>
										<li><a href="__APP__/Judge/showRealTimeEvaluation">实时评测</a></li>
									  	<li><a href="__APP__/Train/index">试炼场</a></li>
									  	<li><a href="__APP__/Exam/index">比赛</a></li>
									  </ul>
									  
									  <ul class="nav navbar-nav navbar-right">
										<?php if(($loginStatus) == "0"): ?><li><a href="__APP__/User/showLogin">登录</a></li>
											<li><a href="__APP__/User/showRegister">注册</a></li>
											<?php else: ?>
											 <li class="dropdown">
												 <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <?php echo ($userinfoData["username"]); ?>
												 <span class="caret"></span></a>
												 <ul class="dropdown-menu">
												  
													<li><a href="__APP__/User/showUserMessage/id/<?php echo ($userinfoData["id"]); ?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;我的信息</a></li>
													<li><a href="__APP__/User/showModifyUserMessage/id/<?php echo ($userinfoData["id"]); ?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;修改信息</a></li>
													<li><a href="__APP__/User/showModifyPassword/id/<?php echo ($userinfoData["id"]); ?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;修改密码</a></li>
													<?php if(($userinfoData["root"]) > "0"): ?><li><a href="__APP__/Admin/showProblemLibrary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;&nbsp;题目管理</a></li>
													<li><a href="__APP__/Admin/showLoginMessage"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;&nbsp;登录管理</a></li>
													<li><a href="__APP__/Admin/showUserMessage"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>&nbsp;&nbsp;用户管理</a></li>
													<li><a href="__APP__/Admin/trainIndex"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>&nbsp;&nbsp;试炼管理</a></li>
													<li><a href="__APP__/ExamAdmin/index"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>&nbsp;&nbsp;比赛管理</a></li><?php endif; ?>
													<li><a href="__APP__/User/logout"><span class="glyphicon glyphicon-off" aria-hidden="true"></span>&nbsp;&nbsp;退出登录</a></li>
													<li role="separator" class="divider"></li>
											  </ul>
												  
											</li><?php endif; ?>
										
									  </ul>
									</div><!-- /.navbar-collapse -->
								  </div><!-- /.container-fluid -->
								</nav>
							</div>
						</div>	
		
		
	<div class="row">
		<div class="col-md-12 text-center">
			<h2>比赛列表</h1>
		</div>
	</div>
	<div class="row" style="margin-bottom: 20px;margin-top: 10px;">
		<div class="col-md-12 text-center" style="color: #0066FF;">
			<span>当前服务器时间：</span>
			<span id="showDate">
				<?php echo (date("Y-m-d H:i:s",$nowTime)); ?>
			</span>
		</div>
	</div>
	<div class="row" style="margin-bottom: 20px;">
			<form action="__APP__/Admin/showContestList" method="post">
				<div class="col-md-4">
					<input type="text" class="form-control"  name="value">
				</div>
				<div class="col-md-4">
					<button class="btn btn-default" type="submit">搜索</button>
				</div>
			</form>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table  class="table table-condensed table-striped table-bordered text-center table-hover">
				<tr>
					<th>比赛名称</th>
					<th>开始时间</th>
					<th>结束时间</th>
					<th>类型</th>
					<th>状态</th>
					<th>作者</th>
				</tr>
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
						<td>
							<a href="__APP__/Exam/showProblemList/id/<?php echo ($vo["id"]); ?>">
								<?php echo ($vo["title"]); ?> 
							</a>
						</td>
						<td> <?php echo (date("Y-m-d H:i:s",$vo["start_time"])); ?>  </td>
						<td> <?php echo (date("Y-m-d H:i:s",$vo["end_time"])); ?> </td>
						<td><?php echo ($vo["type"]); ?></td>
						<td><?php echo ($vo["status"]); ?></td>
						<td><?php echo ($vo["anthor"]); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</table>
		</div>
	</div>
	
	<div class="page"><?php echo ($page); ?></div>

	<script type="text/javascript"> 
			$(function(){ 
				setInterval("getTime();",1000); //每隔一秒执行一次 
				}) 
				//取得系统当前时间 
			var stime = "<?php echo ($nowTime); ?>";
			function getTime(){ 
				stime++;
		        var Otime = new Date(parseInt(stime) * 1000);
		        //$("#showDate").html(Otime.pattern("yyyy-MM-dd EEE HH:mm:ss"));
				//var myDate = new Date(); 
				var myDate=Otime;
				var date = myDate.toLocaleDateString(); 
				var hours = myDate.getHours(); 
				var minutes = myDate.getMinutes(); 
				var seconds = myDate.getSeconds(); 
				$("#showDate").html(date+" "+hours+":"+minutes+":"+seconds); //将值赋给div 
			} 
		</script>

		
			﻿			<div class="row">
				<div class="col-md-12">
					<div class="footer">
						<hr>
						<div class="row footer-bottom">
				          <ul class="list-inline text-center">
				            <li>TCOI在线评测系统</li>
				            <li>|</li>
							<li>Copyright &copy; 2016-  author:吴迎</li>
				          </ul>
				        </div>
					</div>
					<script>
						
						/*导航*/
						(function(){
							if(window.sessionStorage){
				
								var nav = $('.navbar-nav');
								nav.find('li')
									.on('click',function(){
										sessionStorage.activeIndex = $(this).index();
										$(this).addClass('active')
											.siblings()
											.removeClass('active');
									})
									.eq(sessionStorage.activeIndex)
									.addClass('active')
									.siblings()
									.removeClass('active');
							}
						})();
					</script>
				</div>
			</div>
		</div>
	</div>
</div>
		
		
	</body>
</html>
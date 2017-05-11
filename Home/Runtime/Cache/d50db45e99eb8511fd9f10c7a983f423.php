<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	    <link rel="stylesheet" href="__PUBLIC__/bootstrap-3.3.7-dist/css/bootstrap-theme.css" />
	    <link rel="stylesheet" href="__PUBLIC__/bootstrap-3.3.7-dist/css/bootstrap.css" />
		
	<style type="text/css">
		.my-abstrat{
			overflow:hidden;
			text-overflow:ellipsis;
			-o-text-overflow:ellipsis;
			white-space:nowrap;
			width:100%;
		}
	</style>

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
			<h1><?php echo ($levelData["title"]); ?></h1>
			<p><?php echo ($levelData["content"]); ?></p>
		</div>
	</div>

	<div class="row">
		<?php if(is_array($listData)): $i = 0; $__LIST__ = $listData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="col-md-4" style="border:1px solid #CCCCCC;">
				<div class="row">
					<div class="col-md-12">
						<a href="__APP__/Train/showProblemList/id/<?php echo ($vo["id"]); ?>">
							<h4><?php echo ($vo["level_title"]); ?></h4>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<p>
							<span><?php echo ($vo["level_name"]); ?>,共<?php echo ($vo["pass_number"]); ?>道题。</span>
							<?php if(($vo["status"]) == "0"): ?><span style="color: red;">可挑战</span>
							<?php else: ?>
								<span style="color: #C0C0C0;">不可挑战</span><?php endif; ?>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 my-abstrat">
						<?php echo ($vo["level_abstract"]); ?>
					</div>
				</div>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>

		
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
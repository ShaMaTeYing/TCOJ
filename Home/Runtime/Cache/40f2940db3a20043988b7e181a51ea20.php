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
				<div class="col-md-8">
					<div class="row">
						<div class="col-md-2">
							<a href="__APP__/Train/showProblemList">
								任务题目列表
							</a>
						</div>
						<div class="col-md-2">
							<a href="__APP__/Train/showTaskRank">
								任务排行榜
							</a>
						</div>
						<div class="col-md-2">
							<a href="__APP__/Train/showTaskJudge">
								任务评测状态
							</a>
						</div>
						<div class="col-md-2">
							<a href="__APP__/Train/showBBS">
								任务讨论区
							</a>
						</div>
					</div>
					<div class="row">
						
	<div class="col-md-12" style="padding-top: 40px;">
		<form action="__APP__/Train/onlineJudge" method="post">
			<input type="hidden" name="problemID" value="<?php echo ($id); ?>"/>
			<input type="hidden" name="level_msg_id" value="<?php echo ($level_msg_id); ?>"/> 
			<div class="row">
				<h2 class="text-center"></h2>
			</div>
			<div class="row" style="padding-bottom: 15px;">
				<div class="col-md-4">
					<span style="font-size: 18px;">问题标题:<?php echo ($title); ?></span>
				</div>
				<div class="col-md-2" style="font-size: 18px;">
					<span>语言</span>
				</div>
				
				<div class="col-md-2">
					<select class="form-control" name="language">
					  <option value="C++">C++</option>
					  <option value="C">C</option>
					</select>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<textarea class="form-control" rows="20" name="code"></textarea>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<input class="btn btn-default text-center" type="submit" value="提交代码">
				</div>
			</div>
		</form>
	</div>

					</div>
				</div>
				<div class="col-md-4">
					<div class="row">
						<div class="row">
							<div class="col-md-12 text-center">
								<h3>
									<?php echo ($listData["level_title"]); ?>	
								</h3>
							</div>
						</div>
						<div class="col-md-12 text-center" style="margin-top: 30px;">
							<p>
								<span>
									<?php echo ($listData["level_name"]); ?>,共<?php echo ($listData["problem_number"]); ?>道题。
								</span>
								
							</p>
						</div>
						
					</div>
					<div class="row" style="margin-top: 30px;">
						<div class="col-md-12">
							<h4>
								任务说明：
							</h4>
							<p>
								<?php echo ($listData["level_abstract"]); ?>
							</p>
						</div>
					</div>
					<div class="row" >
						<div class="col-md-12">
							<p>
								要完成这个任务，通过左边题目至少<?php echo ($listData["least_pass_number"]); ?>题。
							</p>
						</div>
						
					</div>
				</div>
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
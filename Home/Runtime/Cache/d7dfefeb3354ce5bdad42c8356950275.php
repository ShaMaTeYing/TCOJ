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
		
		
	<div class="row" style="margin-bottom: 20px;">
		<form action="__APP__/Problem/showProblemList" method="post">
			<div class="col-md-4">
				<input type="text" class="form-control" name="value">
			</div>
			<div class="col-md-4">
				<button class="btn btn-default" type="submit">搜索</button>
			</div>
			<br>
		</form>
	</div>
	<div class="row">
		<div class="col-md-9">
			<table  class="table table-condensed table-striped table-bordered table-hover text-center">
				<tr>
					<th style="width: 15%;" class="text-center">我的状态</th>
					<th class="text-center">ID</th>
					<th style="text-align:left;" class="text-center">问题标题</th>
					<th class="text-center" >难度</th>
					<th class="text-center">通过率</th>
				</tr>
				<?php if(is_array($problemData)): $i = 0; $__LIST__ = $problemData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr style="width: 10%;">
						<td class="tCenter">
							<?php if(($vo["judge_status"]) == "0"): ?><span class="glyphicon glyphicon-ok" style="color: #009926;" aria-hidden="true"></span>
							<?php else: ?>
								<?php if(($vo["judge_status"]) > "0"): ?><span class="glyphicon glyphicon-remove" style="color: #CC0000;" aria-hidden="true"></span><?php endif; endif; ?>
						</td>
						<td><?php echo ($vo["id"]); ?> </td>
						<td style="text-align:left;"> <a href='__APP__/Problem/showProblem/id/<?php echo ($vo["id"]); ?>'> <?php echo ($vo["title"]); ?> </a> </td>
						<td>
							<?php if(($vo["difficulty"]) == "0"): ?>入门<?php endif; ?>
							<?php if(($vo["difficulty"]) == "1"): ?>简单<?php endif; ?>
							<?php if(($vo["difficulty"]) == "2"): ?>一般<?php endif; ?>
							<?php if(($vo["difficulty"]) == "3"): ?>中等<?php endif; ?>
							<?php if(($vo["difficulty"]) == "4"): ?>困难<?php endif; ?>
							
						</td>
						<td><?php echo getRatio($vo['accepted'],$vo['submissions']);?>% (<?php echo ($vo["accepted"]); ?>/<?php echo ($vo["submissions"]); ?>) </td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				
				</table>
				<div class="row">
			    	<div class="col-md-12">
			    		<div class="text-center"><?php echo ($page); ?></div>
			    	</div>
			    </div>
		</div>
		<div class="col-md-3">
			<div class="label-heading">
					<h3 class="label-title">
						<span class="glyphicon glyphicon-tag" style="color:#008000" aria-hidden="true"></span>
						分类
					</h3>
				</div>
				<table class="table-hover table table-condensed">
					<?php if(is_array($labelData)): $i = 0; $__LIST__ = $labelData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
						<?php if(($vo["problem_number"]) > "0"): ?><td >
								<a href="__APP__/Problem/showProblemList/label_id/<?php echo ($vo["label_id"]); ?>">
									<?php echo ($vo["label_name"]); ?>
								</a>
							</td>
							
							<td><?php echo ($vo["problem_number"]); ?></td><?php endif; ?>
						</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				</table>
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
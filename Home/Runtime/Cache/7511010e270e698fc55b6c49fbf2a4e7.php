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
			<h2>实时评测状态</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<form action="__APP__/Judge/showRealTimeEvaluation" class="form-inline" role="form" method="post"> 
				<div class="form-group">
			    	<label for="exampleInputName2">问题ID</label>
			   		<input type="text" name="problemId" class="form-control" placeholder="问题ID" value="<?php echo ($pid); ?>"/>	
				</div>
				<div class="form-group">
			    	<label for="exampleInputName2">昵称</label>
			   		<input type="text" name="anthor" class="form-control" placeholder="昵称" value="<?php echo ($ant); ?>"/>
				</div>
				
				<div class="form-group">
			    	<label for="exampleInputName2">语言</label>
			   		<select name="language" class="form-control">
						<?php if(is_array($languageArray)): $i = 0; $__LIST__ = $languageArray;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($lan) == $vo["index"]): ?><option value="<?php echo ($vo["index"]); ?>" selected="selected"> <?php echo ($vo["status"]); ?> </option>
							<?php else: ?>
							<option value="<?php echo ($vo["index"]); ?>"> <?php echo ($vo["status"]); ?> </option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</div>
				<div class="form-group">
			    	<label for="exampleInputName2">状态</label>
			   		<select name="status" class="form-control">
						<?php if(is_array($statusArray)): $i = 0; $__LIST__ = $statusArray;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($sta) == $vo["index"]): ?><option value="<?php echo ($vo["index"]); ?>" selected="selected"> <?php echo ($vo["status"]); ?> </option>
								<?php else: ?>
								<option value="<?php echo ($vo["index"]); ?>"><?php echo ($vo["status"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</div>
				<div class="form-group">
			    	<input class="btn btn-default" type="submit" value="搜索">
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-condensed table-striped table-bordered text-center table-hover">
				<tr>
					<th class="text-center">运行ID</th>
					<th class="text-center">提交时间</th>
					<th class="text-center">评测结果</th>
					<th class="text-center">问题ID</th>
					<th class="text-center">运行时间</th>
					<th class="text-center">运行内存</th>
					<th class="text-center">代码长度</th>
					<th class="text-center">语言</th>
					<th class="text-center">昵称</th>
				</tr>
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
						<td>
							<?php if(($vo["user_id"] == $myId) OR ($myRoot == 2) ): ?><a href="__APP__/Judge/showJudgeDetail/id/<?php echo ($vo["id"]); ?>">
									<?php echo ($vo["id"]); ?>
								</a>
							<?php else: ?> <?php echo ($vo["id"]); endif; ?>
							
						</td>
						<td><?php echo (date("Y-m-d H:i",$vo["submit_time"])); ?></td>
						<td>
							
							<?php if(($vo["judge_status"]) == "0"): ?><span style="color: #008000;">Accepted</span><?php endif; ?>
							<?php if(($vo["judge_status"]) == "1"): ?><span style="color: #CC0000;">Wrong Answer</span><?php endif; ?>
							<?php if(($vo["judge_status"]) == "2"): ?><span style="color: #66512C;">Time Limit Exceeded</span><?php endif; ?>
							<?php if(($vo["judge_status"]) == "3"): ?><span style="color: #66512C;">Memory Limit Exceeded</span><?php endif; ?>
							<?php if(($vo["judge_status"]) == "4"): ?><span style="color: #0000FF;">Runtime Error</span><?php endif; ?>
							<?php if(($vo["judge_status"]) == "5"): ?><span style="color: #0000FF;">Compilation Error</span><?php endif; ?>
							<?php if(($vo["judge_status"]) == "6"): ?><span style="color: #990073;">Output Limit Exceeded</span><?php endif; ?>
							<?php if(($vo["judge_status"]) == "7"): ?><span style="color: #990073;">Input Limit Exceeded</span><?php endif; ?>
							<?php if(($vo["judge_status"]) == "8"): ?><span style="color: #DD1144;">Pending</span><?php endif; ?>
							<?php if(($vo["judge_status"]) == "9"): ?><span style="color: cadetblue;">Compiling</span><?php endif; ?>
							<?php if(($vo["judge_status"]) == "10"): ?><span style="color: darkturquoise;">Runing</span><?php endif; ?>
							<?php if(($vo["judge_status"]) == "11"): ?><span style="color: midnightblue;">Presentation Error</span><?php endif; ?>
						</td>
						<td>
							<a href="__APP__/Problem/showProblem/id/<?php echo ($vo["problem_id"]); ?>">
							<?php echo ($vo["problem_id"]); ?>
							</a>
						</td>
						
						<td><?php echo ($vo["exe_time"]); ?> MS</td>
						<td><?php echo ($vo["exe_memory"]); ?> KB</td>
						<td>
							<!--<?php if(($vo["user_id"]) == $myId): ?><a href="__APP__/Judge/showUserCode/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["code_len"]); ?>B</a>
							<?php else: ?>
							
								<?php if(($$myRoot) == "2"): ?><a href="__APP__/Judge/showUserCode/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["code_len"]); ?>B</a><?php endif; ?>
								<?php if(($$myRoot) < "2"): echo ($vo["code_len"]); ?>B<?php endif; endif; ?>-->
							<?php if(($vo["user_id"] == $myId) OR ($myRoot == 2) ): ?><a href="__APP__/Judge/showUserCode/id/<?php echo ($vo["id"]); ?>/userId/<?php echo ($vo["user_id"]); ?>"><?php echo ($vo["code_len"]); ?>B</a>
							<?php else: ?> <?php echo ($vo["code_len"]); ?>B<?php endif; ?>

						</td>
						<td><?php echo ($vo["language"]); ?></td>
						<td>
							<a href="__APP__/User/showUserMessage/id/<?php echo ($vo["user_id"]); ?>">
							<?php echo ($vo["nickname"]); ?>
							</a>
						</td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>	
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<div ><?php echo ($page); ?></div>
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
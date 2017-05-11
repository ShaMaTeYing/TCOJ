<?php
// 本类由系统自动生成，仅供测试用途
class UserAction extends BaseAction {
	public function showLogin(){
		$this->display();
	}
	//登录判断函数
	public function login(){
		$login_msg_data = array();//记录登录信息
		$login_msg_data['ip']=get_client_ip();//获取ip
		import('ORG.Net.IpLocation');// 导入IpLocation类
		$Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
		$location= $Ip->getlocation(get_client_ip()); // 获取某个IP地址所在的位置
		$login_msg_data['area'] =  $location['country'].$location['area'];
		$login_msg_data['login_time']=time();
		$login_msg_data['username']=$_POST['username'];
		$login_msg_data['password']=$_POST['password'];
		$username = $_POST['username'];//获取POST传值
		$psw =$this->myMD5($_POST['password']);
		
		
		$username=htmlspecialchars($username);//将HTML标签转义
		//$psw=htmlspecialchars($psw);
		$user=M('user')->where(array('username'=>$username))->find();
		$login_msg_data['user_id']=$user['id'];
		$login_msg=M('login_msg');
		//判断用户是否存在
		if(!$user){
			$login_msg_data['status']='用户不存在';
			$login_msg->add($login_msg_data);
			session('loginStatus',0);
			$this->error('用户不存在!');
		}
		//判断用户是否禁用
		if($user['status']==0){
			$login_msg_data['status']='用户已被禁用';
			session('loginStatus',0);
			$login_msg->add($login_msg_data);
			$this->error('用户已被禁用!');
		}
		//判断用户的邮箱是否验证
		if($user['status']==2){
			$login_msg_data['status']='用户邮箱未验证';
			session('loginStatus',0);
			$login_msg->add($login_msg_data);
			$this->error('用户邮箱未验证!');
		}
		//判断用户的密码是否一致
		if($user['password']==$psw){
			$login_msg_data['status']='登录成功';
			session('loginStatus',1);//显示登录成功的界面
			session('userinfo',$user);//设置userinfo的值，以便传值给模板
			//$this->success('登录成功',U('Index/index'));
			$ID=$login_msg->add($login_msg_data);
			if($ID){
				dump("ID");
			}else {
				$this->error('写入login_msg数据库失败!');
			}
			$this->redirect('Index/index');
		}else {
			$login_msg_data['status']='密码错误';
			session('loginStatus',0);//继续显示登录界面
			$login_msg->add($login_msg_data);
			$this->error('密码错误!');
		}
		
	}
	public function myMD5($value){
		for($i=1;$i<=5;$i++){
			$value=md5($value);
		}
		return $value;
	}
	//退出登录函数
	public function logout(){
		session('loginStatus',null);
		$login_msg_data = array();
		$login_msg_data['ip']=get_client_ip();//获取ip
		import('ORG.Net.IpLocation');// 导入IpLocation类
		$Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
		 
		$location= $Ip->getlocation(get_client_ip()); // 获取某个IP地址所在的位置
		$login_msg_data['area'] =  $location['country'].$location['area'];
		
		$login_msg_data['login_time']=time();
		$userinfo=session('userinfo');
		$login_msg_data['username']=$userinfo['username'];
		$login_msg_data['user_id']=$userinfo['id'];
		$login_msg_data['status']='退出成功';
		M('login_msg')->add($login_msg_data);
		//$this->success('退出成功！','index');
		$this->redirect('Index/index');
	}
	//显示注册页面
	public function showRegister(){
		$this->display();
	}
	//判断注册信息
	public function getIpData(){
		$ans['ip'] = get_client_ip();
		import('ORG.Net.IpLocation');// 导入IpLocation类
		$Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
		$location= $Ip->getlocation(get_client_ip()); // 获取某个IP地址所在的位置
		$ans['area'] =  $location['country'].$location['area'];
		return $ans;
	}
	public function getRegisterData($data){
		$registerData=array(
			'username' => $data['username'], 
  			'nickname' => $data['nickname'],
  			'password' => $data['password'],
  			'realname' => $data['realname'],
			'mail' => $data['mail'],
		  	'school' => $data['school'],
		 	'major' => $data['major'],
		  	'motto' => $data['motto'],
		  	'register_time' => $data['register_time']
		);
		$registerData['status']=0;
		$registerData['hash']=$this->getRandChar(40);
		$ipData=$this->getIpData();
		$registerData['ip']=$ipData['ip'];
		$registerData['area']=$ipData['area'];
		return $registerData;
	}
	
	public function register(){
		if($_POST['username']==''){
			$this->error("用户名不能为空！");
		}else if($_POST['nickname']==''){
			$this->error("昵称不能为空！");
		}else if($_POST['password']==''){
			$this->error("密码不能为空！");
		}else if($_POST['repassword']==''){
			$this->error("确认密码不能为空！");
		}else if($_POST['realname']==''){
			$this->error("真实姓名不能为空！");
		}else if($_POST['mail']==''){
			$this->error("邮箱不能为空！");
		}else if($_POST['repassword']!=$_POST['password']){
			$this->error("两次密码不一致！");
		}else {
			$userData=M('user')->where(array('username'=>$_POST['username']))->find();//用户名判重
			$mailData=M('user')->where(array('mail'=>$_POST['mail']))->find();//邮箱判重
			if($userData){
				$this->error("该用户已经存在，请重新注册！");
			}
			if($mailData){
				$this->error("该邮箱已经存在，请重新注册！");
			}

			$_POST['register_time']=time();

			foreach($_POST as $key => $value){
				$_POST[$key]=htmlspecialchars($value);
			}
			
			$registerData=$this->getRegisterData($_POST);
			$registerData['password']=$this->myMD5($registerData['password']);
			//dump($registerData);
			//die;
			$status = M('register')->add($registerData);
			if($status){
				$this->sendMail(0,$_POST['mail'],$status);
				session('userRegId',$status);
				$this->success("邮箱验证已发送，请及时进入邮箱进行验证.",U("Index/index"));
			}
		}
	}

	/* 获取用户邮箱 */
	public function showGetMail(){
		$this->display();
	}

	/* 发送修改密码的验证邮件*/
	public function sendEmail(){
		$conditions['mail']=$_POST['mail_required'];
		$userData=M('user')->where($conditions)->find();
		
		if($userData){
			session('userinfo',$userData);
			$this->sendMail(1,$_POST['mail_required']);
			$this->success('发送成功！',U('Index/index'));
		}else{
			$this->error('此邮箱不存在，请重新输入！');
		}
	}
	/*修改密码*/
	public function showModifyPassword(){
		$this->display();
	}
	public function modifyPassword(){
		//dump($_POST);
		$oldPsw=$this->myMD5($_POST['oldPsw']);
		$newPsw=$this->myMD5($_POST['newPsw']);
		$userinfo=session('userinfo');
		if($oldPsw==$userinfo['password']){
			$saveData['password']=$newPsw;
			$userinfo['password']=$newPsw;
			M('user')->where(array('id'=>$userinfo['id']))->save($saveData);
			session('userinfo',$userinfo);
			$this->success("修改成功！",U("Index/index"));
		}
		else {
			$this->error("密码错误！请重新输入！",U("showModifyPassword"));
		}
	}
	/*存储修改后的密码*/
	public function savePassword(){
		if($_POST['password']==''){
			$this->error("密码不能为空！");
		}else if($_POST['repassword']==''){
			$this->error("确认密码不能为空！");
		}else if($_POST['repassword']!=$_POST['password']){
			$this->error("两次密码不一致！");
		}else {
			$userData=session('userinfo');
		
			$userData['password']=$_POST['password'];
			$count=M('user')->where('id='.$userData['id'])->save($userData);
			if($count)
			$this->success('修改成功！',U('Index/index'));
		}
	}
	public function getUserData($data){
		$res['username']=$data['username'];
		$res['password']=$data['password'];
		$res['status']=1;
		$res['root']=0;
		$res['accepted']=0;
		$res['submission']=0;
		$res['solve_problem']=0;
		$res['school']=$data['school'];
		$res['Submitted_problem']=0;
		$res['motto']=$data['motto'];
		$res['mail']=$data['mail'];
		$res['realname']=$data['realname'];
		$res['major']=$data['major'];
		$res['nickname']=$data['nickname'];
		$res['register_time']=$data['register_time'];
		return $res;
	}
	/*注册邮箱认证*/
	public function regCheckMail(){
		$registerData = M('register')->where(array("id"=>$_GET['id']))->find();
		$startTime=intval(time())-intval($registerData['register_time']);
		if($startTime>5*60){
			$this->error('认证链接失效，请重新注册.',U('Login/showRegister'));
		}
		if($_GET['key'] == $registerData['hash']){
			$status = $registerData['status'];
			if($status == 1)
				$this->error('链接已被认证.',U('Index/index'));
			if($status==0)
				//dump($registerData);
				$userData=$this->getUserData($registerData);
	
				$userId=M('user')->add($userData);
				$registerData['status']=1;
				M('register')->save($registerData);
				session('loginStatus',1);//显示登录成功的界面
				$user=M('user')->where(array('id'=>$userId))->find();
				session('userinfo',$user);
				$this->success('邮箱验证已通过，注册成功.',U('Index/index'));
		}else{
			$this->error('认证链接无效,请重新注册',U('Login/showRegister'));
		}
	}

	/*注册或者找回密码*/

	protected function sendMail($type,$userMail,$id){
		$info = $type==0?'注册':'找回密码';
		$method = $type==0?'regCheckMail':'modifyPassword';
		$userMail = $userMail;
		$mailTitle = 'TCOJ'.$info;
		$local='http://localhost/TCOJ';
		$online='http://123.207.3.24/TCOJ';
		$hash=M('register')->where(array('id'=>$id))->find()['hash'];
		$mailContent = $userMail.',您好，您正在通过邮箱认证完成'.$local.'，链接5分钟内有效，请点击认证链接：'.$online.'/index.php/User/'.$method.'/id/'.$id.'/key/'.$hash;
		import('ORG.Net.Mail');
		//发送方的邮箱信息需要在config.php里配置
		$status = SendMail($userMail,$mailTitle,$mailContent,'TCOJ 管理员');
	}
	/*
		生成随机字符串
	*/
	public function getRandChar($length){
	   $str = null;
	   $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
	   $max = strlen($strPol)-1;

	   for($i=0;$i<$length;$i++){
		$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	   }
	   return $str;
	}
	/*显示所有用户*/
	public function showAllUserRank(){
		$sortParam = $_POST['sort_param'];
		
		$where['nickname']  = array('like','%'.$sortParam.'%');
		$where['solve_problem']  = array('like','%'.$sortParam.'%');
		$where['_logic'] = 'or';
		$map['_complex'] = $where;
		$map['status']  = 1;
		M('user')->where(array('register_time'=>array('lt',time()-60),'status'=>2))->delete();
		
		
		$User = M('User'); // 实例化User对象
		import('ORG.Util.Page');// 导入分页类
		$count      = $User->where($map)->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where($map)->order('solve_problem desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		for($i=0;$i<count($list);$i++){
			$list[$i]['rank']=$Page->firstRow+$i+1;
		}
		//dump($list);
		//dump($Page);
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板
		//M('user')->where('status='.'2')->delete();
		//$problemData=M('user')->where($map)->order('solve_problem desc')->select();
		//dump($problemData);
		
		//$this->assign('problemData',$problemData);
		//$this->display();
		
	}
	public function showUserMessage(){
		$con['id']=$_GET['id'];
		$userInfo = M('user')
			->where($con)
			->find();
		$myRank = M('user')
			->where(array('solve_problem'=>array('gt',$userInfo['solve_problem'])))
			->count()+1;
		$conditions['user_id']=$_GET['id'];
		$conditions['judge_status']='0';
		//dump($conditions);
		$problemData=M('user_problem')->distinct('true')->where($conditions)->field('problem_id')->order('problem_id asc')->select();
		//dump($userInfo);
		$this->assign('user',$userInfo);
		$this->assign('myRank',$myRank);
		//dump($problemData);
		$this->assign('problemData',$problemData);
		$this->display();
	
	}
	public function showModifyUserMessage(){
		$userinfo=session('userinfo');
		$this->assign('userinfo',$userinfo);
		$this->display();
	}
	public function modifyUserMessage(){
		$userdata=session('userinfo');
		$psw=$this->myMD5($_POST['password']);
		if($userdata['password']!=$psw){
			$this->error("密码错误,请重新输入!");
		}
		if($_POST['major']) $data['major']=$_POST['major'];
		if($_POST['school']) $data['school']=$_POST['school'];
		if($_POST['motto']) $data['motto']=$_POST['motto'];
		if($_POST['nickname']) {
			$data['nickname']=$_POST['nickname'];
			$userdata['nickname']=$data['nickname'];
			session('userinfo',$userdata);
		}
		$count=M('user')->where('id='.$userdata['id'])->save($data);
		if($count>0){
			
			if($data['nickname']){
				$newNameData['nickname']=$data['nickname'];
				$where['nickname']=M('user_problem')
									->where('user_id='.$userdata['id'])
									->find()['nickname'];
				M('user_problem')->where($where)->save($newNameData);
				
			}
			
		}
		$this->success('修改成功',U('Problem/showProblemList'));
	}
}
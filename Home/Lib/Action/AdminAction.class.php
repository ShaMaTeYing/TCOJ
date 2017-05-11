<?php
// 本类由系统自动生成，仅供测试用途
class AdminAction extends BaseAction {
	/* 构造函数 */
	function _initialize(){
		$userinfo=session('userinfo');
		if(!$userinfo||$userinfo['root']<1){
			$this->error("非法访问，请先登录!",U('User/showLogin'));
		}
		$login=M('user')->where(array('id'=>$userinfo['id']))->find();
		$loginStatus=session('loginStatus');
		$trainLevelData=M('level')->select();
		$this->assign('trainLevelData',$trainLevelData);
	   //控制切换登录窗口
		$this->assign('loginStatus',session('loginStatus')?session('loginStatus'):0);
		if(session('loginStatus'))//登录成功则传值给模板变量
		{
			$this->assign('userinfoData',session('userinfo'));
		}
	}
	/*显示题库管理主界面*/
	public function showProblemLibrary(){
		$User = M('problem'); // 实例化User对象
		import('ORG.Util.Page');// 导入分页类
		$condition['_logic'] = 'OR';
		$condition['title'] = array('like','%'.$_POST['id'].'%');
		$condition['id'] = array('like','%'.$_POST['id'].'%');
		$condition['description']=array('like','%'.$_POST['id'].'%');
		$count = $User->where($condition)->count();
		$Page  = new Page($count,100);// 实例化分页类 传入总记录数和每页显示的记录数
		$show  = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where($condition)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('problemData',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->assign("content",$_POST['id']);
		$this->display();
	} 
	/*显示添加题目主界面*/
	public function showAddProblem(){
		$this->display();
	}
	//删除空格
	public function trimall($str){
	    $qian=array(" ","　","\t","\n","\r");
	    $hou=array("","","","","");
	    return str_replace($qian,$hou,$str); 
	}
	/*添加题目*/
	public function creatProblemData(){
		
		$User = M("problem");
		foreach($_POST as $key=>$value){
			$_POST[$key]=htmlspecialchars($value);
		}
		//dump($_POST);
		$labelString=$_POST['label'];
		$labelString=$this->trimall($labelString);
		$labelData=trim($labelData);
		$labelData=explode(";", $labelString);
		for($i=0;$i<count($labelData);$i++){
			$where['label_name']=$labelData[$i];
			$cnt=M('label_info')->where($where)->count();
			if($cnt==0){
				$data['label_name']=$labelData[$i];
				$data['status']=0;
				M('label_info')->data($data)->add();
			}
			$labelId=M('label_info')->where($where)->find();
			$problemId=$User->max('id');
			$problemLabelData['problem_id']=$problemId+1;
			$problemLabelData['label_id']=$labelId['id'];
			M('problem_label')->data($problemLabelData)->add();
		}
		unset($_POST['label']);
		//dump($_POST);
		//die;
		
		$_POST['id']=$problemId+1;
		$count=$User->add($_POST);
		if($count)
			$this->success('success','showProblemLibrary');
		else $this->error('fail','showProblemLibrary');
	}
	
	/*删除题目*/
	public function deleteProblem(){
		$id=$_GET['id'];
		$User = M("problem"); // 实例化User对象
		$data['status']=$_GET['status'];
		$count=$User->where('id='.$id)->save($data); // 删除id为5的用户数据
		if($count>0) $this->success('success');
		else $this->error('fail');
	}
	/*显示上传页面*/
	public function showUpload(){
		//dump($_GET);
		$this->assign('problemdata',$_GET);
		$this->display();
	}
	/* 显示上传题目数据的页面 */
	public function showUpZipAddProblem(){
		$this->display();
	}
	public function findMatchString($str,$startStr,$endStr){
		$startPos=strpos($str,$startStr);
		$endPos=strpos($str,$endStr);
		$startStrLen=strlen($startStr);
		$endStrLen=strlen($endStr);
		$ansStrLen=$endPos-($startPos+$startStrLen);
		$ansStr=substr($str,$startPos+$startStrLen,$ansStrLen);
		$ansStr=trim($ansStr);
		if(strlen($ansStr)==0){
			$this->error($startStr.'字段不存在，请检查文本格式！',U('Admin/showUpZipAddProblem'));
		}
		return $ansStr;
	}
	public function getUpProblemData($txtData){
		$ans=array();
		$ans['title']=$this->findMatchString($txtData,"题目标题：","题目描述：");
		$ans['time_limit']=1000;
		$ans['memory_limit']=32768;
		$ans['submissions']=0;
		$ans['accepted']=0;
		$ans['description']=$this->findMatchString($txtData,"题目描述：","输入格式：");
		$ans['input']=$this->findMatchString($txtData,"输入格式：","输出格式：");
		$ans['output']=$this->findMatchString($txtData,"输出格式：","样例输入：");
		$ans['sample_input']=$this->findMatchString($txtData,"样例输入：","样例输出：");
		$ans['sample_output']=$this->findMatchString($txtData,"样例输出：","标签：");
		$ans['author']='TCOJ';
		$ans['label']=$this->findMatchString($txtData,"标签：","来源：");
		$source=substr($txtData,strpos($txtData,"来源：")+strlen("来源："));
		$source=trim($source);
		$ans['source']=$source;
		$ans['status']=1;
		$ans['output_limit']=0;
		$ans['case_number']=10;
		$ans['difficulty']=0;
		return $ans;
		
	}
	public function upZipAddProblem(){
		
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		//$upload->allowExts  = array('jpg', 'gif', 'png', 'txt');// 设置附件上传类型
		$upload->savePath =  'Data/Library/describe/';// 设置附件上传目录
		if(!$upload->upload()) {// 上传错误提示错误信息
		$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
		$info =  $upload->getUploadFileInfo();
		//dump($info);
		}
		$filepath=$info[0]['savepath'].$info[0]['savename'];
		$txtData=file_get_contents($filepath);
		$upProblemData=$this->getUpProblemData($txtData);
		//dump($upProblemData);
		$User = M("problem");
		
		$labelString=$upProblemData['label'];
		$labelString=$this->trimall($labelString);
		$labelData=trim($labelData);
		$labelData=explode(";", $labelString);
		for($i=0;$i<count($labelData);$i++){
			$where['label_name']=$labelData[$i];
			$cnt=M('label_info')->where($where)->count();
			if($cnt==0){
				$data['label_name']=$labelData[$i];
				$data['status']=0;
				M('label_info')->data($data)->add();
			}
			$labelId=M('label_info')->where($where)->find();
			$problemId=$User->max('id');
			$problemLabelData['problem_id']=$problemId+1;
			$problemLabelData['label_id']=$labelId['id'];
			M('problem_label')->data($problemLabelData)->add();
		}
		unset($upProblemData['label']);
		//dump($_POST);
		//die;
		
		$upProblemData['id']=$problemId+1;
		$count=$User->add($upProblemData);
		if($count)
			$this->success('success','showProblemLibrary');
		else $this->error('fail','showProblemLibrary');
	}
	//扫描目录下的所有文件
	public function my_scandir($dir){  
	 $files=array();  
	 if(is_dir($dir)){  
	  if($handle=opendir($dir)){  
	   while(($file=readdir($handle))!==false){  
	    if($file!='.' && $file!=".."){  
	     if(is_dir($dir.$file)){  
	      $files[$file]=my_scandir($dir.$file);  
	     }else{  
	      $files[]=$dir.$file;  
	     }  
	    }  
	   }  
	  }  
	 }  
	 closedir($handle);  
	 return $files;  
	}  
	
	public function fileIsOk($dir,$number){
		$files=$this->my_scandir($dir);
		for($i=1;$i<=$number;$i++){
			$in=$dir.$i.'.in';
			$out=$dir.$i.'.out';
			//dump($in);dump($out);
			if(!in_array($in,$files)) return 0;
			if(!in_array($out,$files)) return 0;
			//chmod($in,0777);
			//chmod($in,0777);
		}
		foreach($files as $key => $value){
			chmod($value,0777);
		}
		return 1;
		//die;
	}
	public function deleteOleFile($dir){
		$files=$this->my_scandir($dir);
		foreach($files as $key => $value){
			//dump("删除了 ".$value);
			unlink($value);
		}
	}
	/*上传文件*/
	public function upload(){
		
		$problem_id=$_POST['id'];
		$path='Data/Library/problems/'.$problem_id;
		if(!file_exists($path)) 
		{
			mkdir($path);
			chmod($path, 0777);
		}
		$this->deleteOleFile($path.'/');//删除目录下所有文件
		//设置上传参数
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 8388608 ;// 设置附件上传大小
		$upload->uploadReplace=true;
		$upload->savePath =  $path.'/';// 设置附件上传目录
		$upload->saveRule='';
		//$_FILES['input_path']['name'] = 'in';
		//$_FILES['output_path']['name'] = 'out';
		//$_FILES['file_path']['name'] = 'data.zip';
		
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			$zip = new ZipArchive();//新建一个对象
			//dump($info);
			$filepath=$upload->savePath.$info[0]['name'];
			//dump($filepath);
			if ($zip->open($filepath) === TRUE)
			{
			    if(($zip->numFiles)%2==1){
			    	$this->error("数据文件格式错误！");
			    }
				$zip->extractTo($upload->savePath);//假设解压缩到在当前路径下images文件夹的子文件夹php
				
				if($this->fileIsOk($upload->savePath,$zip->numFiles))
					$this->error("数据文件格式错误！");
				
				$problemData=M('problem')
							->where(array('id'=>$problem_id))
							->find();
				$problemData['case_number']=($zip->numFiles)/2;
				M('problem')
						->where(array('id'=>$problem_id))
						->save($problemData);
				$zip->close();//关闭处理的zip文件
			}
			
			$this->success('success!','showProblemLibrary');
		}
	}
	/*显示修改题目界面*/
	public function showModifyProblem(){
		
		$id=$_GET['id'];
		$data=M('problem')->where('id='.$id)->find();
		//dump($data);
		$list=M("table")->table('label_info a,problem_label b')
			->where("a.id=b.label_id and b.problem_id=".$data['id']." and b.status=0")
			->select();
		//dump($list);
		

		foreach($list as $key => $value){
			
			$labelData=$labelData.$list[$key]['label_name'].";";
		}
		$labelData=rtrim($labelData,';');
		$this->assign('labelData',$labelData);
		$this->assign('data',$data);
		$this->display();
	}
	/*修改题目*/
	public function modifyProblemData(){
		//dump($_POST);
		
		foreach($_POST as $key=>$value){
			$_POST[$key]=htmlspecialchars($value);
		}
		$data=$_POST;
		//设置该题目对应的标签不可用
		$labelData=M('problem_label')->where('problem_id='.$data['id'])->select();
		foreach($labelData as $key => $value){
			$labelData[$key]['status']=1;
			M('problem_label')
				->where('id='.$labelData[$key]['id'])
				->save($labelData[$key]);
		}
		//dump($labelData);
		//提取出修改后的所有标签
		$labelString=$data['label'];
		$labelData=explode(";", $labelString);
		//dump($labelData);
		for($i=0;$i<count($labelData);$i++){
			$where['label_name']=$labelData[$i];
			$cnt=M('label_info')->where($where)->count();
			if($cnt==0){
				$mydata['label_name']=$labelData[$i];
				$mydata['status']=0;
				M('label_info')->data($mydata)->add();
			}
			$labelId=M('label_info')->where($where)->find();
			//$problemId=$User->max('id');
			$problemLabelData['problem_id']=$data['id'];
			$problemLabelData['label_id']=$labelId['id'];
			$res=M('problem_label')->where($problemLabelData)->count();
			if($res){
				$myData['status']=0;
				M('problem_label')->where($problemLabelData)->save($myData);
			}else {
				M('problem_label')->data($problemLabelData)->add();
			}
				
		}
		unset($_POST['label']);
		//dump($_POST);
		//die;
		$count=M('problem')->where('id='.$_POST['id'])->save($_POST);
		if($count>0){
			$this->success('success!','showProblemLibrary');
		}else {
			$this->error('fail','showProblemLibrary');
		}
	}
	
	/*显示所有登录信息*/
	public function showLoginMessage(){
		$value=$_POST['value'];
		$where['username']  = array('like','%'.$value.'%');
		$where['area']  = array('like','%'.$value.'%');
		$where['ip']  = array('like','%'.$value.'%');
		//$where['login_time']  = array('like','%'.$value.'%');
		$where['status']  = array('like','%'.$value.'%');
		$where['_logic'] = 'or';
	

		$loginMessage=M('login_msg')->order('id desc')->where($where)->select();
		$this->assign('loginMessage',$loginMessage);
		
		$this->display();
	}
	public function showUserMessage(){
		$value=$_POST['value'];
		$where['username']  = array('like','%'.$value.'%');
		$where['nickname']  = array('like','%'.$value.'%');
		$where['realname']  = array('like','%'.$value.'%');
		//$where['login_time']  = array('like','%'.$value.'%');
		$where['mail']  = array('like','%'.$value.'%');
		$where['status']  = array('like','%'.$value.'%');
		$where['root']  = array('like','%'.$value.'%');
		$where['school']  = array('like','%'.$value.'%');
		$where['major']  = array('like','%'.$value.'%');
		$where['_logic'] = 'or';
		

		$map['_complex'] = $where;
		//$map['status']  = 1;
		$userinfo=session('userinfo');
		$map['root']=array('elt',$userinfo['root']);

		$userMessage=M('user')->where($map)->select();
		//dump($userMessage);
		$this->assign('userMessage',$userMessage);
		$this->display();
	}
	public function operation(){
		if($_GET['type']==1){
			if(M('user')->where('id='.$_GET['id'])->setField('status',$_GET['status']))
			$this->success('success!',U('Admin/showUserMessage'));
		}else {
			if(M('user')->where('id='.$_GET['id'])->setField('root',$_GET['status']))
			$this->success('success!',U('Admin/showUserMessage'));
		}
	}
	public function reJudge(){
		//dump($_GET);
		$data['judge_status']=8;
		$allJudgeRecord=M('user_problem')->where('problem_id='.$_GET['id'])->save($data);
		$this->success('success!',U('Admin/showProblemLibrary'));
	}
	public function showUserRecord(){
		//dump($_GET);
		//dump($id);
		$userinfo = session('userinfo');
		$problemId=$_POST['problemId'];
		
		$language=$_POST['language'];
		$judgeResults=$_POST['status'];
		if($_GET['id']) $where['user_id']=$_GET['id'];
		else $where['user_id']=$_POST['id'];
		if($language) $where['language']=$language;
		if($judgeResults) $where['judge_results']=$judgeResults;
		if($problemId) $where['problem_id']  = $problemId;
		
		//dump(M('user_problem')->where($where)->select());
		$where['_logic'] = 'and';
		//dump($where);
		$User = M('user_problem'); // 实例化User对象
		import('ORG.Util.Page');// 导入分页类
		$count = $User->where($where)->count();// 查询满足要求的总记录数
		$Page  = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show  = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$userinfo = session('userinfo');
		$this->myId = $userinfo['id'];
		$this->userId=$where['user_id'];
		$this->myRoot=$userinfo['root'];
		$this->display(); // 输出模板
	}
	public function userReJudge(){
		$data['judge_status']=8;
		$allJudgeRecord=M('user_problem')->where('id='.$_GET['id'])->save($data);
		//$this->showUserMessage();
		$this->success('success!',U('Admin/showUserRecord',array('id'=>$_GET['uid'])));
	}
	public function trainIndex(){
		
		$this->display();
	}
	public function showModifyLevel(){
		$levelData=M('level')->where(array("id"=>$_GET['id']))->find();
		$this->assign('levelData',$levelData);
		//dump($levelData);
		$this->display();
	}
	public function modifyLevel(){
		foreach($_POST as $key => $value){
			if($key!="id") $levelData[$key]=$value;
		}
		M('level')->where(array("id"=>$_POST['id']))->save($levelData);
		$this->success("修改成功",'trainIndex');
	}
	public function showLevelList(){
		$levelData=M('level')->select();
		$this->assign('levelData',$levelData);
		$this->display();
	}
	public function modifyTrainLevelStatus(){
		$statusData['status']=$_GET['status'];
		$res=M('level')->where(array("id"=>$_GET['id']))->save($statusData);
		if($res){
			$this->success('修改成功！',U('showLevelList'));
		}else {
			$this->error('修改失败！',U('showLevelList'));
		}
	}
	public function showTaskList(){
		$levelData=M('level')->where(array("id"=>$_GET['id']))->find();
		$this->assign('levelData',$levelData);
		$levelMsgData=M('level_msg')->where(array('level_id'=>$_GET['id']))->select();
		$this->assign('levelMsgData',$levelMsgData);
		$this->assign('levelMsgId',$_GET['id']);
		$this->display();
	}
	public function showModifyTaskStatus(){
		$modifyStatus['status']=$_GET['status'];
		$res=M('level_msg')->where(array("id"=>$_GET['id']))->save($modifyStatus);
		if($res){
			$this->success("修改成功！",U('trainIndex'));
			
		}else {
			$this->error("修改失败！",U('trainIndex'));
		}
	}
	public function showModifyTask(){
		//dump($_GET);
		$levelMsgData=M('level_msg')->where(array("id"=>$_GET['id']))->find();
		//dump($levelMsgData);
		$this->assign('levelMsgData',$levelMsgData);
		$this->display();
	}
	public function modifyTask(){
		foreach($_POST as $key => $value){
			if($key!="id") $levelMsgData[$key]=$value;
		}
		M('level_msg')->where(array("id"=>$_POST['id']))->save($levelMsgData);
		$this->success("修改成功",'trainIndex');
	}
	public function showAddTask(){
		//dump($_GET);
		$this->assign('levelMsgId',$_GET['id']);
		$this->display();
	}
	public function addTask(){
		foreach($_POST as $key => $value){
			if($key!="id") $levelMsgData[$key]=$value;
		}
		M('level_msg')->where(array("id"=>$_POST['id']))->add($levelMsgData);
		$this->success("新增成功",'trainIndex');
	}
	public function showTrainProblemList(){
		if($_GET['id']){
			session('trainLevelMsgId',$_GET['id']);
		}else {
			$_GET['id']=session('trainLevelMsgId');
		}
		$levelMsgData=M('level_msg')->where(array("id"=>$_GET['id']))->find();
		$trainProblemData=M('train_problem')->where(array("level_msg_id"=>$_GET['id']))->select();
		$this->assign('levelMsgData',$levelMsgData);
		$this->assign('trainProblemData',$trainProblemData);
		$this->display();
	}
	public function modifyTrainProblemStatus(){
		
		$modifyStatus['status']=$_GET['status'];
		$res=M('train_problem')->where(array("id"=>$_GET['id']))->save($modifyStatus);
		if($res){
			$this->success("修改成功！",U('trainIndex'));
			
		}else {
			$this->error("修改失败！",U('trainIndex'));
		}
	}
	public function showTrainProblem(){
		$trainProblemData=M('train_problem')->where(array("id"=>$_GET['id']))->find();
		$this->assign('problemData',$trainProblemData);
		$this->display();
	}
	public function showTrainModifyProblem(){
		$trainProblemData=M('train_problem')->where(array("id"=>$_GET['id']))->find();
		$this->assign('data',$trainProblemData);
		$this->display();
	}
	public function modifyTrainProblemData(){
		
		$res=M('train_problem')->where(array("id"=>$_POST['id']))->save($_POST);
		if($res){
			$this->success("修改成功！",U('trainIndex'));
		}else {
			$this->error("修改失败",U('trainIndex'));
		}
	}
	public function showTrainAddProblem(){
		$levelMsgData=M('level_msg')->select();
		$this->assign('levelMsgData',$levelMsgData);
		$this->display();
	}
	public function creatTrainProblemData(){
		$res=M('train_problem')->add($_POST);
		if($res>0){
			$this->success('新增成功！',U('trainIndex'));
		}else {
			$this->success('新增失败！',U('trainIndex'));
		}
	}
	public function showTrainUpFileAddProblem(){
		$this->assign('levelMsgId',$_GET['id']);
		$this->display();
	}
	public function trainUpFileAddProblem(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		//$upload->allowExts  = array('jpg', 'gif', 'png', 'txt');// 设置附件上传类型
		$upload->savePath =  'Data/Train/describe/';// 设置附件上传目录
		if(!$upload->upload()) {// 上传错误提示错误信息
		$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
		$info =  $upload->getUploadFileInfo();
		//dump($info);
		}
		$filepath=$info[0]['savepath'].$info[0]['savename'];
		$txtData=file_get_contents($filepath);
		$upProblemData=$this->getUpProblemData($txtData);
		$upProblemData['level_msg_id']=$_POST['id'];
		//dump($upProblemData);
		$User = M("train_problem");
		
		
		unset($upProblemData['label']);
		
		$count=$User->add($upProblemData);
		if($count)
			$this->success('success','showTrainProblemList');
		else $this->error('fail','showTrainProblemList');
	}
	public function showTrainUpData(){
		$this->assign('problemdata',$_GET);
		$this->display();
	}
	public function trainUpload(){
		$problem_id=$_POST['id'];
		$path='Data/Train/problems/'.$problem_id;
		if(!file_exists($path)) 
		{
			mkdir($path);
			chmod($path, 0777);
		}
		$this->deleteOleFile($path.'/');//删除目录下所有文件
		//设置上传参数
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 83886080 ;// 设置附件上传大小
		$upload->uploadReplace=true;
		$upload->savePath =  $path.'/';// 设置附件上传目录
		$upload->saveRule='';
		//$_FILES['input_path']['name'] = 'in';
		//$_FILES['output_path']['name'] = 'out';
		//$_FILES['file_path']['name'] = 'data.zip';
		
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			$zip = new ZipArchive();//新建一个对象
			//dump($info);
			$filepath=$upload->savePath.$info[0]['name'];
			//dump($filepath);
			//die;
			if ($zip->open($filepath) === TRUE)
			{
			    if(($zip->numFiles)%2==1){
			    	$this->error("数据文件格式错误！");
			    }
			    
				$zip->extractTo($upload->savePath);//假设解压缩到在当前路径下images文件夹的子文件夹php
				
				if($this->fileIsOk($upload->savePath,$zip->numFiles))
					$this->error("数据文件格式错误！");
				
				$problemData=M('problem')
							->where(array('id'=>$problem_id))
							->find();
				$problemData['case_number']=($zip->numFiles)/2;
				M('problem')
						->where(array('id'=>$problem_id))
						->save($problemData);
				$zip->close();//关闭处理的zip文件
			}
			
			$this->success('success!','trainIndex');
		}
	}
}
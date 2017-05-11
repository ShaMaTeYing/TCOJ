<?php
// 本类由系统自动生成，仅供测试用途
class TrainAction extends BaseAction {
	public function index(){
		$levelData=M('level')->where(array("status"=>0))->select();
		$this->assign('levelData',$levelData);
		$this->display();
	}
	public function showTaskList(){
		$levelMsg=M('level_msg')->where(array('level_id'=>$_GET['id'],'status'=>0))->select();
		$levelData=M('level')->where(array('id'=>$_GET['id']))->find();
		session('levelId',$_GET['id']);
		$this->assign("levelData",$levelData);
		$this->assign("listData",$levelMsg);
		$this->display();
	}
	public function showProblemList(){
		$levelId=session('levelId');
		$levelMsg=M('level_msg')->where(array('id'=>$levelId))->find();
		if($_GET['id']) session('levelMsgId',$_GET['id']);
		$levelMsgId=session('levelMsgId');
		$problemData=M('train_problem')->where(array('level_msg_id'=>$levelMsgId,'status'=>1))->select();
		//dump($levelMsg);
		$this->assign("listData",$levelMsg);
		$this->assign("problemData",$problemData);
		$this->display();
	}
	public function getStatusArray(){
		$status=array('Accepted','Wrong Answer','Time Limit Exceeded',
		'Memory Limit Exceeded','Runtime Error','Compilation Error',
		'Output Limit Exceeded','Input Limit Exceeded','pending',
		'Compiling','runing','All');
		$results=array(array());
		foreach($status as $key => $value){
			$results[$key]['index']=$key;
			$results[$key]['status']=$value;
		}
		return $results;
	}
	public function getLanguage(){
		$language=array('All','C++','C');
		$results=array(array());
		foreach($language as $key => $value){
			$results[$key]['index']=$value;
			$results[$key]['status']=$value;
		}
		return $results;
	}
	public function showTaskJudge(){
		$levelId=session('levelId');
		$levelMsgId=session('levelMsgId');
		$levelMsg=M('level_msg')->where(array('id'=>$levelId))->find();
//		$judgeData=M('train_user_problem')->where(array('level_msg_id'=>$_GET['id']))->select();
//		dump($judgeData);
		$this->assign("listData",$levelMsg);
//		$this->assign("judgeData",$judgeData);
//		$this->display();
		
		$statusArray=$this->getStatusArray();
		$languageArray=$this->getLanguage();
		//dump($statusArray);
		//dump($languageArray);
		$this->assign("statusArray",$statusArray);
		$this->assign("languageArray",$languageArray);
		$userinfo = session('userinfo');
		$problemId=$_POST['problemId'];
		$anthor=$_POST['anthor'];
		$language=$_POST['language'];
		$judgeResults=$_POST['status'];
		$parmCnt=0;
		$where['level_msg_id']=session('levelMsgId');
		if($language&&$language!="All") {
			$where['language']=$language;
			$parmCnt=$parmCnt+1;
			
		}
		if(isset($judgeResults)) {
			if($judgeResults!=11){
				$where['judge_status']=$judgeResults;
				$parmCnt=$parmCnt+1;
			}
			
		}
		if($problemId) {
			$where['problem_id']  = $problemId;
			$parmCnt=$parmCnt+1;
			
		}
		if($anthor) {
			$where['nickname']  = array('like','%'.$anthor.'%');
			$parmCnt=$parmCnt+1;
			
		}
		if($parmCnt>1) $where['_logic'] = 'and';
		//dump($where);
		$User = M('train_user_problem'); // 实例化User对象
		import('ORG.Util.Page');// 导入分页类
		$count = $User->where($where)->count();// 查询满足要求的总记录数
		$Page  = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show  = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $key => $value){
			$list[$key]['title']=
			M('train_problem')->where(array('id'=>$list[$key]['problem_id']))->find()['title'];
		}
		
		//$list['username']=$userinfo;
		if(!$language) $language="All";
		//dump($language);
		if(!isset($judgeResults)) $judgeResults=11;
		//dump($judgeResults);
		$this->assign("lan",$language);
		$this->assign("sta",$judgeResults);
		$this->assign("pid",$problemId);
		$this->assign("ant",$anthor);
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$userinfo = session('userinfo');
		$this->myId = $userinfo['id'];
		$this->myRoot=$userinfo['root'];
		$this->display(); // 输出模板
	}
	public function replace($arr){
		//dump($arr);
		$arr['sample_input']=str_replace('\n', '<br>', $arr['sample_input']);
		
		$arr['sample_output']=str_replace('\n', '<br>', $arr['sample_output']);
		
		$arr['input']=str_replace('\n', '<br>', $arr['input']);
		$arr['output']=str_replace('\n', '<br>', $arr['output']);
	
		$arr['description']=str_replace('\n', '<br>', $arr['description']);
		return $arr;
	}
	public function showProblem(){
		//dump($_GET);
		$levelId=session('levelId');
		$levelMsgId=session('levelMsgId');
		$levelMsg=M('level_msg')->where(array('id'=>$levelId))->find();
		$problemData=M('train_problem')->where(array('id'=>$_GET['id']))->find();
		$problemData=$this->replace($problemData);
		$this->assign("listData",$levelMsg);
		$this->assign("problemData",$problemData);
		$this->display();
	}
	public function showTaskRank(){
		
		$levelId=session('levelId');
		$levelMsgId=session('levelMsgId');
		$levelMsg=M('level_msg')->where(array('id'=>$levelId))->find();
		$this->assign("listData",$levelMsg);
		
		$User = M('train_rank'); // 实例化User对象
		import('ORG.Util.Page');// 导入分页类
		$count      = $User->where(array('level_msg_id'=>$levelMsgId))->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where(array('level_msg_id'=>$levelMsgId))->order('solve_problem desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		
		foreach($list as $key => $value){
			$nickname=M('user')->where(array('id'=>$list[$key]['user_id']))->find()['nickname'];
			$list[$key]['nickname']=$nickname;
			$list[$key]['rank']=$key+1;
		}
		//dump($list);
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板;
	}
	public function showSubmit(){
		$levelId=session('levelId');
		$levelMsgId=session('levelMsgId');
		$levelMsg=M('level_msg')->where(array('id'=>$levelId))->find();
		//dump($_GET);
		$this->assign("listData",$levelMsg);
		$this->assign("level_msg_id",$_GET['level_msg_id']);
		$this->assign("listData",$levelMsg);
		$this->assign("id",$_GET['id']);
		$this->assign("title",$_GET['title']);
		$this->display();
	}
	public function creatFile($filename){
		if(!file_exists($filename)) {
			mkdir($filename);
			chmod($filename, 0777);
		}
	}
	public function onlineJudge(){
		
		$userinfo = session('userinfo');
		$train='train';
		$code=$train.'/code';
		$userpath=$code.'/'.$userinfo['username'];
		$this->creatFile($train);
		$this->creatFile($code);
		$this->creatFile($userpath);
		$lan=$_POST['language'];
		if($lan=='C++'){
			$ext='.cpp';
		}else if($lan=='C'){
			$ext='.c';
		}
		$condition['user_id']=$userinfo['id'];
		$condition['problem_id']=$_POST['problemID'];
		$submitSum=M('train_user_problem')->where($condition)->Count();
		$submitSum=$submitSum+1;
		
		$filepath = $userpath.'/'.$_POST['problemID'].'_'.$submitSum.$ext;
		
		$sourceCode =$_POST['code'];
		file_put_contents($filepath, $sourceCode);
		chmod($filename, 0777);
		
		$resultData['user_id']=$userinfo['id'];
		$resultData['problem_id']=$_POST['problemID'];
		$resultData['submit_time']=time();
		$resultData['judge_status']=8;
		$resultData['exe_time']=0;
		$resultData['exe_memory']=0;
		$resultData['code_len']=strlen($_POST['code']);
		$resultData['language']=$_POST['language'];
		$resultData['nickname']=$userinfo['nickname'];
		$resultData['filepath']=$filepath;
		$resultData['judge_results']=0;
		$resultData['level_msg_id']=$_POST['level_msg_id'];
			
		$message=M('train_user_problem');
		$userProblemId=$message->add($resultData);
		
		//$caseNumber
		$problemData=M('train_problem')->where('id='.$_POST['problemID'])->find();
		$caseNumber=$problemData['case_number'];
		
		$judgeDetail['user_problem_id']=$userProblemId;
		$judgeDetail['judge_status']=8;
		$judgeDetail['exe_time']=0;
		$judgeDetail['exe_memory']=0;
		$judgeDetail['score']=0;
		$judgeDetail['group_score']=intval(100/$caseNumber);
		for($i=1;$i<=$caseNumber;$i++){
			$caseInputPath='train/problems'.'/'.$_POST['problemID'].'/'.$i.'.in';
			$caseOutputPath='train/problems'.'/'.$_POST['problemID'].'/'.$i.'.out';
			$judgeDetail['group_id']=$i;
			$judgeDetail['input_file_path']=$caseInputPath;
			$judgeDetail['output_file_path']=$caseOutputPath;
			if($i==$caseNumber) $judgeDetail['group_score']=100-($caseNumber-1)*intval(100/$caseNumber);
			M('train_judge_detail')->add($judgeDetail);
		}
		$this->redirect('Train/showTaskJudge');
	}
	public function showSourceCode(){
		$levelId=session('levelId');
		$levelMsgId=session('levelMsgId');
		$levelMsg=M('level_msg')->where(array('id'=>$levelId))->find();
		$this->assign("listData",$levelMsg);
		$data=M('train_user_problem')->where(array('id'=>$_GET['id']))->find();
		$filepath = $data['filepath'];
		$contents=file_get_contents($filepath);
		$contents = htmlspecialchars($contents);
		$this->assign('code',$contents);
		$this->display();
	}
	public function showJudgeDetail(){
		$levelId=session('levelId');
		$levelMsgId=session('levelMsgId');
		$levelMsg=M('level_msg')->where(array('id'=>$levelId))->find();
		$this->assign("listData",$levelMsg);
		//dump($_GET);
		$userProblemData=M('train_user_problem')
				->where(array('id'=>$_GET['id']))
				->find();
				
		$judgeData=M('train_judge_detail')
				->where(array('user_problem_id'=>$userProblemData['id']))
				->select();
		//dump($judgeData);
		$problemData=M('train_problem')
				->where(array('id'=>$userProblemData['problem_id']))
				->find();
		
		$allScore=0;
		//dump($judgeData);
		foreach($judgeData as $key => $value){
			$status=$judgeData[$key]['judge_status'];
			//dump($status);
			if($status>=0&&$status<=7){
				if($status==0) {
					//dump("TEST");
					$allScore=$allScore+$judgeData[$key]['group_score'];
					$judgeData[$key]['score']=$judgeData[$key]['group_score'];
					$data['score']=$judgeData[$key]['group_score'];
					//dump($data);
					$newScore['score']=$judgeData[$key]['group_score'];
					M('judge_detail')->where('id='.$judgeData[$key]['id'])->save($newScore);
					M('judge_detail')->where('id='.$judgeData[$key]['id'])->save($data);
				}
			}
		}
		
		//dump($problemData);
		//die;
		$userinfo = session('userinfo');
		$this->assign('score',$allScore);
		$this->assign('myRoot',$userinfo['root']);
		$this->assign('judgeData',$judgeData);
		$this->assign('userProblemData',$userProblemData);
		$this->assign('problemData',$problemData);
		$this->display();
	}
	public function getWangEditorData(){
		$levelId=session('levelId');
		$levelMsgId=session('levelMsgId');
		$userinfo=session('userinfo');
		$levelMsg=M('level_msg')->where(array('id'=>$levelId))->find();
		$this->assign("listData",$levelMsg);
		$BBSData['comment']=$_POST['html'];
		$BBSData['level_msg_id']=$levelMsgId;
		$BBSData['user_id']=$userinfo['id'];
		$BBSData['submit_time']=time();
		M('train_comment')->add($BBSData);
		$resData['url']=U('showBBS');
		$resData['status']=1;
		$this->ajaxReturn($resData, 'json');
	}
	public function showBBS(){
		$levelId=session('levelId');
		$levelMsgId=session('levelMsgId');
		$userinfo=session('userinfo');
		$levelMsg=M('level_msg')->where(array('id'=>$levelId))->find();
		$this->assign("listData",$levelMsg);
		
		
		$User = M('train_comment'); // 实例化User对象
		import('ORG.Util.Page');// 导入分页类
		$count      = $User->where(array('level_msg_id'=>$levelMsgId))->count();// 查询满足要求的总记录数
		$Page       = new Page($count,4);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$BBSData = $User->where(array('level_msg_id'=>$levelMsgId))->order('submit_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($BBSData as $key => $value){
			$BBSData[$key]['nickname']=M('user')->where(array('id'=>$userinfo['id']))->find()['nickname'];
		}
		$this->assign("BBSData",$BBSData);
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}
}
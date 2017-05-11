<?php
// 本类由系统自动生成，仅供测试用途
class JudgeAction extends BaseAction {
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
	public function showRealTimeEvaluation(){
		
		$statusArray=$this->getStatusArray();
		$languageArray=$this->getLanguage();
	
		$this->assign("statusArray",$statusArray);
		$this->assign("languageArray",$languageArray);
		$userinfo = session('userinfo');
		$problemId=$_POST['problemId'];
		$anthor=$_POST['anthor'];
		$language=$_POST['language'];
		$judgeResults=$_POST['status'];
		$parmCnt=0;
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
		
		$User = M('user_problem'); // 实例化User对象
		import('ORG.Util.Page');// 导入分页类
		$count = $User->where($where)->count();// 查询满足要求的总记录数
		$Page  = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show  = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		//dump($count);
		//dump($Page);
		
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
	public function onlineJudge(){
		$problemData=M('problem')->where('id='.$_POST['problemID'])->find();
		if(!$problemData){
			$this->error('非法操作!!!!!!!');
		}
		$caseNumber=$problemData['case_number'];
		$number=intval($problemData['time_limit'],10);
		$timeLimit=$number;
		$memoryLimit=32768;
		$userinfo = session('userinfo');
		$code='Data/Library/code';
		if(!file_exists($code)) {
			mkdir($code);
			chmod($code, 0777);
		}
		if(!file_exists($code.'/'.$userinfo['username'])) {
			mkdir($code.'/'.$userinfo['username']);
			chmod($code.'/'.$userinfo['username'], 0777);
		}
		
		//dump($_POST);
		
		$lan=$_POST['language'];
		$ext='';
		if($lan=='C++'){
			$ext='.cpp';
		}else if($lan=='C'){
			$ext='.c';
		}else if($lan=='Java') {
			$ext='.java';
		}else if($lan=='PHP'){
			$ext='.php';
		}
		

		$condition['user_id']=$userinfo['id'];
		$condition['problem_id']=$_POST['problemID'];
		$submitSum=M('user_problem')->where($condition)->Count();
		$submitSum=$submitSum+1;
		//dump($submitSum);
		
		$filename = $code.'/'.$userinfo['username'].'/'.$_POST['problemID'].'_'.$submitSum.$ext;
		$word =$_POST['code'];
		file_put_contents($filename, $word);
		chmod($filename, 0777);

		$destFile=$filename;
		$file=$_POST['problemID'];
		//for example $destFile="code/wuying/1000_1.cpp"
		//for example $file="1000"
		//$t1 = microtime(true);
		//exec("./jxnuoj $destFile $file $timeLimit $memoryLimit", $output, $verdict);
		//$t2 = microtime(true);
		//$verdict=0;
		
		$verdict=8;//设置成待判状态

//		$resultStatus[0]='Accepted';
//		$resultStatus[1]='Wrong Answer';
//		$resultStatus[2]='Time Limit Exceeded';
//		$resultStatus[3]='Memory Limit Exceeded';
//		$resultStatus[4]='Runtime Error';
//		$resultStatus[5]='Compilation Error';
//		$resultStatus[6]='Output Limit Exceeded';
//		$resultStatus[7]='Input Limit Exceeded';
//		$resultStatus[8]='pending';
//		$resultStatus[9]='Compiling';
//		$resultStatus[10]='runing';
		
		//dump($resultStatus);
		
		$resultData['user_id']=$userinfo['id'];
		$resultData['problem_id']=$_POST['problemID'];
		$resultData['submit_time']=time();
		$resultData['judge_status']=$verdict;
		$resultData['exe_time']=0;
		$resultData['exe_memory']=0;
		$resultData['code_len']=strlen($_POST['code']);
		$resultData['language']=$_POST['language'];
		$resultData['nickname']=$userinfo['nickname'];
		$resultData['filepath']=$filename;
		//dump($resultData);
		
		
			
		$message=M('user_problem');
		$userProblemId=$message->add($resultData);
		
		//$caseNumber
		$judgeDetail['user_problem_id']=$userProblemId;
		$judgeDetail['judge_status']=8;
		$judgeDetail['exe_time']=0;
		$judgeDetail['exe_memory']=0;
		$judgeDetail['score']=0;
		$judgeDetail['group_score']=intval(100/$caseNumber);
		for($i=1;$i<=$caseNumber;$i++){
			$caseInputPath='problems'.'/'.$_POST['problemID'].'/'.$i.'.in';
			$caseOutputPath='problems'.'/'.$_POST['problemID'].'/'.$i.'.out';
			$judgeDetail['group_id']=$i;
			$judgeDetail['input_file_path']=$caseInputPath;
			$judgeDetail['output_file_path']=$caseOutputPath;
			if($i==$caseNumber) $judgeDetail['group_score']=100-($caseNumber-1)*intval(100/$caseNumber);
			M('judge_detail')->add($judgeDetail);
		}
		$this->redirect('Judge/showRealTimeEvaluation');
	}
	public function showUserCode(){
		$id=$_GET['id'];
		$condition['id']=$id;
		$userinfo=session('userinfo');
		if($userinfo['root']==0&&$userinfo['id']!=$_GET['userId']){
			$this->error('不许偷看别人的源代码哦！',U("Judge/showRealTimeEvaluation"));
		}
		$data=M('user_problem')->where($condition)->find();
		
		$filename = $data['filepath'];

		$contents=file_get_contents($filename);
		$contents = htmlspecialchars($contents);
		$this->assign('code',$contents);
		$this->display();
	}
	public function showJudgeDetail(){
		$userProblemData=M('user_problem')
				->where(array('id'=>$_GET['id']))
				->find();
		$judgeData=M('judge_detail')
				->where(array('user_problem_id'=>$userProblemData['id']))
				->select();
		//dump($judgeData);
		$problemData=M('problem')
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
	public function showTestData(){
		//dump($_GET);
		$data=M('judge_detail')->where(array('id'=>$_GET['id']))->find();
		if($_GET['op']) $filePath=$data['output_file_path'];
		else $filePath=$data['input_file_path'];
		$contents=file_get_contents($filePath);
		$contents = htmlspecialchars($contents);
		$this->assign('text',$contents);
		$this->display();
	}
}
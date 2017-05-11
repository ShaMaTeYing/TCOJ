<?php
// 本类由系统自动生成，仅供测试用途
class ProblemAction extends BaseAction {
	public function index(){
		$this->display();
	}
	public  function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){  
        if(is_array($arrays)){  
            foreach ($arrays as $array){  
                if(is_array($array)){  
                    $key_arrays[] = $array[$sort_key];  
                }else{  
                    return false;  
                }  
            }  
        }else{  
            return false;  
        } 
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);  
        return $arrays;  
    } 
    public function showProblemList(){
		$userinfo = session('userinfo');
		$value=$_POST['value'];
		//dump($_GET);
		$labelId=$_GET['label_id'];
		//dump($labelId);
		if($labelId){
			$list=M("table")->table('problem a,problem_label b')
			->where("a.id=b.problem_id and b.label_id=".$labelId." and b.status=0 and a.status=1")
			->select();
		
			//dump($list);
			
			//$User = M('problem'); // 实例化User对象
			import('ORG.Util.Page');// 导入分页类
			$count = M("table")->table('problem a,problem_label b')
			->where("a.id=b.problem_id and b.label_id=".$labelId." and b.status=0 and a.status=1")->count();// 查询满足要求的总记录数
			$Page  = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
			
			$show  = $Page->show();// 分页显示输出
			//dump($list);
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$list = M("table")->table('problem a,problem_label b')
			->where("a.id=b.problem_id and b.label_id=".$labelId." and b.status=0 and a.status=1")->limit($Page->firstRow.','.$Page->listRows)
			->order('problem_id')
			->select();
			//dump($list);
			$listIds = M("table")->table('problem a,problem_label b')
			->where("a.id=b.problem_id and b.label_id=".$labelId." and b.status=0 and a.status=1")
			->limit($Page->firstRow.','.$Page->listRows)
			->getField('problem_id',true);
//			foreach($list as $key => $value){
//				$list['id']=$list['problem_id'];
//			}
			//dump($listIds);
		}
		else {
			$where['title']  = array('like','%'.$value.'%');
			$where['id']  = array('like','%'.$value.'%');
			$where['description']  = array('like','%'.$value.'%');
			$where['_logic'] = 'or';
			$map['_complex'] = $where;
			$map['status']=1;
			//$problemData=M('problem')->select();
			//$this->assign('problemData',$problemData);
			
			$User = M('problem'); // 实例化User对象
			import('ORG.Util.Page');// 导入分页类
			$count = $User->where($map)->count();// 查询满足要求的总记录数
			$Page  = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
			$show  = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$list = $User->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		//	dump($Page);
			$listIds = $User
			->where($map)
			->limit($Page->firstRow.','.$Page->listRows)
			->getField('id',true);
			//dump($listIds);
		}		
		//dump($listIds);
		$userDo = M('user_problem')
			->where(array('problem_id'=>array(IN,$listIds),'user_id'=>$userinfo['id']))
			->distinct('judge_status')
			->order('problem_id')
			->field('problem_id,judge_status')
			->select();
		//dump($userDo);
		$userDoNew = array();
        foreach($userDo as $k => $v){
			if($v['judge_status'] == 0 || $userDoNew[$v['problem_id']] > 0 
				|| $userDoNew[$v['problem_id']] == null){
				$userDoNew[$v['problem_id']] = $v['judge_status'];
			}
		}
		//dump($userDoNew);
		//dump($list);
		foreach($list as $k1 => $v1){
			if($userDoNew[$v1['problem_id']]==null)
				$list[$k1]['judge_status'] = $userDoNew[$v1['id']];
			else $list[$k1]['judge_status'] =$userDoNew[$v1['problem_id']];
			if(isset($list[$k1]['problem_id'])) $list[$k1]['id']=$list[$k1]['problem_id'];
		}
		//dump($list);
		
		$this->assign('problemData',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出

		
		//提取标签数据
		$Label=M('label_info');
		$LabelAllData=$Label->where("status=0")->select();
		$labelData=array(array());
		foreach($LabelAllData as $k => $v){
			$labelData[$k]['label_name']=$v['label_name'];
			$labelData[$k]['label_id']=$v['id'];
			$tmp=M('problem_label')->where(array('label_id'=>$v['id'],'status'=>0))->count();
			if($v['status']){
				$labelData[$k]['problem_number']=0;
			}
			else {
				$labelData[$k]['problem_number']=$tmp;
			}
		}
		$labelData=$this->my_sort($labelData,'problem_number',SORT_DESC);
		$this->assign('labelData',$labelData);
		//dump($labelData);
		$this->display();
		
		//$this->display();
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
		$problemId=$_GET['id'];
		//echo "problemId".$problemId;
		$arr=M('problem')->find($problemId);
		if(!$arr){
			$this->error('不要调皮，乱输入题号！',U("Index/index"));
		}
		//$arr=$this->replace($arr);
		$this->assign('problemData',$arr);
		$this->display();
	}
	public function showSubmit(){
		if(!session('loginStatus')){
			$this->redirect('User/showLogin');
		}
		$problemId=$_GET['id'];
		$arr=M('problem')->find($problemId);
		$this->assign('problemData',$arr);
		$this->display();
	}
	public function isAbnormal(){
		$userinfo=session('userinfo');
		
		$con['submit_time']=array('gt',time()-60);
		$con['user_id']=$userinfo['id'];
		$cnt=M('user_problem')->where($con)->count();
		
		$data['ip']=get_client_ip();
		if($cnt>=5){
			M('black_user')->add($data);
			$this->error('非法操作!');
		}
	}
	
	
	
}
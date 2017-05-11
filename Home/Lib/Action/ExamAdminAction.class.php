<?php
// 本类由系统自动生成，仅供测试用途
class ExamAdminAction extends AdminAction {
	public function index(){
		$User = M('contest_list'); // 实例化User对象
		import('ORG.Util.Page');// 导入分页类
		$count = $User->count();// 查询满足要求的总记录数
		$Page  = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show  = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		//$list['username']=$userinfo;
		foreach($list as $key=>$value){
			
			if(intval($value['start_time'])<time() && time()<intval($value['end_time'])){
				$list[$key]['status']="正在比赛";
			}else if(time()<intval($value['start_time'])){
				$list[$key]['status']="等待开始";
			}else {
				$list[$key]['status']="比赛结束";
			}
		}
		
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('nowTime',time());
	
		$userinfo = session('userinfo');
		$this->myId = $userinfo['id'];
		$this->display(); // 输出模板
	}
	public function showAddExam(){
		$this->display();
	}
}
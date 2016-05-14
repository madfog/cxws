<?php
// 登录文件
//超级外卖:20140406
class PublicAction extends CommonAction {
	public function header(){

		$this->display();
	}
	public function head(){

		$this->display();
	}
	public function foot(){


		$this->display();
	}


	public function _mpty($name){
		//把所有城市的操作解析到city方法
		$this->display('Public:404');
	}


	Public function verify(){
		import('ORG.Util.Image');//本地用
		//import("@.ORG.Image");//去服务器用
		//Image::buildImageVerify();

		Image::buildImageVerify(5,1,png,50,26);

	}

	Public function checkverify(){
		if($_SESSION['verify'] != md5($_POST['param'])) {
			echo '{
			"info":"请输入正确的验证码",
			"status":"n"
		 }'; 
		}

		else{	echo '{
			"info":"",
			"status":"y"
		 }'; }
	}

	/**
	 * 用户注册
	 */
	public function register() {
		 
		if ($_SESSION['user_id']){
			$this->redirect(U('Member/index'));
		}
		$this->display();
	}

	//注册验证

	public function doregister() {
		C('TOKEN_ON',false);

		session('username',null);
		cookie('nickname',null);
		cookie('userpic',null);
		session('user_id',null);
		$Member=D('Members');
		$map['username']=$_POST['username'];
		$map['userpass']=md5($_POST['userpass']);
		$map['useremail']=$_POST['useremail'];
		$map['create_time']=time();
		$map['usergroup']=1;
		$result = $Member->create($map);

		if (!$result){
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			exit($Member->getError());
		}else{
			// 验证通过 可以进行其他数据操作
			$Member->add($map);
			$con['username']=$map['username'];
			$useruid = $Member->where($con)->field('uid,userpass,nickname,username')->find();
		 //用户登录成功
			session('username',$useruid["username"]);
			cookie('nickname',$useruid["nickname"]);
			session('user_id',$useruid["uid"]);

			$data = array(
    						'last_login_time' => time(),
    						'last_login_ip' => get_client_ip(),
			);
			M('Members')->where("uid=".$useruid["uid"])->save($data);
			$this->redirect(U('Member/index'));
		}
	}


	/**
	 * 用户名,邮箱重复验证
	 */

	Public function checkuser(){
		$Member=M('Members');
		$data['username']=$_POST["param"];
		$reusername=$Member->where($data)->select();

		if(empty($reusername)) {

		 echo '{
			"info":"",
			"status":"y"
		 }'; 
		}

		else{		echo '{
			"info":"用户名已存在，请更换个试试",
			"status":"n"
		 }'; 
		}

	}
	//邮箱重复验证
	Public function checkemail(){


		$Member=M('Members');
		$data['useremail']=$_POST["param"];
		$reuseremail=$Member->where($data)->select();

		if(empty($reuseremail)) {

		 echo '{
			"info":"",
			"status":"y"
		 }'; 
		}

		else{		echo '{
			"info":"该邮箱已注册，请更换其他邮箱！",
			"status":"n"
		 }'; 
		}
	}

	/**
	 * 用户登录
	 */
	public function login() {

		$reurl = urlencode($_SERVER['HTTP_REFERER']);
		$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
		if(strpos($ua, "micromessenger") !== false) {
			$this->redirect(U('Public/Wx_login',"reurl=$reurl"));
		}
		else {

			if ($_SESSION['user_id']){
				$this->redirect(U('Member/index',"reurl=$reurl"));
			}
			$reurl =urlencode($_GET['reurl']);

			$this->assign('reurl',$reurl);

			$this->display();
		}
	}

	//验证用户名通过后跳转到源URL

	public function dologin() {
		session('username',null);
		cookie('nickname',null);
		cookie('userpic',null);
		session('user_id',null);
		$Member=D('Members');

		$username =	$_POST['username'];
		$password =	$_POST['password'];
		$reurl =	$_GET['reurl'];

		if(!$username){$this->error('用户名不可以为空');} //判定用户名否为空
		if(!$password){$this->error('密码不可以为空');}//判定密码是否为空
		$con['username']=$username;
		$useruid = $Member->where($con)->field('uid,userpass,nickname,username,userstatus')->find();

		if($useruid['userstatus']==2){$this->error('禁止登录');}
		if(!$useruid){$this->error('用户不存在');}
		else {
			if ($useruid["userpass"]!=md5($password)){$this->error('密码错误');}
			else{

				//用户登录成功
				session('username',$useruid["username"]);
				cookie('nickname',$useruid["nickname"]);
				session('user_id',$useruid["uid"]);
					
				$data = array(
    						'last_login_time' => time(),
    						'last_login_ip' => get_client_ip(),
				);
				M('Members')->where("uid=".$useruid["uid"])->save($data);
				//$this->success("登录验证成功！",$reurl);
				//header("location:U("M/index")");
				header("location:".$reurl);
			}


		}


	}

	/**
	 * 用户退出
	 */
	public function logout(){
		session_destroy();
			
		cookie('nickname',null);
		cookie('userpic',null);
			
		$this->redirect("/index");
	}

	/**
	 * 第三方数据登录
	 */

	public function wx_login() {
		if ($_SESSION['user_id']){
			$this->redirect(U('Member/index'));
		}

		$reurl =urlencode($_GET['reurl']);

		$appid = "wx755a176a07f52614";
		$app_sec = "385bb22c122ec25ae18b15a54c0de6f5";

		$jumpurl = urlencode("http://zy.work.glodon.com/index.php?m=public&a=openauth&jumpurl=$reurl");
		// 构造跳转链接
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$jumpurl}&response_type=code&scope=snsapi_userinfo";


		header("Location:$url");
	}


	public function openauth() {
		ini_set("display_errors","ON");
		error_reporting(E_ALL);
		$jumpurl = $_GET['jumpurl'];
		$code = $_GET['code'];
		// 获取openid
		$wx_info = gen_wx_info($code);
		$openid = $wx_info['openid'];

		if($openid) {
			// 根据open_id 处理登陆或注册
			$Account=M('Open_account');
			$data["wx_open_id"] = $openid;
			$wx_account = $Account->where($data)->find();

			var_dump($wx_account);
			//return;

			if($wx_account) {
				// do_login
				session('username',null);
				cookie('nickname',null);
				cookie('userpic',null);
				session('user_id',null);
				$Member=D('Members');


				$con['uid']=$wx_account['uid'];
				$useruid = $Member->where($con)->field('uid,userpass,nickname,username,userstatus')->find();
				var_dump($useruid, $con);

				if($useruid['userstatus']==2){$this->error('禁止登录');}
				else if(!$useruid){$this->error('用户不存在');}
				else {


						//用户登录成功
						session('username',$useruid["username"]);
						cookie('nickname',$useruid["nickname"]);
						session('user_id',$useruid["uid"]);

						$data = array(
							'last_login_time' => time(),
							'last_login_ip' => get_client_ip(),
						);
						M('Members')->where("uid=".$useruid["uid"])->save($data);
						//$this->success("登录验证成功！",$reurl);
						//header("location:U("M/index")");
						header("location:".$jumpurl);


				}
			}
			else {
				// do_regiter
				C('TOKEN_ON',false);

				$headurl = $wx_info['headimgurl'];
				$headurl = substr($headurl, 0, strlen($headurl)-1);
				$headurl .= "96";

				session('username',null);
				cookie('nickname',null);
				cookie('userpic',null);
				session('user_id',null);
				$Member=D('Members');
				$map['username']=$wx_info['nickname'];
				$map['userpass']=md5("");
				$map['useremail']=$wx_info['openid']."@wx.com";
				$map['create_time']=time();
				$map['usergroup']=1;
				$map['userpic']=$headurl;
				$result = $Member->create($map);

				if (!$result){
					// 如果创建失败 表示验证没有通过 输出错误提示信息
					exit($Member->getError());
				}else{
					// 验证通过 可以进行其他数据操作
					$Member->add($map);
					$con['username']=$map['username'];
					$useruid = $Member->where($con)->field('uid,userpass,nickname,username')->find();

					// 创建account记录

					$account['uid'] = $useruid["uid"];
					$account['wx_open_id'] = $wx_info['openid'];
					//$account['wx_union_id'] = $wx_info['unionid'];

					$accountDb = D('Open_account');

					$ret =$accountDb->create($account);

					if(!$ret) {
						exit($accountDb->getError());
					}
					else {
						$accountDb->add($account);
					}


					//用户登录成功
					session('username',$useruid["username"]);
					cookie('nickname',$useruid["nickname"]);
					session('user_id',$useruid["uid"]);

					$data = array(
						'last_login_time' => time(),
						'last_login_ip' => get_client_ip(),
					);
					M('Members')->where("uid=".$useruid["uid"])->save($data);



					//header("location:".$jumpurl);
					$this->redirect(U('Member/index'));
				}
			}
			// 跳转
		}


	}

}
<?php
/*付款模块
*@超级外卖20140406
*@www.bijiadao.net
*@
*@
*/
class PayAction extends CommonAction {


    function _initialize() {
        parent::_initialize();
        header("Content-Type:text/html; charset=utf-8");
    }




    public function index(){

        $uuid=session('user_id');

        if ($uuid){
            //提取地址列表输出页面
            $Faddress=M('Faddress');
            $addlist=$Faddress->where('uid='.$uuid)->limit(5)->order('addtop desc,faddid desc')->select();
            $this->assign ( 'addlist', $addlist );
        }
        $this->display();



    }



    public function paysuccess(){
        $id=trim($_GET['id']);
        //取订单信息
        $data['oid']=$id;
        $pid=$_COOKIE["PHPSESSID"];
        $data['pid']=$pid;
        // $data['uid']=session('user_id');
        $Order=D('Foodorder');
        $oitem=$Order->relation(true)->where($data)->find();

        if ($oitem){

            //dump($pid);
            $this->assign('orderdetail',$oitem);
            $this->assign('payname',C('payname'));//支付宝帐户
            $this->assign('pid',$pid);
            $this->assign('id',$id);

            $this->display();

        }
        else {
            echo "非法操作";}


    }



}
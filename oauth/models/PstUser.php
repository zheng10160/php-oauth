<?php

namespace app\models;

use Yii;
use yii\base\Model;


/**
 * This is the model class for table "pst_user".
 *
 * @property int $userid 用户自增id
 * @property string $username 用户名
 * @property string $nickname 昵称
 * @property string $useremail 邮箱
 * @property string $usermobile 手机号码
 * @property string $standby_name1 备用字段1
 * @property string $standby_name2 备用字段2
 * @property string $salt 随机四位字符与密码加密
 * @property string $pwd 与salt加密后的密码
 * @property string $ip 注册时ip地址
 * @property int $regtype 注册类型：1用户名,2邮箱,2手机,3第三方,4..后面可能扩展
 * @property int $state 用户状态：0正常,1未激活
 * @property int $seal_number 用户封号状态：0正常，1封号
 * @property string $created_ts 创建时间
 */
class PstUser extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pst_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['pwd'], 'required'],
            [['pwd'], 'string','min'=>6, 'max' => 20,'message'=>'密码位数为6至20位'],
            [['username'], 'string','min'=>4, 'max' => 255,'message'=>'']
        ];
    }

    public static function getDate()
    {
        return time();//当前时间戳
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'pwd' => '密码',
        ];
    }



    /*********************************************存储过程******************************************************/

    /**
     * 根据不同用户条件 判断用户是否已存在
     * @param $spname
     * @param $v
     * @param $type
     * @return array
     */
    public function getFromUserInfoFromDb($spname,$v,$type)
    {

        $res = Yii::$app->db->createCommand("call ".$spname."('".$v."',".$type.");")->queryOne();

        return $res;

    }

    /**
     * 填补完成 需要记录填补log信息
     * @param $userid
     * @param $info
     * @param $time
     */
    public function addUser_pad_info($userid,$info,$time)
    {
        Yii::$app->db->createCommand("call pst_sp_add__pad_user_info_log(".$userid.",'".$info."','".$time."',@ret)")->query();

        $res = Yii::$app->db->createCommand("select @ret");
    }

    /**
     * 判断用户是否存在 存在则返回用户信息
     * @param $username
     * @param $password
     * @param $regtype
     * @param $md5_pwd 加密后的密码
     * @return bool
     */
    public function getCheckUserInfo($username,$password,$regtype,$md5_pwd='')
    {
        $spname = 'pst_sp_userinfo_select';
        $res = Yii::$app->db->createCommand("call ".$spname."('".$username."',".$regtype.");")->queryOne();

        if(!$res){
            return false;
        }

        $salt = $res['salt'];
        $pwd = $res['pwd'];

        $c_pwd = encryptedPwd($password,$salt);

        if($c_pwd == $pwd || $pwd == $md5_pwd){
            return $res;//返回用户信息
        }else{
            return false;
        }
    }

    /**
     * 根据userid 获取pst_user_profile 用户扩展表的数据
     * @param $userid
     * @return array|bool|false
     */
    public function getPstUserProfileInfo($userid)
    {
        $spname = 'pst_sp_select_user_profile';
        $res = Yii::$app->db->createCommand("call ".$spname."(".$userid.");")->queryOne();

        if($res){
            //$userinfo = ['avatar'=>$res['avatar'],'sex'=>$res['sex']];
           // return $res;
            return ['avatar'=>$res['avatar'],'sex'=>$res['sex'],'address'=>$res['address'],'birthday'=>$res['birthday']];//目前返回 地址 生日
        }
        return [];
    }
}

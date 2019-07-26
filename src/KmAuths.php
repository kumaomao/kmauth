<?php  declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: k
 * Date: 2019/7/23
 * Time: 17:52
 */

namespace kumaomao\kmauth;


use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory;
use Swoft\Db\DB;
use Swoft\Http\Server\Router\RouteRegister;

class KmAuths
{

    public static $path=[];



    /**
     * @param $data
     * 存入路径
     */
    public static function setPath(array $data):void
    {
        self::$path[] = $data;
    }


    public static function getPath():array
    {
        return self::$path;
    }
    
    public static function test(){

    }

    /**
     * @method 将注解写入数据库
     * @param array $option
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function authInit(array $option=['isAll'=>false,'isDel'=>false]):array
    {
        $kmauth = BeanFactory::getBean("kmauth");
        $isAll = $option['isAll'] ?? false;
        $isDel = $option['isDel'] ?? false;
        $result = $kmauth->setAuth(self::$path,$isAll,$isDel);
        return $result;
    }

    /**
     * @method 设置用户组
     * @param array $data id,name,permissions,desc 无id时为新增，有id时为修改
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function setAdminGroup(array $data):array
    {
        $adminGroup = BeanFactory::getBean("adminGroup");
        $result = $adminGroup->setAdminGroup($data);
        return $result;
    }

    /**
     * @method设置权限组
     * @param array $data
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function setAuthGroup(array $data):array
    {
        $authGroup = BeanFactory::getBean("authGroup");
        $result = $authGroup->setAuthGroup($data);
        return $result;
    }

    /**
     * @method 删除用户组
     * @param $ids
     * @return mixed
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function delAdminGroup($ids){
        $adminGroup = BeanFactory::getBean("adminGroup");
        $result = $adminGroup->delAdminGroup($ids);
        return $result;
    }

    /**
     * @method 删除权限组
     * @param $ids
     * @return mixed
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function delAuthGroup($ids){
        $authGroup = BeanFactory::getBean("authGroup");
        $result = $authGroup->delAuthGroup($ids);
        return $result;
    }

}
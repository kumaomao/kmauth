<?php
/**
 * Created by PhpStorm.
 * User: k
 * Date: 2019/7/24
 * Time: 16:57
 */

namespace kumaomao\kmauth\Bean;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\DB;

/**
 * 权限组
 * Class AuthGroupBean
 * @package kumaomao\kmauth\Bean
 * @Bean("authGroup")
 */
class AuthGroupBean
{

    private function dbAuthGroup(){
        return DB::table('auth_group');
    }

    /**
     * @method 获取权限组列表
     * @return array
     */
    public function authGrouplist():array
    {
        $db_auth_aroup = $this->dbAuthGroup();
        $list = $db_auth_aroup->get();
        return $list->toArray();
    }

    /**
     * @method 新建/修改权限组
     * @param array $data
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public function setAuthGroup(array $data):bool
    {
        $db_auth_aroup = $this->dbAuthGroup();
        $data['update_time'] = time();
        if(isset($data['id'])){
            $db_auth_aroup->batchUpdateByIds($data);
        }else{
            $data['create_time'] = time();
            $db_auth_aroup->insert($data);
        }
        return true;
    }


    /**
     * 删除用户组
     * @param array $ids
     * @return int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public function delAuthGroup(array $ids){
        $db_auth_aroup = $this->dbAuthGroup();
        $result = $db_auth_aroup->whereIn('id',$ids)->delete();
        return $result;
    }

}
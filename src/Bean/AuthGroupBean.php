<?php
/**
 * Created by PhpStorm.
 * User: k
 * Date: 2019/7/24
 * Time: 16:57
 */

namespace Kumaomao\kmauth\Bean;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\DB;

/**
 * 权限组
 * Class AuthGroupBean
 * @package Kumaomao\kmauth\Bean
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
    public function getAuthGroup(array $options):array
    {
        $db_auth_aroup = $this->dbAuthGroup();
        if(isset($options['limit']) && is_numeric($options['limit'])){
            $limit = $options['limit'];
            $page = (isset($options['page']) && is_numeric($options['page']))?$options['page']:1;
            $list = $db_auth_aroup->paginate($page,$limit);
        }else{
            $list['list'] = $db_auth_aroup->get()->toArray();
        }
        return $list;
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
        $data['permissions'] = is_array($data['permissions'])?implode(',',$data['permissions']):$data['permissions'];
        if(isset($data['id'])){
           $result =  $db_auth_aroup->batchUpdateByIds($data);
        }else{
            $data['create_time'] = time();
            $result = $db_auth_aroup->insert($data);
        }
        return $result;
    }


    /**
     * 删除用户组
     * @param array $ids
     * @return int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public function delAuthGroup($ids){
        $db_auth_aroup = $this->dbAuthGroup();
        $ids = is_array($ids)?$ids:explode(',',$ids);
        $result = $db_auth_aroup->whereIn('id',$ids)->delete();
        return $result;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: k
 * Date: 2019/7/24
 * Time: 16:56
 */

namespace Kumaomao\kmauth\Bean;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\DB;

/**
 * 用户组
 * Class AdminGroupBean
 * @package Kumaomao\kmauth\Bean
 * @Bean("adminGroup")
 */
class AdminGroupBean
{
    private function dbAdminGroup(){
        return DB::table('admin_group');
    }

    /**
     * @method 获取用户组列表
     * @return array
     */
    public function getAdminGroup(int $id):array
    {
        $db_admin_aroup = $this->dbAdminGroup();
        $list = $db_admin_aroup->where('admin_id',$id)->get();
        return $list->toArray();
    }

    /**
     * @method 新建/修改用户组
     * @param array $data
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public function setAdminGroup(array $data):bool
    {
        $db_admin_aroup = $this->dbAdminGroup();
        $data['update_time'] = time();
        if(isset($data['id'])){
            $result = $db_admin_aroup->batchUpdateByIds($data);
        }else{
            $data['create_time'] = time();
            $result = $db_admin_aroup->insert($data);
        }
        return $result;
    }

    /**
     * @method 删除用户组
     * @param array $ids
     * @return int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public function delAdminGroup(array $ids){
        $db_admin_aroup = $this->dbAdminGroup();
        $result = $db_admin_aroup->whereIn('id',$ids)->delete();
        return $result;
    }

}
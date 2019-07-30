<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: k
 * Date: 2019/7/24
 * Time: 11:51
 */
namespace Kumaomao\kmauth\Bean;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\DB;

/**
 * Class KmauthBean
 * @package Kumaomao\kmauth\Bean
 * @Bean("kmauth")
 */
class AuthBean
{


    private function dbAuth(){
        return DB::table('auth');
    }

    public function setAuth(array $data,bool $isAll,bool $isDel):array
    {
        $db_auth = $this->dbAuth();
        $auth_list = $db_auth->get()->toArray();
        $auth_list = $this->ProcessData($data,$auth_list);
        $result_arr=[
            'update'=>0,
            'delete'=>0,
            'insert'=>0
        ];
        if($isAll && $auth_list['is_have']){
            $result = $db_auth->batchUpdateByIds($auth_list['is_have']);
            $result_arr['update']=$result?count($auth_list['is_have']):0;
        }
        if($isDel && $auth_list['no_have']){
            $ids =[];
            foreach ($auth_list['no_have'] as $v){
                $ids[] = $v['id'];
            }
            $result = $db_auth->whereIn('id',$ids)->delete();
            $result_arr['delete']=$result?count($auth_list['no_have']):0;
        }
        if($auth_list['db_no_have']){
            $result = $db_auth->insert($auth_list['db_no_have']);
            $result_arr['insert']=$result?count($auth_list['db_no_have']):0;
        }
        return $result_arr;
    }


    /**
     * @method 获取权限列表
     * @param array $options
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public function getAuth(array $options):array
    {
        $db_auth = $this->dbAuth()->orderBy('sort','desc');
        $isTree = $options['isTree'] ?? true;
        $isMenu = $options['isMenu'] ?? false;
        if($isTree){
            $db_auth = $db_auth->where('pid',0);
        }
        if($isMenu){
            //只读取菜单
            $db_auth = $db_auth->where('type',1);
        }
        if(isset($options['limit']) && is_numeric($options['limit'])){
            $limit = $options['limit'];
            $page = (isset($options['page']) && is_numeric($options['page']))?$options['page']:1;
            $list = $db_auth->paginate($page,$limit);
        }else{
            $list['list'] = $db_auth->get()->toArray();
        }

        if($isTree){
            $list['list'] = $this->setDataForTree($list['list']);
        }
        return $list;
    }

    /**
     * 获取树形数据
     * @param array $data
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    private function setDataForTree(array  $data){
        foreach ($data as $k => $v){
            $childs =  $this->dbAuth()->where('pid',$v['id'])->get()->toArray();
            if($childs){
                $res_childs = $this->setDataForTree($childs);
                $data[$k]['childs'] = $res_childs;
            }
        }
        return $data;
    }

    /**
     * 判断注解数据是否和数据库中数据一致
     * @param array $data
     * @param array $db_data
     * @return array
     */
    private function ProcessData(array $data,array $db_data):array
    {
        $res=[];
        $is_have_arr=[];
        foreach ($data as $k=>$v){
            $is_have = false;
            $is_update = false;
            foreach ($db_data as $db_k=>$db_v){
                //注解数据和数据库数据对比
                if($v['class'].$v['method'] == $db_v['class'].$db_v['method']){
                    //数据库存在相同路径是时整合
                    if($db_v['name'] != $v['name'] || $db_v['type'] != $v['type'] || $db_v['is_display'] != $v['is_display']){
                        $db_v['name'] = $v['name'];
                        $db_v['type'] = $v['type'];
                        $db_v['class'] = addslashes($v['class']);
                        $db_v['method'] = addslashes($v['method']);
                        $db_v['is_display'] = $v['is_display'];
                        $db_v['update_time']=$v['update_time'];
                        $is_update = true;
                    }
                    $is_have = true;
                    break;
                }
            }

            if($is_have){
                if($is_update){
                    $is_have_arr[]=$db_v;
                }
                unset($db_data[$db_k]);
                unset($data[$k]);
            }
        }
        //数据库存在相同的路径数据
        $res['is_have']=$is_have_arr;
        //数据库存在,注解不存在的数据
        $res['no_have']=$db_data;
        //数据库不存在,注解存在的数据
        $res['db_no_have']=$data;
        return $res;
    }

}
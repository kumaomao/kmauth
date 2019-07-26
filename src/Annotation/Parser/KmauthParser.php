<?php
/**
 * Created by PhpStorm.
 * User: k
 * Date: 2019/7/22
 * Time: 17:47
 */
namespace kumaomao\kmauth\Annotation\Parser;

use Doctrine\Common\Annotations\DocParser;
use kumaomao\kmauth\KmauthRegister;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use kumaomao\kmauth\Annotation\Mapping\Kmauth;

/**
 * @AnnotationParser(Kmauth::class)
 */
class KmauthParser extends Parser
{

    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_METHOD) {
            return [];
        }
        $name  = $annotationObject->getName();
        $type = $annotationObject->isType();
        $menu = $annotationObject->isMenu();

       $route = $this->getRoute();
        //处理路径
        \kumaomao\kmauth\KmAuths::setPath([
            'class'=>$this->className,
            'method'=>$this->methodName,
            'name'=>$name,
            'type'=>$type?1:2,
            'is_display'=>$menu?1:2,
            'route'=>$route,
            'create_time'=>time(),
            'update_time'=>time()
        ]);
        return [];
    }


    /**
     * 获取路由注解
     * @return array
     * @throws \ReflectionException
     */
    private function getDocComment(){
        $matches = [];
        //获取方法上的所有注释
        $reflection = new \ReflectionClass($this->className);
        $class_doc = $reflection->getDocComment();
        $class_pos = findInitialTokenPosition($class_doc);
        //获取注解
        $string = trim(substr($class_doc, $class_pos), '* /');
        //方法注释解析
        preg_match('/Controller\("(.+?)"\)/', $string, $class_matches);
        $matches['class'] = $class_matches;

        $method = $reflection->getMethod($this->methodName);
        $method_doc = $method->getDocComment();
        $method_pos = findInitialTokenPosition($method_doc);
        //获取注解
        $string = trim(substr($method_doc, $method_pos), '* /');
        //方法注释解析
        preg_match('/RequestMapping\("(.+?)"\)/', $string, $method_matches);
        $matches['method'] = $method_matches;
        return $matches;
    }
    
    private function getRoute(){
        $routes = $this->getDocComment();
        $class_route = '';
        $method_route = '';
        if($routes['class'] && isset($routes['class'][1])){
            $class_route = $routes['class'][1];
        }
        if($routes['method'] && isset($routes['method'][1])){
            $method_route = $routes['method'][1];
            $frist = substr( $method_route, 0, 1 );
            $method_route = $frist=="/"?$method_route:$class_route.'/'.$method_route;
        }
        return $method_route;
    }






}
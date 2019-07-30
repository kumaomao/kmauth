<?php
/**
 * Created by PhpStorm.
 * User: k
 * Date: 2019/7/22
 * Time: 17:38
 */
namespace Kumaomao\kmauth\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Attribute;
/**
 * Class Kmauth
 * @Annotation
 * @Target("METHOD")
 * @Attributes(
 *     @Attribute("name", type="string"),
 *     @Attribute("type", type="bool"),
 *     @Attribute("menu", type="bool")
 * )
 */
final class Kmauth
{

    /**
     * 权限节点
     */
    public const AUTH = true;

    /**
     * 普通操作节点
     */
    public const ACTION = false;

    /**
     * 是否是菜单节点
     */
    public const IS_MENU = true;

    /**
     * @var string
     * 节点名
     */
    private $name;

    /**
     * @var boolean
     * 节点类型
     */
    private $type;

    /**
     * @var boolean
     */
    private $menu;

    public function __construct(array $value)
    {
        if(isset($value['name'])){
            $this->name = $value['name'];
        }else if(isset($value['value'])){
            $this->name = $value['value'];
        }else{
            $this->name = '鬼知道这是什么';
        }
        //默认为权限节点
        $this->type = $value['type'] ?? self::AUTH;
        $this->menu = $value['menu'] ?? self::IS_MENU;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isType(): bool
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isMenu(): bool
    {
        return $this->menu;
    }







}
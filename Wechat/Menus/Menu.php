<?php

namespace Wechat\Menus;


class Menu extends BaseMenu
{
    protected $name;
    protected $childList = [];

    public function getName()
    {
        return $this->name;
    }

    public function hasChild()
    {
        return !empty($this->childList);
    }

    public function addChildren(Menu $menu)
    {
        $this->childList[] = $menu;
        return $this;
    }

    /**
     * @return Menu[]
     */
    public function getChildList()
    {
        return $this->childList;
    }

    public function toArray()
    {
        return [];
    }

}
<?php
namespace Wechat\Services;

use Wechat\Exceptions\WechatException;
use Wechat\Menus\Menu;

class WechatCustomizeMenu extends BaseService
{
    /**
     * @var Menu[]
     */
    private $menuList = [];
    private $formattedMenuList = [];

    public function addMenu(Menu $menu)
    {
        $this->menuList[] = $menu;
    }

    private function format()
    {
        $menuList = [];
        $levelOneCount = 0;
        foreach ($this->menuList as $item) {
            if ($item->hasChild()) {
                $sumMenuList = [];
                $levelTwoCount = 0;
                foreach ($item->getChildList() as $childItem) {
                    $sumMenuList[] = $childItem->toArray();
                    $levelTwoCount++;
                    if ($levelTwoCount >= 5) {
                        break;
                    }
                }
                $menuList[] = [
                    'name' => $item->getName(),
                    'sub_button' => $sumMenuList,
                ];
            } else {
                $menuList[] = $item->toArray();
            }
            $levelOneCount ++;
            if ($levelOneCount >= 3) {
                break;
            }
        }
        $this->formattedMenuList = $menuList;
        return [
            'button' => $menuList,
        ];
    }

    public function getFormattedMenuList()
    {
        return $this->format();
    }

    public function submit()
    {
        $accessToken = $this->getAccessToken();
        try {
            $response = $this->post('cgi-bin/menu/create', [
                'access_token' => $accessToken,
            ], $this->getFormattedMenuList());
            return $response;
        } catch (WechatException $e) {
            //todo update customize menu failed
            return false;
        }
    }
}
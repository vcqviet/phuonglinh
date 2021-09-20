<?php
namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use HK\CoreBundle\MenuAdmin\MenuAdmin;
use HK\CoreBundle\Master\MasterController;
use HK\CoreBundle\Entity\CmsRole;
use HK\CoreBundle\Entity\CmsRolePermission;

class CommonController extends MasterController
{

    private function isPermission($controller): bool
    {
        $roles = $this->getUser()->getRoles();
        foreach ($roles as $role) {
            if ($role === 'ROLE_' . CmsRole::$_ROLE_ADMIN) {
                return true;
            }
        }
        if (empty($controller)) {
            return false;
        }
        $roles = $this->getUser()->getCmsRoles();
        foreach ($roles as $role) {
            $permissions = $role->getCmsRolePermissions();
            foreach ($permissions as $per) {
                // /$per = new CmsRolePermission();
                if ($per->getModuleName() == $controller && $per->isGranted(CmsRolePermission::$LEVEL_VIEW)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function navLeft(Request $req): Response
    {
        $menu = MenuAdmin::instance()->getMenuAdmin();
        $newMenu = [];
        foreach ($menu as $key => $m) {
            if (isset($m['type']) && ($m['type'] == 'session' || $m['type'] == 'group')) {
                $newMenu[$key] = [
                    'type' => $m['type'],
                    'text' => (isset($m['text']) ? $m['text'] : ''),
                    'menu' => [],
                    'icon' => (isset($m['icon']) ? $m['icon'] : '')
                ];
                foreach ($m['menu'] as $key2 => $m2) {
                    if (isset($m2['type']) && $m2['type'] == 'group') {
                        $newMenu[$key]['menu'][$key2] = [
                            'type' => $m2['type'],
                            'text' => (isset($m2['text']) ? $m2['text'] : ''),
                            'menu' => [],
                            'icon' => (isset($m2['icon']) ? $m2['icon'] : '')
                        ];
                        foreach ($m2['menu'] as $key3 => $m3) {
                            if ($this->isPermission($key3)) {
                                $newMenu[$key]['menu'][$key2]['menu'][$key3] = $m3;
                            }
                        }
                        continue;
                    }
                    if ($this->isPermission($key2)) {
                        $newMenu[$key]['menu'][$key2] = $m2;
                    }
                }
                continue;
            }
            if ($this->isPermission($key)) {
                $newMenu[$key] = $m;
            }
        }
        foreach ($newMenu as $key => $m) {
            if (isset($m['type']) && ($m['type'] == 'session' || $m['type'] == 'group')) {
                if (count($m['menu']) <= 0) {
                    unset($newMenu[$key]);
                    continue;
                }
                foreach ($m['menu'] as $key2 => $m2) {
                    if (isset($m2['type']) && $m2['type'] == 'group') {
                        if (count($m2['menu']) <= 0) {
                            unset($newMenu[$key]);
                            if (count($m['menu']) <= 0) {
                                unset($newMenu[$key]);
                                continue;
                            }
                            continue;
                        }
                    }
                }
            }
        }

        return $this->render('admin/common/nav-left.html.twig', [
            'menus' => $newMenu
        ]);
    }

    public function navTop(Request $req): Response
    {
        return $this->render('admin/common/nav-top.html.twig', []);
    }
}

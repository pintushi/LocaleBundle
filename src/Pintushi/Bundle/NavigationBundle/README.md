导航菜单
======

## 1. 可通过配置文件配置菜单

自动加载每个Bundle的`Resources/config/navigation.yml`文件
```
navigation:
    menu_config:
        items:
            user_list:
                label:           '用户'
                route:           'api_users_get_collection'
                position:        400
                extras:
                    routes: ['/^api_users/']
                    description: '用户管理'
        tree:
            application_menu:
                children:
                    system_tab:
                        children:
                            user_list: ~

```

## 2. 可通过界面添加新菜单，也可更改以配置文件配置的菜单。

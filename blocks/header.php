<?php
    if(isset($_SESSION['role']) && !empty($_SESSION['role'])){
        $result = $db->query("SELECT * FROM users WHERE user_id = '".$_SESSION['user_id']."'");
        $result = $result->fetch();
        echo "<div class=\"wrapper\">
        <header>
            <ul class=\"header\">
            <li class=\"header__part header_logo\"><a href=\"index.php?action=news&page=1\">KaminskyVVDR</a></li>
            <li class=\"header__part header__navigation\">
                <form method=\"post\" action=\"index.php?action=search\">
                    <input type=\"text\" name=\"search-request\" autocomplete=\"off\" class=\"search-form_request-field\" placeholder=\"search\">
                </form>
            </li>
            <li class=\"header__part droplist\">
                <img class=\"header__user-image\" alt=\"".$result['nickname']."\" src=\"img/user/".$result['user_image']."\"> ".$result['nickname']."
                <ul class=\"droplist__list\">
                    <li><a href=\"user_settings.php?action=update_info\">Settings</a></li>
                    <li><a href=\"index.php?action=orders_list\">Orders list</a></li>";
        if ($_SESSION['role']>1) {
            echo"
                    <li><a href=\"orders.php?action=view_orders\">Customers orders</a></li>
            ";
        }
        echo"
                    <li><a href=\"index.php?action=log_out\">Log out</a></li>
                </ul>
                </li>
                    
        </ul>
        <nav class=\"menu\">
            <ul class=\"menu__list\">
                <li>
                    <a href=\"index.php?action=news\" class=\"menu__link\">News</a>
                </li>
                <li>
                    <a href=\"index.php?action=products_by_grand_group&grand_group_id=1\" class=\"menu__link\">PcPeriphrals</a>
                    <span class=\"menu__arrow arrow\"></span>
                    <ul class=\"sub-menu__list\">
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=1\" class=\"sub-menu__link\">Keyboards</a>
                        </li>
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=2\" class=\"sub-menu__link\">Mice</a>
                        </li>
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=3\" class=\"sub-menu__link\">Mousepads</a>
                        </li>
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=4\" class=\"sub-menu__link\">Storage devices</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href=\"index.php?action=products_by_grand_group&grand_group_id=2\" class=\"menu__link\">Console</a>
                    <span class=\"menu__arrow arrow\"></span>
                    <ul class=\"sub-menu__list\">
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=5\" class=\"sub-menu__link\">Playstation</a>
                        </li>
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=6\" class=\"sub-menu__link\">XBOX</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href=\"index.php?action=products_by_grand_group&grand_group_id=3\" class=\"menu__link\">Mobile accessories</a>
                    <span class=\"menu__arrow arrow\"></span>
                    <ul class=\"sub-menu__list\">
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=7\" class=\"sub-menu__link\">Speakers for mobile</a>
                        </li>
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=8\" class=\"sub-menu__link\">Powerbank</a>
                        </li>
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=9\" class=\"sub-menu__link\">Miscellaneous</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href=\"index.php?action=brand_list\" class=\"menu__link\">Brands list</a>
                </li>
            </ul>
        </nav>
        </header>
        ";
      }
      else{
        echo "
        <div class=\"wrapper\">
        <header><ul class=\"header\">
            <li class=\"header__part header_logo\"><a href=\"index.php?action=news&page=1\">KaminskyVVDR</a></li>
            <li class=\"header__part header__navigation\">
                <form method=\"post\" action=\"index.php?action=search\">
                    <input type=\"text\" name=\"search-request\" autocomplete=\"off\" class=\"search-form_request-field\" placeholder=\"search\">
                </form>
            </li>
            <li class=\"header__part header__navigation\"><a href=\"auth.php\">Log in</a></li>
                    
        </ul>
        <nav class=\"menu\">
            <ul class=\"menu__list\">
                <li>
                    <a href=\"index.php?action=news\" class=\"menu__link\">News</a>
                </li>
                <li>
                    <a href=\"index.php?action=products_by_grand_group&grand_group_id=1\" class=\"menu__link\">PcPeriphrals</a>
                    <span class=\"menu__arrow arrow\"></span>
                    <ul class=\"sub-menu__list\">
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=1\" class=\"sub-menu__link\">Keyboards</a>
                        </li>
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=2\" class=\"sub-menu__link\">Mice</a>
                        </li>
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=3\" class=\"sub-menu__link\">Mousepads</a>
                        </li>
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=4\" class=\"sub-menu__link\">Storage devices</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href=\"index.php?action=products_by_grand_group&grand_group_id=2\" class=\"menu__link\">Console</a>
                    <span class=\"menu__arrow arrow\"></span>
                    <ul class=\"sub-menu__list\">
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=5\" class=\"sub-menu__link\">Playstation</a>
                        </li>
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=6\" class=\"sub-menu__link\">XBOX</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href=\"index.php?action=products_by_grand_group&grand_group_id=3\" class=\"menu__link\">Mobile accessories</a>
                    <span class=\"menu__arrow arrow\"></span>
                    <ul class=\"sub-menu__list\">
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=7\" class=\"sub-menu__link\">Speakers for mobile</a>
                        </li>
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=8\" class=\"sub-menu__link\">Powerbank</a>
                        </li>
                        <li>
                            <a href=\"index.php?action=products_by_group&group_id=9\" class=\"sub-menu__link\">Miscellaneous</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href=\"index.php?action=brand_list\" class=\"menu__link\">Brands list</a>
                </li>
            </ul>
        </nav>
        </header>
	  ";
      }
?>
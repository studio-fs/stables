<?php
/**
 * Plugin Name: av horse Add stables
 * Plugin URI: https://studio-av.com
 * Description: Добавляет конюшни
 * Version: 1.2
 * Author: studio-av
 * Author URI: https://studio-av.com
 * License: GPL v2
 */

	defined( 'ABSPATH' ) || exit;

    // Создание новой роли при активации плагина
    register_activation_hook( __FILE__, 'avhorse_add_role' );
    register_deactivation_hook( __FILE__, 'avhorse_remove_role' );

    //Добавляем роль пользователя
    function avhorse_add_role()
    {
        add_role( 'stables', 'Владелец конюшни',
            array(
                'read' => true,
                'level_0' => true
            )
        );
    }
    
    //Удаляем роль пользователя
    function avhorse_remove_role()
    {
        remove_role( 'stables' );
    }
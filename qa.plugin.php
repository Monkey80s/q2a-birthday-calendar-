<?php
/*
Plugin Name: Calendario de Cumpleaños
Plugin URI:
Plugin Description: Muestra un calendario visual con los cumpleaños guardados
Plugin Version: 1.1
Plugin Date: 2025-07-16
Plugin Author: Monkey
Plugin Author URI:
Plugin License: MIT
Plugin Minimum Question2Answer Version: 1.8
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

qa_register_plugin_module('module', 'qa-calendar-admin.php', 'qa_calendar_admin', 'Calendario Cumpleaños - Admin');
qa_register_plugin_module('widget', 'qa-calendar-widget.php', 'qa_calendar_widget', 'Cumpleaños del Mes');

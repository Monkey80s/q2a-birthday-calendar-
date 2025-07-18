<?php

class qa_calendar_widget {
    public function allow_template($template) {
        return true;
    }

    public function allow_region($region) {
        return $region == 'side';
    }

    public function output_widget($region, $place, $themeobject, $template, $request, $qa_content) {
        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

        // Inyectamos estilos responsivos
        echo '
        <style>
        .birthday-calendar {
            max-width: 100%;
            margin: 10px auto;
            font-family: sans-serif;
            overflow-x: auto;
            box-sizing: border-box;
            padding: 5px;
        }

        .calendar-nav {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .calendar-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            table-layout: fixed;
            font-size: 12px;
        }

        .calendar-table th, .calendar-table td {
            border: 1px solid #ccc;
            padding: 6px;
            vertical-align: top;
            position: relative;
            word-wrap: break-word;
        }

        .birthday-day {
            background-color: #ffe0e0;
            font-weight: bold;
        }

        .birthday-tooltip {
            font-size: 10px;
            margin-top: 4px;
            color: #b00;
            word-wrap: break-word;
        }
        </style>';

        include dirname(__FILE__) . '/calendar-view/calendar-html.php';
    }
}

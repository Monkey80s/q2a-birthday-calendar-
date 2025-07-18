
<?php
// Cargar datos desde qa_options
$birthdays = json_decode(qa_opt('birthday_calendar_data'), true);
if (!is_array($birthdays)) {
    $birthdays = [];
}

// Obtener mes y año actual o seleccionados
$month = isset($_GET['calmonth']) ? (int)$_GET['calmonth'] : (int)date('m');
$year = isset($_GET['calyear']) ? (int)$_GET['calyear'] : (int)date('Y');

// Calcular primer día del mes
$first_day = mktime(0, 0, 0, $month, 1, $year);
$days_in_month = date('t', $first_day);
$start_day = date('w', $first_day); // 0 = domingo

// Mapeo de cumpleaños por día
$birthdays_by_day = [];
foreach ($birthdays as $entry) {
    $bday = DateTime::createFromFormat('Y-m-d', $entry['date']);
    if ($bday && (int)$bday->format('m') === $month) {
        $day = (int)$bday->format('d');
        if (!isset($birthdays_by_day[$day])) {
            $birthdays_by_day[$day] = [];
        }
        $birthdays_by_day[$day][] = $entry;
    }
}

// Navegación
$prev_month = $month - 1;
$prev_year = $year;
if ($prev_month < 1) {
    $prev_month = 12;
    $prev_year--;
}
$next_month = $month + 1;
$next_year = $year;
if ($next_month > 12) {
    $next_month = 1;
    $next_year++;
}

// Etiquetas en español
$dias = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
$meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
          'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

echo '<div class="birthday-calendar">';
echo '<h2>Calendario de Cumpleaños - ' . $meses[$month - 1] . ' ' . $year . '</h2>';
echo '<div class="calendar-nav">';
echo '<a href="?calmonth=' . $prev_month . '&calyear=' . $prev_year . '">◀ Mes anterior</a>';
echo '<a href="?calmonth=' . $next_month . '&calyear=' . $next_year . '" style="float:right">Mes siguiente ▶</a>';
echo '</div>';

echo '<table class="calendar-table">';
echo '<tr>';
foreach ($dias as $dia) {
    echo '<th>' . $dia . '</th>';
}
echo '</tr><tr>';

// Espacios antes del primer día
for ($i = 0; $i < $start_day; $i++) {
    echo '<td></td>';
}

$day_counter = 1;
for ($cell = $start_day; $day_counter <= $days_in_month; $cell++) {
    $cell_class = 'day-cell';
    $tooltip = '';
    $content = $day_counter;

    if (isset($birthdays_by_day[$day_counter])) {
        $cell_class .= ' birthday-day';
        $tooltip .= '<div class="birthday-tooltip">';
        foreach ($birthdays_by_day[$day_counter] as $person) {
            $emoji = $person['emoji'] ?: '🎂';
            $tooltip .= $emoji . ' ' . htmlspecialchars($person['name']) . ' (' . $person['gender'] . ')<br>';
        }
        $tooltip .= '</div>';
        $content .= ' 🎉';
    }

    echo '<td class="' . $cell_class . '">' . $content . $tooltip . '</td>';

    if (($cell + 1) % 7 === 0) {
        echo '</tr><tr>';
    }

    $day_counter++;
}

// Rellenar celdas vacías del final
while (($cell + 1) % 7 !== 0) {
    echo '<td></td>';
    $cell++;
}

echo '</tr></table>';
echo '</div>';
?>

<style>
.birthday-calendar {
    width: 100%;
    padding: 5px;
    box-sizing: border-box;
    font-family: sans-serif;
    overflow-x: hidden;
}

.calendar-nav {
    margin-bottom: 6px;
    font-size: 13px;
}

.calendar-table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
    font-size: 13px;
    table-layout: fixed;
}

.calendar-table th,
.calendar-table td {
    border: 1px solid #ccc;
    padding: 4px;
    vertical-align: top;
    position: relative;
    height: 45px;
    overflow-wrap: break-word;
    word-break: break-word;
}

.birthday-day {
    background-color: #ffe0e0;
    font-weight: bold;
}

.birthday-tooltip {
    font-size: 11px;
    margin-top: 3px;
    color: #b00;
}

@media screen and (max-width: 600px) {
    .calendar-table {
        font-size: 12px;
    }

    .calendar-table td,
    .calendar-table th {
        padding: 3px;
        height: 40px;
    }

    .calendar-nav a {
        font-size: 12px;
    }

    .birthday-tooltip {
        font-size: 10px;
    }
}
</style>

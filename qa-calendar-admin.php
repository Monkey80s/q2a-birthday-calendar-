<?php

class qa_html_theme_layer
{
}

class qa_calendar_admin {
    
    public function option_default($option)
    {
        if ($option === 'birthday_calendar_data') {
            return '[]';
        }
        return null;
    }

    public function admin_form(&$qa_content)
    {
        $saved = false;
        $birthdays = json_decode(qa_opt('birthday_calendar_data'), true);
        if (!is_array($birthdays)) {
            $birthdays = [];
        }

        // Eliminar entrada si se envió el índice
        if (qa_clicked('delete_birthday')) {
            $index_to_delete = (int)qa_post_text('delete_index');
            if (isset($birthdays[$index_to_delete])) {
                array_splice($birthdays, $index_to_delete, 1);
                qa_opt('birthday_calendar_data', json_encode($birthdays));
                $saved = true;
            }
        }

        // Agregar nuevo cumpleaños
        if (qa_clicked('save_birthday')) {
            $name = trim(qa_post_text('name'));
            $date = qa_post_text('date');
            $gender = qa_post_text('gender');
            $emoji = qa_post_text('emoji');

            if ($name && $date) {
                $birthdays[] = [
                    'name' => $name,
                    'date' => $date,
                    'gender' => $gender,
                    'emoji' => $emoji,
                ];
                qa_opt('birthday_calendar_data', json_encode($birthdays));
                $saved = true;
            }
        }

        $form = [
            'ok' => $saved ? 'Cambios guardados correctamente.' : null,
            'style' => 'wide',
            'fields' => [],
            'buttons' => [],
        ];

        // Campos de entrada
        $form['fields'][] = [
            'label' => 'Nombre',
            'type' => 'text',
            'tags' => 'name="name"',
        ];
        $form['fields'][] = [
            'label' => 'Fecha de Nacimiento',
            'type' => 'text',
            'tags' => 'name="date" placeholder="YYYY-MM-DD"',
        ];
        $form['fields'][] = [
            'label' => 'Género',
            'type' => 'select',
            'tags' => 'name="gender"',
            'options' => [
                '' => 'Sin especificar',
                'masculino' => 'Masculino',
                'femenino' => 'Femenino',
            ],
        ];
        $form['fields'][] = [
            'label' => 'Emoji',
            'type' => 'text',
            'tags' => 'name="emoji"',
        ];

        $form['buttons'][] = [
            'label' => 'Guardar Cumpleaños',
            'tags' => 'name="save_birthday"',
        ];

        // Lista de cumpleaños existentes
        if (!empty($birthdays)) {
            $html = '<table style="width:100%;border-collapse:collapse;">';
            $html .= '<tr><th>Nombre</th><th>Fecha</th><th>Género</th><th>Emoji</th><th>Eliminar</th></tr>';
            foreach ($birthdays as $i => $entry) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($entry['name']) . '</td>';
                $html .= '<td>' . htmlspecialchars($entry['date']) . '</td>';
                $html .= '<td>' . htmlspecialchars($entry['gender']) . '</td>';
                $html .= '<td>' . htmlspecialchars($entry['emoji']) . '</td>';
                $html .= '<td>';
                $html .= '<form method="post" action="">';
                $html .= '<input type="hidden" name="delete_index" value="' . $i . '">';
                $html .= '<input type="submit" name="delete_birthday" value="Eliminar">';
                $html .= qa_get_form_security_code('admin/form');
                $html .= '</form>';
                $html .= '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';

            $form['fields'][] = [
                'type' => 'custom',
                'label' =>
 'Cumpleaños Registrados',
                'html' => $html,
            ];
        }

        return $form;
    }
}

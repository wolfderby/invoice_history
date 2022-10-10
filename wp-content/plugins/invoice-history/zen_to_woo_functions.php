<?php 
/*
 *  Output a form input field
 */
  function woo_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) {
    // -----
    // Give an observer the opportunity to **totally** override this function's operation.
    //
    $field = false;
    /*$GLOBALS['zco_notifier']->notify(
        'NOTIFY_ZEN_DRAW_INPUT_FIELD_OVERRIDE',
        array(
            'name' => $name,
            'value' => $value,
            'parameters' => $parameters,
            'type' => $type,
            'reinsert_value' => $reinsert_value
        ),
        $field
    );*/
    if ($field !== false) {
        return $field;
    }
    
    $field = '<input type="' . $type . '" name="' . $name . '"';
    if ( (isset($GLOBALS[$name]) && is_string($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= ' value="' . stripslashes($GLOBALS[$name]) . '"';
    } elseif (!is_null($value)) {
      $field .= ' value="' . $value . '"';
    }

    if (!is_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' />';
    
    // -----
    // Give an observer the opportunity to modify the just-rendered field.
    //
    /*$GLOBALS['zco_notifier']->notify(
        'NOTIFY_ZEN_DRAW_INPUT_FIELD',
        array(
            'name' => $name,
            'value' => $value,
            'parameters' => $parameters,
            'type' => $type,
            'reinsert_value' => $reinsert_value
        ),
        $field
    );*/
    return $field;
  }



  function woo_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    // -----
    // Give an observer the opportunity to **totally** override this function's operation.
    //
    $field = false;
    /*$GLOBALS['zco_notifier']->notify(
        'NOTIFY_ZEN_DRAW_PULL_DOWN_MENU_OVERRIDE',
        array(
            'name' => $name,
            'values' => $values,
            'default' => $default,
            'parameters' => $parameters,
            'required' => $required,
        ),
        $field
    );*/
    if ($field !== false) {
        return $field;
    }
    
    $field = '<select';

    if (!strstr($parameters, 'id=')) $field .= ' id="select-'.$name.'"';

    $field .= ' name="' . $name . '"';

    if (!is_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>' . "\n";

    if (empty($default) && isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) ) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '  <option value="' . $values[$i]['id'] . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' selected="selected"';
      }

      $field .= '>' . $values[$i]['text'] . '</option>' . "\n";
    }
    $field .= '</select>' . "\n";

    if ($required == true) $field .= '&nbsp;<span class="fieldRequired">* Required</span>';
    
    // -----
    // Give an observer the chance to make modifications to the just-rendered field.
    //
    /*$GLOBALS['zco_notifier']->notify(
        'NOTIFY_ZEN_DRAW_PULL_DOWN_MENU',
        array(
            'name' => $name,
            'values' => $values,
            'default' => $default,
            'parameters' => $parameters,
            'required' => $required,
        ),
        $field
    );*/
    return $field;
  }

//zen_datetime_long
  function woo_datetime_long($raw_date = 'now')
  {
    if (($raw_date == '0001-01-01 00:00:00') || ($raw_date == '')) {
      return false;
    } elseif ($raw_date == 'now') {
      $raw_date = date('Y-m-d H:i:s');
    }
  
    $year = (int)substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);
  
    return strftime('%b %d, %Y %r', mktime($hour, $minute, $second, $month, $day, $year));
  }




<?php

//include DIR_FS_CATALOG . DIR_WS_CLASSES . 'currencies.php'; 

/**
 * currencies class
 *
 * @package classes
 * @copyright Copyright 2003-2018 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Drbyte Tue Nov 20 12:59:17 2018 -0500 Modified in v1.5.6 $
 */
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

define('CHARSET', 'utf8');
define('ENT_COMPAT', 'false');

function zen_parse_input_field_data($data, $parse) {
    return strtr(trim($data), $parse);
  }


function zen_output_string($string, $translate = false, $protected = false) {
    if ($protected == true) {
      return htmlspecialchars($string, ENT_COMPAT, CHARSET, TRUE);
    } else {
      if ($translate === false) {
        return zen_parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return zen_parse_input_field_data($string, $translate);
      }
    }
  }

  function zen_datetime_long($raw_date = 'now')
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



function zen_output_string_protected($string) {
    return zen_output_string($string, false, true);
  }

function zen_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    // -----
    // Give an observer the opportunity to **totally** override this function's operation.
    //
    $field = false;
    // $GLOBALS['zco_notifier']->notify(
    //     'NOTIFY_ZEN_DRAW_PULL_DOWN_MENU_OVERRIDE',
    //     array(
    //         'name' => $name,
    //         'values' => $values,
    //         'default' => $default,
    //         'parameters' => $parameters,
    //         'required' => $required,
    //     ),
    //     $field
    // );
    if ($field !== false) {
        return $field;
    }
    
    $field = '<select';

    if (!strstr($parameters, 'id=')) $field .= ' id="select-'.zen_output_string($name).'"';

    $field .= ' name="' . zen_output_string($name) . '"';

    if (zen_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>' . "\n";

    if (empty($default) && isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) ) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '  <option value="' . zen_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' selected="selected"';
      }

      $field .= '>' . zen_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>' . "\n";
    }
    $field .= '</select>' . "\n";

    if ($required == true) $field .= 'required';
    
    // -----
    // Give an observer the chance to make modifications to the just-rendered field.
    //
    $GLOBALS['zco_notifier']->notify(
        'NOTIFY_ZEN_DRAW_PULL_DOWN_MENU',
        array(
            'name' => $name,
            'values' => $values,
            'default' => $default,
            'parameters' => $parameters,
            'required' => $required,
        ),
        $field
    );
    return $field;
  }


////
// Wrapper function for round()
function zen_round($value, $precision) {
    $value =  round($value *pow(10,$precision),0);
    $value = $value/pow(10,$precision);
    return $value;
  }


////
function zen_not_null($value) {
    if (null === $value) {
        return false;
    }
    if (is_array($value)) {
      return count($value) > 0;
    }
    if (is_a($value, 'queryFactoryResult')) {
      return count($value->result) > 0;
    }
    return trim($value) !== '' && $value != 'NULL';
  }



 // Calculates Tax rounding the result
 function zen_calculate_tax($price, $tax = 0) {
    global $currencies;
    return $price * $tax / 100;
  }

////
// Add tax to a products price based on whether we are displaying tax "in" the price
function zen_add_tax($price, $tax = 0) {
    global $currencies;

    if ( ('DISPLAY_PRICE_WITH_TAX' == 'true') && ($tax > 0) ) {
      return $price + zen_calculate_tax($price, $tax);
    } else {
      return $price;
    }
  }

/**
 * currencies class
 *
 * @package classes
 */
class currencies //extends base
{
    var $currencies;

    function __construct()
    {
        // global $db;
        // $this->currencies = [];

        // $query   = "select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, `value`
        //             from " . TABLE_CURRENCIES;
        // $results = $db->Execute($query);

        // foreach ($results as $result) {
        //     $this->currencies[$result['code']] = [
        //         'title'           => $result['title'],
        //         'symbol_left'     => $result['symbol_left'],
        //         'symbol_right'    => $result['symbol_right'],
        //         'decimal_point'   => $result['decimal_point'],
        //         'thousands_point' => $result['thousands_point'],
        //         'decimal_places'  => (int)$result['decimal_places'],
        //         'value'           => $result['value'],
        //     ];
        // }
    }

    /**
     * Format the specified number according to the specified currency's rules
     * @param float $number
     * @param bool $calculate_using_exchange_rate
     * @param string $currency_type
     * @param float $currency_value
     * @return string
     */
    function format($number, $calculate_using_exchange_rate = true, $currency_type = '', $currency_value = '')
    {
        // if (IS_ADMIN_FLAG === false && (DOWN_FOR_MAINTENANCE == 'true' && DOWN_FOR_MAINTENANCE_PRICES_OFF == 'true') && (!strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER['REMOTE_ADDR']))) {
        //     return '';
        // }

        if (empty($number)) $number = 0;

        if (empty($currency_type)) $currency_type = (isset($_SESSION['currency']) ? $_SESSION['currency'] : 'DEFAULT_CURRENCY');

        $formatted_string = $this->currencies[$currency_type]['symbol_left'] .
            number_format(
                $this->rateAdjusted($number, $calculate_using_exchange_rate, $currency_type, $currency_value),
                $this->currencies[$currency_type]['decimal_places'],
                $this->currencies[$currency_type]['decimal_point'],
                $this->currencies[$currency_type]['thousands_point']
            ) . $this->currencies[$currency_type]['symbol_right'];

        if ($calculate_using_exchange_rate == true) {
            // Special Case: if the selected currency is in the european euro-conversion and the default currency is euro,
            // then the currency will displayed in both the national currency and euro currency
            // if ('DEFAULT_CURRENCY' == 'EUR' && in_array($currency_type, ['DEM', 'BEF', 'LUF', 'ESP', 'FRF', 'IEP', 'ITL', 'NLG', 'ATS', 'PTE', 'FIM', 'GRD'])) {
            //     $formatted_string .= ' <small>[' . $this->format($number, true, 'EUR') . ']</small>';
            // }
        }

        return $formatted_string;
    }

    /**
     * Convert amount based on currency values
     * Or at least round it to the relevant decimal places
     *
     * @param float $number
     * @param bool $calculate_using_exchange_rate
     * @param string $currency_type
     * @param float $currency_value
     * @return float
     */
    function rateAdjusted($number, $calculate_using_exchange_rate = true, $currency_type = '', $currency_value = null)
    {
        if (empty($currency_type)) $currency_type = (isset($_SESSION['currency']) ? $_SESSION['currency'] : 'DEFAULT_CURRENCY');

        if ($calculate_using_exchange_rate == true) {
            $rate   = zen_not_null($currency_value) ? $currency_value : $this->currencies[$currency_type]['value'];
            $number = $number * $rate;
        }

        return zen_round($number, $this->currencies[$currency_type]['decimal_places']);
    }

    function value($number, $calculate_using_exchange_rate = true, $currency_type = '', $currency_value = null)
    {
        if (empty($currency_type)) $currency_type = (isset($_SESSION['currency']) ? $_SESSION['currency'] : 'DEFAULT_CURRENCY');

        if ($calculate_using_exchange_rate == true) {
            $multiplier = ($currency_type == 'DEFAULT_CURRENCY') ? 1 / $this->currencies[$_SESSION['currency']]['value'] : $this->currencies[$currency_type]['value'];
            $rate = zen_not_null($currency_value) ? $currency_value : $multiplier;
            $number = $number * $rate;
        }

        return zen_round($number, $this->currencies[$currency_type]['decimal_places']);
    }

    /**
     * Normalize "decimal" placeholder to actually use "."
     * @param $valueIn
     * @param string $currencyCode
     * @return string
     */
    function normalizeValue($valueIn, $currencyCode = null)
    {
        if ($currencyCode === null) $currencyCode = (isset($_SESSION['currency']) ? $_SESSION['currency'] : 'DEFAULT_CURRENCY');
        $value = str_replace($this->currencies[$currencyCode]['decimal_point'], '.', $valueIn);

        return $value;
    }

    function is_set($code)
    {
        return isset($this->currencies[$code]) && zen_not_null($this->currencies[$code]);
    }

    /**
     * Retrieve the exchange-rate of a specified currency
     * @param string $code currency code
     * @return float
     */
    function get_value($code)
    {
        return $this->currencies[$code]['value'];
    }

    /**
     * @param string $code currency code
     * @return int
     */
    function get_decimal_places($code)
    {
        return $this->currencies[$code]['decimal_places'];
    }

    /**
     * Calculate amount based on $quantity, and format it according to current currency
     * @param $product_price
     * @param $product_tax
     * @param int $quantity
     * @return string
     */
    function display_price($product_price, $product_tax, $quantity = 1)
    {
        return $this->format(zen_add_tax($product_price, $product_tax) * $quantity);
    }
}

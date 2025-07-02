<?php
if(!function_exists('get_host'))
{
  function get_host()
  {
    return ((@$_SERVER["HTTPS"] == "on") ? "https://" : "http://") . $_SERVER["HTTP_HOST"];
  }
}

if(!function_exists('get_version'))
{
  function get_version()
  {
    return config("app.version");
  }
}

if(!function_exists('get_const'))
{
  function get_const($key)
  {
    $response = "";

    if(defined($key))
    {
      $response = constant($key);
    }
    elseif(!is_null(env($key)))
    {
      $response = env($key);
    }

    return $response;
  }
}

if(!function_exists('str_icontains'))
{
  function str_icontains($cSearch, $cString)
  {
    return strpos(strtolower($cString), strtolower($cSearch)) !== false;
  }
}

if(!function_exists('get_asset'))
{
  function get_asset($curl="")
  {
    $response = "";

    $curl = trim($curl);

    if(!empty($curl))
    {
      if(stripos($curl, 'http') !== false)
      {
        $response = $curl;
      }
      else
      {
        $curl = (substr($curl, 0, 1) === '/') ? $curl : '/' . $curl;

        $response = get_const('APP_URL') . $curl;
      }
    }

    return $response . '?' . get_version();
  }
}

if(!function_exists('str_icontains'))
{
  function str_icontains($cWord, $cString)
  {
    return strpos(strtolower($cString), strtolower($cWord)) !== false;
  }
}

if(!function_exists('translateRomanNumerals'))
{
  function translateRomanNumerals($num)
  {
    // intval(xxx) para que convierta explícitamente a int
    $n = intval($num);
    $res = '';
    // Array con los números romanos
    $roman_numerals = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);

    foreach($roman_numerals as $roman => $number)
    {
      // Dividir para encontrar resultados en array
      $matches = intval($n / $number);
      // Asignar el numero romano al resultado
      $res .= str_repeat($roman, $matches);
      // Descontar el numero romano al total
      $n = $n % $number;
    }

    // Res = String
    return $res;
  }
}

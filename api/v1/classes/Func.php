<?php

use Google\Cloud\Storage\StorageClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use \Osms\Osms;

class Func
{


   

    public static function getIp()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = null;

        return $ipaddress;
    }

    public static function preParams($ids)
    {
        return implode(',', array_fill(0, count($ids), '?'));
    }

    public static function time()
    {
        return strtotime('now Europe/Paris');
    }

    public static function sort($a, $b, $key = 'id')
    {
        $a = (array)$a;
        $b = (array)$b;
        return $b[$key] - $a[$key];
    }


    public static function upload($file, $name, $dir = UPLOAD_PATH, $allowed = array('image/*', 'video/*'), $maxSize = 5024)
    {

        require_once "upload/Upload.php";

        if (strpos($file, 'data:') == false) {
            $_data = file_get_contents($file);
            $file = 'data:;base64,' . base64_encode($_data);
        }

        $handle = new Upload($file);
        $type = null;
        if (strpos($handle->file_src_mime, 'video') !== false) {
            $type = 'video';
            $maxSize = 5024;
        }
        if (strpos($handle->file_src_mime, 'image') !== false) {
            $type = 'image';
            $maxSize = 1024;
        }

        if ($handle->uploaded) {
            $handle->allowed = $allowed;
            $handle->file_new_name_body = $name;
            $handle->file_max_size = $maxSize * 1000;
            $handle->file_auto_rename = false;
            $handle->process($dir);

            if ($handle->processed) {
                $ext = $handle->file_dst_name_ext;
                $size = $handle->file_src_size;
                $width = $handle->image_dst_x;
                $height = $handle->image_dst_y;
                App::$response["dds"] = $handle;
                $handle->clean();
                return [
                    "type" => $type,
                    "width" => $width,
                    "height" => $height,
                    "size" => $size,
                    "upload" => true,
                    "path" => $dir . $name . '.' . $ext
                ];
            } else {
                return [
                    "upload" => false,
                    "upload_error" => $handle->error,
                ];
            }
        } else {
            return [
                "upload" => false,
                "upload_error" => $handle->error,
            ];
        }
    }


    public static function sendSms($receiver, $message, $senderName = 'WeenCar')
    {
        $config = array(
            'clientId' => 'UwvNqE0RTMMQ0oZjOPKPDkvRHIDXxTxc',
            'clientSecret' => 'kzFSLvb16tVcC0AP'
        );

        $osms = new Osms($config);
        $osms->setVerifyPeerSSL(false);

        $response = $osms->getTokenFromConsumerKey();

        if (!empty($response['access_token'])) {
            $senderAddress = 'tel:+21620710484';
            $receiverAddress = 'tel:+216' . $receiver;
            $response =  @$osms->sendSMS($senderAddress, $receiverAddress, $message, $senderName);
            if (!@$response->error) {
                return [
                    "status" => "OK",
                    "info" => $response
                ];
            } else {
                return [
                    "status" => "ERROR"
                ];
            }
        } else {
            return [
                "status" => "ERROR"
            ];
        }
        return [
            "status" => "ERROR"
        ];
    }


    public static function dateRange($data)
    {
        if (@$data["start"]) {
            $start = strtotime($data["start"] . ' Africa/Tunis');
        } else {
            $start =  strtotime("-122323239360 days", strtotime("now"));
        }

        if (@$data["end"]) {
            $end = strtotime($data["end"] . ' Africa/Tunis');
        } else {
            if (@$data["start"] && !@$data["end"]) {
                $end = strtotime("+23 hours 59 minutes",   $start);
            } else {
                $end =  strtotime("+122323239360 days");
            }
        }
        return [$start, $end];
    }

    public static function pagi($statement, $filter, $params = null, $statement_count = 'count(*)')
    {
        if (!$params) {
            $params = App::$request;
        }

        $max = $params["max"] ?? 12;
        $cp = $params["cp"] ?? 1;
        $sortby = $params["sortby"] ?? "p.id";
        $sort_type = $params["sort_type"] ?? "desc";

        if ($max > 100) {
            $max = 100;
        }

        $cp > 0 ? $cp-- : $cp = 0;

        $req = $filter($statement);

        if ($statement_count != 'count(*)')
            $total_rows = $filter($statement_count)->execute()->rowCount();
        else
            $total_rows = $filter($statement_count)->execute()->fetchColumn();

        $total_pages  =  ceil($total_rows / $max);
        $from =  ceil(($cp) * $max);

        $req->orderBy($sortby . "  " . $sort_type);
        $items = $req->limit("$from,$max")->execute();

        $products = $items->fetchAll();

        $detail  = [
            "cp" => $cp,
            "count" => $items->rowCount(),
            "total" => $total_rows,
            "pages" => $total_pages
        ];

        return [
            "detail" =>  $detail,
            "result" =>  $products,
        ];
    }


    public static function function_get_output($fn)
    {
        $args = func_get_args();
        unset($args[0]);
        ob_start();
        call_user_func_array($fn, $args);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public static function  display($template, $params = array())
    {
        $params = (array) $params;
        extract($params);
        include $template;
    }

    public static function render($template, $params = array())
    {
        return self::function_get_output('self::display', $template, $params);
    }

    public static function fHash($value)
    {
        return password_hash($value, PASSWORD_BCRYPT);
    }

    public static function spaceFix($value)
    {
        return trim(preg_replace('/\s+/', ' ', $value));
    }

    static function filterWord($value)
    {
        return (str_replace("-", "", str_replace(" ", "", strtoupper(trim($value)))));
    }

    public static function emptyCheck($params = [])
    {
        $empty_elements = [];
        foreach ($params as $key => $value) {
            if (!isset($value)  || (empty($value) && is_null($value) && $value != 0) || $value == "")
                self::error("IS_NULL", 400);
        }
    }

    public static function number($value)
    {
        return (float) str_replace(' ', '', preg_replace("/[^0-9.]/", "",  str_replace(',', '.', $value)));
    }



    public static function recaptcha($recaptchaToken)
    {
        $recaptchaInfo =  json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . RECAPTCHA_KEY . "&response=" . $recaptchaToken), false);
        if ($recaptchaInfo->success)
            return true;

        return false;
    }


    public static function urlEexists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

    public static function requestMap($query)
    {
        $api_keys = [
            "AIzaSyCnbCN9zcMhrD1KzEe__utGsZ4MN5IrKTU",
            "AIzaSyBJiQZyvPNERRKjmN4nnsrA6WFQQTnwYKA",
            "AIzaSyBjekIVdzGQ1EZqnWJyxwNp5lkiQJWcppo",
            "AIzaSyAwkUR61tzMvJYaKOzbovIffCPBVpijnl0",
            "AIzaSyCAYorWuqzvRAPmNRs8C95Smp7hhdATzc8",
            "AIzaSyDZfSnQBIu3V5N9GWbpKGtAUYmDDyxPonU",
            "AIzaSyDqEFJTjKx6L-RpoT-nPiqTi1KJVJimH3I",
            "AIzaSyD6AreMUlOQ90uaRuERD8J4Jv5DnQ85Xys"
        ];

        $data = [];
        foreach ($api_keys as $key => $api_key) {

            $output = file_get_contents(preg_replace('/\s+/', '%20', "https://maps.googleapis.com/maps/api/place/" . $query . "&key=" . $api_key));
            $data =  json_decode($output, false);
            if ($data->status == "OK" || $data->status == "ZERO_RESULTS")  break;
            $data->current = $key + 1;
        }
        return  $data;
    }

    public static function between($string, $start, $end = "</", $remove_space = false, $strip_tags = true)
    {
        $start = strtolower($start);
        $end = strtolower($end);
        $string = ' ' . $string;
        $ini = strpos(strtolower($string), $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos(strtolower($string), $end, $ini) - $ini;

        $string = trim(substr($string, $ini, $len));


        if ($remove_space)
            $string = str_replace(' ', '', $string);
        return $string;
    }

    public static function betweenAll($str, $startDelimiter, $endDelimiter = "</", $with_space = false, $left_content = "")
    {
        $startDelimiter = strtolower($startDelimiter);
        $endDelimiter = strtolower($endDelimiter);
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos(strtolower($str), $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos(strtolower($str), $endDelimiter, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $string = trim(substr($str, $contentStart, $contentEnd - $contentStart));
            if ($with_space)
                $string = str_replace(' ', '', $string);

            $contents[] = $left_content . $string;
            $startFrom = $contentEnd + $endDelimiterLength;
        }

        return $contents;
    }

    public static function request(...$params)
    {
        $method = @$params[0];
        $url = @$params[1];
        $option = @$params[2] ?? [];
        $client = new Client([
            'verify' => false,
            'headers' => [
                'charset' => 'utf-8',
                'User-Agent' => USER_AGENT
            ]
        ]);
        try {
            $request = $client->request($method, $url, $option);

            return [
                "content" => $request->getBody()->getContents(),
                "status" => $request->getStatusCode(),
            ];
        } catch (RequestException $e) {
            if ($e->getResponse()) {
                return [
                    "content" => $e->getResponse()->getBody()->getContents(),
                    "status" => 400,
                ];
            } else {
                return [
                    "content" => $e->getMessage(),
                    "status" => 400,
                ];
            }
        }
    }


    public static function error($status = null, $response = 401, $errorMsg = null)
    {
        http_response_code($response);

        if ($status == null)
            $status = self::httpResponseMsg($response);
        // show error message
        echo json_encode(array(
            "response"  => $response,
            "status"  => $status,
            "error_message"   => $errorMsg
        ));

        exit;
    }

    public static function checkImage($image)
    {
        if (strpos($image, "http") !== false) {
            return $image;
        } else {
            if ($image != null) {
                return BASE_URL . "$image";
            } else {
                return null;
            }
        }
    }
    public static function array_sort($array, $value = 'value', $inc = true)
    {
        count($array);
        usort($array, function ($a, $b) use ($value, $inc) {
            if ($inc) {
                return ($a[$value] >=  $b[$value]);
            } else {
                return ($a[$value] <=  $b[$value]);
            }
        });
        return $array;
    }

    public static function max_attribute_in_array($data_points, $value = 'value', $type = "max")
    {
        $max = 0;
        foreach ($data_points as $point) {
            if ($max < (float) $point[$value]) {
                $max = $point[$value];
            }
        }
        return $max;
    }

    public static function setArray($array, $propertie, $value, $where = 'left')
    {
        foreach ($array as $key => $val) {
            $prop_value = $array[$key][$propertie];
            if (!empty($prop_value)) {
                if ($where == 'left')
                    $array[$key][$propertie] = self::checkImage($value . $prop_value);
                else
                    $array[$key][$propertie] = self::checkImage($prop_value . $value);
            }
        }

        return $array;
    }


    //Remove the specific properties of an object in an array (Ex password or email)
    public static function unset($array, $properties)
    {
        $properties = explode(',', $properties);
        foreach ($array as $key => $val) {
            foreach ($properties as $propertie) {
                unset($array[$key][$propertie]);
            }
        }

        return $array;
    }

    //To make slug (URL string)
    public static function slugify($text, $maxLenght = 5, $lang = null)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        if ($lang != "ar") {
            // transliterate
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

            // remove unwanted characters
            $text = preg_replace('~[^-\w]+~', '', $text);
        }

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = mb_strtolower($text, 'UTF-8');

        if (empty($text)) {
            return 'n-a';
        }

        return substr($text, 0, $maxLenght);
    }



    public static function randomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public static function urlMix($name, $nameLength = 10, $randomLength = 10, $lang = null, $is_left = true)
    {
        if ($is_left) {
            return trim(self::slugify($name, $nameLength, $lang) . '-' . self::randomString($randomLength), '-');
        } else {
            return trim(self::randomString($randomLength) . '-' . self::slugify($name, $nameLength, $lang), '-');
        }
    }




    //Ex return X-TOKEN VALUE getHeader("X-Token")
    public static function getHeader($key)
    {
        return getallheaders()[$key];
    }

    public static function httpResponseMsg($num)
    {
        $array = array(
            100 => 'CONTINUE',
            101 => 'SWITCHING_PROTOCOLS',
            102 => 'PROCESSING', // WEBDAV; RFC 2518
            200 => 'OK',
            201 => 'CREATED',
            202 => 'ACCEPTED',
            203 => 'NON-AUTHORITATIVE_INFORMATION', // SINCE HTTP/1.1
            204 => 'NO CONTENT',
            205 => 'RESET_CONTENT',
            206 => 'PARTIAL_CONTENT',
            207 => 'MULTI-STATUS', // WEBDAV; RFC 4918
            208 => 'ALREADY_REPORTED', // WEBDAV; RFC 5842
            226 => 'IM USED', // RFC 3229
            300 => 'MULTIPLE_CHOICES',
            301 => 'MOVED_PERMANENTLY',
            302 => 'FOUND',
            303 => 'SEE_OTHER', // SINCE HTTP/1.1
            304 => 'NOT_MODIFIED',
            305 => 'USE_PROXY', // SINCE HTTP/1.1
            306 => 'SWITCH_PROXY',
            307 => 'TEMPORARY_REDIRECT', // SINCE HTTP/1.1
            308 => 'PERMANENT_REDIRECT', // APPROVED AS EXPERIMENTAL RFC
            400 => 'BAD_REQUEST',
            401 => 'UNAUTHORIZED',
            402 => 'PAYMENT_REQUIRED',
            403 => 'FORBIDDEN',
            404 => 'NOT_FOUND',
            405 => 'METHOD_NOT_ALLOWED',
            406 => 'NOT_ACCEPTABLE',
            407 => 'PROXY_AUTHENTICATION_REQUIRED',
            408 => 'REQUEST_TIMEOUT',
            409 => 'CONFLICT',
            410 => 'GONE',
            411 => 'LENGTH_REQUIRED',
            412 => 'PRECONDITION_FAILED',
            413 => 'REQUEST_ENTITY_TOO_LARGE',
            414 => 'REQUEST-URI_TOO_LONG',
            415 => 'UNSUPPORTED_MEDIA_TYPE',
            416 => 'REQUESTED_RANGE_NOT_SATISFIABLE',
            417 => 'EXPECTATION_FAILED',
            418 => 'I\'M_A_TEAPOT', // RFC 2324
            419 => 'AUTHENTICATION_TIMEOUT', // NOT IN RFC 2616
            420 => 'ENHANCE_YOUR_CALM', // TWITTER
            420 => 'METHOD_FAILURE', // SPRING FRAMEWORK
            422 => 'UNPROCESSABLE_ENTITY', // WEBDAV; RFC 4918
            423 => 'LOCKED', // WEBDAV; RFC 4918
            424 => 'FAILED_DEPENDENCY', // WEBDAV; RFC 4918
            424 => 'METHOD_FAILURE', // WEBDAV)
            425 => 'UNORDERED_COLLECTION', // INTERNET DRAFT
            426 => 'UPGRADE_REQUIRED', // RFC 2817
            428 => 'PRECONDITION_REQUIRED', // RFC 6585
            429 => 'TOO_MANY_REQUESTS', // RFC 6585
            431 => 'REQUEST_HEADER_FIELDS_TOO_LARGE', // RFC 6585
            444 => 'NO_RESPONSE', // NGINX
            449 => 'RETRY_WITH', // MICROSOFT
            450 => 'BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS', // MICROSOFT
            451 => 'REDIRECT', // MICROSOFT
            451 => 'UNAVAILABLE_FOR_LEGAL_REASONS', // INTERNET DRAFT
            494 => 'REQUEST_HEADER_TOO_LARGE', // NGINX
            495 => 'CERT_ERROR', // NGINX
            496 => 'NO_CERT', // NGINX
            497 => 'HTTP_TO_HTTPS', // NGINX
            499 => 'CLIENT_CLOSED_REQUEST', // NGINX
            500 => 'INTERNAL_SERVER_ERROR',
            501 => 'NOT_IMPLEMENTED',
            502 => 'BAD_GATEWAY',
            503 => 'SERVICE_UNAVAILABLE',
            504 => 'GATEWAY_TIMEOUT',
            505 => 'HTTP_VERSION_NOT_SUPPORTED',
            506 => 'VARIANT_ALSO_NEGOTIATES', // RFC 2295
            507 => 'INSUFFICIENT_STORAGE', // WEBDAV; RFC 4918
            508 => 'LOOP_DETECTED', // WEBDAV; RFC 5842
            509 => 'BANDWIDTH_LIMIT EXCEEDED', // APACHE BW/LIMITED EXTENSION
            510 => 'NOT_EXTENDED', // RFC 2774
            511 => 'NETWORK_AUTHENTICATION_REQUIRED', // RFC 6585
            598 => 'NETWORKREAD_TIMEOUT_ERROR', // UNKNOWN
            599 => 'NETWORK_CONNECT_TIMEOUT_ERROR', // UNKNOWN
        );
        return $array[$num];
    }
}

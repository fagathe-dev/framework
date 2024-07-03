<?php
namespace Fagathe\Framework\Helpers;

use Symfony\Component\HttpFoundation\Session\Session;

trait Helpers
{

     public function __construct()
     {
     }

     /**
      * Skip accents in string
      * @param string $str
      * @param string $charset
      * @return string
      */
     public function skipAccents(string $str, string $charset = 'utf-8'): string
     {
          $str = trim($str);
          $str = htmlentities($str, ENT_NOQUOTES, $charset);

          $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|uml);#', '\1', $str);
          $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
          $str = preg_replace('#&[^;]+;#', '', $str);
          $str = preg_replace('/[^A-Za-z0-9\-]/', ' ', $str);

          return $str;
     }

     /**
      * getMethod
      * @param  string $str
      * @return string
      */
     public function getMethod(string $str): string
     {
          $needle = '_';
          $method = "";
          if (preg_match("#{$needle}#", $str)) {
               $array = preg_split("#{$needle}#", $str);
               if (is_array($array)) {
                    foreach ($array as $v) {
                         $method .= ucfirst($v);
                    }
               }
               return $method;
          }
          return ucfirst($str);
     }

     /**
      * Transform string in slug
      * @param string $str
      * @return string
      */
     public function generateSlug(string ...$vars)
     {
          $str = trim(join(' ', func_get_args()));
          $str = trim($this->skipAccents($str));
          return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $str));
     }

     /**
      * Put needle in a string
      * @param string $separator
      * @param array $array
      * @return string
      */
     public function putBetween(string $separator, array $array): string
     {
          $str = "";
          if (is_array($array)) {
               foreach ($array as $k => $v):
                    $str .= $k === 0 ? $v : " {$separator} {$v}";
               endforeach;
          }
          return $str;
     }

     /**
      * Put needle before string
      * @param string $separator
      * @param array $array
      * @return string
      */
     public function putBefore(string $separator, array $array): string
     {
          $str = "";
          if (is_array($array)) {
               foreach ($array as $v):
                    $str .= "{$separator}{$v}";
               endforeach;
          }
          return $str;
     }

     /**
      * Transform keys for PDO::execute()
      * @param array $values
      * @return array
      */
     public function transformKeys(array $values): array
     {
          $execute = [];

          foreach ($values as $k => $v) {
               $execute[":{$k}"] = $v;
          }
          return $execute;
     }

     /**
      * Check if every field is filled
      * @param array $data
      * @return boolean
      */
     public function checkFieldsSet(array $data = []): bool
     {
          foreach ($data as $v) {
               if ($v === '' || $v === NULL)
                    return false;
          }
          return true;
     }

     /**
      * Sanitize input data
      * @param array $data
      * @return array
      */
     public function sanitize(array $data = []): array
     {
          $sanitize = [];
          $tags = ['a', 'article', 'aside', 'b', 'br', 'div', 'em', 'font', 'hr', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'i', 'img', 'li', 'ol', 'p', 'pre', 'section', 'small', 'strong', 'sub', 'sup', 'u', 'ul'];
          foreach ($data as $k => $v) {
               $v = trim($v);
               $v = strip_tags($v, array_to_string($tags));
               $sanitize[$k] = htmlspecialchars(trim($v), ENT_COMPAT | ENT_HTML5 | ENT_IGNORE | ENT_QUOTES, "UTF-8");
          }
          return $sanitize;
     }

     /**
      * Check if csrf_token is valid
      * @param string $token
      * @return boolean
      */
     public function checkCsrfToken(string $token): bool
     {
          $session_csrf = (new Session)->get('csrf_token');
          return $session_csrf && $session_csrf === $token ? true : false;
     }

     /**
      * Undocumented function
      * @param string $file_name
      * @param array $allowed_extensions
      * @return boolean
      */
     public function checkExtension(string $file_name, array $allowed_extensions): bool
     {
          $file_extension = strtolower(explode('.', $file_name)[1]);
          return in_array($file_extension, $allowed_extensions);
     }

}
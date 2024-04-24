<?php
    /**
     * Error Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Error.php, v1.00 4/25/2023 1:28 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Core;
    
    use ErrorException;
    use Throwable;
    use Wojo\Debug\Debug;
    use Wojo\File\File;
    use Wojo\Url\Url;
    
    class Error
    {
        
        /**
         *
         */
        public function __construct()
        {
            register_shutdown_function([__CLASS__, 'shutdown']);
            set_error_handler([__CLASS__, 'errorHandler']);
            set_exception_handler([__CLASS__, 'exceptionHandler']);
        }
        

        public static function run()
        {
            if (DEBUG) {
                error_reporting(E_ALL);
                return new self();
            } else {
                error_reporting(0);
            }
        }
        
        /**
         * shutdown
         *
         * @return void
         */
        public static function shutdown(): void
        {
            $error = error_get_last();
            if (!is_null($error) && $error['type'] === E_ERROR) {
                if (!str_contains($error['message'], 'Uncaught')) {
                    self::exceptionHandler($error);
                }
            }
        }
        
        /**
         * errorHandler
         *
         * @param $err_code
         * @param $err_msg
         * @param $err_file
         * @param $err_line
         * @return bool
         * @throws ErrorException
         */
        public static function errorHandler($err_code, $err_msg, $err_file, $err_line): bool
        {
            switch ($err_code) {
                case E_USER_WARNING:
                case E_USER_NOTICE:
                    Debug::addMessage('errors', '<i>error</i>', $err_code . ' ' . $err_msg . ' ' . $err_file . ' on line: ' . $err_line, 'session');
                    return true;
                case E_USER_ERROR:
                default:
                    throw new ErrorException($err_msg, 0, $err_code, $err_file, $err_line);
                    break;
            }
        }
        
        /**
         * exceptionHandler
         *
         * @param $error
         * @return void
         */
        public static function exceptionHandler($error): void
        {
            $trace = '';
            $traceArr = $error->getTrace();
            foreach ($traceArr as $t) {
                if (!array_key_exists('line', $t) || !array_key_exists('file', $t)) {
                    continue;
                }
                $trace .= '<tr>';
                $trace .= '<td>' . $t['line'] . '</td>';
                $trace .= '<td>' . $t['file'] . '</td>';
                $trace .= '<td>' . (!array_key_exists('class', $t) ? '' : $t['class']) . '</td>';
                $trace .= '<td>' . (!array_key_exists('function', $t) ? '' : $t['function']) . '</td>';
                $trace .= '</tr>';
            }
            
            $extra = '';
            try {
                $extraArr = $error->getExtra();
                foreach ($extraArr as $k => $v) {
                    $extra .= '<tr>';
                    $extra .= '<td>' . $k . '</td>';
                    $extra .= '<td>' . $v . '</td>';
                    $extra .= '</tr>';
                }
            } catch (Throwable) {
            }
            
            $code = '';
            try {
                $line_start = (int) $error->getLine() - 10;
                if ($line_start < 0) {
                    $line_start = 0;
                }
                $source = file($error->getFile());
                $source = array_slice($source, $line_start > 1 ? $line_start - 1 : $line_start, 20);
                
                foreach ($source as $k => $line) {
                    $line_number = $line_start + $k;
                    $class = ($error->getLine() == $line_number) ? 'bg-danger' : '';
                    $class2 = in_array($line_number, [$error->getLine() - 1, $error->getLine() + 1]) ? 'bg-danger2' : '';
                    $code .= "\n<tr class=\"$class\">";
                    $code .= "\n<td class=\"code-line_number\">" . $line_number . '</td>';
                    $code .= "\n<td class=\"$class2\">" . self::highlight($line) . '</td>';
                    $code .= "\n</tr>";
                }
            } catch (Throwable) {
            }
            
            Debug::addMessage('errors', '<i>error</i>', get_class($error) . ' ' . $error->getMessage() . ' ' . $error->getFile() . ' on line: ' . $error->getLine(), 'session');
            self::show($error, $trace, $extra, $code);
            exit;
        }
        
        /**
         * highlight
         *
         * @param  string  $code
         * @return array|string|null
         */
        private static function highlight(string $code): array|string|null
        {
            $code = str_replace("\n", '', $code);
            $code = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
            
            $code = preg_replace('/(&quot;(.*)&quot;)/ui', "<span class=\"code-string\">$1</span>", $code);
            $code = preg_replace('/(&#039;(.*)&#039;)/ui', "<span class=\"code-string\">$1</span>", $code);
            
            $code = preg_replace('/(if|for|switch|elseif|while|foreach)(\s*\()/ui', "<span class=\"code-core\">$1</span>$2", $code);
            $code = preg_replace('/((function|public|class|private|const|use|namespace|throw|new|require_once|require|include|include_once)\s+)/ui', "<span class=\"code-core\">$1</span>", $code);
            $code = preg_replace("/((null|true|false|else|continue|break|self::|self|static::|static|\$this)\s*)/ui", "<span class=\"code-core\">$1</span>", $code);
            $code = preg_replace('/((echo|return|extends|implements|protected)\s+)/ui', "<span class=\"code-core\">$1</span>", $code);
            
            $code = preg_replace('/([a-z_]+[a-z_0-9]*\s*)(\()/ui', "<span class=\"code-func\">$1</span>$2", $code);
            $code = preg_replace('/([a-z_]+\s*)(\()/ui', "<span class=\"code-func\">$1</span>$2", $code);
            
            $code = preg_replace('/(-&gt;)([a-z]+[a-z0-9_]*)/ui', "$1<span class=\"code-variable\">$2</span>", $code);
            $code = preg_replace("/(\\$([a-z_]+[a-z0-9_]*))/ui", "<span class=\"code-variable\">$1</span>", $code);
            
            $code = preg_replace('/(\)|\(|\}|\{)/ui', "<span class=\"code-brace\">$1</span>", $code);
            $code = preg_replace('/(\]|\[)/ui', "<span class=\"code-arr\">$1</span>", $code);
            
            $code = preg_replace('/((\/|\s)\*+(.*))/ui', "<span class=\"code-comment\">$1</span>", $code);
            $code = preg_replace('/^(\*+(.*))/ui', "<span class=\"code-comment\">$1</span>", $code);
            return preg_replace('/(\/\/(.*)$)/ui', "<span class=\"code-comment\">$1</span>", $code);
        }
        
        /**
         * show
         *
         * @param $error
         * @param $trace
         * @param $extra
         * @param $code
         * @return void
         */
        private static function show($error, $trace, $extra, $code): void
        {
            ob_clean();
            $html = '
            <!DOCTYPE html>
            <head>
            <title>Debugger</title>
            <style>' . File::loadFile(BASEPATH . 'view/admin/css/debugger.css') . '</style>
            </head>
            <body>
            <div id="debugger-box">
            <div id="error-box">
                <h3 class="title">
                    <span>{class}</span>
                    <span class="file-line">{file} : {line}</span>
                </h3>
                <p>{message}</p>
                <div class="{extra_status} overflow bordered extraInfo">
                    <table class="table">
                        <thead>
                        <tr>
                            <td colspan="2">
                                <h3 class="title">Extra info</h3>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        {extra}
                        </tbody>
                    </table>
                </div>
                <div class="small">{uri}</div>
            </div>
            <div id="code-box">
                <h3 class="title2">
                    <span>Code</span>
                    <span class="file-line">{file}:{line}</span>
                </h3>
                <pre class="overflow">
                  <table>
                    <tbody>
                      <tr><td class="code-line_number">&nbsp;</td><td>&nbsp;</td></tr>
                      {code}
                      <tr><td class="code-line_number">&nbsp;</td><td>&nbsp;</td></tr>
                    </tbody>
                  </table>
                </pre>
            </div>
            <div id="trace-box">
                <h3 class="title"><span>Trace</span></h3>
                <div class="overflow">
                    <table class="table">
                        <thead>
                        <tr>
                            <td>Line</td>
                            <td>File</td>
                            <td>Class</td>
                            <td>Function</td>
                        </tr>
                        </thead>
                        <tbody>
                        {trace}
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
            </body>
            </html>
            ';
            
            $values = [
                '{message}' => $error->getMessage(),
                '{class}' => get_class($error),
                '{file}' => $error->getFile(),
                '{line}' => $error->getLine(),
                '{uri}' => Url::currentUrl(),
                '{trace}' => $trace,
                '{extra}' => $extra,
                '{code}' => $code,
                '{extra_status}' => $extra ? '' : 'hide',
            ];
            print str_replace(array_keys($values), $values, $html);
            ob_flush();
        }
    }
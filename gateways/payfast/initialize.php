<?php
    /**
     * initialize
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: initialize.php, v1.00 6/25/2023 2:32 PM Gewa Exp $
     *
     */
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    //enable or disable debugging
    const PF_DEBUG = false;
    const PF_CURL = false;
    
    const PF_TIMEOUT = 15;
    const PF_EPSILON = 0.01;
    
    // User agent constituents (for cURL)
    const PF_SOFTWARE_NAME = 'Cms Pro';
    const PF_SOFTWARE_VER = '6.0';
    const PF_MODULE_NAME = 'PayFast-Cms Pro';
    const PF_MODULE_VER = '1.0';
    
    // Error
    const PF_ERR_AMOUNT_MISMATCH = 'Amount mismatch';
    const PF_ERR_BAD_ACCESS = 'Bad access of page';
    const PF_ERR_BAD_SOURCE_IP = 'Bad source IP address';
    const PF_ERR_CONNECT_FAILED = 'Failed to connect to PayFast';
    const PF_ERR_INVALID_SIGNATURE = 'Security signature mismatch';
    const PF_ERR_MERCHANT_ID_MISMATCH = 'Merchant ID mismatch';
    const PF_ERR_NO_SESSION = 'No saved session found for ITN transaction';
    const PF_ERR_ORDER_ID_MISSING_URL = 'Order ID not present in URL';
    const PF_ERR_ORDER_ID_MISMATCH = 'Order ID mismatch';
    const PF_ERR_ORDER_INVALID = 'This order ID is invalid';
    const PF_ERR_ORDER_PROCESSED = 'This order has already been processed';
    const PF_ERR_PDT_FAIL = 'PDT query failed';
    const PF_ERR_PDT_TOKEN_MISSING = 'PDT token not present in URL';
    const PF_ERR_SESSIONID_MISMATCH = 'Session ID mismatch';
    const PF_ERR_UNKNOWN = 'Unkown error occurred';
    const PF_ERR_BAD_ATTENDEE_SESSION_ID = 'Incorrect attendee session id';
    
    const PF_MSG_OK = 'Payment was successful';
    const PF_MSG_FAILED = 'Payment has failed';
    const PF_MSG_PENDING = 'The payment is pending. Please note, you will receive another Instant' . ' Transaction Notification when the payment status changes to' . ' "Completed", or "Failed"';
    
    $pfFeatures = 'PHP ' . phpversion() . ';';
    
    if (in_array('curl', get_loaded_extensions())) {
        $pfVersion = curl_version();
        $pfFeatures .= ' curl ' . $pfVersion['version'] . ';';
    } else {
        $pfFeatures .= ' nocurl;';
    }
    
    define('PF_USER_AGENT', PF_SOFTWARE_NAME . '/' . PF_SOFTWARE_VER . ' (' . trim($pfFeatures) . ') ' . PF_MODULE_NAME . '/' . PF_MODULE_VER);
    
    /**
     * pflog
     *
     * @param $msg
     * @param $close
     * @return void
     */
    function pflog($msg = '', $close = false): void
    {
        static $fh = 0;
        
        // Only log if debugging is enabled
        if (PF_DEBUG) {
            if ($close) {
                fclose($fh);
            } else {
                // If file doesn't exist, create it
                if (!$fh) {
                    $pathinfo = pathinfo(__file__);
                    $fh = fopen($pathinfo['dirname'] . '/payfast.log', 'a+');
                }
                
                // If file was successfully created
                if ($fh) {
                    $line = date('Y-m-d H:i:s') . ' : ' . $msg . "\n";
                    
                    fwrite($fh, $line);
                }
            }
        }
    }
    
    /**
     * pfGetData
     *
     * @return array|false
     */
    function pfGetData(): false|array
    {
        // Posted variables from ITN
        $pfData = $_POST;
        
        // Strip any slashes in data
        foreach ($pfData as $key => $val) {
            $pfData[$key] = stripslashes($val);
        }
        // Return "false" if no data was received
        return sizeof($pfData) == 0 ? false : $pfData;
    }
    
    /**
     * pfValidSignature
     *
     * @param $pfData
     * @param $passPhrase
     * @param $pfParamString
     * @return bool
     */
    function pfValidSignature($pfData = null, $passPhrase = null, &$pfParamString = null): bool
    {
        // Dump the submitted variables and calculate security signature
        foreach ($pfData as $key => $val) {
            if ($key != 'signature') {
                $pfParamString .= $key . '=' . urlencode($val) . '&';
            }
        }
        
        // Remove the last '&' from the parameter string
        $pfParamString = substr($pfParamString, 0, -1);
        $pfTempParamString = $pfParamString;
        if (!empty($passPhrase)) {
            $pfTempParamString .= '&passphrase=' . urlencode($passPhrase);
        }
        $signature = md5($pfTempParamString);
        $result = ($pfData['signature'] == $signature);
        pflog('Signature = ' . ($result ? 'valid' : 'invalid'));
        return ($result);
    }
    
    /**
     * pfValidData
     *
     * @param $pfHost
     * @param $pfParamString
     * @param $curlUrl
     * @return bool
     */
    function pfValidData($pfHost = 'www.payfast.co.za', $pfParamString = '', $curlUrl = ''): bool
    {
        pflog('beginning of function ' . __function__);
        pflog();
        pflog('Host = ' . $pfHost);
        pflog('Params = ' . $pfParamString);
        pflog('URL = ' . $curlUrl);
        pflog();
        
        // Use cURL (if available)
        if (PF_CURL) {
            
            pflog('using CURL [' . $curlUrl . ']');
            
            // Create default cURL object
            $ch = curl_init();
            
            // Set cURL options - Use curl_setopt for freater PHP compatibility
            // Base settings
            curl_setopt($ch, CURLOPT_USERAGENT, PF_USER_AGENT); // Set user agent
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return output as string rather than outputting it
            curl_setopt($ch, CURLOPT_HEADER, false); // Don't include header in output
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            // Standard settings
            curl_setopt($ch, CURLOPT_URL, "$curlUrl");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $pfParamString);
            curl_setopt($ch, CURLOPT_TIMEOUT, PF_TIMEOUT);
            
            // Execute CURL
            $response = curl_exec($ch);
            pflog(print_r(curl_getinfo($ch), true));
            
            if (curl_errno($ch)) {
                pflog('CURL Error number ' . curl_errno($ch));
                return false;
            }
            curl_close($ch);
        } else {
            $pfHost = preg_replace('%^https://%', '', $pfHost);
            pflog("using fsockopen (ssl://$pfHost, 443, errno, errstr, {PF_TIMEOUT})");
            // Variable initialization
            $headerDone = false;
            $response = '';
            
            // Construct Header
            $header = "POST /eng/query/validate HTTP/1.0\r\n";
            $header .= 'Host: ' . $pfHost . "\r\n";
            $header .= 'User-Agent: ' . PF_USER_AGENT . "\r\n";
            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $header .= 'Content-Length: ' . strlen($pfParamString) . "\r\n\r\n";
            
            // Connect to server
            if (!$socket = fsockopen('ssl://' . $pfHost, 443, $errno, $errstr, PF_TIMEOUT)) {
                pflog("fsockopen[$pfHost] errno[$errno] errstr[$errstr] returned FALSE");
                return false;
            }
            
            // Send command to server
            fputs($socket, $header . $pfParamString);
            
            // Read the response from the server
            while (!feof($socket)) {
                $line = fgets($socket, 1024);
                
                // Check if we are finished reading the header yet
                if (strcmp($line, "\r\n") == 0) {
                    $headerDone = true;
                } else {
                    if ($headerDone) {
                        // Read the main response
                        $response .= $line;
                    }
                }
            }
        }
        
        pflog("Response:\n" . print_r($response, true));
        $lines = explode("\r\n", $response);
        $verifyResult = trim($lines[0]);
        
        return strcasecmp($verifyResult, 'VALID') == 0;
    }
    
    /**
     * pfValidIP
     *
     * @param $sourceIP
     * @return bool
     */
    function pfValidIP($sourceIP): bool
    {
        $validHosts = array(
            'www.payfast.co.za',
            'sandbox.payfast.co.za',
            'w1w.payfast.co.za',
            'w2w.payfast.co.za',
        );
        
        $validIps = array();
        
        foreach ($validHosts as $pfHostname) {
            $ips = gethostbynamel($pfHostname);
            
            if ($ips !== false) {
                $validIps = array_merge($validIps, $ips);
            }
        }
        
        // Remove duplicates
        $validIps = array_unique($validIps);
        
        pflog("Valid IPs:\n" . print_r($validIps, true));
        
        return in_array($sourceIP, $validIps);
    }
    
    /**
     * pfAmountsEqual
     *
     * @param $amount1
     * @param $amount2
     * @return bool
     */
    function pfAmountsEqual($amount1, $amount2): bool
    {
        return !(abs(floatval($amount1) - floatval($amount2)) > PF_EPSILON);
    }
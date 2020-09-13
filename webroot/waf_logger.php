<?php
/*
	
	7G Firewall : Log Blocked Requests
	
	Version 1.2 2020/09/07 by Jeff Starr
    Modified on 2020 by Carlos Barbosa
	
	https://perishablepress.com/7g-firewall/
	https://perishablepress.com/7g-firewall-log-blocked-requests/
	
	-
	
	License: GPL v3 or later https://www.gnu.org/licenses/gpl.txt
	
	Overview: Logs HTTP requests blocked by 7G. Recommended for testing/development only.
	
	Requires: Apache, mod_rewrite, PHP >= 5.4.0, 7G Firewall
	
	Usage:
	
	1. Add 7G Firewall to root .htaccess (or Apache config)
	
	2. Configure 7G Firewall for logging (via tutorial)
	
	2. Add 7G_log.php + 7G_log.txt to root web directory
	
	3. Make 7G_log.txt writable and protect via .htaccess
	
	4. Edit the five lines/options below if necessary
	
	Test well & leave feedback @ https://perishablepress.com/contact/
	
	Notes:
	
	In log entries, matching firewall patterns are indicated via brackets like [this]
	
*/

use Quantum\QString;

require_once("../composer/vendor/autoload.php");
require_once("../quantum/apps/shared/services/ExternalErrorLogger/ExternalErrorLoggerService.php");

define('SEVENGLOGPATH', dirname(__FILE__) .'/../quantum/local/etc/logs/');

define('SEVENGSTATUS', 403); // 403 = Forbidden

define('SEVENGLOGFILE', '7G_firewall.log');

define('SEVENGUALENGTH', 0); // 0 = all

define('SEVENGEXIT', 'WAF Security Exception');

date_default_timezone_set('America/Chicago');



/**
 * @return array|false|QString
 */
function get_ip()
{
    if (getenv('HTTP_CLIENT_IP'))
        $address = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $address = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $address = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $address = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $address = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $address = getenv('REMOTE_ADDR');
    else
        $address = 'UNKNOWN';

    return $address;
}
// Do not edit below this line --~

function perishablePress_7G_init() {
	
	if (perishablePress_7G_check()) {
		
		perishablePress_7G_log();
		
		perishablePress_7G_exit();
		
	}
	
}

function perishablePress_7G_vars() {
	
	$date     = date('Y/m/d H:i:s');
	
	$method   = perishablePress_7G_request_method();
	
	$protocol = perishablePress_7G_server_protocol();
	
	$uri      = perishablePress_7G_request_uri();
	
	$string   = perishablePress_7G_query_string();
	
	$address  = perishablePress_7G_ip_address();
	
	$host     = perishablePress_7G_remote_host();
	
	$referrer = perishablePress_7G_http_referrer();
	
	$agent    = perishablePress_7G_user_agent();
	
	return array($date, $method, $protocol, $uri, $string, $address, $host, $referrer, $agent);
	
}

function perishablePress_7G_check() {
	
	$check = isset($_SERVER['REDIRECT_QUERY_STRING']) ? $_SERVER['REDIRECT_QUERY_STRING'] : '';
	
	return ($check === 'log') ? true : false;
	
}

function perishablePress_7G_log() {
	
	list ($date, $method, $protocol, $uri, $string, $address, $host, $referrer, $agent) = perishablePress_7G_vars();
	
	$log = $address .' - '. $date .' - '. $method .' - '. $protocol .' - '. $uri .' - '. $string .' - '. $host .' - '. $referrer .' - '. $agent ."\n";
	
	$log = preg_replace('/(\ )+/', ' ', $log);
	
	$fp = fopen(SEVENGLOGPATH . SEVENGLOGFILE, 'a+') or exit("Error: can't open log file!");
	
	fwrite($fp, $log);
	
	fclose($fp);
	
}

function perishablePress_7G_exit() {
	
	http_response_code(SEVENGSTATUS);

	$response = ExternalErrorLoggerService::error('7G_firewall_exception', json_encode(perishablePress_7G_vars()));
	
	//exit(SEVENGEXIT);

    render_error_page($response->getUuid());

    //header('Lcation: /waf?query_string='.http_build_query());
	
}

function perishablePress_7G_server_protocol() {
	
	return isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : '';
	
}

function perishablePress_7G_request_method() {
	
	$string = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
	
	$match = isset($_SERVER['REDIRECT_7G_REQUEST_METHOD']) ? $_SERVER['REDIRECT_7G_REQUEST_METHOD'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_query_string() {
	
	$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	
	$query = parse_url($request_uri);
	
	$string = isset($query['query']) ? $query['query'] : '';
	
	$match = isset($_SERVER['REDIRECT_7G_QUERY_STRING']) ? $_SERVER['REDIRECT_7G_QUERY_STRING'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_request_uri() {
	
	$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	
	$query = parse_url($request_uri);
	
	$string = isset($query['path']) ? $query['path'] : '';
	
	$match = isset($_SERVER['REDIRECT_7G_REQUEST_URI']) ? $_SERVER['REDIRECT_7G_REQUEST_URI'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_user_agent() {
	
	$string = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''; 
	
	$string = (defined(SEVENGUALENGTH)) ? substr($string, 0, SEVENGUALENGTH) : $string;
	
	$match = isset($_SERVER['REDIRECT_7G_USER_AGENT']) ? $_SERVER['REDIRECT_7G_USER_AGENT'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_ip_address() {
	
	$string = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	
	$match = isset($_SERVER['REDIRECT_7G_IP_ADDRESS']) ? $_SERVER['REDIRECT_7G_IP_ADDRESS'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_remote_host() {
	
	$string = ''; // todo: get host by address
	
	$match = isset($_SERVER['REDIRECT_7G_REMOTE_HOST']) ? $_SERVER['REDIRECT_7G_REMOTE_HOST'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_http_referrer() {
	
	$string = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	
	$match = isset($_SERVER['REDIRECT_7G_HTTP_REFERRER']) ? $_SERVER['REDIRECT_7G_HTTP_REFERRER'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_get_patterns($string, $match) {
	
	$patterns = explode('___', $match);
	
	foreach ($patterns as $pattern) {
		
		$string .= (!empty($pattern)) ? ' ['. $pattern .'] ' : '';
		
	}
	
	$string = preg_replace('/\s+/', ' ', $string);
	
	return $string;
	
}

function render_error_page($error_code)
{
    echo "<!DOCTYPE html>
<html>
<head>
	<title>403 Forbidden</title>
	<style>
		html {
			font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif;
			font-size: 0.875rem;
			line-height: 1.42857143;
			color: #333;
			background-color: #fff;
			padding: 0;
			margin: 0;
		}

		body {
			padding: 0;
			margin: 0;
		}

		a {
			color:#00709e;
		}

		h1, h2, h3, h4, h5, h6 {
			font-weight: 200;
			line-height: 1.1;
		}

		h1, .h1 { font-size: 3rem; }
		h2, .h2 { font-size: 2.5rem; }
		h3, .h3 { font-size: 1.5rem; }
		h4, .h4 { font-size: 1rem; }
		h5, .h5 { font-size: 0.875rem; }
		h6, .h6 { font-size: 0.75rem; }

		h1, h2, h3 {
			margin-top: 20px;
			margin-bottom: 10px;
		}
		h4, h5, h6 {
			margin-top: 10px;
			margin-bottom: 10px;
		}

		.wf-btn {
			display: inline-block;
			margin-bottom: 0;
			font-weight: normal;
			text-align: center;
			vertical-align: middle;
			touch-action: manipulation;
			cursor: pointer;
			background-image: none;
			border: 1px solid transparent;
			white-space: nowrap;
			text-transform: uppercase;
			padding: .4rem 1rem;
			font-size: .875rem;
			line-height: 1.3125rem;
			border-radius: 4px;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none
		}

		@media (min-width: 768px) {
			.wf-btn {
				padding: .5rem 1.25rem;
				font-size: .875rem;
				line-height: 1.3125rem;
				border-radius: 4px
			}
		}

		.wf-btn:focus,
		.wf-btn.wf-focus,
		.wf-btn:active:focus,
		.wf-btn:active.wf-focus,
		.wf-btn.wf-active:focus,
		.wf-btn.wf-active.wf-focus {
			outline: 5px auto -webkit-focus-ring-color;
			outline-offset: -2px
		}

		.wf-btn:hover,
		.wf-btn:focus,
		.wf-btn.wf-focus {
			color: #00709e;
			text-decoration: none
		}

		.wf-btn:active,
		.wf-btn.wf-active {
			outline: 0;
			background-image: none;
			-webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
			box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125)
		}

		.wf-btn.wf-disabled,
		.wf-btn[disabled],
		.wf-btn[readonly],
		fieldset[disabled] .wf-btn {
			cursor: not-allowed;
			-webkit-box-shadow: none;
			box-shadow: none
		}

		a.wf-btn {
			text-decoration: none
		}

		a.wf-btn.wf-disabled,
		fieldset[disabled] a.wf-btn {
			cursor: not-allowed;
			pointer-events: none
		}

		.wf-btn-default {
			color: #00709e;
			background-color: #fff;
			border-color: #00709e
		}

		.wf-btn-default:focus,
		.wf-btn-default.focus {
			color: #00709e;
			background-color: #e6e6e6;
			border-color: #00161f
		}

		.wf-btn-default:hover {
			color: #00709e;
			background-color: #e6e6e6;
			border-color: #004561
		}

		.wf-btn-default:active,
		.wf-btn-default.active {
			color: #00709e;
			background-color: #e6e6e6;
			border-color: #004561
		}

		.wf-btn-default:active:hover,
		.wf-btn-default:active:focus,
		.wf-btn-default:active.focus,
		.wf-btn-default.active:hover,
		.wf-btn-default.active:focus,
		.wf-btn-default.active.focus {
			color: #00709e;
			background-color: #d4d4d4;
			border-color: #00161f
		}

		.wf-btn-default:active,
		.wf-btn-default.wf-active {
			background-image: none
		}

		.wf-btn-default.wf-disabled,
		.wf-btn-default[disabled],
		.wf-btn-default[readonly],
		fieldset[disabled] .wf-btn-default {
			color: #777;
			background-color: #fff;
			border-color: #e2e2e2;
			cursor: not-allowed
		}

		.wf-btn-default.wf-disabled:hover,
		.wf-btn-default.wf-disabled:focus,
		.wf-btn-default.wf-disabled.wf-focus,
		.wf-btn-default[disabled]:hover,
		.wf-btn-default[disabled]:focus,
		.wf-btn-default[disabled].wf-focus,
		.wf-btn-default[readonly]:hover,
		.wf-btn-default[readonly]:focus,
		.wf-btn-default[readonly].wf-focus,
		fieldset[disabled] .wf-btn-default:hover,
		fieldset[disabled] .wf-btn-default:focus,
		fieldset[disabled] .wf-btn-default.wf-focus {
			background-color: #fff;
			border-color: #00709e
		}

		input[type=\"text\"], input.wf-input-text {
			text-align: left;
			max-width: 200px;
			height: 30px;
			border-radius: 0;
			border: 0;
			background-color: #ffffff;
			box-shadow: 0px 0px 0px 1px rgba(215,215,215,0.65);
			padding: 0.25rem;
		}

		hr {
			margin-top: 1rem;
			margin-bottom: 1rem;
			border: 0;
			border-top: 4px solid #eee
		}

		p {
			font-size: 1.4rem;
			font-weight: 300;
		}

		p.medium, div.medium p {
			font-size: 1.1rem;
		}

		p.small, div.small p {
			font-size: 1rem;
		}

		.container {
			max-width: 900px;
			padding: 0 1rem;
			margin: 0 auto;
		}

		.top-accent {
			height: 25px;
			background-color: #000000;
		}

		.block-data {
			width: 100%;
			border-top: 6px solid #00709e;
		}

		.block-data tr:nth-child(odd) th, .block-data tr:nth-child(odd) td {
			background-color: #eeeeee;
		}

		.block-data th, .block-data td {
			text-align: left;
			padding: 1rem;
			font-size: 1.1rem;
		}

		.block-data th.reason, .block-data td.reason {
			color: #930000;
		}

		.block-data th {
			font-weight: 300;
		}

		.block-data td {
			font-weight: 500;
		}

		.about {
			margin-top: 2rem;
			display: flex;
			flex-direction: row;
			align-items: stretch;
		}

		.about .badge {
			flex-basis: 116px;
			flex-grow: 0;
			flex-shrink: 0;
			display: flex;
			align-items: center;
			justify-content: flex-start;
		}

		.about svg {
			width: 100px;
			height: 100px;

		}

		.about-text {
			background-color: #00709e;
			color: #ffffff;
			padding: 1rem;
		}

		.about-text .h4 {
			font-weight: 500;
			margin-top: 0;
			margin-bottom: 0.25rem;
			font-size: 0.875rem;
		}

		.about-text p {
			font-size: 0.875rem;
			font-weight: 200;
			margin-top: 0.3rem;
			margin-bottom: 0.3rem;
		}

		.about-text p:first-of-type {
			margin-top: 0;
		}

		.about-text p:last-of-type {
			margin-bottom: 0;
		}

		.st0{fill:#00709e;}
		.st1{fill:#FFFFFF;}

		.generated {
			color: #999999;
			margin-top: 2rem;
		}
	</style>
</head>
<body>

<div class=\"top-accent\"></div>
<div class=\"container\">
	<h1>A potentially unsafe operation has been detected in your request to this site</h1>
	<p>Your access to this service has been limited. (HTTP response code 403)</p>
	<p>If you think you have been blocked in error, contact the owner of this site for assistance.</p>
	

	<h2 class=\"h3\">Block Technical Data</h2>
	<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"block-data\">
		<tr>
			<th class=\"reason\">Block Reason:</th>
			<td class=\"reason\">A potentially unsafe operation has been detected in your request to this site</td>
		</tr>
		<tr>
			<th class=\"time\">Time:</th>
			<td class=\"time\">".htmlspecialchars(gmdate('D, j M Y G:i:s T'))."</td>
		</tr>
		<tr>
			<th class=\"time\">Error Code:</th>
			<td class=\"time\">".$error_code."</td>
		</tr>
	</table>

	<div class=\"about\">
		<div class=\"badge\">
			
		</div>
		<div class=\"about-text\">
			<h3 class=\"h4\">About our WAF</h3>
			<p>Site Security provided by Sucuri & McAfee</p>
		</div>
	</div>
	
	

	<p class=\"generated small\"><em>This Exception has been logged and administrators have now been notified.</p>
	<p class=\"generated small\"><em>Client IP:".get_ip()."</p>
</div>
</body>
</html>";
}

perishablePress_7G_init();
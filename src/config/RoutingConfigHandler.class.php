<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Agavi package.                                   |
// | Copyright (c) 2003-2006 the Agavi Project.                                |
// | Based on the Mojavi3 MVC Framework, Copyright (c) 2003-2005 Sean Kerr.    |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.agavi.org/LICENSE.txt                   |
// |   vi: set noexpandtab:                                                    |
// |   Local Variables:                                                        |
// |   indent-tabs-mode: t                                                     |
// |   End:                                                                    |
// +---------------------------------------------------------------------------+

/**
 * AgaviRoutingConfigHandler allows you to specify a list of routes that will
 * be matched against any given string.
 *
 * @package    agavi
 * @subpackage config
 *
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @copyright  (c) Authors
 * @since      0.11.0
 *
 * @version    $Id$
 */
class AgaviRoutingConfigHandler extends AgaviConfigHandler
{
	/**
	 * Execute this configuration handler.
	 *
	 * @param      string An absolute filesystem path to a configuration file.
	 * @param      string Name of the executing context (if any).
	 *
	 * @return     string Data to be written to a cache file.
	 *
	 * @throws     <b>AgaviUnreadableException</b> If a requested configuration file
	 *                                             does not exist or is not readable.
	 * @throws     <b>AgaviParseException</b> If a requested configuration file is
	 *                                        improperly formatted.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function execute($config, $context = null)
	{
		$routing = AgaviContext::getInstance($context)->getRouting();

		if($context == null) {
			$context = '';
		}

		// parse the config file
		$configurations = $this->orderConfigurations(AgaviConfigCache::parseConfig($config)->configurations, AgaviConfig::get('core.environment'), $context);

		// clear the routing
		$routing->importRoutes(array());
		$data = array();
		
		foreach($configurations as $cfg)
		{
			$this->parseRoutes($routing, $cfg->routes);
		}

		$code = '$this->importRoutes(' . var_export($routing->exportRoutes(), true) . ');';

		// compile data
		$retval = "<?php\n" .
				  "// auto-generated by AgaviRoutingConfigHandler\n" .
				  "// date: %s\n%s\n?>";
		$retval = sprintf($retval, date('m/d/Y H:i:s'), $code);

		return $retval;

	}

	protected function parseRoutes($routing, $routes, $parent = null)
	{
		foreach($routes as $route) {
			$pattern = $route->getAttribute('pattern');
			$opts = array();
			if($route->hasAttribute('imply'))					$opts['imply']				= $this->literalize($route->getAttribute('imply'));
			if($route->hasAttribute('cut'))						$opts['cut']					= $this->literalize($route->getAttribute('cut'));
			if($route->hasAttribute('stopping'))			$opts['stopping']			= $this->literalize($route->getAttribute('stopping'));
			if($route->hasAttribute('name'))					$opts['name']					= $route->getAttribute('name');
			if($route->hasAttribute('output_type'))		$opts['output_type']	= $route->getAttribute('output_type');
			if($route->hasAttribute('module'))				$opts['module']				= $route->getAttribute('module');
			if($route->hasAttribute('action'))				$opts['action']				= $route->getAttribute('action');
			if($route->hasAttribute('callback'))			$opts['callback']			= $route->getAttribute('callback');

			if($route->hasChildren('ignores')) {
				foreach($route->ignores as $ignore) {
					$opts['ignores'][] = $ignore->getValue();
				}
			}

			if($route->hasChildren('defaults')) {
				foreach($route->defaults as $default) {
					$opts['defaults'][$default->getAttribute('for')] = $default->getValue();
				}
			}

			if($route->hasChildren('parameters')) {
				foreach($route->parameters as $parameter) {
					$opts['parameters'][$parameter->getAttribute('name')] = $parameter->getValue();
				}
			}

			$name = $routing->addRoute($pattern, $opts, $parent);
			if($route->hasChildren('routes')) {
				$this->parseRoutes($routing, $route->routes, $name);
			}
		}
	}
}


?>
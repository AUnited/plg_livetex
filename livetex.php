<?php
# @version		$version 0.1 Amvis United Company Limited  $
# @copyright	Copyright (C) 2016 AUnited Co Ltd. All rights reserved.
# @license		Jivosite plugin licensed under Apache v2.0, see LICENSE
# Updated		14st August 2016
#
# Site: http://aunited.ru
# Email: info@aunited.ru
# Phone
#
# Joomla! is free software. This version may have been modified pursuant
# to the GNU General Public License, and as distributed it includes or
# is derivative of works licensed under the GNU General Public License or
# other free or open source software licenses.
# See COPYRIGHT.php for copyright notices and details.


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class plgSystemlivetex extends JPlugin
{
	function plglivetex(&$subject, $config)
	{		
		parent::__construct($subject, $config);
		$this->_plugin = JPluginHelper::getPlugin( 'system', 'livetex' );
		$this->_params = new JParameter( $this->_plugin->params );
	}
	
	function onAfterRender()
	{
        $app = JFactory::getApplication();
        if($app->isAdmin())
        {
            return;
        }
		// Initialise variables
		$enabled 			= $this->params->get( 'enabled', '' );
		$id 					= $this->params->get( 'id', '' );

		//getting body code and storing as buffer
		$buffer = JResponse::getBody();
		
		$script	=  "<!-- {literal} -->
        <script type='text/javascript'>
        window['liv'+'eT'+'ex'] = true,
        window['l'+'iveT'+'exID'] = ".$id.",
        window['li'+'veT'+'ex_obj'+'e'+'c'+'t'] = true;
        (function() {
        var t = document['creat'+'eEleme'+'nt']('script');
        t.type ='text/javascript';
        t.async = true;
        t.src = '//cs15.livete'+'x.ru/js/clien'+'t.j'+'s';
        var c = document['ge'+'tEleme'+'n'+'tsBy'+'TagName']('script')[0];
        if ( c ) c['par'+'entNo'+'d'+'e']['inse'+'rtBefo'+'re'](t, c);
        else document['docu'+'ment'+'Element']['fi'+'rstCh'+'il'+'d']['ap'+'p'+'endCh'+'ild'](t);
        })();
        </script>
        <!-- {/literal} -->";

		//is it enabled?
		$javascript='';
        if ($enabled)	$javascript= $javascript.$script;


		$buffer = preg_replace ("/<\/body>/", $javascript."\n\n</body>", $buffer);
		
		//output the buffer
		JResponse::setBody($buffer);
		
		return true;
	}
}
?>

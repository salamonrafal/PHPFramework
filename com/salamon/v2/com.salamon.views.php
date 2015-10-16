<?php
/**
 * @name templates system
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.views.php
 * @package Views
 */

require 'com.salamon.interface.views.php';

class com_salamon_views implements com_salamon_interface_views
{
    /*
     * Private variables
     */

    /**
     * Event class
     * 
     * @var com_salamon_events
     */
    public $_Event = '';

    /**
     * Routes class
     * 
     * @var com_salamon_routes
     */
    protected $_Routes = '';

    /**
     * Default layout name
     * 
     * @var string
     */
    protected $_default_layout = 'main.layout';

    /**
     * Layout
     * 
     * @var string
     */
    protected $_layout = '';

    /**
     * View name
     * 
     * @var string
     */
    protected $_view = '';

    /**
     * Css files
     * 
     * @var array
     */
    protected $_styles = Array();

    /**
     * JavaScripts files
     * 
     * @var array
     */
    protected $_scripts = Array();

    /**
     * Namespace
     * 
     * @var string
     */
    protected $_namespace = '_default';

    /**
     * Setting class
     * 
     * @var com_salamon_settings
     */
    public $_Setting = '';

    /**
     * Css sections
     * 
     * @var array
     */
    protected $_css_sections = Array();

    /**
     * Module name
     * 
     * @var string
     */
    protected $_app_namespace = '';
    
    protected $_folders = array(
            'views' => 'views/',
            'layouts' => 'layouts/'
    );
    
    protected $_without_namespace = false;
    
    protected $_without_layout = false;
    
    protected $_T = '';
    
    protected $_modulename = '';



    /*
     * Public methods
     */

    /**
     * @see com_salamon_interface_views::__construct()
     */
    public function __construct($Event, $Setting, $Routes, $t, $withoutNamespace = false, $withoutLayout = false)
    {
        $this -> _Event = $Event;
        $this -> _Setting = $Setting;
        $this -> _Routes = $Routes;
        $this -> _T = $t;
        $this -> setDefaultLayout($this -> _Setting -> getSetting('layout', 'defaults'));
        $this -> setNamespace($this -> _Setting -> getSetting('namespace', 'defaults'));
        $this -> _without_namespace = $withoutNamespace;
        $this -> _without_layout =$withoutLayout;
    }

    /**
     * @see com_salamon_interface_views::render()
     */
    public function render()
    {
        if (!$this -> _without_layout) {
                $path = $this -> _folders['layouts'] . ( $this -> _without_namespace ? '' : $this -> getNamespace() )  . '/' . $this -> getLayout() . '.php';
                $path_orginal = $path;
                
                if ($this -> _modulename != '') {
                    $path = './modules/' . $this -> _modulename .'/' . $path;
                } 
                
                try {
                    if (file_exists($path)) {
                        include ($path);
                    } elseif (file_exists($path_orginal)) {
                        include ($path_orginal);
                    } else {
                        throw new Exception("Cannot find layout: '{$path}'", 404);
                    }
                } catch (Exception $e) {
                    if (!headers_sent()) {
                        com_salamon_controllers :: missingError($e -> getMessage());
                    }
                }	
        } else {
            $this -> getContent();
        }

    }

    /**
     * @see com_salamon_interface_views::loadStyles()
     */
    public function loadStyles()
    {
        $returnhtml = '';

        for ($i = 0; $i < count($this -> _styles); $i++)
        {
            $returnhtml .= '<link rel="stylesheet" media="' . $this -> _styles[$i]['media'] . '" href="' . $this -> _styles[$i]['path'] . '" type="text/css" />';
        }

        return $returnhtml;
    }

    /**
     * @see com_salamon_interface_views::addNewCSSSection()
     */
    public function addNewCSSSection($sectionname)
    {
        if (!$this -> checkCSSSectionExists($sectionname)) {
            $this -> _css_sections[] = $sectionname;
        }
    }

    /**
     * @see com_salamon_interface_views::removeCSSSection()
     */
    public function removeCSSSection($sectionname)
    {
        for ($i = 0; $i < count($this -> _css_sections); $i++)
        {
            if ($this -> _css_sections[$i] == $sectionname) {
                unset($this -> _css_sections[$i]);
            }
        }
    }

    /**
     * @see com_salamon_interface_views::getCSSSections()
     */
    public function getCSSSections()
    {
        $sectionnames = '';

        if (count($this -> _css_sections) > 0)
        {
            $sectionnames = ' class="';
            $sectionnames .=  implode(" ", $this -> _css_sections);
            $sectionnames .= '"';
        }

        return $sectionnames;
    }

    /**
     * @see com_salamon_interface_views::checkCSSSectionExists()
     */
    public function checkCSSSectionExists($sectionname)
    {
        $exists = false;

        for ($i = 0; $i < count($this -> _css_sections); $i++)
        {
            if ($this -> _css_sections[$i] == $sectionname) {
                $exists = true;
            }
        }

        return $exists;
    }

    /**
     * @see com_salamon_interface_views::loadScripts()
     */
    public function loadScripts()
    {
        $returnhtml = '';

        for ($i = 0; $i < count($this -> _scripts); $i++)
        {
            $returnhtml .= '<script type="text/javascript" language="javascript" src="' . $this -> _scripts[$i] . '"><!-- --></script>';	
        }

        return $returnhtml;
    }

    /**
     * @see com_salamon_interface_views::renderViewContent()
     */
    public function renderViewContent ($viewName, $args = array())
    {
        $path = $this -> _folders['views'] . ( $this -> _without_namespace ? '' : $this -> getNamespace() )  . '/' . $viewName . '.php';
        $content = '';

        try {
            if (file_exists($path))
            {
                ob_start();
                    include ($path);
                    $content = ob_get_contents();
                ob_end_clean();
            } else {
                throw new Exception("Cannot find view '{$path}'", 404);
            }
        } catch (Exception $e) {
            if (!headers_sent()) {
                com_salamon_controllers :: missingError($e -> getMessage());
            }
        }

        return $content;
    }

    /**
     * @see com_salamon_interface_views::setStyle()
     */
    public function setStyle($pathtocss, $media)
    {
        $path = $this -> _Setting -> getSetting('pathtoapp', 'paths') . $pathtocss;
        if (file_exists($path)) {
            array_push($this -> _styles, Array('path' => $pathtocss, 'media' => $media));
        }
    }

    /**
     * @see com_salamon_interface_views::setScript()
     */
    public function setScript ($pathtoscript)
    {
        $this -> _scripts[] = $pathtoscript;
    }

    /**
     * @see com_salamon_interface_views::setLayout()
     */
    public function setLayout ($layoutname)
    {
        $this -> _layout = $layoutname;
    }

    /**
     * @see com_salamon_interface_views::setModule()
     */
    public function setAppNamespace ($app_namespace)
    {
        $this -> _app_namespace = $app_namespace;
    }

    /**
     * @see com_salamon_interface_views::setDefaultLayout()
     */
    public function setDefaultLayout ($layoutname)
    {
        $this -> _default_layout = $layoutname; 	
    }
    
    /**
     * @see com_salamon_interface_views::setModuleName()
     */
    public function setModuleName ($modulename) {
        $this -> _modulename = $modulename;
    }

    /**
     * @see com_salamon_interface_views::setView()
     */
    public function setView ($viewname)
    {
        $this -> _view = $viewname;
    }

    /**
     * @see com_salamon_interface_views::setNamespace()
     */
    public function setNamespace ($namespace)
    {
        $this -> _namespace = $namespace;
    }

    /**
     * @see com_salamon_interface_views::setViewFolder()
     */
    public function setViewFolder ($folderName) {
        $this -> _folders['views'] = $folderName;
    }

    /**
     * @see com_salamon_interface_views::getContent()
     */
    public function getContent()
    {
        $path = $this -> _folders['views'] . ( $this -> _without_namespace ? '' : $this -> getNamespace() )  . '/' . $this -> getView() . '.php';

        try {
            if (file_exists($path))
            {
                include ($path);
            } else {
                throw new Exception("Cannot find view '{$path}'", 404);
            }
        } catch (Exception $e) {
            if (!headers_sent()) {
                com_salamon_controllers :: missingError($e -> getMessage());
            }
        }
    }

    /**
     * @see com_salamon_interface_views::renderView()
     */
    public function renderView($viewname)
    {
        $path = $this -> _folders['views'] . ( $this -> _without_namespace ? '' : $this -> getNamespace() )  . '/' . $viewname . '.php';

        try {
            if (file_exists($path))
            {
                include ($path);
            } else {
                throw new Exception("Cannot find view '{$path}'", 404);
            }
        } catch (Exception $e) {
            if (!headers_sent()) {
                com_salamon_controllers :: missingError($e -> getMessage());
            }
        }
    }
    
    /**
     * @see com_salamon_interface_views::getModuleName()
     */
    public function getModuleName () {
        return $this -> _modulename;
    }

    /**
     * @see com_salamon_interface_views::getNamespace()
     */
    public function getNamespace () {
        return $this -> _namespace;
    }

    /**
     * @see com_salamon_interface_views::getLayout()
     */
    public function getLayout() {
        if ($this -> _layout != '') {
            return $this -> _layout;
        } else {
            return $this -> _default_layout;
        }
    }

    /**
     * @see com_salamon_interface_views::getView()
     */
    public function getView()
    {
        return $this -> _view;	
    }

    /**
     * @see com_salamon_interface_views::getAppNamespace()
     */
    public function getAppNamespace()
    {
        return $this -> _app_namespace;	
    }

    /**
     * @see com_salamon_interface_views::getCanonicalTag()
     */
    public function getCanonicalTag ()
    {
        $canonicals = Array();
        $canonicalsDomains = Array();
        $request_uri = $_SERVER['REQUEST_URI'];
        $canonical_tag = '';
        $path = $this -> _Setting -> getSetting('pathtoapp', 'paths') . $this -> _Setting -> getSetting('configuration', 'paths') . 'canonicals' . ($this -> getAppNamespace() != '' ? '_' . $this -> getAppNamespace() : '') . '.php';

        if (file_exists($path))
        {
            include($path);

            if (isset($canonicalsDomains[APP_DOMAIN]))
            {
                if (isset($canonicals[$request_uri]))
                {
                    $url = 'http://'. $this -> _Setting -> getSetting('env', 'defaults') . '.' . $canonicalsDomains[APP_DOMAIN] . $canonicals[$request_uri];
                    $canonical_tag = '<link rel="canonical" href="' . $url . '" />';
                    $canonical_tag .= '<meta property="og:url" content="' . $url . '" />';
                } else {
                    if ($_SERVER["REQUEST_URI"] == '/')
                    {
                        $url = 'http://' . $this -> _Setting -> getSetting('env', 'defaults') . '.' . $canonicalsDomains[APP_DOMAIN];
                        $canonical_tag = '<link rel="canonical" href="' . $url . '/" />';
                        $canonical_tag .= '<meta property="og:url" content="' . $url . '" />';
                    } else {
                        $url = 'http://' . $this -> _Setting -> getSetting('env', 'defaults') . '.' . $canonicalsDomains[APP_DOMAIN] . $_SERVER["REQUEST_URI"];
                        $canonical_tag = '<link rel="canonical" href="' . $url . '" />';
                        $canonical_tag .= '<meta property="og:url" content="' . $url . '" />';
                    }
                }
            } else {
                if (isset($canonicals[$request_uri]))
                {
                    $canonical_tag = '<link rel="canonical" href="' .  $canonicals[$request_uri] . '" />';
                    $canonical_tag .= '<meta property="og:url" content="' . $canonicals[$request_uri] . '" />';
                }
            }

        } else {
            $canonical_tag = '<!-- Missing file: ' . $path . ' -->';
        }

        return $canonical_tag;
    }

    /**
     * @see com_salamon_interface_views::getCanonicalURL()
     */
    public function getCanonicalURL ()
    {
        $canonicals = Array();
        $canonicalsDomains = Array();
        $request_uri = $_SERVER['REQUEST_URI'];
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $path = $this -> _Setting -> getSetting('pathtoapp', 'paths') . $this -> _Setting -> getSetting('configuration', 'paths') . 'canonicals' . ($this -> getAppNamespace() != '' ? '_' . $this -> getAppNamespace() : '') . '.php';

        if (file_exists($path))
        {
            include($path);
            
            if (isset($canonicalsDomains[APP_DOMAIN]))
            {
                if (isset($canonicals[$request_uri]))
                {
                    $url = 'http://'. $this -> _Setting -> getSetting('env', 'defaults') . '.' . $canonicalsDomains[APP_DOMAIN] . $canonicals[$request_uri];
                    $canonical_tag = '<link rel="canonical" href="' . $url . '" />';
                    $canonical_tag .= '<meta property="og:url" content="' . $url . '" />';
                } else {
                    if ($_SERVER["REQUEST_URI"] == '/')
                    {
                        $url = 'http://' . $this -> _Setting -> getSetting('env', 'defaults') . '.' . $canonicalsDomains[APP_DOMAIN];
                    } else {
                        $url = 'http://' . $this -> _Setting -> getSetting('env', 'defaults') . '.' . $canonicalsDomains[APP_DOMAIN] . $_SERVER["REQUEST_URI"];
                    }
                }
            } else {
                if (isset($canonicals[$request_uri]))
                {
                    $url  = 'http://' . $_SERVER['SERVER_NAME'] . $canonicals[$request_uri];
                }
            }

        }

        return $url;
    }

    /**
     * @see com_salamon_interface_views::setViewParam()
     */
    public function setViewParam($name, $value)
    {
        $this -> _Event -> setValue ($name, $value);
    }

    /*
     * Private methods
     */
}
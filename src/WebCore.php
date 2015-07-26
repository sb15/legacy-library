<?
require_once 'Zend/Controller/Action.php';

class WebCore extends Zend_Controller_Action
{

    private static $_user = null;

    private static $_forms = null;
    private static $_sessions = array();

    public static function getBootstrap()
    {
        return Zend_Controller_Front::getInstance()->getParam("bootstrap");
    }


    public function noRender()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function getLayout()
    {
        return self::getBootstrap()->getResource('layout');
    }

    public function setLayout($name)
    {
        return self::getBootstrap()->getResource('layout')->setLayout($name);
    }

    public function disableLayout()
    {
        $this->_helper->layout()->disableLayout();
    }

    public function _p($param = null, $default = null, $urlFix = false)
    {
        if (is_null($param)) {
            return $this->getRequest()->getParams();
        } else {
            $result = $this->getRequest()->getParam($param, $default);
            if ($urlFix) {
                $result = str_replace("_", " ", $result);
            }
            return $result;
        }
    }

    /**
     * @return Zend_Db_Adapter_Pdo_Mysql
     */
    public static function getDb()
    {
        return self::getBootstrap()->getResource('db');
    }

    public static function getSession($namespace = 'Default')
    {
        if (!array_key_exists($namespace, self::$_sessions)) {
            self::$_sessions[$namespace] = new Zend_Session_Namespace($namespace);
        }
        return self::$_sessions[$namespace];
    }

    public static function getUserId($namespace = 'Default')
    {
        return intval(self::getSession($namespace)->id);
    }

    public function getUser()
    {
        if (!self::$_user) {
            throw new Exception('No user'); // отловить и на логин
        }
        return self::$_user;
    }

    public function getRouterNames()
    {
        return array_keys($this->getFrontController()->getRouter()->getRoutes());
    }

    protected function _url($urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        return $this->_helper->url->url($urlOptions, $name, $reset, $encode);
    }

    /**
     * @param unknown_type $formName
     * @param unknown_type $options
     * @throws Exception
     * @return Zend_Form
     */
    public function getForm($formName, $options = null, $useTemplate = false)
    {
        if (is_null(self::$_forms)) {

            if (is_file(APPLICATION_PATH . '/configs/forms.ini')) {
                $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms.ini');
            } else {
                $config = new Zend_Config(include_once APPLICATION_PATH . '/configs/forms.php');
            }
            $config = $config->toArray();
            self::$_forms = $config;
        }

        if (!array_key_exists($formName, $config)) {
            throw new Exception("Form not exist");
        }

        if ($useTemplate) {
            //$form = new Zend_Form_Template($config[$formName]);
            //$form->setTemplate($formName, $options);

            $form = new Zend_Form($config[$formName]);
            $elements = $form->getElements();
            foreach ($elements as &$element) {
                $element->removeDecorator('Label');
                $element->removeDecorator('HtmlTag');
                $element->removeDecorator('DtDdWrapper');
                $element->removeDecorator('Description');
                $element->removeDecorator('Errors');
                $element->addDecorator(array('data' => 'Errors'), array('tag' => 'p', 'class' => 'description'));
            }

            $filter = new Zend_Filter_Word_CamelCaseToDash();
            $formName = $filter->filter($formName);
            $options['viewScript'] = 'forms/' . $formName . '.phtml';

            $form->setDecorators(array(
                array('viewScript', $options))
            );


        } else {
            $form = new Zend_Form($config[$formName]);
          /*  $form->addElementPrefixPath('Zend_Decorator',
            'Zend/Decorator/',
            'decorator');
            $form->setDecorators(array('Default'));*/
            //Zend_Debug::dump($form->getDecorator('Errors'));

            /*$elements = $form->getElements();
            foreach ($elements as &$element) {

                $element->setDecorators(
                    array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element')),
                    'Label',
                    array(array('row' => 'HtmlTag'), array('tag' => 'li'))
                    )
                );

                //Zend_Debug::dump($element->getDecorator('Errors'));

            };*/
            /*
            $form->setElementDecorators(array(
                'ViewHelper',
                array('Errors', array('class' => 'help-inline control-error')),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'controls')),
                array(array('label' => 'Label'), array('class' => 'control-label')),
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group')),
            ));

           */


           // $form->addElement("text", "naem", $opt);

           /* $form->setDecorators(array(
                'FormElements',
                array('Form', array('class' => 'form-horizontal'))
            ));*/
            //Zend_Debug::dump($form);
            // вынести декораторы
        }

        $formAction = $form->getAction();

        $routes = $this->getRouterNames();

        $actionParams = array();
        if (is_array($options) && array_key_exists('actionParams', $options)) {
            $actionParams = $options['actionParams'];
        }

        if (in_array($formAction, $routes)) {
            if (array_key_exists("actionParams", $config[$formName]) && is_array($config[$formName]['actionParams'])) {
                $actionParams = $config[$formName]['actionParams'];
            }
            $form->setAction($this->_url($actionParams, $formAction));
        }

        //Zend_Debug::dump($form);

        return $form;
    }

    public static function getDate($now = null)
    {
        Utils_Date::getDate($now);
    }

    public function preDispatch()
    {
        self::getDb()->query("SET time_zone = '+4:00'");

        $locale = 'ru';
        $translate = self::getBootstrap()->getResource('translate');
        if ($translate) {
            $locales = $translate->getList();

            if (in_array($this->_p('locale'), $locales)) {
                $translate->setLocale($this->_p('locale'));
                $locale = $this->_p('locale');
            }
        }

        if ($this->_p('disableLayout')) {
            $this->disableLayout();
        }

        if (Zend_Auth::getInstance()->hasIdentity()) {
            $identity = Zend_Auth::getInstance()->getIdentity();

            if (isset($identity->internalUserId)) {
                self::$_user = (object) User::getInstance()->getUserById($identity->internalUserId);
                $this->view->user = self::$_user;
            }
        }

        $this->view->headTitle()->setSeparator(' / ');
        $this->view->headTitle()->setDefaultAttachOrder(Zend_View_Helper_Placeholder_Container_Abstract::PREPEND);

        $this->view->assign('p', $this->_p());

    }

    public function nameToUrl($name)
    {
        return $this->view->getHelper('nameToUrl')->nameToUrl($name);
    }

    /**
     * @deprecated
     * @param unknown_type $param
     */
    public function getParamForUrl($param)
    {
        return $this->view->getHelper('nameToUrl')->nameToUrl($param);
    }

    public function getDomainName()
    {
        return $_SERVER['SERVER_NAME'];
    }

    public function getPrimaryDomainName()
    {
        $domain = $this->getDomainName();
        $domainParts = explode(".", $domain);
        return $domainParts[count($domainParts)-2] . '.' . $domainParts[count($domainParts)-1];
    }

    public function postDispatch()
    {
        self::getDb()->closeConnection();
    }
}
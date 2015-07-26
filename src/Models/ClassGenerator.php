<?php

namespace Models;

class ClassGenerator
{

    private $className = null;
    private $classNamespace = null;
    private $classExtend = null;
    private $properties = array();
    private $methods = array();
    const TAB = '    ';

    public function __construct($className, $classExtend = '', $classNamespace = '')
    {
        $this->className = $className;
        $this->classNamespace = $classNamespace;
        $this->classExtend = $classExtend;
    }
    
    public function addProperty($name, $scope = 'private', $value = 'null', $docBlock = '')
    {
        $this->properties[$name] = array(
            'name' => $name,
            'scope' => $scope,
            'docBlock' => $docBlock,
            'value' => $value
        );
    }

    public function addMethod($name, $scope = 'public', $params = array(), $content = '', $docBlock = '')
    {
        $this->methods[$name] = array(
            'name' => $name,
            'scope' => $scope,
            'params' => $params,
            'content' => $content,
            'docBlock' => $docBlock
        );
    }

    public function generateGettersAndSetters()
    {
        $filter = new \Zend_Filter_Word_UnderscoreToCamelCase();

        foreach ($this->properties as $property) {

            $propertyName = $property['name'];
            $propertyNameFiltered = lcfirst($filter->filter($property['name']));

            $params = array(
                'name' => $propertyNameFiltered,
                'default' => '',
            );
            $content = self::TAB . self::TAB . "\$this->{$propertyName} = \${$propertyNameFiltered};";
            $this->addMethod("set" . ucfirst($propertyNameFiltered), 'public', array($params), $content);

            $content = self::TAB . self::TAB . "return \$this->{$propertyName};";
            $this->addMethod("get" . ucfirst($propertyNameFiltered), 'public', array(), $content);
        }
    }

    public function getPropertyText($property)
    {
        if (is_array($property['value'])) {
            $result = self::TAB . "{$property['scope']} \${$property['name']} = array(\n";
            foreach ($property['value'] as $v) {
                $result .= self::TAB . self::TAB . "'{$v}',\n";
            }
            $result .= self::TAB . ");\n";
            return $result;
        } else {
            return self::TAB . "{$property['scope']} \${$property['name']} = {$property['value']};\n\n";
        }
    }

    public function getMethodText($method)
    {
        $result = '';

        if ($method['docBlock']) {
            $result = self::TAB . "/**\n" . self::TAB . " *\n " . self::TAB . "* ";
            $result .= $method['docBlock'] . "\n";
            $result .= self::TAB . " */\n";
        }

        $result .= self::TAB . "{$method['scope']} function {$method['name']}(";

        $i = 0;
        foreach ($method['params'] as $param) {
            if (isset($param['type'])) {
				$result .= $param['type'] . ' ';
			}
			
			$result .= "\${$param['name']}";
			
			if (isset($param['default']) && !empty($param['default'])) {
				$result .= " = {$param['default']}";
			}			
			
            $i++;
            if ($i != count($method['params'])) {
                $result .= ", ";
            }
        }

        $result .= ")\n";

        $result .=
        self::TAB . "{\n" .
        rtrim($method['content']) . "\n" .
        self::TAB . "}\n\n";

        return $result;
    }

    public function __toString()
    {
        $result = "<?php \n\n";


        if ($this->classNamespace) {
            $result .= "namespace {$this->classNamespace};\n\n";
        }

        $result .= "class " . $this->className;

        if ($this->classExtend) {
            $result .= " extends {$this->classExtend}";
        }

        $result .= "\n{\n";

        foreach ($this->properties as $property) {
            $result .= $this->getPropertyText($property);
        }

        foreach ($this->methods as $method) {
            $result .= $this->getMethodText($method);
        }

        $result .= "}\n";

        return $result;
    }
    
}
